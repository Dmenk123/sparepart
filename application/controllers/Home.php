<?php
//defined('BASEPATH ') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_global');
	}

	protected $data_passing = [];

	public function index()
	{	
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tanggal = $obj_date->format('Y-m-d');
		
		/**
		 * set properti data passing, untuk view
		 */
		$this->get_temp_container_header();
		$this->get_temp_container_menu();
		$this->get_temp_container_slider();
		$this->get_temp_container_feature();
		$this->get_temp_container_latest();
		$this->get_temp_container_adv_big();
		$this->get_temp_container_product_listing();
		$this->get_temp_container_product_related();
		$this->get_temp_container_blog();
		$data = $this->data_passing;
		$data['val'] = 'iki kirimen nng view';

		// echo "<pre>";
		// print_r ($data);
		// echo "</pre>";
		// exit;

		$this->load->view('v_template', $data, FALSE);
		// $this->load->view('v_home', $data, FALSE);
	}

	public function get_temp_container_header()
	{
		$this->data_passing['container_header'] = 'temp_component/v_container_header';
	}

	public function get_temp_container_menu()
	{
		$this->data_passing['container_menu'] = 'temp_component/v_container_menu';
	}

	public function get_temp_container_slider()
	{
		$this->data_passing['container_slider'] = 'temp_component/v_container_slider';
	}

	public function get_temp_container_feature()
	{
		$this->data_passing['container_feature'] = 'temp_component/v_container_feature';
	}

	public function get_temp_container_latest()
	{
		$this->data_passing['container_latest'] = 'temp_component/v_container_latest';
	}

	public function get_temp_container_adv_big()
	{
		$this->data_passing['container_adv_big'] = 'temp_component/v_container_adv_big';
	}

	public function get_temp_container_product_listing()
	{
		$this->data_passing['container_product_listing'] = 'temp_component/v_container_product_listing';
	}

	public function get_temp_container_product_related()
	{
		$this->data_passing['container_product_related'] = 'temp_component/v_container_product_related';
	}

	public function get_temp_container_blog()
	{
		$this->data_passing['container_blog'] = 'temp_component/v_container_blog';
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
