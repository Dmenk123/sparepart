<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_pelanggan extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('m_agen');
		$this->load->model('m_pelanggan');
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
			'title' => 'Pengelolaan Data Master Pelanggan',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'provinsi'  => $this->m_global->getSelectedData('t_provinsi', NULL),
			'kota'  => $this->m_global->getSelectedData('t_kota', NULL),
		);

		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => 'modal_master_pelanggan',
			'js'	=> 'master_pelanggan.js',
			'view'	=> 'view_master_pelanggan'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_pelanggan()
	{
		$list = $this->m_pelanggan->get_datatable_user();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $pembeli) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $pembeli->nama_pembeli;
			$row[] = $pembeli->alamat;
			$row[] = $pembeli->nama_provinsi;
			$row[] = $pembeli->nama_kota;
			$row[] = $pembeli->kecamatan;
			$row[] = $pembeli->no_telp;
			$row[] = $pembeli->email;
			$row[] = $pembeli->nama_toko;
			
			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">
						<button class="dropdown-item" onclick="edit_pelanggan(\''.$pembeli->id_pelanggan.'\')">
							<i class="la la-pencil"></i> Edit Pelanggan
						</button>
						<button class="dropdown-item" onclick="delete_pelanggan(\''.$pembeli->id_pelanggan.'\')">
							<i class="la la-trash"></i> Hapus
						</button>
			';

			$str_aksi .= '</div></div>';
			$row[] = $str_aksi;

			$data[] = $row;
		}//end loop

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_agen->count_all(),
			"recordsFiltered" => $this->m_agen->count_filtered(),
			"data" => $data
		];
		
		echo json_encode($output);
	}

	public function edit_pelanggan()
	{
		$this->load->library('Enkripsi');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_by_id($id_user);
	
		$id = $this->input->post('id');
		//$oldData = $this->m_user->get_by_id($id);

		$select = "m_pelanggan.*";
		$where = ['m_pelanggan.id_pelanggan' => $id];
		$table = 'm_pelanggan';
		// $join = [ 
		// 	[
		// 		'table' => 'm_role',
		// 		'on'	=> 'm_user.id_role = m_role.id'
		// 	]
		// ];

		$oldData = $this->m_global->single_row($select, $where, $table);
		
		if(!$oldData){
			return redirect($this->uri->segment(1));
		}
		// var_dump($oldData);exit;
	
		
		$data = array(
			'data_user' => $data_user,
			'old_data'	=> $oldData,
		);
		
		echo json_encode($data);
	}

	public function add_data_pelanggan()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$arr_valid = $this->rule_validasi();
		
		$nama_pembeli 	= $this->input->post('nama_pembeli');
		$alamat 		= $this->input->post('alamat');
		$provinsi		= $this->input->post('provinsi');
		$kota			= $this->input->post('kota');
		$kecamatan		= $this->input->post('kecamatan');
		$no_telp		= $this->input->post('telp');
		$email			= $this->input->post('email');
		$nama_toko		= $this->input->post('nama_toko');

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$this->db->trans_begin();
		
		$data_pelanggan = [
			'nama_pembeli' => $nama_pembeli,
			'alamat' => $alamat,
			'id_provinsi' => $provinsi,
			'id_kota' => $kota,
			'kecamatan' => $kecamatan,
			'no_telp' => $no_telp,
			'email' => $email,
			'nama_toko' => $nama_toko
		];
		
		$insert = $this->m_pelanggan->save($data_pelanggan);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan master Pelanggan';
		}else{
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses menambahkan master Pelanggan';
		}

		echo json_encode($retval);
	}

	public function update_data_pelanggan()
	{
		$sesi_id_user = $this->session->userdata('id_user'); 
		$id_agen = $this->input->post('id_agen');
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		
		// if($this->input->post('skip_pass') != null){
		// 	$skip_pass = true;
		// }else{
		// 	$skip_pass = false;
		// }
		
		$arr_valid = $this->rule_validasi(true);

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$id_pelanggan   = $this->input->post('id_pelanggan');
		$nama_pembeli 	= $this->input->post('nama_pembeli');
		$alamat 		= $this->input->post('alamat');
		$provinsi		= $this->input->post('provinsi');
		$kota			= $this->input->post('kota');
		$kecamatan		= $this->input->post('kecamatan');
		$no_telp		= $this->input->post('telp');
		$email			= $this->input->post('email');
		$nama_toko		= $this->input->post('nama_toko');
		
		$q = $this->m_pelanggan->get_by_id($id_pelanggan);
		
		$this->db->trans_begin();

		$data_pelanggan = [
			'nama_pembeli' => $nama_pembeli,
			'alamat' => $alamat,
			'id_provinsi' => $provinsi,
			'id_kota' 	=> $kota,
			'kecamatan' 	=> $kecamatan,
			'no_telp' 	=> $no_telp,
			'email' 	=> $email,
			'nama_toko' 	=> $nama_toko,
			'updated_at' => $timestamp
		];
		

		$where = ['id_pelanggan' => $id_pelanggan];
		$update = $this->m_pelanggan->update($where, $data_pelanggan);

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$data['status'] = false;
			$data['pesan'] = 'Gagal update Master Pelanggan';
		}else{
			$this->db->trans_commit();
			$data['status'] = true;
			$data['pesan'] = 'Sukses update Master Pelanggan';
		}
		
		echo json_encode($data);
	}

	/**
	 * Hanya melakukan softdelete saja
	 * isi kolom updated_at dengan datetime now()
	 */
	public function delete_pelanggan()
	{
		$id_pelanggan = $this->input->post('id');
		$del = $this->m_pelanggan->softdelete_by_id($id_pelanggan);
		if($del) {
			$retval['status'] = TRUE;
			$retval['pesan'] = 'Data Master Pelanggan berhasil dihapus';
		}else{
			$retval['status'] = FALSE;
			$retval['pesan'] = 'Data Master Pelanggan berhasil dihapus';
		}

		echo json_encode($retval);
	}

	public function edit_status_user($id)
	{
		$input_status = $this->input->post('status');
		// jika aktif maka di set ke nonaktif / "0"
		$status = ($input_status == "aktif") ? $status = 0 : $status = 1;
			
		$input = array('status' => $status);

		$where = ['id' => $id];

		$this->m_user->update($where, $input);

		if ($this->db->affected_rows() == '1') {
			$data = array(
				'status' => TRUE,
				'pesan' => "Status User berhasil di ubah.",
			);
		}else{
			$data = array(
				'status' => FALSE
			);
		}

		echo json_encode($data);
	}

	// ===============================================
	private function rule_validasi($is_update=false, $skip_pass=false)
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

		if ($this->input->post('nama_pembeli') == '') {
			$data['inputerror'][] = 'nama_pembeli';
            $data['error_string'][] = 'Wajib Mengisi Nama Pembeli';
            $data['status'] = FALSE;
		}

		if ($this->input->post('alamat') == '') {
			$data['inputerror'][] = 'alamat';
            $data['error_string'][] = 'Wajib Mengisi Alamat Pelanggan';
            $data['status'] = FALSE;
		}

		if ($this->input->post('provinsi') == '') {
			$data['inputerror'][] = 'provinsi';
            $data['error_string'][] = 'Wajib Mengisi Alamat Provinsi Pelanggan';
            $data['status'] = FALSE;
		}

		if ($this->input->post('kota') == '') {
			$data['inputerror'][] = 'kota';
            $data['error_string'][] = 'Wajib Mengisi Alamat Kota Pelanggan';
            $data['status'] = FALSE;
		}

		if ($this->input->post('nama_toko') == '') {
			$data['inputerror'][] = 'nama_toko';
            $data['error_string'][] = 'Wajib Mengisi Nama Toko Pelanggan';
            $data['status'] = FALSE;
		}
        return $data;
	}

	private function konfigurasi_upload_img($nmfile)
	{ 
		//konfigurasi upload img display
		$config['upload_path'] = './files/img/barang_img/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
		$config['overwrite'] = TRUE;
		$config['max_size'] = '4000';//in KB (4MB)
		$config['max_width']  = '0';//zero for no limit 
		$config['max_height']  = '0';//zero for no limit
		$config['file_name'] = $nmfile;
		//load library with custom object name alias
		$this->load->library('upload', $config, 'file_obj');
		$this->file_obj->initialize($config);
	}

	private function konfigurasi_image_resize($filename)
	{
		//konfigurasi image lib
	    $config['image_library'] = 'gd2';
	    $config['source_image'] = './files/img/user_img/'.$filename;
	    $config['create_thumb'] = FALSE;
	    $config['maintain_ratio'] = FALSE;
	    $config['new_image'] = './files/img/user_img/'.$filename;
	    $config['overwrite'] = TRUE;
	    $config['width'] = 450; //resize
	    $config['height'] = 500; //resize
	    $this->load->library('image_lib',$config); //load image library
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();
	}

	private function konfigurasi_image_thumb($filename, $gbr)
	{
		//konfigurasi image lib
	    $config2['image_library'] = 'gd2';
	    $config2['source_image'] = './files/img/user_img/'.$filename;
	    $config2['create_thumb'] = TRUE;
	 	$config2['thumb_marker'] = '_thumb';
	    $config2['maintain_ratio'] = FALSE;
	    $config2['new_image'] = './files/img/user_img/thumbs/'.$filename;
	    $config2['overwrite'] = TRUE;
	    $config2['quality'] = '60%';
	 	$config2['width'] = 45;
	 	$config2['height'] = 45;
	    $this->load->library('image_lib',$config2); //load image library
	    $this->image_lib->initialize($config2);
	    $this->image_lib->resize();
	    return $output_thumb = $gbr['raw_name'].'_thumb'.$gbr['file_ext'];	
	}

	private function seoUrl($string) {
	    //Lower case everything
	    $string = strtolower($string);
	    //Make alphanumeric (removes all other characters)
	    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	    //Clean up multiple dashes or whitespaces
	    $string = preg_replace("/[\s-]+/", " ", $string);
	    //Convert whitespaces and underscore to dash
	    $string = preg_replace("/[\s_]/", "-", $string);
	    return $string;
	}

	public function get_kota()
	{
		$id = $this->input->post('id');
		$data_where = array('id_provinsi' => $id);
		$data       = $this->m_global->getSelectedData('t_kota', $data_where)->result();
		echo json_encode($data);
	}

	public function template_excel()
	{
		$file_url = base_url().'files/template_dokumen/template_master_pelanggan.xlsx';
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
		readfile($file_url); 
	}

	public function import_data_master()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');

		$file_mimes = ['text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		$retval = [];

		if(isset($_FILES['file_excel']['name']) && in_array($_FILES['file_excel']['type'], $file_mimes)) {
			$arr_file = explode('.', $_FILES['file_excel']['name']);
			$extension = end($arr_file);
			if('csv' == $extension){
				$reader = $this->excel->csv_reader_obj();
			} else {
				$reader = $this->excel->reader_obj();
			}

			$spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			
			for ($i=0; $i <count($sheetData); $i++) { 
				
				if ($sheetData[$i][0] == null || $sheetData[$i][1] == null || $sheetData[$i][2] == null || $sheetData[$i][3] == null) {
					if($i == 0) {
						$flag_kosongan = true;
						$status_import = false;
						$pesan = "Data Kosong...";
					}else{
						$flag_kosongan = false;
						$status_import = true;
						$pesan = "Data Sukses Di Import";
					}

					break;
				}

				$data['nama_pembeli'] = strtoupper(strtolower(trim($sheetData[$i][0])));
				$data['alamat'] = strtolower(trim($sheetData[$i][1]));
				$data['no_telp'] = strtolower(trim($sheetData[$i][2]));
				$data['email'] = strtolower(trim($sheetData[$i][3]));
				$data['nama_toko'] = strtolower(trim($sheetData[$i][4]));
				$data['id_provinsi'] = strtolower(trim($sheetData[$i][5]));
				$data['id_kota'] = strtolower(trim($sheetData[$i][6]));
				$data['kecamatan'] = strtolower(trim($sheetData[$i][7]));
				
				#pegawai
				// $id_pegawai = $this->m_user->get_id_pegawai_by_name(strtolower(trim($sheetData[$i][2])));
				// if($id_pegawai){
				// 	$data['id_pegawai'] = $id_pegawai->id;
				// }else{
					// if($i == 0) {
					// 	continue;
					// }else{
					// 	$flag_kosongan = false;
					// 	$status_import = false;
					// 	$pesan = "Terjadi Kesalahan Dalam Penulisan Nama Pegawai, Mohon Cek Kembali";
					// 	break;
					// }
				// }
				#end pegawai

				#role
				$id_role = $this->m_user->get_id_role_by_name(strtolower(trim($sheetData[$i][3])));

				// if($id_role){
				// 	$data['id_role'] = $id_role->id;
				// }else{
					// if($i == 0) {
					// 	continue;
					// }else{
					// 	$flag_kosongan = false;
					// 	$status_import = false;
					// 	$pesan = "Terjadi Kesalahan Dalam Penulisan Nama Role, Mohon Cek Kembali";
					// 	break;
					// }
				// }
				#end role

				$data['created_at'] = $timestamp;
				// $data['foto'] = 'user_default.png';
				// $data['status'] = 1;
				#default password 123456
				// $data['password'] = $this->enkripsi->enc_dec('encrypt', '123456');

				$retval[] = $data;

				######## jika lancar sampai akhir beri flag sukses
				if($i == (count($sheetData) - 1)) {
					$flag_kosongan = false;
					$status_import = true;
					$pesan = "Data Sukses Di Import";
				}
			}

			if($status_import) {
				// var_dump(count($retval));exit;
				## jika array maks cuma 1, maka batalkan (soalnya hanya header saja disana) ##
				if(count($retval) <= 1) {
					echo json_encode([
						'status' => false,
						'pesan'	=> 'Import dibatalkan, Data Kosong...'
					]);

					return;
				}
				
				$this->db->trans_begin();
				
				#### truncate loh !!!!!!
				// $this->m_pelanggan->trun_master_pelanggan();
				
				foreach ($retval as $keys => $vals) {
					#### simpan
					// $vals['id'] = $this->m_user->get_max_id_user();
					$simpan = $this->m_pelanggan->save($vals);
				}

				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$status = false;
					$pesan = 'Gagal melakukan Import, cek ulang dalam melakukan pengisian data excel';
				}else{
					$this->db->trans_commit();
					$status = true;
					$pesan = 'Sukses Import data pelanggan';
				}

				echo json_encode([
					'status' => $status,
					'pesan'	=> $pesan
				]);
				
			}else{
				echo json_encode([
					'status' => false,
					'pesan'	=> $pesan
				]);
			}

		}else{
			echo json_encode([
				'status' => false,
				'pesan'	=> 'Terjadi Kesalahan dalam upload file. pastikan file adalah file excel .xlsx/.xls'
			]);
		}
	}

	public function export_excel()
	{
		$select = "m_pelanggan.*";
		$where = ['m_pelanggan.deleted_at' => null];
		$table = 'm_pelanggan';
		// $join = [ 
		// 	[
		// 		'table' => 'm_pegawai',
		// 		'on'	=> 'm_user.id_pegawai = m_pegawai.id'
		// 	],
		// 	[
		// 		'table' => 'm_role',
		// 		'on'	=> 'm_user.id_role = m_role.id'
		// 	]
		// ];

		$data = $this->m_global->multi_row($select, $where, $table);
		
		$spreadsheet = $this->excel->spreadsheet_obj();
		$writer = $this->excel->xlsx_obj($spreadsheet);
		$number_format_obj = $this->excel->number_format_obj();
		
		$spreadsheet
			->getActiveSheet()
			->getStyle('A1:E1000')
			->getNumberFormat()
			->setFormatCode($number_format_obj::FORMAT_TEXT);
		
		$sheet = $spreadsheet->getActiveSheet();

		$sheet
			->setCellValue('A1', 'Nama')
			->setCellValue('B1', 'Alamat')
			->setCellValue('C1', 'Telepon')
			->setCellValue('D1', 'Email')
			->setCellValue('E1', 'Nama toko')
			->setCellValue('F1', 'Id Provinsi')
			->setCellValue('G1', 'Id Kota')
			->setCellValue('H1', 'Kecamatan');
		
		$startRow = 2;
		$row = $startRow;
		if($data){
			foreach ($data as $key => $val) {
			
				$sheet
					->setCellValue("A{$row}", $val->nama_pembeli)
					->setCellValue("B{$row}", $val->alamat)
					->setCellValue("C{$row}", $val->no_telp)
					->setCellValue("D{$row}", $val->email)
					->setCellValue("E{$row}", $val->nama_toko)
					->setCellValue("F{$row}", $val->id_provinsi)
					->setCellValue("G{$row}", $val->id_kota)
					->setCellValue("H{$row}", $val->kecamatan);
				$row++;
			}

			$endRow = $row - 1;
		}
		
		
		$filename = 'master-pelanggan-'.time();
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
		
	}
}
