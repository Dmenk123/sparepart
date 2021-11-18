<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_barang extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('m_barang');
		$this->load->model('m_user');
		$this->load->model('m_global');
		$this->load->model('set_role/m_set_role', 'm_role');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		$show_menu = ($this->input->get('showmenu') != null && $this->input->get('showmenu') == 'false') ? false : true;
		
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'Pengelolaan Data Barang',
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
			'show_menu' => $show_menu,
			'css' 	=> null,
			'modal' => ['modal_master_barang','modal_detail_gambar', ],
			'js'	=> 'master_barang.js',
			'view'	=> 'view_master_barang'
		];

		$data['kategori'] = $this->m_global->getSelectedData('m_kategori', NULL);
		$data['satuan'] = $this->m_global->getSelectedData('m_satuan', NULL);

		$this->template_view->load_view($content, $data);
	}

	public function list_barang()
	{
		$list = $this->m_barang->get_datatable_barang();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $barang) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $barang->sku;
			$row[] = ' <img src='.base_url().'files/img/barcode/'.$barang->sku.'.jpg style="width:100px;" height="auto" class="center">';
			$row[] = $barang->nama;
			// $row[] = $barang->stok;
			$row[] = $barang->nama_satuan;
			$row[] = 'Rp '.number_format($barang->harga);
			$row[] = $barang->nama_kategori;
			$row[] = ' <img src='.base_url().'files/img/barang_img/'.$barang->gambar.' style="width:60px;" height="auto" class="center">';
			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">
						<button class="dropdown-item" onclick="edit_barang(\''.$barang->id_barang.'\')">
							<i class="la la-pencil"></i> Edit Barang
						</button>
						<button class="dropdown-item" onclick="delete_barang(\''.$barang->id_barang.'\')">
							<i class="la la-trash"></i> Hapus
						</button>
						<button class="dropdown-item" onclick="detail_gambar(\''.$barang->id_barang.'\')">
							<i class="fa fa-images"></i> Detail Gambar
						</button>
			';

			$str_aksi .= '</div></div>';
			$row[] = $str_aksi;

			$data[] = $row;
		}//end loop

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_user->count_all(),
			"recordsFiltered" => $this->m_user->count_filtered(),
			"data" => $data
		];
		
		echo json_encode($output);
	}

	public function edit_barang()
	{
		$this->load->library('Enkripsi');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_by_id($id_user);
	
		$id = $this->input->post('id');
		//$oldData = $this->m_user->get_by_id($id);

		$select = "m_barang.*";
		$where = ['m_barang.id_barang' => $id];
		$table = 'm_barang';
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
		if($oldData->gambar) {
			$url_foto = base_url('files/img/barang_img/').$oldData->gambar;
		}
		else{
			$url_foto = base_url('files/img/barang_img/user_default.png');
		}

		if($oldData->gambar_kedua) {
			$url_foto_kedua = base_url('files/img/barang_img/').$oldData->gambar_kedua;
		}
		else{
			$url_foto_kedua = base_url('files/img/barang_img/user_default.png');
		}

		if($oldData->gambar_ketiga) {
			$url_foto_ketiga = base_url('files/img/barang_img/').$oldData->gambar_ketiga;
		}
		else{
			$url_foto_ketiga = base_url('files/img/barang_img/user_default.png');
		}

		if($oldData->gambar_keempat) {
			$url_foto_keempat = base_url('files/img/barang_img/').$oldData->gambar_keempat;
		}
		else{
			$url_foto_keempat = base_url('files/img/barang_img/user_default.png');
		}

	
		$foto 			= base64_encode(file_get_contents($url_foto));
		$foto_kedua 	= base64_encode(file_get_contents($url_foto_kedua));
		$foto_ketiga 	= base64_encode(file_get_contents($url_foto_ketiga));  
		$foto_keempat	= base64_encode(file_get_contents($url_foto_keempat));  
		
		$data = array(
			'data_user' => $data_user,
			'old_data'	=> $oldData,
			'foto_encoded' => $foto,
			'foto_encoded_kedua' => $foto_kedua,
			'foto_encoded_ketiga' => $foto_ketiga,
			'foto_encoded_keempat' => $foto_keempat
		);
		
		echo json_encode($data);
	}

	public function add_data_barang()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tanggal = $obj_date->format('Y-m-d');
		$arr_valid = $this->rule_validasi();
		
		$sku 	    = trim($this->input->post('sku'));
		$nama 	    = trim($this->input->post('nama'));
		$harga 	    = trim($this->input->post('harga'));
		$harga      = str_replace('.', '', $harga);
		$kategori 	= $this->input->post('kategori');
		$satuan       = $this->input->post('satuan');
		$namafileseo = $this->seoUrl($nama.' '.$sku);
		$namafileseo_2 = $this->seoUrl($nama.' '.$sku.'_2');
		$namafileseo_3 = $this->seoUrl($nama.' '.$sku.'_3');
		$namafileseo_4 = $this->seoUrl($nama.' '.$sku.'_4');

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$data_where = ['sku' => $sku];
		$cek_sku    = $this->m_global->getSelectedData('m_barang', $data_where)->row();
		if (!empty($cek_sku)) {
			$retval['status'] = false;
			$retval['pesan'] = 'Kode SKU telah ada di Master Barang !!';
			echo json_encode($retval);
			exit;
		}

		
		$this->db->trans_begin();
		$this->barcode_scanner($sku);
		
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
				$namafileseo = $this->seoUrl($nama.' '.$sku).'.'.$extDet;
			} else {
				$error = array('error' => $this->file_obj->display_errors());
			}
		}else{
			$namafileseo = 'user_default.png';
		}

		if(isset($_FILES['foto_kedua']['name']) && in_array($_FILES['foto_kedua']['type'], $file_mimes)) {
			$namafileseo_2 = $namafileseo_2;
			// var_dump($namafileseo_2); die();
			$this->konfigurasi_upload_img($namafileseo_2);
			//get detail extension
			$pathDet = $_FILES['foto_kedua']['name'];
			$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);
			
			if ($this->file_obj->do_upload('foto_kedua')) 
			{

				$gbrBukti = $this->file_obj->data();
				$nama_file_foto = $gbrBukti['file_name'];
				$this->konfigurasi_image_resize($nama_file_foto, 2);
				$output_thumb = $this->konfigurasi_image_thumb($nama_file_foto, $gbrBukti, 2);
				$this->image_lib->clear();
				## replace nama file + ext
				$namafileseo_2 = $this->seoUrl($namafileseo_2).'.'.$extDet;
			} else {
				$error = array('error' => $this->file_obj->display_errors());
			}
		}else{
			$namafileseo_2 = 'user_default.png';
		}

		if(isset($_FILES['foto_ketiga']['name']) && in_array($_FILES['foto_ketiga']['type'], $file_mimes)) {
			$namafileseo_3 = $namafileseo_3;
			// var_dump($namafileseo_2); die();
			$this->konfigurasi_upload_img($namafileseo_3);
			//get detail extension
			$pathDet = $_FILES['foto_ketiga']['name'];
			$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);
			
			if ($this->file_obj->do_upload('foto_ketiga')) 
			{

				$gbrBukti = $this->file_obj->data();
				$nama_file_foto = $gbrBukti['file_name'];
				$this->konfigurasi_image_resize($nama_file_foto, 3);
				$output_thumb = $this->konfigurasi_image_thumb($nama_file_foto, $gbrBukti, 3);
				$this->image_lib->clear();
				## replace nama file + ext
				$namafileseo_3 = $this->seoUrl($namafileseo_3).'.'.$extDet;
			} else {
				$error = array('error' => $this->file_obj->display_errors());
			}
		}else{
			$namafileseo_3 = 'user_default.png';
		}

		if(isset($_FILES['foto_keempat']['name']) && in_array($_FILES['foto_keempat']['type'], $file_mimes)) {
			$namafileseo_4 = $namafileseo_4;
			// var_dump($namafileseo_2); die();
			$this->konfigurasi_upload_img($namafileseo_4);
			//get detail extension
			$pathDet = $_FILES['foto_keempat']['name'];
			$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);
			
			if ($this->file_obj->do_upload('foto_keempat')) 
			{

				$gbrBukti = $this->file_obj->data();
				$nama_file_foto = $gbrBukti['file_name'];
				$this->konfigurasi_image_resize($nama_file_foto, 4);
				$output_thumb = $this->konfigurasi_image_thumb($nama_file_foto, $gbrBukti, 4);
				$this->image_lib->clear();
				## replace nama file + ext
				$namafileseo_4 = $this->seoUrl($namafileseo_4).'.'.$extDet;
			} else {
				$error = array('error' => $this->file_obj->display_errors());
			}
		}else{
			$namafileseo_4 = 'user_default.png';
		}

		$data_barang = [
			'sku' 				=> $sku,
			'nama' 				=> $nama,
			'harga' 			=> $harga,
			'id_kategori' 		=> $kategori,
			'id_satuan'      	=> $satuan,
			'gambar'			=> $namafileseo,
			'gambar_kedua' 		=> $namafileseo_2,
			'gambar_ketiga' 	=> $namafileseo_3,
			'gambar_keempat' 	=> $namafileseo_4,
			'shopee_link'		=> $this->input->post('shopee'),
			'tokopedia_link' 	=> $this->input->post('tokopedia'),
			'bukalapak_link' 	=> $this->input->post('bukalapak'),
			'lazada_link'		=> $this->input->post('lazada'),
		];
		
		$id_insert = $this->m_barang->store_id($data_barang, 'm_barang');
		$insert_log_harga = $this->m_global->save(['id_barang' => $id_insert, 'harga_jual' => $harga, 'tanggal' => $tanggal, 'is_harga_awal' => 1], 't_log_harga_jual');

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan master barang';
		}else{
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses menambahkan master barang';
		}

		echo json_encode($retval);
	}

	public function update_data_barang()
	{
		$sesi_id_user = $this->session->userdata('id_user'); 
		$id_barang = $this->input->post('id_barang');
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		
		$arr_valid = $this->rule_validasi(true);

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$nama = $this->input->post('nama');
		$harga = $this->input->post('harga');
		$harga    = str_replace('.', '', $harga);
		$kategori = $this->input->post('kategori');
		$sku      = $this->input->post('sku');
		$satuan     = $this->input->post('satuan');
		
		$q = $this->m_barang->get_by_id($id_barang);
		$namafileseo = $this->seoUrl($q->nama.' '.$sku);
		$namafileseo_2 = $this->seoUrl($q->nama.' '.$sku.'_2');
		$namafileseo_3 = $this->seoUrl($q->nama.' '.$sku.'_3');
		$namafileseo_4 = $this->seoUrl($q->nama.' '.$sku.'_4');
		
		$this->db->trans_begin();

		$data_where = array('sku' => $sku);
		$cek_sku    = $this->m_global->getSelectedData('m_barang', $data_where)->row();

		if (empty($cek_sku)) {
			$this->barcode_scanner($sku);
		}

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
				$namafileseo = $this->seoUrl($q->nama.' '.$sku).'.'.$extDet;
				$foto = $namafileseo;
			} else {
				$error = array('error' => $this->file_obj->display_errors());
				var_dump($error);exit;
			}
		}else{
			$foto = null;
		}

		if(isset($_FILES['foto_kedua']['name']) && in_array($_FILES['foto_kedua']['type'], $file_mimes)) {
			$namafileseo_2 = $namafileseo_2;
			// var_dump($namafileseo_2); die();
			$this->konfigurasi_upload_img($namafileseo_2);
			//get detail extension
			$pathDet = $_FILES['foto_kedua']['name'];
			$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);
			
			if ($this->file_obj->do_upload('foto_kedua')) 
			{

				$gbrBukti = $this->file_obj->data();
				$nama_file_foto = $gbrBukti['file_name'];
				$this->konfigurasi_image_resize($nama_file_foto, 2);
				$output_thumb = $this->konfigurasi_image_thumb($nama_file_foto, $gbrBukti, 2);
				$this->image_lib->clear();
				## replace nama file + ext
				$namafileseo_2 = $this->seoUrl($namafileseo_2).'.'.$extDet;
			} else {
				$error = array('error' => $this->file_obj->display_errors());
			}
		}else{
			$namafileseo_2 = null;
		}

		if(isset($_FILES['foto_ketiga']['name']) && in_array($_FILES['foto_ketiga']['type'], $file_mimes)) {
			$namafileseo_3 = $namafileseo_3;
			// var_dump($namafileseo_2); die();
			$this->konfigurasi_upload_img($namafileseo_3);
			//get detail extension
			$pathDet = $_FILES['foto_ketiga']['name'];
			$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);
			
			if ($this->file_obj->do_upload('foto_ketiga')) 
			{

				$gbrBukti = $this->file_obj->data();
				$nama_file_foto = $gbrBukti['file_name'];
				$this->konfigurasi_image_resize($nama_file_foto, 3);
				$output_thumb = $this->konfigurasi_image_thumb($nama_file_foto, $gbrBukti, 3);
				$this->image_lib->clear();
				## replace nama file + ext
				$namafileseo_3 = $this->seoUrl($namafileseo_3).'.'.$extDet;
			} else {
				$error = array('error' => $this->file_obj->display_errors());
			}
		}else{
			$namafileseo_3 = null;
		}

		if(isset($_FILES['foto_keempat']['name']) && in_array($_FILES['foto_keempat']['type'], $file_mimes)) {
			$namafileseo_4 = $namafileseo_4;
			// var_dump($namafileseo_2); die();
			$this->konfigurasi_upload_img($namafileseo_4);
			//get detail extension
			$pathDet = $_FILES['foto_keempat']['name'];
			$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);
			
			if ($this->file_obj->do_upload('foto_keempat')) 
			{

				$gbrBukti = $this->file_obj->data();
				$nama_file_foto = $gbrBukti['file_name'];
				$this->konfigurasi_image_resize($nama_file_foto, 4);
				$output_thumb = $this->konfigurasi_image_thumb($nama_file_foto, $gbrBukti, 4);
				$this->image_lib->clear();
				## replace nama file + ext
				$namafileseo_4 = $this->seoUrl($namafileseo_4).'.'.$extDet;
			} else {
				$error = array('error' => $this->file_obj->display_errors());
			}
		}else{
			$namafileseo_4 = null;
		}

		$data_barang = [
			'nama' => $nama,
			'sku' => $sku,
			'harga' => $harga,
			'id_satuan'  => $satuan,
			'id_kategori' => $kategori,
			'updated_at' => $timestamp
		];
		
		if($foto != null) {
			$data_barang['gambar'] = $foto;
		}

		if ($namafileseo_2 != NULL) {
			$data_barang['gambar_kedua'] = $namafileseo_2;
		}

		if ($namafileseo_3 != NULL) {
			$data_barang['gambar_ketiga'] = $namafileseo_3;
		}

		if ($namafileseo_4 != NULL) {
			$data_barang['gambar_keempat'] = $namafileseo_4;
		}

		$where = ['id_barang' => $id_barang];
		$update = $this->m_barang->update($where, $data_barang);

		$cek_log_harga    = $this->m_global->getSelectedData('t_log_harga_jual', ['id_barang' => $id_barang])->row();
		if ($cek_log_harga) {
			$this->m_global->update('t_log_harga_jual', ['harga_jual' => $harga], ['id_barang' => $id_barang, 'is_harga_awal' => 1]);
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$data['status'] = false;
			$data['pesan'] = 'Gagal update Master Barang';
		}else{
			$this->db->trans_commit();
			$data['status'] = true;
			$data['pesan'] = 'Sukses update Master Barang';
		}
		
		echo json_encode($data);
	}

	/**
	 * Hanya melakukan softdelete saja
	 * isi kolom updated_at dengan datetime now()
	 */
	public function delete_barang()
	{
		$id_barang = $this->input->post('id');
		$del = $this->m_barang->softdelete_by_id($id_barang);
		if($del) {
			$retval['status'] = TRUE;
			$retval['pesan'] = 'Data Master Barang berhasil dihapus';
		}else{
			$retval['status'] = FALSE;
			$retval['pesan'] = 'Data Master Barang berhasil dihapus';
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

		if ($this->input->post('harga') == '') {
			$data['inputerror'][] = 'role';
            $data['error_string'][] = 'Wajib Mengisi Harga';
            $data['status'] = FALSE;
		}

		if ($this->input->post('nama') == '') {
			$data['inputerror'][] = 'nama';
            $data['error_string'][] = 'Wajib Mengisi Nama';
            $data['status'] = FALSE;
		}

		if ($this->input->post('kategori') == '') {
			$data['inputerror'][] = 'kategori';
            $data['error_string'][] = 'Wajib Mengisi Kategori';
            $data['status'] = FALSE;
		}

		if ($this->input->post('satuan') == '') {
			$data['inputerror'][] = 'satuan';
            $data['error_string'][] = 'Wajib Mengisi satuan';
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

	private function konfigurasi_image_resize($filename, $urutan=NULL)
	{
		//konfigurasi image lib
	    $config['image_library'] = 'gd2';
	    $config['source_image'] = './files/img/barang_img/'.$filename;
	    $config['create_thumb'] = FALSE;
	    $config['maintain_ratio'] = FALSE;
	    $config['new_image'] = './files/img/barang_img/resize_image/'.$filename;
	    $config['overwrite'] = TRUE;
	    $config['width'] = 450; //resize
	    $config['height'] = 500; //resize
	    $this->load->library('image_lib',$config); //load image library
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();
	}

	private function konfigurasi_image_thumb($filename, $gbr, $urutan=NULL)
	{
		//konfigurasi image lib
	    $config2['image_library'] = 'gd2';
	    $config2['source_image'] = './files/img/barang_img/'.$filename;
	    $config2['create_thumb'] = TRUE;
	 	$config2['thumb_marker'] = '_thumb';
	    $config2['maintain_ratio'] = FALSE;
	    $config2['new_image'] = './files/img/barang_img/thumbs/'.$filename;
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

	public function modal_detail_gambar()
	{
		$id = $this->input->post('id');
		$data_where = ['id_barang' => $id];
		$barang = $this->m_global->getSelectedData('m_barang', $data_where)->row();
		?>
		<div class="mySlides col-sm-12">
        <div class="numbertext">1 / 4</div>
		<?php
			if ($barang->gambar != NULL) { ?>
				   <img src="<?php echo base_url();?>files/img/barang_img/<?php echo $barang->gambar;?>" style="width:70%" height="auto" class="center">
			<?php }
		?>
        </div>

        <div class="mySlides  col-sm-12">
        <div class="numbertext">2 / 4</div>
		<?php
			if ($barang->gambar_kedua != NULL) { ?>
				   <img src="<?php echo base_url();?>files/img/barang_img/<?php echo $barang->gambar_kedua;?>" style="width:70%" height="auto" class="center">
			<?php }
		?>
        </div>

        <div class="mySlides  col-sm-12">
        <div class="numbertext">3 / 4</div>
		<?php
			if ($barang->gambar_ketiga != NULL) { ?>
				   <img src="<?php echo base_url();?>files/img/barang_img/<?php echo $barang->gambar_ketiga;?>" style="width:70%" height="auto" class="center">
			<?php }
		?>
        </div>
        
        <div class="mySlides  col-sm-12">
        <div class="numbertext">4 / 4</div>
		<?php
			if ($barang->gambar_keempat != NULL) { ?>
				   <img src="<?php echo base_url();?>files/img/barang_img/<?php echo $barang->gambar_keempat;?>" style="width:70%" height="auto" class="center">
			<?php }
		?>
        </div>
        
        <a class="prev-gambar" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next-gambar" onclick="plusSlides(1)">&#10095;</a>

        <div class="caption-container">
        <p id="caption"></p>
        </div>

        <div cass="row">
            <div class="column col-sm-3">
			<?php
				if ($barang->gambar != NULL) {
			?>
					 <img class="demo cursor" src="<?php echo base_url();?>files/img/barang_img/<?php echo $barang->gambar;?>" style="width:100%" onclick="currentSlide(1)" alt="">
			<?php
				}
			?>
           
            </div>
            <div class="column col-sm-3">
			<?php
				if ($barang->gambar_kedua != NULL) {
			?>
					 <img class="demo cursor" src="<?php echo base_url();?>files/img/barang_img/<?php echo $barang->gambar_kedua;?>" style="width:100%" onclick="currentSlide(1)" alt="">
			<?php
				}
			?>
            </div>
            <div class="column col-sm-3">
			<?php
				if ($barang->gambar_ketiga != NULL) {
			?>
					 <img class="demo cursor" src="<?php echo base_url();?>files/img/barang_img/<?php echo $barang->gambar_ketiga;?>" style="width:100%" onclick="currentSlide(1)" alt="">
			<?php
				}
			?>
            </div>
            <div class="column col-sm-3">
			<?php
				if ($barang->gambar_keempat != NULL) {
			?>
					 <img class="demo cursor" src="<?php echo base_url();?>files/img/barang_img/<?php echo $barang->gambar_keempat;?>" style="width:100%" onclick="currentSlide(1)" alt="">
			<?php
				}
			?>
            </div>
		</div>
	<?php 
		
		
	}

	public function barcode_scanner($sku)
	{
		$path = 'assets/';
		$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
		// $generated = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
		// $this->assertEquals('PNG', substr($generated, 1, 3));
		file_put_contents('../bo/files/img/barcode/'.$sku.'.jpg', $generator->getBarcode($sku, $generator::TYPE_CODE_128, 3, 50));
		// echo "<img src='barcode1.jpg' alt=''>";
	}

	public function mod_detail_gambar($id_barang)
	{
		$data_where = ['id_barang' => $id_barang];
		$data['barang'] = $this->m_global->getSelectedData('m_barang', $data_where)->row();
		$this->load->view('modal_detail_gambar', $data);
	}

	public function template_excel()
	{
		$file_url = base_url().'files/template_dokumen/template_master_barang.xlsx';
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

				$data['sku'] = strtoupper(strtolower(trim($sheetData[$i][0])));
				$data['nama'] = strtolower(trim($sheetData[$i][1]));
				$data['harga'] = strtolower(trim($sheetData[$i][2]));
				$data['id_kategori'] = strtolower(trim($sheetData[$i][3]));
				$data['stok'] = strtolower(trim($sheetData[$i][4]));
				$data['id_satuan'] = strtolower(trim($sheetData[$i][5]));
				$this->barcode_scanner($sheetData[$i][0]);
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
					$simpan = $this->m_barang->save($vals);
				}

				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$status = false;
					$pesan = 'Gagal melakukan Import, cek ulang dalam melakukan pengisian data excel';
				}else{
					$this->db->trans_commit();
					$status = true;
					$pesan = 'Sukses Import data barangn';
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
		$select = "m_barang.*, m_kategori.nama_kategori, m_satuan.nama_satuan";
		$where = ['m_barang.deleted_at' => null];
		$table = 'm_barang';
		$join = [ 
			[
				'table' => 'm_kategori',
				'on'	=> 'm_kategori.id_kategori = m_barang.id_kategori'
			],
			[
				'table' => 'm_satuan',
				'on'	=> 'm_satuan.id_satuan = m_barang.id_satuan'
			]
		];

		$data = $this->m_global->multi_row($select, $where, $table, $join);
		
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
			->setCellValue('A1', 'Kode SKU')
			->setCellValue('B1', 'Nama Barang')
			->setCellValue('C1', 'Harga')
			->setCellValue('D1', 'Kategori')
			->setCellValue('E1', 'Qty')
			->setCellValue('F1', 'Satuan');
		
		$startRow = 2;
		$row = $startRow;
		if($data){
			foreach ($data as $key => $val) {
			
				$sheet
					->setCellValue("A{$row}", $val->sku)
					->setCellValue("B{$row}", $val->nama)
					->setCellValue("C{$row}", $val->harga)
					->setCellValue("D{$row}", $val->nama_kategori)
					->setCellValue("E{$row}", $val->stok)
					->setCellValue("F{$row}", $val->nama_satuan);
				$row++;
			}

			$endRow = $row - 1;
		}
		
		
		$filename = 'master-barang-'.time();
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
		
	}

	
}
