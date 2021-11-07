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
			'title' => 'Pengelolaan Daftar Penjualan',
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
			'modal' => 'modal_detail_pengeluaran',
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
			$row[] = DateTime::createFromFormat('Y-m-d H:i:s', $invoice->created_at)->format('d-m-Y');
			$row[] = $invoice->no_faktur;
			$row[] = $invoice->nama_toko;
			$row[] = $invoice->alamat;
			$row[] = $invoice->nama_sales;
			$row[] = $invoice->metode;
			
			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">
						<button class="dropdown-item" onclick="edit_penjualan(\''.$invoice->no_faktur.'\')">
							<i class="la la-pencil"></i> Edit Invoice
						</button>
						<button class="dropdown-item" onclick="detail_penjualan(\''.$invoice->no_faktur.'\',\''.$invoice->id_penjualan.'\')">
							<i class="la la-desktop"></i> Lihat Penjualan
						</button>
						<button class="dropdown-item" onclick="delete_penjualan(\''.$invoice->no_faktur.'\',\''.$invoice->id_penjualan.'\')">
							<i class="la la-trash"></i> Hapus
						</button>
						<button class="dropdown-item" onclick="cetak_invoice(\''.$invoice->no_faktur.'\')">
							<i class="la la-print"></i> Cetak
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

	public function get_detail_penjualan()
	{
		$id = $this->input->get('id');
		$kode = $this->input->get('kode');
		
		$join = [ 
			['table' => 'm_pelanggan', 'on' => 't_penjualan.id_pelanggan = m_pelanggan.id_pelanggan'],
			['table' => 'm_user', 'on' => 't_penjualan.id_sales = m_user.id'],
		];
		$header = $this->m_global->single_row('t_penjualan.*, m_pelanggan.nama_toko, m_user.nama as nama_sales', [
			't_penjualan.no_faktur' => $kode,
			't_penjualan.id_penjualan' => $id, 
			't_penjualan.deleted_at' => null,
		], 't_penjualan', $join);

		$detail = $this->m_penjualan->getPenjualanDet($id)->result();
		
		$html_det = '';
		if($detail) {
			$total_harga_sum = 0;
			foreach ($detail as $key => $value) {
				$total_harga_sum += $value->sub_total;
				$html_det .= '<tr>
					<td style="vertical-align: middle;">'.$value->qty.'</td>
					<td style="vertical-align: middle;">'.$value->nama.'</td>
					<td style="vertical-align: middle;" align="right">'.number_format($value->harga_diskon).'</td>
					<td style="vertical-align: middle;" align="right">'.number_format($value->sub_total).'</td>
				</tr>';
			}
			$html_det .= '<tr>
				<td colspan="3" style="vertical-align: middle;font-weight:bold;" align="center">Grand Total</td>
				<td style="vertical-align: middle;font-weight:bold;" align="right">'.number_format($total_harga_sum).'</td>
			</tr>';
		}else{
			$html_det .= '<tr>
				<td style="vertical-align: middle;" colspan="4" align="center">Belum ada data transaksi ...</td>
			</tr>';
		}
	
		
		echo json_encode([
			'header' => $header,
			'html_det' => $html_det
		]);
		
	}

	public function delete_penjualan()
	{
		$this->db->trans_begin();
		$id = $this->input->post('id');
		$kode = $this->input->post('kode');

		$cek = $this->m_global->single_row('*', ['id_penjualan' => $id], 't_penjualan');
		$cek2 = $this->m_penjualan->getPenjualanDet($id);

		
		echo "<pre>";
		print_r ($cek2);
		echo "</pre>";
		exit;

		if($cek) {
			$del = $this->m_global->force_delete(['id_penjualan' => $id], 't_penjualan');
		}

		if($cek2) {
			$loop_data = $cek2;
			$del2 = $this->m_global->force_delete(['id_penjualan' => $id], 't_penjualan_det');
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal Hapus Data';
		}else{
			// $roll = $this->lib_mutasi->rollBack($cek->no_faktur);
			// $roll = $this->lib_mutasi->updateMutasi($id_barang, $totalPermintaan, $id_kategori_trans, $kode_reff,  $id_gudang = null, $tanggal = null);
			// if($roll['status'] == true) {
			// 	$this->db->trans_commit();
			// 	$retval['status'] = true;
			// 	$retval['pesan'] = 'Sukses Hapus Data ';
			// }else{
			// 	$this->db->trans_rollback();
			// 	$retval['status'] = false;
			// 	$retval['pesan'] = 'Gagal Hapus Data';
			// }
		}
		
		echo json_encode($retval);
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
			$no_faktur = $this->input->get('no_faktur');
			$invoice = $this->m_global->getSelectedData('t_penjualan', array('no_faktur'=>$no_faktur))->row();
			
			if(!$invoice) {
				return redirect('penjualan');
			}

			$data['invoice'] = $invoice;
			$data['mode'] = $mode;
			$data['title'] = "Edit Penjualan";
			$data['id_penjualan'] = $invoice->id_penjualan;
			$data['no_faktur'] = $invoice->no_faktur;
			// $data['tgl_jatuh_tempo'] = date("d/m/Y", strtotime($invoice->tgl_jatuh_tempo));
		}
		// else{
		// 	return redirect('penjualan');
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

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$is_update = false;

		if($this->input->post('id_penjualan') != '') {
			$cek = $this->m_penjualan->get_by_id($this->input->post('id_penjualan'));
			if($cek){
				$is_update = true;
				$no_faktur = $cek->no_faktur;
				$id_penjualan_fix = $cek->id_penjualan;
			}else{
				$retval['status'] = false;
				$retval['pesan'] = 'Data Tidak Ditemukan';
				echo json_encode($retval);
				return;
			}
		}
		
		if(!$is_update) {
			$counter_penjualan = $this->m_penjualan->get_max_penjualan();
			$no_faktur = no_faktur($tgl, $counter_penjualan);
			$data['no_faktur'] = $no_faktur;
			$data['created_at']	= $timestamp;
		}
		
		$id_pelanggan 		= $this->input->post('pelanggan');
		$id_sales 			= $this->input->post('sales');
		$is_kredit			= ($this->input->post('metode') == '1') ? 1 : null;

		$data['id_pelanggan'] = $id_pelanggan;
		$data['id_sales'] = $id_sales;
		$data['is_kredit'] = $is_kredit;

		$this->db->trans_begin();

		if($is_update) {
			$this->m_penjualan->update(['id_penjualan' => $id_penjualan_fix], $data);
		}else{
			$insert = $this->m_penjualan->save($data);
		}

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

	public function get_option_barang()
	{
		$id_gudang = $this->input->get('id_gudang');
		$select = 't_stok.*, m_barang.nama';
		$where = ['t_stok.id_gudang' => $id_gudang, 't_stok.deleted_at' => null];
		$join = [ 
			['table' => 'm_barang', 'on' => 't_stok.id_barang = m_barang.id_barang'],
			// ['table' => 'm_agen', 'on' => 't_pembelian.id_agen = m_agen.id_agen'],
			// ['table' => 'm_user', 'on' => 't_pembelian.id_user = m_user.id'],
		];

		$order_by = 'm_barang.nama asc';
		$data_stok = $this->m_global->multi_row($select, $where, 't_stok', $join, $order_by);
		
		$html = '<option value="0">-PILIH-</option>';
		if($data_stok) {
			foreach ($data_stok as $key => $value) {
				$html .= "<option value='$value->id_barang'>".$value->nama."</option>";
			}
		}

		echo json_encode([
			'html' => $html,
		]);		
	}

	public function select_qty_barang()
	{
		$id_barang = $this->input->get('id_barang');
		$id_gudang = $this->input->get('id_gudang');
		$data_stok = $this->m_global->single_row('*', ['id_gudang' => $id_gudang, 'id_barang' => $id_barang, 'deleted_at' => null], 't_stok');

		if($data_stok) {
			$qty = $data_stok->qty;
		}else{
			$qty = 0;
		}

		echo json_encode($qty);		
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

		$cek_header = $this->m_penjualan->getPenjualan($no_faktur)->row();
		if(!$cek_header) {
			return redirect('penjualan');
		}

		$data['invoice'] = $cek_header;
		$data['gudang']  = $this->m_global->getSelectedData('m_gudang', array('deleted_at'=>NULL));
		// $data['barang']  = $this->m_global->getSelectedData('m_barang', array('deleted_at'=>NULL));

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
		$id_gudang      = $this->input->post('id_gudang');

		$data_where     = array('id_barang'=> $id_barang);
		$barang         = $this->m_global->getSelectedData('m_barang', $data_where)->row();
		$qty            = $this->input->post('qty');
		$diskon    		= str_replace('%', '', $this->input->post('diskon'));
		$diskon    		= str_replace(',','.',$diskon);
		$nilai     		= ($diskon/100)*$barang->harga;
		$harga_diskon 	= $barang->harga - $nilai;
		
		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$data_header = $this->m_global->getSelectedData('t_penjualan', ['id_penjualan' => $id_penjualan])->row();

		if(!$data_header) {
			$retval['status'] = false;
			$retval['pesan'] = 'Data Penjualan Tidak Ditemukan';
			echo json_encode($retval);
			return;
		}

		$faktur = $data_header->no_faktur;
		$sub_total = $harga_diskon * $qty;
		$this->db->trans_begin();
		
		$data_order = [
			'id_penjualan'  => $id_penjualan,
			'id_barang' 	=> $id_barang,
			'harga_awal' 	=> $barang->harga,
			'harga_diskon' 	=> $harga_diskon,
			'besaran_diskon'=> $diskon,
			'sub_total'     => $sub_total,
			'id_gudang'		=> $id_gudang,
			'qty'           => $qty
		];
		
		$insert = $this->m_global->save($data_order, 't_penjualan_det');

		if($insert) {
			$mutasi = $this->lib_mutasi->simpan_mutasi($id_barang, $qty, 2, $faktur, $id_gudang);
			
			if($mutasi === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menambahkan Order';
				return;
			}

			$laporan = $this->lib_mutasi->insertDataLap($sub_total, 2, $faktur, $data_header->is_kredit);

			if($laporan['status'] === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menambahkan Order';
				return;
			}

		}
		
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
                <!-- <td width="10%"><input type="number" class="form-control" width="5" id="qty_order_<?php echo $row->id_penjualan_det;?>" value="<?php echo $row->qty; ?>" onchange="tes(<?php echo $row->id_penjualan_det ?>)"></td> -->
				<td width="10%"><input type="hidden" class="form-control" width="5" id="qty_order_<?php echo $row->id_penjualan_det;?>" value="<?php echo $row->qty; ?>" onchange="tes(<?php echo $row->id_penjualan_det ?>)"><?php echo $row->qty; ?></td>
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
		$this->db->trans_begin();
		$id = $this->input->post('id');
		$join = [ 
			['table' => 't_penjualan', 'on' => 't_penjualan_det.id_penjualan = t_penjualan.id_penjualan'],
		];

		$data_where = ['id_penjualan_det' => $id];

		$cek_trans = $this->m_global->single_row('t_penjualan_det.*, t_penjualan.no_faktur, t_penjualan.is_kredit', $data_where, 't_penjualan_det', $join);
		
		$del = $this->m_global->force_delete($data_where, 't_penjualan_det');

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal Hapus Data';
		}else{
			// $cek = $this->lib_mutasi->rollBack($cek_trans->no_faktur, $cek_trans->id_barang, $cek_trans->qty);
			$cek = $this->lib_mutasi->updateMutasi(
				$cek_trans->id_barang, 
				-abs($cek_trans->qty), 
				2, 
				$cek_trans->no_faktur, 
				$cek_trans->id_gudang
			);

			if($cek) {
				// update data lap keuangan
				$keu = $this->lib_mutasi->updateDataLap(
					-$cek_trans->sub_total,
					2,
					$cek_trans->no_faktur,
					$cek_trans->is_kredit
				);

				if($keu['status'] == true) {
					$this->db->trans_commit();
					$retval['status'] = true;
					$retval['pesan'] = 'Sukses Hapus Data ';
				}else{
					$this->db->trans_rollback();
					$retval['status'] = false;
					$retval['pesan'] = 'Gagal Hapus Data';
				}
			}else{
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal Hapus Data';
			}
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
