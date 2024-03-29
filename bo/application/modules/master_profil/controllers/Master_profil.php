<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_profil extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model(['m_profil', 'm_user', 'm_global']);
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_klinik = $this->m_global->single_row("*", "deleted_at is null", "m_profil", NULL, "nama_usaha asc");

		if($data_klinik) {
			$url_foto = base_url('files/img/app_img/').$data_klinik->gambar;
		}else{
			$url_foto = base_url('files/img/app_img/logo_default.png');
		}

		$foto = base64_encode(file_get_contents($url_foto));
		$foto_encoded = 'data:image/jpeg;base64,'.$foto; 
		
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'Profil Usaha',
			'data_user' => $data_user,
			'data_klinik'=> $data_klinik,
			'foto_encoded' => $foto_encoded
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
			'js'	=> 'master_profil.js',
			'view'	=> 'view_master_profil'
		];

		$this->template_view->load_view($content, $data);
	}

	public function simpan_data()
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$arr_valid = $this->rule_validasi();
		
		$nama = trim(strtoupper($this->input->post('nama')));
		$alamat = trim(strtoupper($this->input->post('alamat')));
		$kelurahan = trim(strtoupper($this->input->post('kelurahan')));
		$kecamatan = trim(strtoupper($this->input->post('kecamatan')));
		$kota = trim(strtoupper($this->input->post('kota')));
		$provinsi = trim(strtoupper($this->input->post('provinsi')));
		$kodepos = trim($this->input->post('kodepos'));
		$website = trim($this->input->post('website'));
		// $dokter = trim(strtoupper($this->input->post('dokter')));
		// $sip = trim($this->input->post('sip'));
		$telp = trim($this->input->post('telp'));
		$email = trim($this->input->post('email'));
		$namafileseo = $this->seoUrl('logo');

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}
		
		$this->db->trans_begin();
		
		$file_mimes = ['image/png', 'image/x-citrix-png', 'image/x-png', 'image/x-citrix-jpeg', 'image/jpeg', 'image/pjpeg'];

		if(isset($_FILES['foto']['name']) && in_array($_FILES['foto']['type'], $file_mimes)) {
			$this->konfigurasi_upload_img($namafileseo);
			//get detail extension
			$pathDet = $_FILES['foto']['name'];
			$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);
			
			if ($this->file_obj->do_upload('foto')) 
			{
				$gbrBukti = $this->file_obj->data();
				$nama_file_foto = $gbrBukti['file_name'];
				$this->konfigurasi_image_resize($nama_file_foto);
				$output_thumb = $this->konfigurasi_image_thumb($nama_file_foto, $gbrBukti);
				$this->image_lib->clear();
				## replace nama file + ext
				$namafileseo = $this->seoUrl('logo').'.'.$extDet;
			} else {
				$error = array('error' => $this->file_obj->display_errors());
				var_dump($error);exit;
			}
		}else{
			$namafileseo = false;
		}

		$data_profil = [
			'id' => $this->m_profil->get_max_id_klinik(),
			'nama_usaha' => $nama,
			'alamat' => $alamat,
			'kelurahan' => $kelurahan,
			'kecamatan' => $kecamatan,
			'kota' => $kota,
			'kode_pos' => $kodepos,
			'provinsi' => $provinsi,
			'telp' => $telp,
			'email' => $email,
			'website' => $website,
			// 'nama_dokter' => $dokter,
			// 'sip' => $sip,
			'created_at' => $timestamp,
		];

		if($namafileseo != false) {
			$data_profil['gambar'] = $namafileseo; 
		}

		## get last data and set deleted at with timestamp
		$last_data = $this->m_profil->get_by_condition('deleted_at is null', true);
		
		if($last_data) {
			$this->m_profil->update(['id' => $last_data->id], ['deleted_at' => $timestamp]);
		}

		$insert = $this->m_profil->save($data_profil);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal Menyimpan Profil profil';
		}else{
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Menyimpan Profil profil';
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

		if ($this->input->post('nama') == '') {
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Wajib mengisi Nama';
			$data['status'] = FALSE;
		}
			
		if ($this->input->post('alamat') == '') {
			$data['inputerror'][] = 'alamat';
            $data['error_string'][] = 'Wajib mengisi Alamat';
            $data['status'] = FALSE;
		}

		if ($this->input->post('kelurahan') == '') {
			$data['inputerror'][] = 'kelurahan';
			$data['error_string'][] = 'Wajib mengisi Kelurahan';
			$data['status'] = FALSE;
		}

		if ($this->input->post('kecamatan') == '') {
			$data['inputerror'][] = 'kecamatan';
			$data['error_string'][] = 'Wajib mengisi Kecamatan';
			$data['status'] = FALSE;
		}

		if ($this->input->post('kota') == '') {
			$data['inputerror'][] = 'kota';
            $data['error_string'][] = 'Wajib mengisi Kota';
            $data['status'] = FALSE;
		}

		if ($this->input->post('provinsi') == '') {
			$data['inputerror'][] = 'provinsi';
            $data['error_string'][] = 'Wajib mengisi Provinsi';
            $data['status'] = FALSE;
		}

		if ($this->input->post('kodepos') == '') {
			$data['inputerror'][] = 'kodepos';
            $data['error_string'][] = 'Wajib mengisi kodepos';
            $data['status'] = FALSE;
		}

		// if ($this->input->post('dokter') == '') {
		// 	$data['inputerror'][] = 'dokter';
        //     $data['error_string'][] = 'Wajib mengisi nama dokter';
        //     $data['status'] = FALSE;
		// }

		if ($this->input->post('telp') == '') {
			$data['inputerror'][] = 'telp';
            $data['error_string'][] = 'Wajib mengisi nama Telp/HP';
            $data['status'] = FALSE;
		}

		if ($this->input->post('email') == '') {
			$data['inputerror'][] = 'email';
            $data['error_string'][] = 'Wajib mengisi nama email';
            $data['status'] = FALSE;
		}
		

        return $data;
	}

	private function konfigurasi_upload_img($nmfile)
	{ 
		//konfigurasi upload img display
		$config['upload_path'] = './files/img/app_img/';
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
	    $config['source_image'] = './files/img/app_img/'.$filename;
	    $config['create_thumb'] = FALSE;
	    $config['maintain_ratio'] = FALSE;
	    $config['new_image'] = './files/img/app_img/'.$filename;
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
	    $config2['source_image'] = './files/img/app_img/'.$filename;
	    $config2['create_thumb'] = TRUE;
	 	$config2['thumb_marker'] = '_thumb';
	    $config2['maintain_ratio'] = FALSE;
	    $config2['new_image'] = './files/img/app_img/thumbs/'.$filename;
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
}
