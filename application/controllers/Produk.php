<?php
//defined('BASEPATH ') OR exit('No direct script access allowed');

class Produk extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_global');
		$this->load->model('m_barang');
	}

	protected $data_passing_content = [];

	public function kategori()
	{	
		if($this->input->get('kat')) {
			$txt_kat = clean_string(trim(strtolower(str_ireplace('-', ' ', $this->input->get('kat')))));
		}else{
			$txt_kat = '';
		}

		if($this->input->get('sort')) {
			$txt_sort = clean_string(trim(strtolower(str_ireplace('-', ' ', $this->input->get('sort')))));
			$cek_sort = $this->terjemahan_sorting($txt_sort);
			
			if($cek_sort != false) {
				$sort_by = $cek_sort;
			}else{
				$sort_by = 'nama asc';
			}
		}else{
			$sort_by = 'nama asc';
		}

		if($this->input->get('tampil')) {
			$per_page = clean_string(trim(strtolower(str_ireplace('-', ' ', $this->input->get('tampil')))));
			if(!is_numeric($per_page)) {
				$per_page = 9;
			}else{
				if((int)$per_page < 9) {
					$per_page = 9;
				}else{
					$hsl_bagi = (int)$per_page % 3;
					if((int)$per_page > 24) {
						$per_page = 24;
					}else{
						$per_page = (int)$per_page - $hsl_bagi;
					}
				}
			}
		}else{
			$per_page = 3;
		}
		
		### kategori
		$data_kat = $this->m_global->single_row('*',['trim(nama_kategori)' => $txt_kat],'m_kategori');

		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tanggal = $obj_date->format('Y-m-d');
		
		/**
		 * set properti data passing, untuk content view
		 */
		$this->get_temp_container_header();
		$this->get_temp_container_menu();
		$this->get_temp_container_slider();
		// $this->get_temp_container_feature();
		// $this->get_temp_container_latest();
		// $this->get_temp_container_adv_big();
		// $this->get_temp_container_product_listing();
		// $this->get_temp_container_product_related();
		$this->get_temp_produk_kategori();
		$content = $this->data_passing_content;
		## WAJIB SET ARRAY CONTENT
		$data['content'] = $content;
		/**
		 * end set properti data passing, untuk content view
		*/

		## paging config
		$page = 1;		

		if($data_kat) {
			$data_produk = $this->m_global->multi_row('*', ['id_kategori' => $data_kat->id_kategori, 'deleted_at' => null], 'm_barang');
			$where_paging = ['deleted_at' => null, 'id_kategori' => $data_kat->id_kategori];
		}else{
			$data_produk = $this->m_global->multi_row('*', ['deleted_at' => null], 'm_barang');
			$where_paging = ['deleted_at' => null];
		}
		
		$str_links = $this->set_paging_config($data_produk, $per_page);

		$data['links'] = $str_links;
		$data['data_produk'] = $data_produk;
		$data['results'] = $this->m_barang->get_list_barang($per_page, $page, $sort_by, $where_paging);
		$data['js'] = 'home.js';

		$this->load->view('v_template', $data, FALSE);
	}

	/**
	 * return links
	 */
	public function set_paging_config($data, $per_page)
	{
		## set paging config
		$this->paging_config(count($data), $per_page);
		## get links
		return $this->custom_paging->create_links_without_anchor();
	}

	public function get_temp_produk_item()
	{
		$page = $this->input->get('page');
		$per_page = ($this->input->get('perPage')) ? $this->input->get('perPage') : 3;
		$sort_by = ($this->input->get('sortBy')) ? $this->input->get('sortBy') : 'created_at desc';
		if($this->input->get('kat')) {
			$txt_kat = clean_string(trim(strtolower(str_ireplace('-', ' ', $this->input->get('kat')))));
		}else{
			$txt_kat = '';
		}

		### kategori
		$data_kat = $this->m_global->single_row('*',['trim(nama_kategori)' => $txt_kat],'m_kategori');

		if($data_kat) {
			$all_produk = $this->m_global->multi_row('*', ['id_kategori' => $data_kat->id_kategori, 'deleted_at' => null], 'm_barang');
			$where_paging = ['deleted_at' => null, 'id_kategori' => $data_kat->id_kategori];
		}else{
			$all_produk = $this->m_global->multi_row('*', ['deleted_at' => null], 'm_barang');
			$where_paging = ['deleted_at' => null];
		}

		$data_produk = $this->m_barang->get_list_barang($per_page, $page, $sort_by, $where_paging);
		$this->paging_config(count($all_produk), $per_page, $page);
		$str_links = $this->custom_paging->create_links_without_anchor();
		
		// var_dump($str_links);exit;

		if($data_produk) {
			$html = '';
			foreach ($data_produk as $key => $value) {
				$html .= '<div class="col-lg-4 col-sm-6">
					<div class="l_product_item">
						<div class="l_p_img">
							<img class="img-fluid" src="'.base_url('bo/files/img/barang_img/resize_image/').$value->gambar.'" alt="">
						</div>
						<div class="l_p_text">
							<ul>
								<li><a class="add_cart_btn" href="'.base_url('produk/produk_detail/'.$value->sku).'">Lihat Detail</a></li>
							</ul>
							<h4>'.$value->nama.'</h4>
							<h5>Rp '.number_format($value->harga,2,',','.').'</h5>
						</div>
					</div>
				</div>';
			}

			echo json_encode(['status' => true, 'html' => $html, 'links' => $str_links]);
		}else{
			echo json_encode(['status' => false]);
			return;
		}
	}

	private function get_temp_container_header()
	{
		$this->data_passing_content[] = 'temp_component/v_container_header';
	}

	private function get_temp_container_menu()
	{
		$this->data_passing_content[] = 'temp_component/v_container_menu';
	}

	private function get_temp_container_slider()
	{
		$this->data_passing_content[] = 'temp_component/v_container_slider';
	}

	private function get_temp_container_feature()
	{
		$this->data_passing_content[] = 'temp_component/v_container_feature';
	}

	private function get_temp_container_latest()
	{
		$this->data_passing_content[] = 'temp_component/v_container_latest';
	}

	private function get_temp_container_adv_big()
	{
		$this->data_passing_content[] = 'temp_component/v_container_adv_big';
	}

	private function get_temp_container_product_listing()
	{
		$this->data_passing_content[] = 'temp_component/v_container_product_listing';
	}

	private function get_temp_container_product_related()
	{
		$this->data_passing_content[] = 'temp_component/v_container_product_related';
	}

	private function get_temp_container_blog()
	{
		$this->data_passing_content[] = 'temp_component/v_container_blog';
	}

	private function get_temp_produk_kategori()
	{
		$this->data_passing_content[] = 'temp_component/v_produk_kategori';
	}


	public function paging_config($total_row, $per_page, $current_page=0)
	{
		if (!$per_page) {
			$per_page = 10; //default per page
		}

		//set array for pagination library
		$config = array();
		//$config["base_url"] = base_url() . "home/index/";
		$config["base_url"] = '#';
        $config["total_rows"] = $total_row;
        $config["per_page"] = $per_page;
		$config['num_links'] = 10;
		$config["cur_page"] = $current_page;
		$config['first_link'] = 'First';
		$config['attributes'] = array('class' => 'page-link paging', 'onClick' => 'getPaging(this)');
		// $config['attributes']['rel'] = FALSE;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';

		$config['cur_tag_open'] = '<li class="page-item active">';
        $config['cur_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		
		$config['prev_tag_open'] = '<li class="page-item prev">';
		$config['prev_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li class="page-item next">';
		$config['next_tag_close'] = '</li>';
		
       
        // $config['reuse_query_string'] = TRUE;
        // $config['query_string_segment'] = '';
       $config['use_page_numbers'] = TRUE;
        

        $this->custom_paging->initialize($config);

	}

	private function terjemahan_sorting($txt_sort){
		
		$data = [
			'snama' => 'nama asc',
			'snew' => 'created_at desc',
			'sold'	=> 'created_at asc',
			'sminprice' => 'harga asc',
			'smaxprice' => 'harga desc'
		];

		if($data[$txt_sort]) {
			$retval = $data[$txt_sort];
		}else{
			$retval = false;
		}

		return $retval;
	}


	public function oops()
	{	
		$this->load->view('login/view_404');
	}

	public function bulan_indo($bulan)
	{
		$arr_bulan =  [
			1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
		];

		return $arr_bulan[(int) $bulan];
	}

	private function generate_kode_ref() {

		$chars = array(
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
			'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
		);
	
		shuffle($chars);
	
		$num_chars = count($chars) - 1;
		$token = '';
	
		for ($i = 0; $i < 8; $i++){ // <-- $num_chars instead of $len
			$token .= $chars[mt_rand(0, $num_chars)];
		}
	
		return $token;
	}

	private function get_harga_teks($harga)
	{
		
	}

}
