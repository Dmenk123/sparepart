<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

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
			'title' => 'Pengelolaan Daftar Pembelian',
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
			'modal' => null,
			'js'	=> 'pembelian.js',
			'view'	=> 'view_list_pembelian'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_pembelian()
	{
		$list = $this->t_pembelian->get_datatable_pembelian();
		
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $value) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = DateTime::createFromFormat('Y-m-d H:i:s', $value->created_at)->format('d-m-Y');
			$row[] = $value->kode_pembelian;
			$row[] = $value->nama_perusahaan;
			$row[] = number_format($value->total_pembelian);
			$row[] = $value->metode_bayar;
			$row[] = $value->status_terima;

			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">
						<button class="dropdown-item" onclick="detail_pembelian(\''.$value->kode_pembelian.'\')">
							<i class="la la-desktop"></i> Lihat Pembelian
						</button>
			';
			
			if($value->status_terima != 'Lunas') {
				$str_aksi .= '
					<button class="dropdown-item" onclick="edit_pembelian(\''.$value->kode_pembelian.'\')">
						<i class="la la-pencil"></i> Edit Pembelian
					</button>
					<button class="dropdown-item" onclick="delete_pembelian(\''.$value->kode_pembelian.'\')">
						<i class="la la-trash"></i> Hapus
					</button>
				';
			}

			$str_aksi .= '</div></div>';
			
			
			$row[] = $str_aksi;

			$data[] = $row;
		}//end loop

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->t_pembelian->count_all(),
			"recordsFiltered" => $this->t_pembelian->count_filtered(),
			"data" => $data
		];
		
		echo json_encode($output);
	}

	public function new_pembelian()
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');

		$counter_pembelian = $this->t_pembelian->get_max_pembelian();
		
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'Pembelian Baru',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'agen' 		=> $this->m_global->getSelectedData('m_agen', array('deleted_at' => NULL)),
			'kode_trans'=> generate_kode_transaksi($tgl, $counter_pembelian, 'ORD'),
			'mode'		=> 'add',
		);

		$mode = $this->input->get('mode');
		if ($mode == 'edit') {
			$order_id = $this->input->get('order_id');
			$pembelian = $this->m_global->getSelectedData('t_pembelian', array('order_id' => $order_id))->row();
			$data['pembelian'] = $pembelian;
			$data['mode'] = $mode;
			$data['title'] = "Edit Pembelian";
			$data['id_pembelian'] = $pembelian->id_penjualan;
			$data['kode_trans'] = $pembelian->kode_pembelian;
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
			'js'	=> 'pembelian.js',
			'view'	=> 'view_new_pembelian'
		];

		$this->template_view->load_view($content, $data);
	}

	public function add_pembelian()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		$kode = $this->input->get('kode_pembelian');
		$is_update = $this->input->get('update');
		

		### cek jika kode valid
		$cek_kode = $this->m_global->single_row("*", ['kode_pembelian' => $kode, 'deleted_at' => null], 't_pembelian');
		if(!$cek_kode) {
			return redirect('pembelian');
		}

		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		
		// var_dump($diskon); die();
			
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'data_user' => $data_user,
			'data_role'	=> $data_role,
		);

		if($is_update == 'true') {
			$data['title'] = 'Update Pembelian';
		}else{
			$data['title'] = 'Pembelian Baru';
		}

		$data['pembelian']  = $cek_kode;
		$data['kode_trans']  = $kode;
		$data['id_pembelian']  = $cek_kode->id_pembelian;
		$data['id_agen']  = $cek_kode->id_agen;
		$data['barang']  = $this->m_global->getSelectedData('m_barang', array('deleted_at'=>NULL));
		$data['agen']  = $this->m_global->single_row("*", ['id_agen' => $cek_kode->id_agen, 'deleted_at' => null], 'm_agen');
		

		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => null,
			'js'	=> 'pembelian.js',
			'view'	=> 'view_add_pembelian'
		];

		$this->template_view->load_view($content, $data);
	}

	public function save_new_pembelian()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		$arr_valid = $this->rule_validasi();

		$id_agen 			= $this->input->post('id_agen');
		$kode_pembelian 	= $this->input->post('kode_pembelian');
		$is_kredit 			= ($this->input->post('method_bayar') == '2') ? 1 : null;

		$counter_pembelian = $this->t_pembelian->get_max_pembelian();
		$cek_kode = generate_kode_transaksi($tgl, $counter_pembelian, 'ORD');

		## untuk menghindari duplikat kode
		if($kode_pembelian == $cek_kode) {
			$kode_pembelian_fix = $kode_pembelian;
		}else{
			$kode_pembelian_fix = $cek_kode;
		}

		### jika is_kredit flag is_lunas = null
		if($is_kredit == 1) {
			$is_lunas = null;
			$tgl_lunas = null;
		}else{
			$is_lunas = 1;
			$tgl_lunas = $tgl;
		}

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$this->db->trans_begin();

		$data_pembelian = [
			'kode_pembelian' => $kode_pembelian_fix,
			'id_agen' 	=> $id_agen,
			'id_user' 	=> $this->session->userdata('id_user'),
			'tanggal' 	=> $tgl,
			'total_pembelian' => 0,
			'total_disc'     => 0,
			'is_kredit'	=> $is_kredit,
			'is_lunas' => $is_lunas,
			'tgl_lunas' => $tgl_lunas,
			'created_at' => $timestamp,
		];

		$insert = $this->m_global->save($data_pembelian, 't_pembelian');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan Data Pembelian';
		} else {
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Menambahkan Data Pembelian';
			$retval['kode'] = $kode_pembelian_fix;
		}

		echo json_encode($retval);
	}

	public function save_pembelian()
	{
		try {
			$this->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');
			$arr_valid = $this->rule_validasi_pembelian();
			
			$id_agen = $this->input->post('id_agen');
			$id_pembelian = $this->input->post('id_pembelian');
			$id_barang 	= $this->input->post('id_barang');
			$qty      = $this->input->post('qty');

			$harga 	    	= trim($this->input->post('hsat'));
			$harga    		= str_replace('.', '', $harga);

			$diskon    		= str_replace('%', '', $this->input->post('dis'));
			$diskon    		= str_replace(',', '.', $diskon);

			if ($diskon > 0) {
				$nilai = ($diskon / 100) * $harga;
			} else {
				$nilai = 0;
			}

			$harga_satuan 	= $harga - $nilai;

			if ($arr_valid['status'] == FALSE) {
				echo json_encode($arr_valid);
				return;
			}
			
			$sub_total = $harga_satuan * $qty;

			$cek_header = $this->m_global->single_row("*", ['id_pembelian' => $id_pembelian, 'deleted_at' => null], 't_pembelian');

			$data_detail = [
				'id_pembelian' => $id_pembelian,
				'id_barang' => $id_barang,
				'qty' => $qty,
				'harga' => $harga,
				'disc' => $nilai,
				'disc_persen' => $diskon,
				'harga_fix' => $harga_satuan,
				'harga_total_fix' => $sub_total,
				'created_at' => $timestamp
			];

			$insert_detail = $this->m_global->save($data_detail, 't_pembelian_det');

			$q_grand_total = $this->t_pembelian->getTotalPembelian($id_pembelian)->row();
			$q_disc_total = $this->t_pembelian->getTotalDiskon($id_pembelian)->row();

			$update_header = $this->m_global->update('t_pembelian', ['total_pembelian' => $q_grand_total->total, 'total_disc' => $q_disc_total->disc_total, 'updated_at' => $timestamp], ['id_pembelian' => $id_pembelian]);
			
			$ins_laporan = $this->lib_mutasi->insertDataLap($q_grand_total->total ,1, $cek_header->kode_pembelian, $cek_header->is_kredit);
			
			if($ins_laporan['status'] == false) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menambahkan Pembelian';
				return;
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menambahkan Pembelian';
			} else {
				$this->db->trans_commit();
				$retval['status'] = true;
				$retval['pesan'] = 'Sukses menambahkan Pembelian';
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
        $data = $this->t_pembelian->getPembelianDet($id)->result();
        foreach($data as $row){
            ?>
            <tr>
                <td width="10%"><input type="number" class="form-control" width="5" id="qty_order_<?php echo $row->id_pembelian_det;?>" value="<?php echo $row->qty; ?>" onchange="tes(<?php echo $row->id_pembelian_det ?>)"></td>
                <td style="vertical-align: middle;"><?php echo $row->nama; ?></td>
                <td style="vertical-align: middle;"><?php echo 'Rp '.number_format($row->harga_fix); ?></td>
                <td style="vertical-align: middle;"><?php echo 'Rp '.number_format($row->harga_total_fix); ?></td>
				<td style="vertical-align: middle;"><button class="btn-danger" alt="batalkan" onclick="hapus_trans_detail(<?php echo $row->id_pembelian_det; ?>)"><i class="fa fa-times"></i></button></td>
            </tr>
            <?php
        }
	}

	public function total_pembelian()
	{
		
		$id = $this->input->post('id');
		$hasil = $this->t_pembelian->getTotalPembelian($id)->row();
		$data = array();
		if (!empty($hasil)) {
			// var_dump($data->total);
			$data['total'] = 'Rp '.number_format($hasil->total);
		} else {
			$data['total'] = 0;
		}

		echo json_encode($data);
		
	}

	public function change_qty()
	{
		$this->db->trans_begin();
		$id_pembelian_det = $this->input->post('id');
		$qty              = $this->input->post('qty');
		$pembelian_det     = $this->m_global->getSelectedData('t_pembelian_det', array('id_pembelian_det' => $id_pembelian_det))->row();

		
		$subtotal = $pembelian_det->harga_fix * $qty; 
		
		$data = array(
			'qty' => $qty,
			'harga_total_fix' => $subtotal
		);
				
		$data_where = array('id_pembelian_det' => $id_pembelian_det);
		$update = $this->t_pembelian->updatePembelianDet($data_where, $data);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal Mengubah Data';
		}else{
			$this->db->trans_commit();

			$hasil_total = $this->t_pembelian->getTotalPembelian($pembelian_det->id_pembelian)->row();
			$update_header = $this->m_global->update('t_pembelian', ['total_pembelian' => $hasil_total->total], ['id_pembelian' => $pembelian_det->id_pembelian]);

			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Mengubah Data ';
			// $retval['order_id'] = $order_id;
		}

		echo json_encode($retval);
	}

	public function delete_pembelian()
	{
		try {
			$this->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');
			$kode_pembelian = $this->input->post('id');
			
			$cek_header = $this->m_global->single_row("*", ['kode_pembelian' => $kode_pembelian, 'deleted_at' => null], 't_pembelian');
			
			if(!$cek_header) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal hapus Pembelian';
				echo json_encode($retval);
				return;
			}

			### harddeletes
			$del = $this->m_global->force_delete(['id_pembelian' => $cek_header->id_pembelian], 't_pembelian');
			$del_det = $this->m_global->force_delete(['id_pembelian' => $cek_header->id_pembelian], 't_pembelian_det');

			$cek_lap_keu = $this->m_global->single_row("*", ['id_kategori_trans' => 1, 'kode_reff' => $cek_header->kode_pembelian], 't_lap_keuangan');
			if($cek_lap_keu) {
				$del_lap = $this->m_global->force_delete(['id_kategori_trans' => 1, 'kode_reff' => $cek_header->kode_pembelian], 't_lap_keuangan');
			}
			

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menghapus Pembelian';
			} else {
				$this->db->trans_commit();
				$retval['status'] = true;
				$retval['pesan'] = 'Sukses menghapus Pembelian';
			}

		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}

		echo json_encode($retval);
	}

	public function hapus_trans_detail()
	{
		try {
			$this->db->trans_begin();

			$id = $this->input->post('id');
			$pembelian_det     = $this->m_global->getSelectedData('t_pembelian_det', array('id_pembelian_det' => $id))->row();

			$id_pembelian = $pembelian_det->id_pembelian;

			$pembelian     = $this->m_global->getSelectedData('t_pembelian', array('id_pembelian' => $id_pembelian))->row();

			$kode_pembelian = $pembelian->kode_pembelian;

			$del = $this->m_global->force_delete(['id_pembelian_det' => $id], 't_pembelian_det');

			$hasil_total = $this->t_pembelian->getTotalPembelian($id_pembelian)->row();
			$update_header = $this->m_global->update('t_pembelian', ['total_pembelian' => $hasil_total->total], ['id_pembelian' => $id_pembelian]);
		
			$upd_laporan = $this->lib_mutasi->updateDataLap(
				$hasil_total->total,
				1, 
				$kode_pembelian,
				$pembelian->is_kredit,
			);
				
			if($upd_laporan['status'] == false) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menghapus detail Pembelian';
				return;
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menghapus detail Pembelian';
			} else {
				$this->db->trans_commit();
				$retval['status'] = true;
				$retval['pesan'] = 'Sukses menghapus detail Pembelian';
			}
		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}
		

		echo json_encode($retval);

	}

	// ===============================================
	private function rule_validasi($is_update = false, $skip_pass = false)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('id_agen') == '') {
			$data['inputerror'][] = 'id_agen';
			$data['error_string'][] = 'Wajib Memilih agen';
			$data['status'] = FALSE;
		}

		if ($this->input->post('kode_pembelian') == '') {
			$data['inputerror'][] = 'kode_pembelian';
			$data['error_string'][] = 'Wajib Menginputkan kode_pembelian';
			$data['status'] = FALSE;
		}

		return $data;
	}

	private function rule_validasi_pembelian($is_update = false, $skip_pass = false)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('id_barang') == '') {
			$data['inputerror'][] = 'id_barang';
			$data['error_string'][] = 'Wajib Memilih Barang';
			$data['status'] = FALSE;
		}

		if ($this->input->post('qty') == '') {
			$data['inputerror'][] = 'qty';
			$data['error_string'][] = 'Wajib Menginputkan qty';
			$data['status'] = FALSE;
		}

		// if ($this->input->post('dis') == '') {
		// 	$data['inputerror'][] = 'dis';
		// 	$data['error_string'][] = 'Wajib Menginputkan diskon';
		// 	$data['status'] = FALSE;
		// }

		if ($this->input->post('hsat') == '') {
			$data['inputerror'][] = 'hsat';
			$data['error_string'][] = 'Wajib Menginputkan satuan';
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
			// $hpp = number_format((float)$cek_mutasi->hpp, 2, '.', '');
			$hpp = (int)$cek_mutasi->hpp;
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
