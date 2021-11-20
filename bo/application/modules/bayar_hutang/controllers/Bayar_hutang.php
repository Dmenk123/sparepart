<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bayar_hutang extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('t_bayar_hutang');
		$this->load->model('t_pembelian');
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
			'title' => 'Pengelolaan Pembayaran Hutang Pembelian',
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
			'js'	=> 'bayar_hutang.js',
			'view'	=> 'view_list'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_bayar_hutang()
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

		$listData = $this->t_bayar_hutang->get_datatable_transaksi($paramdata);
		$datas = [];
		$i = 1;
		foreach ($listData as $key => $value) {
			$datas[$key][] = $i++;
			$datas[$key][] = tanggal_indo($value->tanggal);
			$datas[$key][] = $value->kode;
			$datas[$key][] = ($value->is_lunas == 1) ? 'Lunas' : 'Belum Lunas';
			$datas[$key][] = $value->nama_user;
			$datas[$key][] = $value->keterangan;
			$datas[$key][] = number_format($value->nilai_bayar, 0, ',', '.');

			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">';

			if($value->is_lunas != 1) {
				$str_aksi .= '<button class="dropdown-item" onclick="edit_transaksi(\'' . $value->kode . '\',\'' . $value->id . '\')">
								<i class="la la-pencil"></i> Edit Transaksi
							</button>';
			}
			
			$str_aksi .= '<button class="dropdown-item" onclick="detail_transaksi(\'' . $value->kode . '\',\'' . $value->id . '\')">
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

	public function new_bayar_utang()
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		$counter_trans = $this->t_bayar_hutang->get_max_transaksi();
		$a = $this->t_bayar_hutang->getDataLapKeuangan();
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'Pambayaran Hutang Baru',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'data_hutang' => $this->t_bayar_hutang->getDataLapKeuangan(),
			'kode_trans'=> generate_kode_transaksi($tgl, $counter_trans, 'BYR'),
			'mode'		=> 'add',
		);

		// $mode = $this->input->get('mode');
		
		// if ($mode == 'edit') {
		// 	$order_id = $this->input->get('order_id');
		// 	$pembelian = $this->m_global->getSelectedData('t_pembelian', array('order_id' => $order_id))->row();
		// 	$data['pembelian'] = $pembelian;
		// 	$data['mode'] = $mode;
		// 	$data['title'] = "Edit Pembelian";
		// 	$data['id_pembelian'] = $pembelian->id_penjualan;
		// 	$data['kode_trans'] = $pembelian->kode_pembelian;
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
			'js'	=> 'bayar_hutang.js',
			'view'	=> 'view_new'
		];

		$this->template_view->load_view($content, $data);
	}

	public function simpan_pembayaran()
	{
		try {
			$this->db->trans_begin();
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');

			$id_trans = $this->input->post('id_trans');
			$id_trans_det = $this->input->post('id_trans_det');
			$kode_trans = $this->input->post('kode_trans');
			$tanggal = $this->input->post('tanggal');
			$tanggal = DateTime::createFromFormat('d/m/Y', $tanggal)->format('Y-m-d');
			
			$kode_pembelian = $this->input->post('kode_pembelian');
			$data_beli = $this->m_global->single_row('*', ['kode_pembelian' => $kode_pembelian, 'deleted_at' => null], 't_pembelian');
			if(!$data_beli) {
				$retval['status'] = false;
				$retval['pesan'] = 'Data Pembelian tidak ditemukan';
				echo json_encode($retval);
				return;
			}

			$hutang = $this->input->post('hutang');
			$pembayaran = trim($this->input->post('pembayaran'));
			
			$hutang = (float)str_replace('.', '', $hutang);
			$pembayaran = (float)str_replace('.', '', $pembayaran);
			$keterangan = $this->input->post('keterangan');

			$arr_valid = $this->rule_validasi($hutang, $pembayaran);
			if ($arr_valid['status'] == FALSE) {
				echo json_encode($arr_valid);
				return;
			}

			if($id_trans == '') {
				$is_update = false;
			}else{
				$is_update = true;
			}

			if($is_update == false) {
				$max_transaksi = $this->m_global->max('id', 't_bayar_hutang', [
					'id_pembelian' => $data_beli->id_pembelian,
					'deleted_at' => null
				]);

				if($max_transaksi->id != null) {
					$id_transaksi = $max_transaksi->id;
				}else{
					$new_id = $this->m_global->max('id', 't_bayar_hutang', [
						'deleted_at' => null
					]);

					if($new_id->id != null) {
						$id_transaksi = $new_id->id+1;
					}else{
						$id_transaksi = 1;
					}
				}
	
				$max_transaksi_det = $this->m_global->max('id_bayar_det', 't_bayar_hutang', [
					'id_pembelian' => $data_beli->id_pembelian
				]);
	
				if($max_transaksi_det != null) {
					$id_transaksi_det = $max_transaksi_det->id_bayar_det+1;
				}else{
					$id_transaksi_det = 1;
				}
			}else{
				$id_transaksi = $id_trans;
				$id_transaksi_det = $id_trans_det;
			}

			### insert pembayaran
			$arr_pembayaran = [
				'id_pembelian' => $data_beli->id_pembelian,
				'kode' => $kode_trans,
				'tanggal' => $tanggal,
				'nilai_bayar' => $pembayaran,
				'keterangan' => $keterangan,
				'id_user' => $this->session->userdata('id_user'),
			];

			if(!$is_update) {
				$arr_pembayaran += [
					'id' => $id_transaksi,
					'id_bayar_det' => $id_transaksi_det,
					'created_at' => $timestamp,
				];
			}else{
				$arr_pembayaran += [
					'updated_at' => $timestamp,
				];
			}

			if(!$is_update) {
				$simpan = $this->m_global->save($arr_pembayaran, 't_bayar_hutang');
			}else{
				$simpan = $this->m_global->update('t_bayar_hutang', $arr_pembayaran, ['id' => $id_transaksi, 'id_bayar_det' => $id_transaksi_det]);
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menambahkan Pembayaran';
			} else {
				$this->db->trans_commit();

				#### insert laporan keuangan (pengurangan piutang pembelian)
				$lap = $this->lib_mutasi->insertDataLap(
					$pembayaran, 
					16,
					$data_beli->kode_pembelian,
					null,
					$tanggal,
					$kode_trans,
				);

				if($lap['status']) {

					### cek data pembayaran disamakan dengan total pembelian apakah sudah sama
					if ((float)$data_beli->total_pembelian == (float)$this->t_bayar_hutang->sum_pembayaran_by_id($id_transaksi)) {
						$update = $this->t_pembelian->update(['id_pembelian' => $data_beli->id_pembelian], ['is_lunas' => 1, 'tgl_lunas' => $tanggal]);
					} 

					$retval['status'] = true;
					$retval['pesan'] = 'Sukses menambahkan Penerimaan';
				}else{
					$retval['status'] = false;
					$retval['pesan'] = 'Gagal menambahkan Penerimaan';
				}
				
			}
						
		} catch (\Throwable $th) {
			// $this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}

		echo json_encode($retval);
	}

	public function get_detail_transaksi()
	{
		$id = $this->input->get('id');
		$kode = $this->input->get('kode');
		
		$join = [ 
			['table' => 't_pembelian', 'on'	=> 't_bayar_hutang.id_pembelian = t_pembelian.id_pembelian'],
			['table' => 'm_user', 'on' => 't_pembelian.id_user = m_user.id'],
		];
		$header = $this->m_global->single_row('t_bayar_hutang.*, t_pembelian.kode_pembelian, t_pembelian.is_lunas, m_user.nama as nama_user', ['t_bayar_hutang.kode' => $kode, 't_bayar_hutang.deleted_at' => null], 't_bayar_hutang', $join);
		
		echo json_encode([
			'header' => $header,
		]);
		
	}

	public function delete_transaksi()
	{
		try {
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');
			$id = $this->input->post('id');
			$kode = $this->input->post('kode');
			
			$cek_header = $this->m_global->single_row("*", ['id' => $id, 'kode' => $kode, 'deleted_at' => null], 't_bayar_hutang');

			if(!$cek_header) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal hapus Transaksi';
				echo json_encode($retval);
				return;
			}

			$data_beli = $this->m_global->single_row('*', ['id_pembelian' => $cek_header->id_pembelian, 'deleted_at' => null], 't_pembelian');
			if(!$data_beli) {
				$retval['status'] = false;
				$retval['pesan'] = 'Data Pembelian tidak ditemukan';
				echo json_encode($retval);
				return;
			}


			$this->db->trans_begin();

			$cek_lap_keu = $this->m_global->single_row("*", ['id_kategori_trans' => 16, 'kode_reff2' => $cek_header->kode], 't_lap_keuangan');

			if($cek_lap_keu) {
				$del_lap = $this->m_global->soft_delete(['id_kategori_trans' => 16, 'kode_reff2' => $cek_header->kode], 't_lap_keuangan');
			}
			
			### soft_delete
			$del = $this->m_global->soft_delete(['id' => $cek_header->id, 'kode' => $cek_header->kode], 't_bayar_hutang');
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menghapus Pembayaran Hutang';
			} else {
				$this->db->trans_commit();

				### cek data pembayaran disamakan dengan total pembelian apakah sudah sama
				if ((float)$data_beli->total_pembelian == (float)$this->t_bayar_hutang->sum_pembayaran_by_id($cek_header->id)) {
					$data_update['is_lunas'] = 1;
					$data_update['tgl_lunas'] = $tgl;
				}else{
					$data_update['is_lunas'] = null;
					$data_update['tgl_lunas'] = null;
				}

				$update = $this->t_pembelian->update(['id_pembelian' => $data_beli->id_pembelian], $data_update);

				$retval['status'] = true;
				$retval['pesan'] = 'Sukses menghapus Pembayaran';
			}

		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}

		echo json_encode($retval);
	}
	// ===============================================
	private function rule_validasi($hutang=null, $pembayaran=null)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('kode_trans') == '') {
			$data['inputerror'][] = 'kode_trans';
			$data['error_string'][] = 'Wajib Mengisi Kode Transaksi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('tanggal') == '') {
			$data['inputerror'][] = 'tanggal';
			$data['error_string'][] = 'Wajib Memilih Tanggal';
			$data['status'] = FALSE;
		}

		if ($this->input->post('kode_pembelian') == '') {
			$data['inputerror'][] = 'kode_pembelian';
			$data['error_string'][] = 'Wajib Menginputkan Kode Pembelian';
			$data['status'] = FALSE;
		}

		if ($this->input->post('pembayaran') == '') {
			$data['inputerror'][] = 'pembayaran';
			$data['error_string'][] = 'Wajib Menginputkan Pembayaran';
			$data['status'] = FALSE;
		}

		if ($pembayaran > $hutang) {
			$data['inputerror'][] = 'pembayaran';
			$data['error_string'][] = 'Pembayaran Melebihi Nilai Hutang';
			$data['status'] = FALSE;
		}

		if ($this->input->post('hutang_txt') == '') {
			$data['inputerror'][] = 'hutang_txt';
			$data['error_string'][] = 'Wajib Menginputkan Hutang';
			$data['status'] = FALSE;
		}

		return $data;
	}

	public function get_harga_barang()
	{
		$id_barang = $this->input->get('id_barang');

		### cek hpp di stok mutasi
		$q_mutasi = $this->db->select('hpp')
						->from('t_stok_mutasi')
						->where('deleted_at', null)
						->group_start()
							->where('id_kategori_trans', 1)
							->or_where('id_kategori_trans', 3)
						->group_end() 
						->order_by('tanggal desc')
						->get();
	
		$cek_mutasi = $q_mutasi->row();
		
		if($cek_mutasi) {
			$hpp = $cek_mutasi->hpp;
		}else{
			#####
			$hpp = 0;
		}

		echo json_encode([
			'hpp' => $hpp
		]);
	}

	// ===============================================

	public function update_new_invoice()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		$arr_valid = $this->rule_validasi();
		
		$id_penjualan       = $this->input->post('id_penjualan');
		$id_pelanggan 		= $this->input->post('pelanggan');
		$id_sales 			= $this->input->post('sales');
		$tgl_jatuh_tempo	= $this->input->post('tgl_jatuh_tempo');
		$date = str_replace('/', '-', $tgl_jatuh_tempo);
		$jatuh_tempo 		= date("Y-m-d H:i:s", strtotime($date) );

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$this->db->trans_begin();
		
		$data = [
			'id_pelanggan' 		=> $id_pelanggan,
			'id_sales' 			=> $id_sales,
			'tgl_jatuh_tempo'	=> $jatuh_tempo,
			'updated_at'		=> $timestamp
		];

		$data_where = array('id_Penjualan'=>$id_penjualan);
		
		$update = $this->m_penjualan->update($data_where, $data);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal Mengubah Data Invoice';
		}else{
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Mengubah Data Invoice';
			// $retval['order_id'] = $order_id;
		}

		echo json_encode($retval);
	}
	
	function generateRandomString($tgl) {
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
					"01"=>"A", 
					"02"=>"B", 
					"03"=>"C", 
					"04"=>"D", 
					"05"=>"E", 
					"06"=>"F", 
					"07"=>"G", 
					"08"=>"H", 
					"09"=>"I", 
					"10"=>"J", 
					"11"=>"K", 
					"12"=>"L"
				);
		

		$no_faktur = $nama_bulan[$bulan].''.$tgl_date.''.$randomString;
		return $no_faktur;
	}

	public function cetak_invoice()
	{
		$order_id = $this->input->get('order_id');
		$pen      = $this->m_global->getSelectedData('t_penjualan', array('order_id'=>$order_id))->row();
		$data['invoice'] = $this->m_penjualan->getPenjualanDet($pen->id_penjualan)->result();
		$this->load->view('view_cetak_invoice_penjualan', $data);
	}
}
