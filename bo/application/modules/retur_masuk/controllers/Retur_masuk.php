<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Retur_masuk extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('t_retur_beli');
		$this->load->model('t_retur_masuk');
		$this->load->model('m_user');
		$this->load->model('m_global');
		$this->load->model('set_role/m_set_role', 'm_role');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'Penerimaan Retur',
			'data_user' => $data_user,
			'data_role'	=> $data_role
		);

		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => 'modal_detail',
			'js'	=> 'retur_masuk.js',
			'view'	=> 'view_list'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_data_tabel()
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$bulan = ($this->input->post('bulan') == '') ? (int)$obj_date->format('m') : $this->input->post('bulan');
		$tahun = ($this->input->post('tahun') == '') ? (int)$obj_date->format('Y') : $this->input->post('tahun');
		$kategori = ($this->input->post('kategori') == '') ? 'all' : $this->input->post('kategori');

		$paramdata = [
			'bulan' => $bulan,
			'tahun' => $tahun,
			'kategori' => $kategori
		];

		$listData = $this->t_retur_masuk->get_datatable_transaksi($paramdata);
		$datas = [];
		$i = 1;
		foreach ($listData as $key => $value) {
			$datas[$key][] = $i++;
			$datas[$key][] = tanggal_indo($value->tanggal);
			$datas[$key][] = $value->kode_retur;
			$datas[$key][] = $value->nama_perusahaan;
			$datas[$key][] = ($value->jenis_retur == '1') ? 'Ganti Barang' : 'Potong Nota';
			$datas[$key][] = $value->nama_user;
			$datas[$key][] = number_format($value->total_nilai_retur, 0, ',', '.');
			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">
						<button class="dropdown-item" onclick="edit_transaksi(\'' . $value->kode_retur . '\',\'' . $value->id . '\')">
							<i class="la la-pencil"></i> Edit Transaksi
						</button>
						<button class="dropdown-item" onclick="detail_transaksi(\'' . $value->kode_retur . '\',\'' . $value->id . '\')">
							<i class="la la-desktop"></i> Lihat Detail
						</button>
						<button class="dropdown-item" onclick="delete_transaksi(\'' . $value->kode_retur . '\',\'' . $value->id . '\')">
							<i class="la la-trash"></i> Hapus
						</button>
						<button class="dropdown-item" onclick="cetak_invoice(\'' . $value->kode_retur . '\',\'' . $value->id . '\')">
							<i class="la la-print"></i> Cetak
						</button>
					</div>
				</div>
			';
			$datas[$key][] =  $str_aksi;
		}

		$data = [
			'data' => $datas
		];

		echo json_encode($data);
	}

	public function new_transaksi()
	{
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		/**
		 * data passing ke halaman view content
		 */

		$data = array(
			'title' => 'Transaksi Retur Baru',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'arr_data' => [1 => 'Ganti Barang', 2 => 'Potong Nota'],
			'data_penerimaan' => $this->m_global->getSelectedData('t_penerimaan', array('deleted_at' => null))->result(),
			'mode'		=> 'add',
		);

		$mode = $this->input->get('mode');

		if ($mode == 'edit') {
			$kode = $this->input->get('kode');
			$data_header = $this->m_global->getSelectedData('t_retur_beli', array('kode_retur' => $kode))->row();

			if (!$data_header) {
				return redirect($this->uri->segment(1));
			}

			$data['old_data'] = $data_header;
			$data['mode'] = $mode;
			$data['title'] = "Edit Transaksi Retur";
			$data['id_retur'] = $data_header->id;
			$data['kode'] = $data_header->kode_retur;
			$data['arr_data'] = [1 => 'Ganti Barang', 2 => 'Potong Nota'];
			// $data['tgl_jatuh_tempo'] = date("d/m/Y", strtotime($invoice->tgl_jatuh_tempo));
		}
		
		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => null,
			'js'	=> 'retur_beli.js',
			'view'	=> 'view_new'
		];

		$this->template_view->load_view($content, $data);
	}

	public function add_new_transaksi()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $this->input->post('tanggal');
		$tgl_fix = DateTime::createFromFormat('d/m/Y', $tgl)->format('Y-m-d');
		$id_penerimaan = $this->input->post('id_penerimaan');
		$jenis = $this->input->post('jenis');
		
		$arr_valid = $this->rule_validasi(false);

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$q_penerimaan = $this->m_global->single_row('*', ['id_penerimaan' => $id_penerimaan, 'deleted_at' => null], 't_penerimaan');
		if(!$q_penerimaan) {
			$retval['status'] = false;
			$retval['pesan'] = 'Data Penerimaan Tidak Ditemukan';
			echo json_encode($retval);
			return;
		}

		$q_pembelian = $this->m_global->single_row('*', ['id_pembelian' => $q_penerimaan->id_pembelian, 'deleted_at' => null], 't_pembelian');
		if (!$q_pembelian) {
			$retval['status'] = false;
			$retval['pesan'] = 'Data Pembelian Tidak Ditemukan';
			echo json_encode($retval);
			return;
		}

		$is_update = false;

		if ($this->input->post('index') != '') {
			$cek = $this->t_retur_beli->get_by_id($this->input->post('id'));
			if ($cek) {
				$is_update = true;
				$kode = $cek->kode;
				$id_transaksi_fix = $cek->id;
			} else {
				$retval['status'] = false;
				$retval['pesan'] = 'Data Tidak Ditemukan';
				echo json_encode($retval);
				return;
			}
		}

		if (!$is_update) {
			$counter_trans = $this->t_retur_beli->get_max_transaksi();
			$kode = generate_kode_transaksi($tgl_fix, $counter_trans, strtoupper(strtolower('RTR')));

			$data['id_penerimaan'] = $id_penerimaan;
			$data['tanggal'] = $tgl_fix;
			$data['id_user'] = $this->session->userdata('id_user');
			$data['jenis_retur'] = $jenis;	
			$data['kode_retur'] = $kode;
			$data['id_agen'] = $q_pembelian->id_agen;
			$data['total_nilai_retur'] = 0;
			$data['created_at']	= $timestamp;
		} else {
			$data['id_user'] = $this->session->userdata('id_user');
			$data['jenis_retur'] = $jenis;
			$data['tanggal'] = $tgl_fix;
			$data['updated_at']	= $timestamp;
		}

		$this->db->trans_begin();

		if ($is_update) {
			$this->t_retur_beli->update(['id' => $id_transaksi_fix], $data);
		} else {
			$insert = $this->t_retur_beli->save($data);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan Data Transaksi';
		} else {
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Menambahkan Data Transaksi';
			$retval['kode'] = $kode;
		}

		echo json_encode($retval);
	}

	public function add_transaksi_det()
	{
		$kode = $this->input->get('kode');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		$profil = $this->m_global->single_row('*', ['deleted_at' => null], 'm_profil');
		// var_dump($diskon); die();

		/**
		 * data passing ke halaman view content
		 */
		$cek_header = $this->t_retur_beli->getDataHeader($kode)->row();

		if (!$cek_header) {
			return redirect($this->uri->segment(1));
		}

		$data_detail = $this->t_retur_beli->getDataDetail($cek_header->id)->result();
		$data_penerimaan = $this->t_retur_beli->getDetailPenerimaan($cek_header->id_penerimaan)->result();
		
		$data = array(
			'title' => 'Tambah Pengeluaran Lain-Lain',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'profil' => $profil,
			'data_header' => $cek_header,
			'data_detail' => $data_detail,
			'data_penerimaan' => $data_penerimaan
		);
		
		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => 'modal_detail',
			'js'	=> 'retur_beli.js',
			'view'	=> 'view_add'
		];

		$this->template_view->load_view($content, $data);
	}

	public function pakai_data()
	{
		try {
			$this->db->trans_begin();
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$id = $this->input->post('id');
			$id_retur = $this->input->post('id_retur');
			$id_stok = $this->input->post('id_stok');
			$qty = $this->input->post('qty');
			$kode_retur = $this->input->post('kode_retur');
			
			$select = "t_penerimaan_det.*, m_barang.nama as nama_barang, m_gudang.nama_gudang, m_gudang.id_gudang";
			$join = [
				['table' => 't_penerimaan', 'on' => 't_penerimaan_det.id_penerimaan = t_penerimaan.id_penerimaan'],
				['table' => 'm_gudang', 'on' => 't_penerimaan.id_gudang = t_penerimaan.id_gudang'],
				['table' => 'm_barang', 'on' => 't_penerimaan_det.id_barang = m_barang.id_barang'],
			];
			$data_where = ['t_penerimaan_det.id_penerimaan_det' => $id, 't_penerimaan_det.deleted_at' => null];
			$cek_trans = $this->m_global->single_row($select, $data_where, 't_penerimaan_det', $join);
			
			if(!$cek_trans) {
				echo json_encode([
					'status' => false,
					'pesan' => 'Data Penerimaan tidak ditemukan'
				]);
				return;
			}

			$cek_trans_retur = $this->m_global->single_row('*', ['id' => $id_retur, 'deleted_at' => null], 't_retur_beli');

			if(!$cek_trans_retur) {
				echo json_encode([
					'status' => false,
					'pesan' => 'Data Retur tidak ditemukan'
				]);
				return;
			}

			if($qty > $cek_trans->qty) {
				echo json_encode([
					'status' => false,
					'pesan' => 'Qty Retur melebihi jumlah Penerimaan'
				]);
				return;
			}

			if($qty == "" || $qty <= 0) {
				echo json_encode([
					'status' => false,
					'pesan' => 'Retur tidak boleh kosong'
				]);
				return;
			}

			$cek_trans_det = $this->m_global->single_row('*', ['id_retur_beli' => $id_retur, 'id_stok' => $id_stok, 'deleted_at' => null], 't_retur_beli_det');
			if($cek_trans_det) {
				echo json_encode([
					'status' => false,
					'pesan' => 'Maaf Barang yang dipilih sudah ada. Silahkan Hapus Terlebih dahulu'
				]);
				return;
			}

			$data = [
				'id_retur_beli' => $id_retur,
				'id_stok' => $id_stok,
				'qty' => $qty,
				'harga' => $cek_trans->harga,
				'harga_total' => $qty * $cek_trans->harga,
				'created_at' => $timestamp
			];

			$insert = $this->m_global->store($data, 't_retur_beli_det');

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal Hapus Data';
			} else {
				### update data header
				$q_grand_total = $this->t_retur_beli->getTotalTransaksiDet($id_retur)->row();
				$upd = $this->m_global->update('t_retur_beli', ['total_nilai_retur' => $q_grand_total->total, 'updated_at' => $timestamp], ['id' => $id_retur]);
	
				if ($upd) {
					$mutasi = $this->lib_mutasi->simpan_mutasi($cek_trans->id_barang, $qty, 6, $kode_retur, $cek_trans->id_gudang);
					$this->db->trans_commit();
					$retval['status'] = true;
					$retval['pesan'] = 'Sukses Menambahkan Data ';
				} else {
					$this->db->trans_rollback();
					$retval['status'] = false;
					$retval['pesan'] = 'Gagal Menambahkan Data';
				}
			}
		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}

		echo json_encode($retval);
	}

	public function fetch()
	{
		$id = $this->input->post('id');
		$data = $this->t_retur_beli->getDataDetail($id)->result();
		foreach ($data as $key => $row) {
		?>
			<tr>
				<td style="vertical-align: middle;"><?php $key++; echo $key; ?></td>
				<td style="vertical-align: middle;"><?php echo $row->nama_barang; ?></td>
				<td style="vertical-align: middle;"><?php echo $row->nama_gudang; ?></td>
				<td style="vertical-align: middle;"><?php echo $row->qty; ?></td>
				<td style="vertical-align: middle;"><?php echo 'Rp ' . number_format($row->harga); ?></td>
				<td style="vertical-align: middle;"><?php echo 'Rp ' . number_format($row->harga_total); ?></td>
				<td style="vertical-align: middle;"><button class="btn-danger" alt="batalkan" onclick="hapus_trans_det(<?php echo $row->id; ?>)"><i class="fa fa-times"></i></button></td>
			</tr>
		<?php
		}

		$data_total = $this->total_tabel_trans($id);
		?>
		<tr>
			<td style="vertical-align: middle;font-weight:bold;" colspan="5">Grand Total</td>
			<td style="vertical-align: middle;font-weight:bold;font-size:16px;" colspan="2"><?php echo $data_total; ?></td>
		</tr>
		<?php
	}

	public function total_tabel_trans($id)
	{
		$hasil = $this->t_retur_beli->getTotalTransaksiDet($id)->row();
		$data = array();
		if (!empty($hasil)) {
			// var_dump($data->total);
			$data['total'] = 'Rp ' . number_format($hasil->total);
		} else {
			$data['total'] = 0;
		}

		return $data['total'];
	}

	public function hapus_trans_detail()
	{
		try {
			$this->db->trans_begin();
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');

			$id = $this->input->post('id');
			
			$join = [
				['table' => 't_retur_beli', 'on' => 't_retur_beli_det.id_retur_beli = t_retur_beli.id'],
				['table' => 't_stok', 'on' => 't_retur_beli_det.id_stok = t_stok.id_stok'],
			];

			$data_where = ['t_retur_beli_det.id' => $id, 't_retur_beli_det.deleted_at' => null];
			$cek_trans = $this->m_global->single_row('t_retur_beli_det.*, t_retur_beli.kode_retur, t_stok.id_gudang, t_stok.id_barang', $data_where, 't_retur_beli_det', $join);
			
			$del = $this->m_global->soft_delete($data_where, 't_retur_beli_det');

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal Hapus Data';
			} else {
				### update data header
				$q_grand_total = $this->t_retur_beli->getTotalTransaksiDet($cek_trans->id_retur_beli)->row();
				$upd = $this->m_global->update('t_retur_beli', ['total_nilai_retur' => $q_grand_total->total, 'updated_at' => $timestamp], ['id' => $cek_trans->id_retur_beli]);
	
				if ($upd) {
					$mutasi = $this->lib_mutasi->updateMutasi(
						$cek_trans->id_barang, 
						-abs($cek_trans->qty), 
						6, 
						$cek_trans->kode_retur, 
						$cek_trans->id_gudang
					);
					$this->db->trans_commit();
					$retval['status'] = true;
					$retval['pesan'] = 'Sukses Menghapus Data ';
				} else {
					$this->db->trans_rollback();
					$retval['status'] = false;
					$retval['pesan'] = 'Gagal Menghapus Data';
				}
			}
		} catch (\Throwable $th) {
			//throw $th;
		}

		echo json_encode($retval);
	}

	public function get_detail_transaksi()
	{
		$id = $this->input->get('id');
		$kode = $this->input->get('kode');

		$header = $this->t_retur_beli->getDataHeader($kode)->row();

		// if (!$header) {
		// 	return redirect($this->uri->segment(1));
		// }

		$data_detail = $this->t_retur_beli->getDataDetail($header->id)->result();
		$data_penerimaan = $this->t_retur_beli->getDetailPenerimaan($header->id_penerimaan)->result();

		$html_det = '';
		if ($data_detail) {
			$total_harga_sum = 0;
			foreach ($data_detail as $key => $value) {
				$total_harga_sum += $value->harga_total;
				$html_det .= '<tr>
					<td style="vertical-align: middle;">' . $value->qty . '</td>
					<td style="vertical-align: middle;">' . $value->nama_barang . '</td>
					<td style="vertical-align: middle;">' . $value->nama_gudang . '</td>
					<td style="vertical-align: middle;" align="right">' . number_format($value->harga) . '</td>
					<td style="vertical-align: middle;" align="right">' . number_format($value->harga_total) . '</td>
				</tr>';
			}
			$html_det .= '<tr>
				<td colspan="4" style="vertical-align: middle;font-weight:bold;" align="center">Grand Total</td>
				<td style="vertical-align: middle;font-weight:bold;" align="right">' . number_format($total_harga_sum) . '</td>
			</tr>';
		} else {
			$html_det .= '<tr>
				<td style="vertical-align: middle;" colspan="5" align="center">Belum ada data transaksi ...</td>
			</tr>';
		}

		echo json_encode([
			'header' => $header,
			'jenis' => ($header->jenis_retur == '1') ? 'Ganti Barang' : 'Potong Nota',
			'penerimaan' => $data_penerimaan,
			'html_det' => $html_det
		]);
	}

	public function delete_transaksi()
	{
		try {
			$this->db->trans_begin();
			$id = $this->input->post('id');
			$kode = $this->input->post('kode');

			$cek = $this->m_global->single_row('*', ['id' => $id, 'deleted_at' => null], 't_retur_beli');
			$cek2 = $this->t_retur_beli->getDataDetail($id);
			$cek2 = $cek2->result();

			if ($cek2) {
				$del = $this->m_global->soft_delete(['id' => $id], 't_retur_beli');
			}

			if ($cek2) {
				$loop_data = $cek2;
				foreach ($loop_data as $key => $value) {
					// update data lap keuangan
					$mutasi = $this->lib_mutasi->updateMutasi(
						$value->id_barang, 
						-abs($value->qty), 
						6, 
						$cek->kode_retur, 
						$value->id_gudang
					);

					if ($mutasi) {
						$this->db->trans_commit();
					} else {
						$this->db->trans_rollback();
						$retval['status'] = false;
						$retval['pesan'] = 'Gagal Hapus Data';
						echo json_encode($retval);
						return;
					}
				}

				$del2 = $this->m_global->soft_delete(['id_retur_beli' => $id], 't_retur_beli_det');
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal Hapus Data';
			} else {
				$this->db->trans_commit();
				$retval['status'] = true;
				$retval['pesan'] = 'Sukses Hapus Data ';
			}

			echo json_encode($retval);
		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
			echo json_encode($retval);
		}
	}

	// ===============================================
	private function rule_validasi($detail_transaksi = false)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		if($detail_transaksi == FALSE) {
			### validasi transaksi header
			if ($this->input->post('id_penerimaan') == '') {
				$data['inputerror'][] = 'id_penerimaan';
				$data['error_string'][] = 'Wajib Memilih Penerimaan';
				$data['status'] = FALSE;
			}
			if ($this->input->post('jenis') == '') {
				$data['inputerror'][] = 'jenis';
				$data['error_string'][] = 'Wajib Memilih Jenis Retur';
				$data['status'] = FALSE;
			}
			if ($this->input->post('tanggal') == '') {
				$data['inputerror'][] = 'tanggal';
				$data['error_string'][] = 'Wajib Memilih tanggal';
				$data['status'] = FALSE;
			}
		}else{
			### validasi transaksi detail
			// if ($this->input->post('id_penerimaan') == '') {
			// 	$data['inputerror'][] = 'id_penerimaan';
			// 	$data['error_string'][] = 'Wajib Memilih Penerimaan';
			// 	$data['status'] = FALSE;
			// }
			// if ($this->input->post('jenis') == '') {
			// 	$data['inputerror'][] = 'jenis';
			// 	$data['error_string'][] = 'Wajib Memilih Jenis Retur';
			// 	$data['status'] = FALSE;
			// }
			// if ($this->input->post('tanggal') == '') {
			// 	$data['inputerror'][] = 'tanggal';
			// 	$data['error_string'][] = 'Wajib Memilih tanggal';
			// 	$data['status'] = FALSE;
			// }
		}
		


		return $data;
	}

	// ===============================================

	public function cek_qty_stok()
	{
		$qty = $this->input->post('qty');
		$id_barang = $this->input->post('id_barang');
		$id_gudang = $this->input->post('id_gudang');
		$cek = $this->m_global->single_row('*', [
			'id_barang' => $id_barang,
			'id_gudang' => $id_gudang,
			'deleted_at' => null,
		], 't_stok');

		if ($qty >= $cek->qty) {
			echo json_encode($cek->qty);
		} else {
			echo json_encode($qty);
		}
	}

	public function cetak_invoice()
	{
		$order_id = $this->input->get('order_id');
		$pen      = $this->m_global->getSelectedData('t_penjualan', array('order_id' => $order_id))->row();
		$data['invoice'] = $this->m_penjualan->getPenjualanDet($pen->id_penjualan)->result();
		$this->load->view('view_cetak_invoice_penjualan', $data);
	}

	public function change_qty()
	{
		$this->db->trans_begin();
		$id_penjualan_det = $this->input->post('id');
		$qty              = $this->input->post('qty');

		$penjualan_det     = $this->m_global->getSelectedData('t_penjualan_det', array('id_penjualan_det' => $id_penjualan_det))->row();



		$subtotal = $penjualan_det->harga_diskon * $qty;
		$data = array(
			'qty' => $qty,
			'sub_total' => $subtotal
		);

		$data_where = array('id_penjualan_det' => $id_penjualan_det);
		$update = $this->m_penjualan->updatePenjualandet($data_where, $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal Mengubah Data';
		} else {
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Mengubah Data ';
			// $retval['order_id'] = $order_id;
		}

		echo json_encode($retval);
	}
}
