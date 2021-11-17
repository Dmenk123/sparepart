<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_lain extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('t_penerimaan_lain', 't_in');
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
			'title' => 'Pengelolaan Penerimaan Lain-Lain',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'kategori' => $this->m_global->multi_row('*', ['is_lain' => 1, 'deleted_at' => null, 'is_penerimaan !=', null], 'm_kategori_transaksi', null, 'nama_kategori_trans')
		);

		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => 'modal_detail_penerimaan_lain',
			'js'	=> 'penerimaan_lain.js',
			'view'	=> 'view_list'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_data_penerimaan()
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

		$listData = $this->t_in->get_datatable_penerimaan($paramdata);
		$datas = [];
		$i = 1;
		foreach ($listData as $key => $value) {
			$datas[$key][] = $i++;
			$datas[$key][] = tanggal_indo($value->tanggal);
			$datas[$key][] = $value->kode;
			$datas[$key][] = $value->nama_kategori_trans;
			$datas[$key][] = $value->nama_user;
			$datas[$key][] = number_format($value->nilai_total, 0, ',', '.');
			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">
						<button class="dropdown-item" onclick="edit_transaksi(\'' . $value->kode . '\',\'' . $value->id . '\')">
							<i class="la la-pencil"></i> Edit Transaksi
						</button>
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

	public function fetch()
	{
		// $order_id  = $this->input->get('order_id');
		// $penjualan = $this->m_global->getSelectedData('t_penjualan', array('order_id'=>$order_id))->row();
		// $id        = $penjualan->id_penjualan;
		$id = $this->input->post('id');
		$data = $this->t_in->getPenerimaanDet($id)->result();
		foreach ($data as $row) {
		?>
			<tr>
				<td width="10%"><input type="hidden" class="form-control" width="5" id="qty_order_<?php echo $row->id; ?>" value="<?php echo $row->qty; ?>" onchange="tes(<?php echo $row->id ?>)"><?php echo $row->qty; ?></td>
				<td style="vertical-align: middle;"><?php echo $row->nama; ?></td>
				<td style="vertical-align: middle;"><?php echo $row->keterangan; ?></td>
				<td style="vertical-align: middle;"><?php echo 'Rp ' . number_format($row->nilai); ?></td>
				<td style="vertical-align: middle;"><?php echo 'Rp ' . number_format($row->sub_total); ?></td>
				<td style="vertical-align: middle;"><button class="btn-danger" alt="batalkan" onclick="hapus_trans_det(<?php echo $row->id; ?>)"><i class="fa fa-times"></i></button></td>
			</tr>
		<?php
		}
	}

	public function total_tabel_trans()
	{

		$id = $this->input->post('id');
		$hasil = $this->t_in->getTotalTransaksiDet($id)->row();
		$data = array();
		if (!empty($hasil)) {
			// var_dump($data->total);
			$data['total'] = 'Rp ' . number_format($hasil->total);
		} else {
			$data['total'] = 0;
		}

		echo json_encode($data);
	}

	public function new_penerimaan()
	{
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');

		/**
		 * data passing ke halaman view content
		 */

		$data = array(
			'title' => 'Penerimaan Baru',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'kategori' => $this->m_global->multi_row('*', ['deleted_at' => NULL, 'is_lain' => 1, 'is_penerimaan' => 1], 'm_kategori_transaksi', NULL, 'nama_kategori_trans asc'),
			'mode'		=> 'add',
		);

		$mode = $this->input->get('mode');

		if ($mode == 'edit') {
			$kode = $this->input->get('kode');
			$data_header = $this->m_global->getSelectedData('t_penerimaan_lain', array('kode' => $kode))->row();

			if (!$data_header) {
				return redirect('penerimaan_lain');
			}

			$data['old_data'] = $data_header;
			$data['mode'] = $mode;
			$data['title'] = "Edit Penerimaan";
			$data['id_pengeluaran'] = $data_header->id;
			$data['kode'] = $data_header->kode;
			$data['kategori'] = $this->m_global->multi_row('*', ['deleted_at' => NULL, 'is_lain' => 1], 'm_kategori_transaksi', NULL, 'nama_kategori_trans asc');
			// $data['tgl_jatuh_tempo'] = date("d/m/Y", strtotime($invoice->tgl_jatuh_tempo));
		}
		// else{
		// 	return redirect('penjualan');
		// }

		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => null,
			'js'	=> 'penerimaan_lain.js',
			'view'	=> 'view_new_penerimaan_lain'
		];

		$this->template_view->load_view($content, $data);
	}

	public function add_new_penerimaan()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		// $tgl = $obj_date->format('Y-m-d');
		$tgl = $this->input->post('tanggal');
		$tgl_fix = DateTime::createFromFormat('d/m/Y', $tgl)->format('Y-m-d');

		$arr_valid = $this->rule_validasi();

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$is_update = false;

		if ($this->input->post('id_penerimaan') != '') {
			$cek = $this->t_in->get_by_id($this->input->post('id_penerimaan'));
			if ($cek) {
				$is_update = true;
				$kode = $cek->kode;
				$id_penerimaan_fix = $cek->id;
			} else {
				$retval['status'] = false;
				$retval['pesan'] = 'Data Tidak Ditemukan';
				echo json_encode($retval);
				return;
			}
		}

		if (!$is_update) {
			$cek_kategori = $this->m_global->single_row('*', [
				'id_kategori_trans' => $this->input->post('kategori'),
				'deleted_at' => null,
			], 'm_kategori_transaksi');

			$counter_pengeluaran = $this->t_in->get_max_transaksi();
			$kode = generate_kode_transaksi($tgl_fix, $counter_pengeluaran, strtoupper(strtolower($cek_kategori->singkatan)));

			$data['kode'] = $kode;
			$data['id_kategori_trans'] = $cek_kategori->id_kategori_trans;
			$data['id_user'] = $this->session->userdata('id_user');
			$data['tanggal'] = $tgl_fix;
			$data['created_at']	= $timestamp;
		} else {
			$data['id_user'] = $this->session->userdata('id_user');
			$data['tanggal'] = $tgl_fix;
			$data['updated_at']	= $timestamp;
		}

		$this->db->trans_begin();

		if ($is_update) {
			$this->t_in->update(['id' => $id_penerimaan_fix], $data);
		} else {
			$insert = $this->t_in->save($data);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan Data Penerimaan';
		} else {
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Menambahkan Data Penerimaan';
			$retval['kode'] = $kode;
		}

		echo json_encode($retval);
	}

	public function add_penerimaan_det()
	{
		$kode = $this->input->get('kode');
		$id = $this->input->get('index');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		$profil = $this->m_global->single_row('*', ['deleted_at' => null], 'm_profil');
		// var_dump($diskon); die();

		/**
		 * data passing ke halaman view content
		 */
		$cek_header = $this->t_in->getPenerimaan($kode)->row();

		if (!$cek_header) {
			return redirect('pengeluaran_lain');
		}

		$barang = $this->m_global->multi_row('*', ['deleted_at' => null, 'sku' => null, 'id_kategori' => $cek_header->id_kategori_trans], 'm_barang', null, 'nama asc');


		$data = array(
			'title' => 'Tambah Penerimaan Lain-Lain',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'profil' => $profil,
			'data_header' => $cek_header,
			'barang' => $barang
		);
		// $data['gudang']  = $this->m_global->getSelectedData('m_gudang', array('deleted_at' => NULL));
		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => 'modal_detail_penerimaan_lain',
			'js'	=> 'penerimaan_lain.js',
			'view'	=> 'view_add_penerimaan_lain'
		];

		$this->template_view->load_view($content, $data);
	}


	public function save_trans_detail()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$arr_valid = $this->rule_validasi_order();

		$id_penerimaan_lain	= $this->input->post('id_penerimaan_lain');
		$id_barang 	= $this->input->post('id_barang');
		$keterangan = $this->input->post('keterangan');
		$qty     = $this->input->post('qty');
		$nilai   = $this->input->post('nilai');
		$nilai   = str_replace('.', '', $nilai);

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$data_header = $this->m_global->getSelectedData('t_penerimaan_lain', ['id' => $id_penerimaan_lain])->row();

		if (!$data_header) {
			$retval['status'] = false;
			$retval['pesan'] = 'Data Transaksi Tidak Ditemukan';
			echo json_encode($retval);
			return;
		}

		$datadet = $this->m_global->getSelectedData('t_penerimaan_lain_det', ['id_penerimaan_lain' => $id_penerimaan_lain, 'id_barang' => $id_barang])->row();

		if ($datadet) {
			$retval['status'] = false;
			$retval['pesan'] = 'Barang yang dipilih sudah ada, mohon memilih barang yang lain';
			echo json_encode($retval);
			return;
		}

		$kode = $data_header->kode;
		$sub_total = $nilai * $qty;
		$this->db->trans_begin();

		$data_trans = [
			'id_penerimaan_lain'  => $id_penerimaan_lain,
			'id_barang' 	=> $id_barang,
			'qty'           => $qty,
			'nilai' 		=> $nilai,
			'sub_total'     => $sub_total,
			'keterangan'     => $keterangan,
			'created_at'	=> $timestamp
		];

		### insert detail
		$ins = $this->m_global->save($data_trans, 't_penerimaan_lain_det');
		### update header
		$sum_transaksi = $this->t_in->getTotalTransaksiDet($id_penerimaan_lain)->row();

		if (!empty($sum_transaksi)) {
			$nilai_sum_total = $sum_transaksi->total;
		} else {
			$nilai_sum_total = 0;
		}

		$upd = $this->m_global->update('t_penerimaan_lain', ['nilai_total' => $nilai_sum_total, 'updated_at' => $timestamp], ['id' => $id_penerimaan_lain]);

		//if ($ins && $upd) {
		$laporan = $this->lib_mutasi->insertDataLap($sub_total, $data_header->id_kategori_trans, $kode, null, $data_header->tanggal);

		if ($laporan['status'] === FALSE) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan detil transaksi';
			return;
		}
		// }else{
		// 	echo 'jaran';
		// 	exit;
		// }	

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan detil transaksi';
		} else {
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses menambahkan detil transaksi';
		}

		echo json_encode($retval);
	}

	public function hapus_trans_detail()
	{
		$this->db->trans_begin();
		$id = $this->input->post('id');
		$join = [
			['table' => 't_pengeluaran_lain', 'on' => 't_pengeluaran_lain_det.id_pengeluaran_lain = t_pengeluaran_lain.id'],
		];

		$data_where = ['t_pengeluaran_lain_det.id' => $id, 't_pengeluaran_lain_det.deleted_at' => null];

		$cek_trans = $this->m_global->single_row('t_pengeluaran_lain_det.*, t_pengeluaran_lain.kode, t_pengeluaran_lain.id_kategori_trans, t_pengeluaran_lain.tanggal', $data_where, 't_pengeluaran_lain_det', $join);

		$del = $this->m_global->soft_delete($data_where, 't_pengeluaran_lain_det');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal Hapus Data';
		} else {
			// update data lap keuangan
			$keu = $this->lib_mutasi->updateDataLap(
				-$cek_trans->sub_total,
				$cek_trans->id_kategori_trans,
				$cek_trans->kode,
				null,
				$cek_trans->tanggal
			);

			if ($keu['status'] == true) {
				$this->db->trans_commit();
				$retval['status'] = true;
				$retval['pesan'] = 'Sukses Hapus Data ';
			} else {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal Hapus Data';
			}
		}

		echo json_encode($retval);
	}

	public function get_detail_transaksi()
	{
		$id = $this->input->get('id');
		$kode = $this->input->get('kode');

		$header = $this->t_in->getPenerimaan($kode)->row();
		$detail = $this->t_in->getPenerimaanDet($id)->result();

		$html_det = '';
		if ($detail) {
			$total_harga_sum = 0;
			foreach ($detail as $key => $value) {
				$total_harga_sum += $value->sub_total;
				$html_det .= '<tr>
					<td style="vertical-align: middle;">' . $value->qty . '</td>
					<td style="vertical-align: middle;">' . $value->nama . '</td>
					<td style="vertical-align: middle;">' . $value->keterangan . '</td>
					<td style="vertical-align: middle;" align="right">' . number_format($value->nilai) . '</td>
					<td style="vertical-align: middle;" align="right">' . number_format($value->sub_total) . '</td>
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
			'html_det' => $html_det
		]);
	}

	public function delete_transaksi()
	{
		try {
			$this->db->trans_begin();
			$id = $this->input->post('id');
			$kode = $this->input->post('kode');

			$cek = $this->m_global->single_row('*', ['id' => $id, 'deleted_at' => null], 't_pengeluaran_lain');
			$cek2 = $this->t_in->getPengeluaranDet($id);
			$cek2 = $cek2->result();

			if ($cek2) {
				$del = $this->m_global->soft_delete(['id' => $id], 't_pengeluaran_lain');
			}

			if ($cek2) {
				$loop_data = $cek2;
				foreach ($loop_data as $key => $value) {
					// update data lap keuangan
					$keu = $this->lib_mutasi->updateDataLap(
						-$value->sub_total,
						$cek->id_kategori_trans,
						$cek->kode,
						null,
						$cek->tanggal
					);

					if ($keu['status'] == true) {
						$this->db->trans_commit();
					} else {
						$this->db->trans_rollback();
						$retval['status'] = false;
						$retval['pesan'] = 'Gagal Hapus Data';
						echo json_encode($retval);
						return;
					}
				}

				$del2 = $this->m_global->soft_delete(['id_pengeluaran_lain' => $id], 't_pengeluaran_lain_det');
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
	private function rule_validasi($is_update = false, $skip_pass = false)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('kategori') == '') {
			$data['inputerror'][] = 'kategori';
			$data['error_string'][] = 'Wajib Memilih kategori';
			$data['status'] = FALSE;
		}

		if ($this->input->post('tanggal') == '') {
			$data['inputerror'][] = 'tanggal';
			$data['error_string'][] = 'Wajib Memilih tanggal';
			$data['status'] = FALSE;
		}


		return $data;
	}

	// ===============================================
	private function rule_validasi_order($is_update = false, $skip_pass = false)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;



		// if ($this->input->post('icon_menu') == '') {
		// 	$data['inputerror'][] = 'icon_menu';
		//     $data['error_string'][] = 'Wajib mengisi icon menu';
		//     $data['status'] = FALSE;
		// }

		if ($this->input->post('id_barang') == '') {
			$data['inputerror'][] = 'id_barang';
			$data['error_string'][] = 'Wajib Memilih Barang yang akan diorder';
			$data['status'] = FALSE;
		}

		if ($this->input->post('qty') == '') {
			$data['inputerror'][] = 'qty';
			$data['error_string'][] = 'Wajib Menginputkan Jumlag Quantity';
			$data['status'] = FALSE;
		}


		return $data;
	}



	public function get_option_barang()
	{
		$id_gudang = $this->input->get('id_gudang');
		$select = 't_stok.*, m_barang.nama';
		$where = ['t_stok.id_gudang' => $id_gudang, 't_stok.deleted_at' => null];
		$join = [
			['table' => 'm_barang', 'on' => 't_stok.id_barang = m_barang.id_barang'],
			// ['table' => 'm_agen', 'on' => 't_pembelian.id_agen = m_agen.id_agen'],
			// ['table' => 'm_user', 'on' => 't_pembelian.id_user = m_user.id'],
		];

		$order_by = 'm_barang.nama asc';
		$data_stok = $this->m_global->multi_row($select, $where, 't_stok', $join, $order_by);

		$html = '<option value="0">-PILIH-</option>';
		if ($data_stok) {
			foreach ($data_stok as $key => $value) {
				$html .= "<option value='$value->id_barang'>" . $value->nama . "</option>";
			}
		}

		echo json_encode([
			'html' => $html,
		]);
	}

	public function select_qty_barang()
	{
		$id_barang = $this->input->get('id_barang');
		$id_gudang = $this->input->get('id_gudang');
		$data_stok = $this->m_global->single_row('*', ['id_gudang' => $id_gudang, 'id_barang' => $id_barang, 'deleted_at' => null], 't_stok');

		if ($data_stok) {
			$qty = $data_stok->qty;
		} else {
			$qty = 0;
		}

		echo json_encode($qty);
	}






	function generateRandomString($tgl)
	{
		$bulan      = date('m', strtotime($tgl));
		$tgl_date   = date('d', strtotime($tgl));
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$length = 3;
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		$nama_bulan = array(
			"01" => "A",
			"02" => "B",
			"03" => "C",
			"04" => "D",
			"05" => "E",
			"06" => "F",
			"07" => "G",
			"08" => "H",
			"09" => "I",
			"10" => "J",
			"11" => "K",
			"12" => "L"
		);


		$no_faktur = $nama_bulan[$bulan] . '' . $tgl_date . '' . $randomString;
		return $no_faktur;
	}



	public function menu_edit()
	{
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');

		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'NEW INVOICE',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
		);

		$order_id = $this->input->get('order_id');
		$data['invoice'] = $this->m_penjualan->getPenjualan($order_id)->row();

		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => null,
			'js'	=> 'penjualan.js',
			'view'	=> 'view_menu_edit'
		];

		$this->template_view->load_view($content, $data);
	}

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
