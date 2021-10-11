<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('m_penjualan');
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
			'title' => 'Invoice Penjualan',
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
			'js'	=> 'penjualan.js',
			'view'	=> 'view_invoice_penjualan'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_penjualan()
	{
		$list = $this->m_penjualan->get_datatable_user();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $invoice) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $invoice->order_id;
			$row[] = $invoice->no_faktur;
			$row[] = $invoice->nama_toko;
			$row[] = $invoice->alamat;
			$row[] = $invoice->tgl_jatuh_tempo;
			$row[] = $invoice->username;
			
			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">
						<button class="dropdown-item" onclick="edit_penjualan(\''.$invoice->order_id.'\')">
							<i class="la la-pencil"></i> Edit Invoice
						</button>
						<button class="dropdown-item" onclick="delete_penjualan(\''.$invoice->order_id.'\')">
							<i class="la la-trash"></i> Hapus
						</button>
						<button class="dropdown-item" onclick="cetak_invoice(\''.$invoice->order_id.'\')">
							<i class="la la-trash"></i> Cetak
						</button>
			';

			$str_aksi .= '</div></div>';
			$row[] = $str_aksi;

			$data[] = $row;
		}//end loop

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_penjualan->count_all(),
			"recordsFiltered" => $this->m_penjualan->count_filtered(),
			"data" => $data
		];
		
		echo json_encode($output);
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

		if ($this->input->post('pelanggan') == '') {
			$data['inputerror'][] = 'pelanggan';
            $data['error_string'][] = 'Wajib Memilih Nama Toko Pelanggan';
            $data['status'] = FALSE;
		}

		if ($this->input->post('sales') == '') {
			$data['inputerror'][] = 'sales';
            $data['error_string'][] = 'Wajib Memilih Nama Sales';
            $data['status'] = FALSE;
		}

		if ($this->input->post('tgl_jatuh_tempo') == '') {
			$data['inputerror'][] = 'tgl_jatuh_tempo';
            $data['error_string'][] = 'Wajib Mengisi Tgl Jatuh Tempo';
            $data['status'] = FALSE;
		}

        return $data;
	}

	// ===============================================
	private function rule_validasi_order($is_update=false, $skip_pass=false)
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

	public function new_penjualan()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
			
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'Penjualan Baru',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'pelanggan' => $this->m_global->getSelectedData('m_pelanggan', array('deleted_at'=>NULL)),
			'sales'     => $this->m_global->getSelectedData('m_user', array('id_role'=>6)),
			'mode'		=> 'add',
		);

		$mode = $this->input->get('mode');
		if ($mode == 'edit') {
			$order_id = $this->input->get('order_id');
			$invoice = $this->m_global->getSelectedData('t_penjualan', array('order_id'=>$order_id))->row();
			$data['invoice'] = $invoice;
			$data['mode'] = $mode;
			$data['title'] = "Edit Penjualan";
			$data['id_penjualan'] = $invoice->id_penjualan;
			$data['tgl_jatuh_tempo'] = date("d/m/Y", strtotime($invoice->tgl_jatuh_tempo));
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
			'js'	=> 'penjualan.js',
			'view'	=> 'view_new_invoice'
		];

		$this->template_view->load_view($content, $data);
	}

	public function add_new_penjualan()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		$arr_valid = $this->rule_validasi();
		$counter_penjualan = $this->m_penjualan->get_max_penjualan();
		
		$id_pelanggan 		= $this->input->post('pelanggan');
		$id_sales 			= $this->input->post('sales');
		$tgl_jatuh_tempo	= $this->input->post('tgl_jatuh_tempo');
		$date 				= str_replace('/', '-', $tgl_jatuh_tempo);
		$jatuh_tempo 		= date("Y-m-d H:i:s", strtotime($date) );
		$no_faktur          = no_faktur($tgl, $counter_penjualan);

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$this->db->trans_begin();
		
		$data = [
			'no_faktur'         => $no_faktur,
			'id_pelanggan' 		=> $id_pelanggan,
			'id_sales' 			=> $id_sales,
			'tgl_jatuh_tempo'	=> $jatuh_tempo,
			'created_at'		=> $timestamp
		];
		
		$insert = $this->m_penjualan->save($data);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan Data Invoice';
		}else{
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Menambahkan Data Invoice';
			$retval['no_faktur'] = $no_faktur;
		}

		echo json_encode($retval);
	}


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


	public function add_order()
	{
		$no_faktur = $this->input->get('no_faktur');
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		
		// var_dump($diskon); die();
			
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'NEW INVOICE',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
		);

		$data['invoice'] = $this->m_penjualan->getPenjualan($no_faktur)->row();
		$data['barang']  = $this->m_global->getSelectedData('m_barang', array('deleted_at'=>NULL));

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
			'view'	=> 'view_add_order_penjualan'
		];

		$this->template_view->load_view($content, $data);
	}

	public function save_order()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$arr_valid = $this->rule_validasi_order();
		
		$id_penjualan 	= $this->input->post('id_penjualan');
		$id_barang      = $this->input->post('id_barang');
		$data_where     = array('id_barang'=> $id_barang);
		$barang         = $this->m_global->getSelectedData('m_barang', $data_where)->row();
		$qty            = $this->input->post('qty');
		$diskon    = str_replace('%', '', $this->input->post('diskon'));
		$diskon    = str_replace(',','.',$diskon);

		$nilai     =($diskon/100)*$barang->harga;
		$harga_diskon = $barang->harga - $nilai;
		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$sub_total = $harga_diskon * $qty;
		$this->db->trans_begin();
		
		$data_order = [
			'id_penjualan'  => $id_penjualan,
			'id_barang' 	=> $id_barang,
			'harga_awal' 	=> $barang->harga,
			'harga_diskon' 	=> $harga_diskon,
			'besaran_diskon'=> $diskon,
			'sub_total'     => $sub_total,
			'qty'           => $qty
		];
		
		$insert = $this->m_global->save($data_order, 't_penjualan_det');
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan Order';
		}else{
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses menambahkan Order';
		}

		echo json_encode($retval);
	}

	public function fetch()
	{
		// $order_id  = $this->input->get('order_id');
		// $penjualan = $this->m_global->getSelectedData('t_penjualan', array('order_id'=>$order_id))->row();
		// $id        = $penjualan->id_penjualan;
		$id = $this->input->post('id');
        $data = $this->m_penjualan->getPenjualanDet($id)->result();
        foreach($data as $row){
            ?>
            <tr>
                <td width="10%"><input type="number" class="form-control" width="5" id="qty_order_<?php echo $row->id_penjualan_det;?>" value="<?php echo $row->qty; ?>" onchange="tes(<?php echo $row->id_penjualan_det ?>)"></td>
                <td style="vertical-align: middle;"><?php echo $row->nama; ?></td>
                <td style="vertical-align: middle;"><?php echo 'Rp '.number_format($row->harga_diskon); ?></td>
                <td style="vertical-align: middle;"><?php echo 'Rp '.number_format($row->sub_total); ?></td>
				<td style="vertical-align: middle;"><button class="btn-danger" alt="batalkan" onclick="hapus_order(<?php echo $row->id_penjualan_det; ?>)"><i class="fa fa-times"></i></button></td>
            </tr>
            <?php
        }
	}
	
	public function total_order()
	{
		
		$id = $this->input->post('id');
		$hasil = $this->m_penjualan->getTotalOrder($id)->row();
		$data = array();
		if (!empty($hasil)) {
			// var_dump($data->total);
			$data['total'] = 'Rp '.number_format($hasil->total);
		} else {
			$data['total'] = 0;
		}

		echo json_encode($data);
		
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

	public function hapus_order()
	{
		$id = $this->input->post('id');
		$data_where = array('id_penjualan_det' => $id);
		$del = $this->m_global->force_delete($data_where, 't_penjualan_det');
		if($del) {
			$retval['status'] = TRUE;
			$retval['pesan'] = 'Data Order berhasil dihapus';
		}else{
			$retval['status'] = FALSE;
			$retval['pesan'] = 'Data Order berhasil dihapus';
		}

		echo json_encode($retval);

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

	public function cetak_invoice()
	{
		$order_id = $this->input->get('order_id');
		$pen      = $this->m_global->getSelectedData('t_penjualan', array('order_id'=>$order_id))->row();
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
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal Mengubah Data';
		}else{
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Mengubah Data ';
			// $retval['order_id'] = $order_id;
		}

		echo json_encode($retval);
	}
}
