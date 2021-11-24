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

		$paramdata = [
			'bulan' => $bulan,
			'tahun' => $tahun,
		];

		$listData = $this->t_retur_masuk->get_datatable_transaksi($paramdata);
		$datas = [];
		$i = 1;
		foreach ($listData as $key => $value) {
			$datas[$key][] = $i++;
			$datas[$key][] = tanggal_indo($value->tanggal);
			$datas[$key][] = $value->kode;
			$datas[$key][] = $value->kode_retur;
			$datas[$key][] = $value->nama_perusahaan;
			$datas[$key][] = $value->nama_user;
			$datas[$key][] = number_format($value->total_nilai_retur, 0, ',', '.');
			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">
						<button class="dropdown-item" onclick="detail_transaksi(\'' . $value->kode . '\',\'' . $value->id . '\')">
							<i class="la la-desktop"></i> Lihat Detail
						</button>
						<button class="dropdown-item" onclick="delete_transaksi(\'' . $value->kode . '\',\'' . $value->id . '\')">
							<i class="la la-trash"></i> Hapus
						</button>
						<button class="dropdown-item" onclick="cetak_invoice(\'' . $value->kode . '\',\'' . $value->id . '\')">
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
			'title' => 'Tambah Penerimaan Retur',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'data_retur' => $this->m_global->getSelectedData('t_retur_beli', array('deleted_at' => NULL, 'jenis_retur' => 1, 'is_terima_all' => NULL))->result(),
			'data_gudang' => $this->m_global->multi_row('*', ['deleted_at' => null], 'm_gudang', null, 'nama_gudang'),
			'mode'		=> 'add',
		);

		$mode = $this->input->get('mode');

		if ($mode == 'edit') {
			$kode = $this->input->get('kode');
			$data_header = $this->m_global->getSelectedData('t_retur_masuk', array('kode' => $kode))->row();

			if (!$data_header) {
				return redirect($this->uri->segment(1));
			}

			$data['old_data'] = $data_header;
			$data['mode'] = $mode;
			$data['title'] = "Edit Penerimaan Retur";
			$data['id_retur'] = $data_header->id;
			$data['kode'] = $data_header->kode;
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
			'js'	=> 'retur_masuk.js',
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
		$id_retur = $this->input->post('id_retur');
		$id_gudang = $this->input->post('id_gudang');
		
		$arr_valid = $this->rule_validasi();

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$q_retur = $this->m_global->single_row('*', ['id' => $id_retur, 'deleted_at' => null], 't_retur_beli');
		if(!$q_retur) {
			$retval['status'] = false;
			$retval['pesan'] = 'Data Penerimaan Tidak Ditemukan';
			echo json_encode($retval);
			return;
		}

		$is_update = false;

		if ($this->input->post('index') != '') {
			$cek = $this->t_retur_masuk->get_by_id($this->input->post('id'));
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
			$counter_trans = $this->t_retur_masuk->get_max_transaksi();
			$kode = generate_kode_transaksi($tgl_fix, $counter_trans, strtoupper(strtolower('RTM')));

			$data['id_retur_beli'] = $id_retur;
			$data['tanggal'] = $tgl_fix;
			$data['id_user'] = $this->session->userdata('id_user');
			$data['id_gudang'] = $id_gudang;
			$data['kode'] = $kode;
			$data['id_agen'] = $q_retur->id_agen;
			$data['total_nilai_retur'] = 0;
			$data['created_at']	= $timestamp;
		} else {
			$data['id_user'] = $this->session->userdata('id_user');
			$data['tanggal'] = $tgl_fix;
			$data['updated_at']	= $timestamp;
		}

		$this->db->trans_begin();

		if ($is_update) {
			$this->t_retur_masuk->update(['id' => $id_transaksi_fix], $data);
		} else {
			$insert = $this->t_retur_masuk->save($data);
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
		
		/**
		 * data passing ke halaman view content
		 */
		$cek_header = $this->t_retur_masuk->getDataHeader($kode)->row();

		if (!$cek_header) {
			return redirect($this->uri->segment(1));
		}

		$data_detail = $this->t_retur_masuk->getDataDetail($cek_header->id)->result();
		// $data_penerimaan = $this->t_retur_masuk->getDetailPenerimaan($cek_header->id_penerimaan)->result();

		
		// echo "<pre>";
		// print_r ($cek_header);
		// echo "</pre>";

		// echo "<pre>";
		// print_r ($data_detail);
		// echo "</pre>";

		// exit;
		
		
		$data = array(
			'title' => 'Tambah Penerimaan Retur',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'profil' => $profil,
			'data_header' => $cek_header,
			'data_detail' => $data_detail,
			// 'data_penerimaan' => $data_penerimaan
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
			'view'	=> 'view_add'
		];

		$this->template_view->load_view($content, $data);
	}

	public function simpan_penerimaan_retur()
	{
		try {
			$this->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');
			
			$id_retur_masuk = $this->input->post('id');
			$id_retur_beli = $this->input->post('id_retur');
			$kode_retur_masuk = $this->input->post('kode');
			
			$cek_retur_masuk = $this->m_global->single_row("*", ['id' => $id_retur_masuk, 'deleted_at' => null], 't_retur_masuk');

			$sum_harga_total = 0;
			for ($i=0; $i < count($this->input->post('qty')); $i++) { 
				$data_stok = $this->m_global->single_row("*", ['id_barang' => $this->input->post('id_barang')[$i], 'id_gudang' => $cek_retur_masuk->id_gudang, 'deleted_at' => null], 't_stok');
				$sum_harga_total += $this->input->post('harga_total_raw')[$i];
				
				$joni = [ 
					['table' => 't_retur_beli', 'on' => 't_retur_beli_det.id_retur_beli = t_retur_beli.id'],
				];

				$cek_retur_beli_det = $this->m_global->single_row(
					"t_retur_beli_det.*, t_retur_beli.kode_retur", 
					['t_retur_beli_det.id' => $this->input->post('retur_beli_det')[$i], 't_retur_beli_det.deleted_at' => null], 
					't_retur_beli_det', 
					$joni
				);

				// var_dump($cek_retur_beli_det);exit;

				### insert retur masuk det
				$arr_retur_masuk = [
					'qty' => $this->input->post('qty')[$i],
					'id_retur_masuk' => $id_retur_masuk,
					'id_stok' => $data_stok->id_stok,
					'harga' => $cek_retur_beli_det->harga,
					'harga_total' => $this->input->post('harga_total_raw')[$i],
					'created_at' => $timestamp,
				];

				$insert_det = $this->m_global->save($arr_retur_masuk, 't_retur_masuk_det');
				
				#### update retur_beli_det
				$where_update = ['id' => $this->input->post('retur_beli_det')[$i]];
				$data_update['qty_terima'] = $cek_retur_beli_det->qty_terima + $this->input->post('qty')[$i];

				### cek qty tabel retur beli det, jika qty penerimaan / sum penerimaan sesuai jumlah, set flag is_terima
				if($cek_retur_beli_det->qty == $this->t_retur_masuk->sum_barang_masuk_pertransaksi($data_stok->id_stok, $id_retur_beli)) {
					$data_update['is_terima'] = 1;
				} 

				$update = $this->m_global->update('t_retur_beli_det', $data_update, $where_update);

				#### mutasi
				$mutasi = $this->lib_mutasi->mutasiMasuk(
					$this->input->post('id_barang')[$i], 
					$this->input->post('qty')[$i], 
					null, 
					5, 
					null, 
					$cek_retur_beli_det->harga, 
					$cek_retur_masuk->id_gudang, 
					$cek_retur_masuk->kode
				);
				
			}

			### update t_retur_masuk (total_harga)
			$data_where = ['id' => $id_retur_masuk];
			$this->m_global->update('t_retur_masuk', ['total_nilai_retur' => $sum_harga_total + $cek_retur_masuk->total_nilai_retur], $data_where);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menambahkan Penerimaan Retur';
			} else {
				$this->db->trans_commit();

				$data_retur_beli_det = $this->m_global->multi_row('*', ['id_retur_beli' => $id_retur_beli], 't_retur_beli_det');
				$arr = [];

				foreach ($data_retur_beli_det as $key => $value) {
					if($value->qty == $value->qty_terima) {
						$txt = 'ok';	
					}else{
						$txt = 'belum';
					}

					$arr[] = $txt; 
				}

				### jika tidak ada yg belum
				### update t_retur_beli_det set flag is_terima_all = 1 where is_terima di masing-masing det not null
				if(!in_array('belum', $arr)) {
					$data_where = ['id' => $id_retur_beli];
					$this->m_global->update('t_retur_beli', ['is_terima_all' => 1], $data_where);
				}
				
				$retval['status'] = true;
				$retval['pesan'] = 'Sukses menambahkan Penerimaan Retur';
			}

			$retval['status'] = true;
			$retval['pesan'] = 'Sukses menambahkan Penerimaan Retur';
			
		} catch (\Throwable $th) {
			// $this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}

		echo json_encode($retval);
	}

	public function fetch()
	{
		$id = $this->input->post('id');
		$id_retur = $this->input->post('id_retur');

		$flag_is_update = false;
		$flag_has_transaksi = false;

		$data_masuk = $this->t_retur_masuk->getDataDetail($id)->result();
		
		if($data_masuk == null) {			
			### transaksi baru
			$data = $this->t_retur_masuk->getDetailRetur($id_retur)->result();
			$flag_has_transaksi = true;
		}else{
			### transaksi update
			$data = $data_masuk;
			$flag_is_update = true;
		}

		foreach ($data as $key => $row) {
			$idx_row = ($flag_is_update) ? $row->id : $row->id;
			$harga = ($flag_is_update) ? $row->harga : $row->harga;
			$harga_total = ($flag_is_update) ? $row->harga_total : $row->harga_total;

			if ($flag_has_transaksi) {
				### qty retur - qty diterima (jika retur ini sudah pernah diterima)
				$qty = $row->qty - $row->qty_terima;
				### replace value harga_total
				$harga_total = $qty * $harga;
			} else {
				$qty = $row->qty;
			}
			if(!$qty == 0) {  
			?>

			<tr>
                <td width="10%">
					<input type="number" min="1" max="<?=$qty;?>" class="form-control" width="5" id="qty_order_<?php echo $idx_row;?>" value="<?php echo $qty; ?>" onchange="tes(<?php echo $idx_row ?>)" name="qty[]">
					<input type="hidden" class="form-control kelas_htotal" name="harga_total_raw[]" id="harga_total_raw_<?php echo $idx_row;?>" value="<?php echo $harga_total; ?>">
					<input type="hidden" class="form-control" value="<?php echo $idx_row; ?>" name="retur_beli_det[]">
					<input type="hidden" class="form-control" value="<?php echo $row->id_barang; ?>" name="id_barang[]">
				</td>
                <td style="vertical-align: middle;"><?php echo $row->nama_barang; ?></td>
                <td style="vertical-align: middle;"><?php echo 'Rp '.number_format($harga); ?></td>
                <td style="vertical-align: middle;" id="harga_total_<?php echo $idx_row;?>"><?php echo 'Rp '.number_format($harga_total); ?></td>
				<td style="vertical-align: middle;"><button type="button" class="btn-danger" alt="batalkan" onclick="hapus_trans_detail(this)"><i class="fa fa-times"></i></button></td>
            </tr>
			<?php
			}

		}
	}

	public function total_tabel_trans($id)
	{
		$hasil = $this->t_retur_masuk->getTotalTransaksiDet($id)->row();
		$data = array();
		if (!empty($hasil)) {
			// var_dump($data->total);
			$data['total'] = 'Rp ' . number_format($hasil->total);
		} else {
			$data['total'] = 0;
		}

		return $data['total'];
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
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');
			$id_retur_masuk = $this->input->post('id');
			$kode_retur_masuk = $this->input->post('kode');
			
			$cek_header = $this->m_global->single_row("*", ['id' => $id_retur_masuk, 'deleted_at' => null], 't_retur_masuk');

			if(!$cek_header) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal hapus Penerimaan Retur';
				echo json_encode($retval);
				return;
			}

			$this->db->trans_begin();

			$data_detail = $this->t_retur_masuk->getDataDetail($cek_header->id)->result();
			
			$arr_temp_detail = null;
			
			if($data_detail) {
				$arr_temp_detail = $data_detail;
			}
			
			foreach ($arr_temp_detail as $key => $value) {
				#### hapus stok mutasi
				$mutasi = $this->lib_mutasi->hapusMutasiMasuk(
					$value->id_barang, 
					$value->qty, 
					5,  
					null, 
					$cek_header->id_gudang, 
					$cek_header->kode,
				);

				// rollback retur beli
				$joni = [ 
					['table' => 't_retur_beli', 'on' => 't_retur_beli_det.id_retur_beli = t_retur_beli.id'],
				];

				$cek_retur_beli_det = $this->m_global->single_row(
					"t_retur_beli_det.*, t_retur_beli.kode_retur", 
					['t_retur_beli_det.id_retur_beli' => $value->id_retur_beli, 't_retur_beli_det.id_stok' => $value->id_stok, 't_retur_beli_det.deleted_at' => null], 
					't_retur_beli_det', 
					$joni
				);

				if($cek_retur_beli_det) {
					$where_update = ['id_retur_beli' => $value->id, 'id_stok' => $value->id_stok, 'deleted_at' => null];
					$data_update['is_terima'] = null;
					$data_update['qty_terima'] = $cek_retur_beli_det->qty_terima - $value->qty;

					// $data_update['tgl_terima'] = $cek_pembelian_det->qty_terima - $value->qty;
					// $data_update['reff_terima'] = $cek_pembelian_det->qty_terima - $value->qty;
					
					$update = $this->m_global->update('t_retur_beli_det', $data_update, $where_update);
				}
			}

			### soft_delete
			$del = $this->m_global->soft_delete(['id' => $cek_header->id], 't_retur_masuk');
			$del_det = $this->m_global->soft_delete(['id_retur_masuk' => $cek_header->id], 't_retur_masuk_det');
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menghapus Penerimaan Retur';
			} else {
				$this->db->trans_commit();

				$data_retur_beli_det = $this->m_global->multi_row('*', ['id_retur_beli' => $cek_header->id_retur_beli], 't_retur_beli_det');
				$arr = [];

				foreach ($data_retur_beli_det as $key => $value) {
					if ($value->qty == $value->qty_terima) {
						$txt = 'ok';
					} else {
						$txt = 'belum';
					}

					$arr[] = $txt;
				}

				### jika tidak ada yg belum
				### update t_pembelian set flag is_terima_all = 1 where is_terima di masing-masing det not null
				if (!in_array('belum', $arr)) {
					$data_upd = ['is_terima_all' => 1];
				}else{
					$data_upd = ['is_terima_all' => null];
				}

				$data_where = ['id' => $cek_header->id_retur_beli];
				$this->m_global->update('t_retur_beli', $data_upd, $data_where);

				$retval['status'] = true;
				$retval['pesan'] = 'Sukses menghapus Penerimaan Retur';
			}

		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}

		echo json_encode($retval);
	}

	// ===============================================
	private function rule_validasi()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		### validasi transaksi header
		if ($this->input->post('id_retur') == '') {
			$data['inputerror'][] = 'id_retur';
			$data['error_string'][] = 'Wajib Memilih Kode Retur';
			$data['status'] = FALSE;
		}
		if ($this->input->post('tanggal') == '') {
			$data['inputerror'][] = 'tanggal';
			$data['error_string'][] = 'Wajib Memilih Tanggal Retur';
			$data['status'] = FALSE;
		}
		if ($this->input->post('id_gudang') == '') {
			$data['inputerror'][] = 'id_gudang';
			$data['error_string'][] = 'Wajib Memilih Gudang';
			$data['status'] = FALSE;
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
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');

		$this->db->trans_begin();
		$id_retur_beli_det = $this->input->post('id');
		$qty              = $this->input->post('qty');
		$kodereff		  = $this->input->post('kodereff');

		$retur_beli_det = $this->m_global->getSelectedData('t_retur_beli_det', ['id' => $id_retur_beli_det])->row();
		$retur_masuk = $this->m_global->getSelectedData('t_retur_masuk', ['id_retur_beli' => $retur_beli_det->id_retur_beli, 'kode' => $kodereff])->row();
		$retur_masuk_det =  $this->m_global->getSelectedData('t_retur_masuk_det', array('id_retur_masuk' => $retur_masuk->id, 'id_stok' => $retur_beli_det->id_stok))->result();

		$qty_in = 0;
		
		if($retur_masuk_det) {
			foreach ($retur_masuk_det as $key => $value) {
				$qty_in += $value->qty;
			}
		}

		### jika qty inputan lebih besar dari seharusnya (bisa karena barang sudah diterima)
		### return false
		if(($qty > $qty_in) && ($qty_in > 0)) {
			$this->db->trans_rollback();
			$retval['qty'] = $qty_in;
			$retval['harga_total'] = 'Rp '.number_format($retur_beli_det->harga * $qty_in);
			$retval['harga_raw'] = $retur_beli_det->harga * $qty_in;
			echo json_encode($retval);
			return;
		}

		$qty_remaining = $retur_beli_det->qty - $qty_in;

		### jika qty inputan lebih besar dari seharusnya (bisa karena barang sudah diterima)
		### return false
		if($qty > $qty_remaining) {
			$retval['qty'] = $qty_remaining;
			$retval['harga_total'] = 'Rp '.number_format($retur_beli_det->harga * $qty_remaining);
			$retval['harga_raw'] = $retur_beli_det->harga * $qty_remaining;
			echo json_encode($retval);
			return;
		}

		$subtotal = $retur_beli_det->harga * $qty; 
				
		$retval['qty'] = $qty;
		$retval['harga_total'] = 'Rp '.number_format($retur_beli_det->harga * $qty);
		$retval['harga_raw'] = $retur_beli_det->harga * $qty;

		echo json_encode($retval);
	}
}
