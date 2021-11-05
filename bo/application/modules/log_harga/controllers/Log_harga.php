<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_harga extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('m_log_harga');
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
			'title' => 'Setting Harga Jual',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'barang'  => $this->m_global->getSelectedData('m_barang', array('deleted_at'=>NULL)),
		);

		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => ['modal_log_harga','modal_detail_log'],
			'js'	=> 'log_harga.js',
			'view'	=> 'view_log_harga'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_log_harga()
	{
		$list = $this->m_log_harga->get_datatable();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $log) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $log->nama;
			$row[] = 'Rp '.number_format($log->harga_jual);
			
			$str_aksi = '
				
						<button class="btn btn-sm btn-primary" onclick="detail2(\''.$log->id_barang.'\')">
							<i class="la la-navicon"></i> Log harga
						</button>
						
			';

			$str_aksi .= '</div></div>';
			$row[] = $str_aksi;

			$data[] = $row;
		}//end loop

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_log_harga->count_all(),
			"recordsFiltered" => $this->m_log_harga->count_filtered(),
			"data" => $data
		];
		
		echo json_encode($output);
	}

	public function edit_agen()
	{
		$this->load->library('Enkripsi');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_by_id($id_user);
	
		$id = $this->input->post('id');
		//$oldData = $this->m_user->get_by_id($id);

		$select = "m_agen.*";
		$where = ['m_agen.id_agen' => $id];
		$table = 'm_agen';
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

	public function add_log_harga_jual()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$timestamp2 = $obj_date->format('Y-m-d');
		$arr_valid = $this->rule_validasi();
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_user->get_detail_user($id_user);
		
		$id_barang 	= $this->input->post('id_barang');
		$harga_jual = $this->input->post('harga_jual');

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$this->db->trans_begin();
		
		$data = [
			'id_barang' => $id_barang,
			'harga_jual' => $harga_jual,
			'tanggal'  => $timestamp2,
			'created_by' => $data_user[0]->id
		];
		
		$insert = $this->m_log_harga->save($data);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan Log Harga';
		}else{
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses menambahkan Log Harga';
		}

		echo json_encode($retval);
	}

	public function update_data_agen()
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

		$nama_pers		= $this->input->post('nama_pers');
		$produk 		= $this->input->post('produk');
		$alamat			= $this->input->post('alamat');
		$telp     		= $this->input->post('telp');
		
		$q = $this->m_agen->get_by_id($id_agen);
		
		$this->db->trans_begin();

		$data_agen = [
			'nama_perusahaan' => $nama_pers,
			'produk' => $produk,
			'alamat' => $alamat,
			'telp' 	=> $telp,
			'updated_at' => $timestamp
		];
		

		$where = ['id_agen' => $id_agen];
		$update = $this->m_agen->update($where, $data_agen);

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$data['status'] = false;
			$data['pesan'] = 'Gagal update Master Agen';
		}else{
			$this->db->trans_commit();
			$data['status'] = true;
			$data['pesan'] = 'Sukses update Master Agen';
		}
		
		echo json_encode($data);
	}

	/**
	 * Hanya melakukan softdelete saja
	 * isi kolom updated_at dengan datetime now()
	 */
	public function delete_agen()
	{
		$id_agen = $this->input->post('id');
		$del = $this->m_agen->softdelete_by_id($id_agen);
		if($del) {
			$retval['status'] = TRUE;
			$retval['pesan'] = 'Data Master Agen berhasil dihapus';
		}else{
			$retval['status'] = FALSE;
			$retval['pesan'] = 'Data Master Agen berhasil dihapus';
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

		if ($this->input->post('id_barang') == '') {
			$data['inputerror'][] = 'ida-barang';
            $data['error_string'][] = 'Wajib Memilih Barang';
            $data['status'] = FALSE;
		}

		if ($this->input->post('harga_jual') == '') {
			$data['inputerror'][] = 'harga_jual';
            $data['error_string'][] = 'Wajib Mengisi Harga Jual';
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

	function get_datatable_detail()
    {
		$id_log = $this->input->post('id_log');
		$data_table = $this->m_log_harga->get_datatable_detail($id_log);
		// var_dump($data_table); die();
		$data = [];
        foreach ($data_table as $key => $value) {
			
			$data[$key][] = $key+1;
			$data[$key][] = $value->nama_barang;
			$data[$key][] = 'Rp '.number_format($value->harga_jual); 
			$data[$key][] = date('d-m-Y', strtotime($value->tanggal)); 
			$data[$key][] = $value->nama_user;     
			// $data[$key][] = $value->jenis_kelamin;
			// $data[0][] = $value->created_at;
			

			
		}
		
		// $this->output->enable_profiler(TRUE);

        echo json_encode([
            'data' => $data
        ]);
	}
}
