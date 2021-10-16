<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_barang extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('m_barang');
		$this->load->model('t_stok');
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
			'title' => 'Pengelolaan Stok Barang',
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
			'modal' => ['modal_stok_barang'],
			'js'	=> 'stok_barang.js',
			'view'	=> 'view_stok_barang'
		];

		$data['barang'] = $this->m_global->multi_row('*', ['deleted_at' => null], 'm_barang', NULL, 'nama');
		$data['gudang'] = $this->m_global->multi_row('*', ['deleted_at' => null], 'm_gudang', NULL, 'nama_gudang');

		$this->template_view->load_view($content, $data);
	}

	public function list_stok_barang()
	{
		$listData = $this->t_stok->get_datatable_stok();
    	$datas = [];
    	$i = 1;
    	foreach ($listData as $key => $value) {
			$show_action_button = true;
			// cek t_stok_mutasi dengan kode_trans selain stok awal, jika null boleh edit, dika ada datanya maka edit di hidden 
			$cek_exist_stok_mutasi =  $this->m_global->single_row('*',[
				'deleted_at' => null, 
				'id_kategori_trans !=' => 3, 
				'id_barang' => $value->id_barang,
				'id_gudang' => $value->id_gudang
			], 't_stok_mutasi');

			if($cek_exist_stok_mutasi) {
				$show_action_button = false;
			}

    		$datas[$key][] = $i++;
            $datas[$key][] = $value->nama_barang;
            $datas[$key][] = $value->nama_satuan;
            $datas[$key][] = $value->qty;
            $datas[$key][] = $value->qty_min;
            $datas[$key][] = $value->nama_kategori;
            $datas[$key][] = $value->nama_gudang;
			$datas[$key][] = ' <img src='.base_url().'files/img/barang_img/'.$value->gambar.' style="width:60px;" height="auto" class="center">';

			if($show_action_button) {
				$str_aksi = '
					<div class="btn-group">
						<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
						<div class="dropdown-menu">
							<button class="dropdown-item" onclick="edit_stok(\''.$value->id_stok.'\')">
								<i class="la la-pencil"></i> Edit Stok
							</button>
							<button class="dropdown-item" onclick="delete_stok(\''.$value->id_stok.'\')">
								<i class="la la-trash"></i> Hapus
							</button>
				';
			}else{
				$str_aksi = '
					<div class="btn-group">
						<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
						<div class="dropdown-menu">
				';
			}
			

			$str_aksi .= '</div></div>';
			$datas[$key][] =  $str_aksi;
    	}

    	$data = [
    		'data' => $datas
    	];
		
		echo json_encode($data);
	}

	public function edit_stok()
	{
		$this->load->library('Enkripsi');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_by_id($id_user);
	
		$id = $this->input->post('id');
		//$oldData = $this->m_user->get_by_id($id);

		$select = "t_stok.*, t_stok_mutasi.hpp";
		$where = ['t_stok.id_stok' => $id];
		$table = 't_stok';
		
		$join = [ 
			[
				'table' => 't_stok_mutasi',
				'on'	=> 't_stok.id_stok = t_stok_mutasi.id_stok and t_stok.id_barang = t_stok_mutasi.id_barang and t_stok.id_gudang = t_stok_mutasi.id_gudang and t_stok_mutasi.id_kategori_trans = 3'
			]
		];

		$oldData = $this->m_global->single_row($select, $where, $table, $join);
		
		if(!$oldData){
			return redirect($this->uri->segment(1));
		}
		
		$data = array(
			'data_user' => $data_user,
			'old_data'	=> $oldData,
		);
		
		echo json_encode($data);
	}

	public function add_stok_barang()
	{
		try {
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tanggal = $obj_date->format('Y-m-d');
			$arr_valid = $this->rule_validasi();
			
			$id_barang 	    = trim($this->input->post('id_barang'));
			$id_gudang 	    = trim($this->input->post('id_gudang'));
			$qty 	    	= (int)$this->input->post('sawal');
			$qty_min 	    = (int)$this->input->post('smin');
			
			$hpp 	  = trim($this->input->post('hpp'));
			$hpp      = str_replace('.', '', $hpp);

			if ($arr_valid['status'] == FALSE) {
				echo json_encode($arr_valid);
				return;
			}

			$data_where = ['id_barang' => $id_barang, 'id_gudang' => $id_gudang];
			$cek_exist_stok    = $this->m_global->getSelectedData('t_stok', $data_where)->row();

			if (!empty($cek_exist_stok)) {
				$retval['status'] = false;
				$retval['pesan'] = 'Data Stok Pada Item Ini Sudah Ada !!';
				echo json_encode($retval);
				exit;
			}

			$this->db->trans_begin();

			$data_stok = [
				'id_barang' 	=> $id_barang,
				'id_gudang' 	=> $id_gudang,
				'qty' 			=> $qty,
				'qty_min' 		=> $qty_min,
				'created_at'	=> $timestamp
			];
			
			$id_stok = $this->t_stok->store_id($data_stok, 't_stok');

			$mutasi = $this->lib_mutasi->mutasiMasuk(
				$id_barang, 
				$qty, 
				$qty_min, 
				3, 
				null, 
				$hpp, 
				$id_gudang, 
				'STOK AWAL'
			);

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menambahkan stok barang';
			}else{
				$this->db->trans_commit();
				$retval['status'] = true;
				$retval['pesan'] = 'Sukses menambahkan stok barang';
			}

			echo json_encode($retval);
		} 
		catch (\Throwable $th) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}
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

		if ($this->input->post('id_barang') == '') {
			$data['inputerror'][] = 'id_barang';
            $data['error_string'][] = 'Wajib Mengisi barang';
            $data['status'] = FALSE;
		}

		if ($this->input->post('id_gudang') == '') {
			$data['inputerror'][] = 'id_gudang';
            $data['error_string'][] = 'Wajib Mengisi Gudang';
            $data['status'] = FALSE;
		}

		if ($this->input->post('sawal') == '') {
			$data['inputerror'][] = 'sawal';
            $data['error_string'][] = 'Wajib Mengisi Stok Awal';
            $data['status'] = FALSE;
		}

		if ($this->input->post('smin') == '') {
			$data['inputerror'][] = 'smin';
            $data['error_string'][] = 'Wajib Mengisi Stok Minimum';
            $data['status'] = FALSE;
		}
		
		if ($this->input->post('hpp') == '') {
			$data['inputerror'][] = 'hpp';
            $data['error_string'][] = 'Wajib Mengisi HPP Barang';
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

	
}
