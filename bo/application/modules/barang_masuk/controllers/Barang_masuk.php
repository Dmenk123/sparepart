<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_masuk extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('t_penerimaan');
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
			'title' => 'Pengelolaan Penerimaan Pembelian',
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
			'modal' => 'modal_detail_barang_masuk',
			'js'	=> 'penerimaan.js',
			'view'	=> 'view_list_barang_masuk'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_barang_masuk()
	{
		$list = $this->t_penerimaan->get_datatable_penerimaan();

		$data = array();
		$no =$_POST['start'];
		foreach ($list as $value) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $value->tanggal;
			$row[] = $value->kode_penerimaan;
			$row[] = $value->nama_perusahaan;
			$row[] = $value->kode_pembelian;
			$row[] = number_format($value->total_harga);

			// $str_aksi = '
			// 	<div class="btn-group">
			// 		<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
			// 		<div class="dropdown-menu">
			// 			<button class="dropdown-item" onclick="detail_penerimaan(\''.$value->kode_penerimaan.'\')">
			// 				<i class="la la-desktop"></i> Lihat Penerimaan
			// 			</button>
			// 			<button class="dropdown-item" onclick="edit_penerimaan(\''.$value->kode_penerimaan.'\')">
			// 				<i class="la la-pencil"></i> Edit Penerimaan
			// 			</button>
			// 			<button class="dropdown-item" onclick="delete_penerimaan(\''.$value->kode_penerimaan.'\')">
			// 				<i class="la la-trash"></i> Hapus
			// 			</button>
			// 		';

			$str_aksi = '
			<div class="btn-group">
				<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
				<div class="dropdown-menu">
					<button class="dropdown-item" onclick="detail_penerimaan(\''.$value->kode_penerimaan.'\',\''.$value->id_penerimaan.'\')">
						<i class="la la-desktop"></i> Lihat Penerimaan
					</button>
					<button class="dropdown-item" onclick="delete_penerimaan(\''.$value->kode_penerimaan.'\')">
						<i class="la la-trash"></i> Hapus
					</button>
				';

			$str_aksi .= '</div></div>';
			$row[] = $str_aksi;

			$data[] = $row;
		}//end loop

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->t_penerimaan->count_all(),
			"recordsFiltered" => $this->t_penerimaan->count_filtered(),
			"data" => $data
		];
		
		echo json_encode($output);
	}

	public function new_penerimaan()
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');

		$counter_penerimaan = $this->t_penerimaan->get_max_penerimaan();
		
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'Penerimaan Pembelian Baru',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'data_beli' => $this->m_global->getSelectedData('t_pembelian', array('deleted_at' => NULL, 'is_terima_all' => NULL)),
			'data_gudang' => $this->m_global->multi_row('*', ['deleted_at' => null], 'm_gudang', null, 'nama_gudang'),
			'kode_trans'=> generate_kode_transaksi($tgl, $counter_penerimaan, 'RCV'),
			'mode'		=> 'add',
		);

		// $mode = $this->input->get('mode');
		
		// if ($mode == 'edit') {
		// 	$order_id = $this->input->get('order_id');
		// 	$pembelian = $this->m_global->getSelectedData('t_pembelian', array('order_id' => $order_id))->row();
		// 	$data['pembelian'] = $pembelian;
		// 	$data['mode'] = $mode;
		// 	$data['title'] = "Edit Pembelian";
		// 	$data['id_pembelian'] = $pembelian->id_penjualan;
		// 	$data['kode_trans'] = $pembelian->kode_pembelian;
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
			'js'	=> 'penerimaan.js',
			'view'	=> 'view_new_barang_masuk'
		];

		$this->template_view->load_view($content, $data);
	}

	public function add_penerimaan()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		$kode = $this->input->get('reff');
		$is_update = $this->input->get('update');
		

		### cek jika kode valid
		$join = [ 
			['table' => 't_pembelian', 'on'	=> 't_penerimaan.id_pembelian = t_pembelian.id_pembelian'],
			['table' => 'm_agen', 'on' => 't_pembelian.id_agen = m_agen.id_agen']
		];
		$cek_kode = $this->m_global->single_row('t_penerimaan.*, t_pembelian.kode_pembelian, t_pembelian.tanggal as tanggal_beli, m_agen.nama_perusahaan', ['kode_penerimaan' => $kode, 't_penerimaan.deleted_at' => null], 't_penerimaan', $join);

		if(!$cek_kode) {
			return redirect('barang_masuk');
		}

		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		
		// var_dump($diskon); die();
			
		/**
		 * data passing ke halaman view content
		 */
		$retval = array(
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'data' => $cek_kode,
			'is_update' => $is_update
		);

		if($is_update == 'true') {
			$retval['title'] = 'Update Barang Masuk';
		}else{
			$retval['title'] = 'Barang Masuk Baru';
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
			'js'	=> 'penerimaan.js',
			'view'	=> 'view_add_barang_masuk'
		];

		$this->template_view->load_view($content, $retval);
	}

	public function save_new_penerimaan()
	{
		$this->load->library('Enkripsi');
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		$arr_valid = $this->rule_validasi();

		$kode_penerimaan = $this->input->post('kode_penerimaan');
		$id_pembelian 	 = $this->input->post('id_pembelian');

		$counter_penerimaan = $this->t_penerimaan->get_max_penerimaan();
		$cek_kode = generate_kode_transaksi($tgl, $counter_penerimaan, 'RCV');

		## untuk menghindari duplikat kode
		if($kode_penerimaan == $cek_kode) {
			$kode_penerimaan_fix = $kode_penerimaan;
		}else{
			$kode_penerimaan_fix = $cek_kode;
		}

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$this->db->trans_begin();

		$data_penerimaan = [
			'id_pembelian' 	=> $id_pembelian,
			'kode_penerimaan' => $kode_penerimaan_fix,
			'id_user' 	=> $this->session->userdata('id_user'),
			'id_gudang' => $this->input->post('id_gudang'),
			'tanggal' 	=> $tgl,
			'total_harga' => 0,
			'created_at' => $timestamp,
		];

		$insert = $this->m_global->save($data_penerimaan, 't_penerimaan');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = 'Gagal menambahkan Data Barang Masuk';
		} else {
			$this->db->trans_commit();
			$retval['status'] = true;
			$retval['pesan'] = 'Sukses Menambahkan Data Barang Masuk';
			$retval['kode'] = $kode_penerimaan_fix;
		}

		echo json_encode($retval);
	}

	public function fetch()
	{
		$id = $this->input->post('id');
		$id_masuk = $this->input->post('idMasuk');
		$flag_is_update = false;
		$flag_has_transaksi = false;
		$data_beli_det = null;
		
		$data_masuk = $this->t_penerimaan->getPenerimaanDet($id_masuk)->result();
		
		if($data_masuk == null) {			
			### transaksi baru
			$data = $this->t_penerimaan->getPembelianDet($id)->result();
			$flag_has_transaksi = true;
		}else{
			### transaksi update
			$data = $data_masuk;
			$flag_is_update = true;
		}
        
        foreach($data as $row){ 
			$idx_row = ($flag_is_update) ? $row->id_penerimaan_det : $row->id_pembelian_det;
			$harga = ($flag_is_update) ? $row->harga : $row->harga_fix;
			$harga_total = ($flag_is_update) ? $row->harga_total : $row->harga_total_fix;

			if($flag_has_transaksi) {
				### qty pembelian - qty diterima (jika pembelian ini sudah pernah diterima)
				$qty = $row->qty - $row->qty_terima;
				### replace value harga_total
				$harga_total = $qty * $harga;
			}else{
				$qty = $row->qty;
			}

			if(!$qty == 0) {  ?>
            <tr>
                <td width="10%">
					<input type="number" min="1" max="<?=$qty;?>" class="form-control" width="5" id="qty_order_<?php echo $idx_row;?>" value="<?php echo $qty; ?>" onchange="tes(<?php echo $idx_row ?>)" name="qty[]">
					<input type="hidden" class="form-control kelas_htotal" name="harga_total_raw[]" id="harga_total_raw_<?php echo $idx_row;?>" value="<?php echo $harga_total; ?>">
					<input type="hidden" class="form-control" value="<?php echo $idx_row; ?>" name="pembelian_det[]">
					<input type="hidden" class="form-control" value="<?php echo $row->id_barang; ?>" name="id_barang[]">
				</td>
                <td style="vertical-align: middle;"><?php echo $row->nama; ?></td>
                <td style="vertical-align: middle;"><?php echo 'Rp '.number_format($harga); ?></td>
                <td style="vertical-align: middle;" id="harga_total_<?php echo $idx_row;?>"><?php echo 'Rp '.number_format($harga_total); ?></td>
				<td style="vertical-align: middle;"><button type="button" class="btn-danger" alt="batalkan" onclick="hapus_trans_detail(this)"><i class="fa fa-times"></i></button></td>
            </tr>
            <?php
			}
        }
	}

	public function get_detail_penerimaan()
	{
		$id = $this->input->get('id');
		$kode = $this->input->get('kode');
		
		$join = [ 
			['table' => 't_pembelian', 'on'	=> 't_penerimaan.id_pembelian = t_pembelian.id_pembelian'],
			['table' => 'm_agen', 'on' => 't_pembelian.id_agen = m_agen.id_agen'],
			['table' => 'm_user', 'on' => 't_pembelian.id_user = m_user.id'],
		];
		$header = $this->m_global->single_row('t_penerimaan.*, t_pembelian.kode_pembelian, t_pembelian.tanggal as tanggal_beli, m_agen.nama_perusahaan, m_user.nama as nama_user', ['kode_penerimaan' => $kode, 't_penerimaan.deleted_at' => null], 't_penerimaan', $join);

		$detail = $this->t_penerimaan->getPenerimaanDet($id)->result();
		$html_det = '';
		if($detail) {
			$total_harga_sum = 0;
			foreach ($detail as $key => $value) {
				$total_harga_sum += $value->harga_total;
				$html_det .= '<tr>
					<td style="vertical-align: middle;">'.$value->qty.'</td>
					<td style="vertical-align: middle;">'.$value->nama.'</td>
					<td style="vertical-align: middle;" align="right">'.number_format($value->harga).'</td>
					<td style="vertical-align: middle;" align="right">'.number_format($value->harga_total).'</td>
				</tr>';
			}
			$html_det .= '<tr>
				<td colspan="3" style="vertical-align: middle;font-weight:bold;" align="center">Grand Total</td>
				<td style="vertical-align: middle;font-weight:bold;" align="right">'.number_format($total_harga_sum).'</td>
			</tr>';
		}
		
		echo json_encode([
			'header' => $header,
			'html_det' => $html_det
		]);
		
	}
	// public function hapus_trans_detail() {
		// 	try {
		// 		$this->db->trans_begin();

		// 		$id = $this->input->post('id');
		// 		$pembelian_det = $this->m_global->getSelectedData('t_pembelian_det', array('id_pembelian_det' => $id))->row();

		// 		$id_pembelian = $pembelian_det->id_pembelian;

		// 		$pembelian     = $this->m_global->getSelectedData('t_pembelian', array('id_pembelian' => $id_pembelian))->row();

		// 		$kode_pembelian = $pembelian->kode_pembelian;

		// 		$del = $this->m_global->force_delete(['id_pembelian_det' => $id], 't_pembelian_det');

		// 		$hasil_total = $this->t_pembelian->getTotalPembelian($id_pembelian)->row();
		// 		$update_header = $this->m_global->update('t_pembelian', ['total_pembelian' => $hasil_total->total], ['id_pembelian' => $id_pembelian]);
			
		// 		$upd_laporan = $this->lib_mutasi->updateDataLap($hasil_total->total ,1, $kode_pembelian);
					
		// 		if($upd_laporan['status'] == false) {
		// 			$this->db->trans_rollback();
		// 			$retval['status'] = false;
		// 			$retval['pesan'] = 'Gagal menghapus detail Pembelian';
		// 			return;
		// 		}

		// 		if ($this->db->trans_status() === FALSE) {
		// 			$this->db->trans_rollback();
		// 			$retval['status'] = false;
		// 			$retval['pesan'] = 'Gagal menghapus detail Pembelian';
		// 		} else {
		// 			$this->db->trans_commit();
		// 			$retval['status'] = true;
		// 			$retval['pesan'] = 'Sukses menghapus detail Pembelian';
		// 		}
		// 	} catch (\Throwable $th) {
		// 		$this->db->trans_rollback();
		// 		$retval['status'] = false;
		// 		$retval['pesan'] = $th;
		// 	}
			

		// 	echo json_encode($retval);

	// }

	public function simpan_penerimaan_barang()
	{
		try {
			$this->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');
			
			$id_penerimaan = $this->input->post('id_penerimaan');
			$id_pembelian = $this->input->post('id_pembelian');
			$kode_penerimaan = $this->input->post('kode_penerimaan');
			
			$cek_penerimaan = $this->m_global->single_row("*", ['id_penerimaan' => $id_penerimaan, 'deleted_at' => null], 't_penerimaan');

			$sum_harga_total = 0;
			for ($i=0; $i < count($this->input->post('qty')); $i++) { 
				$sum_harga_total += $this->input->post('harga_total_raw')[$i];
				
				$joni = [ 
					['table' => 't_pembelian', 'on'	=> 't_pembelian_det.id_pembelian = t_pembelian.id_pembelian'],
				];

				$cek_pembelian_det = $this->m_global->single_row(
					"t_pembelian_det.*, t_pembelian.kode_pembelian, t_pembelian.is_kredit", 
					['id_pembelian_det' => $this->input->post('pembelian_det')[$i], 't_pembelian_det.deleted_at' => null], 
					't_pembelian_det', 
					$joni
				);

				### insert penerimaan det
				$arr_penerimaan_det = [
					'qty' => $this->input->post('qty')[$i],
					'id_penerimaan' => $id_penerimaan,
					'id_barang' => $this->input->post('id_barang')[$i],
					'harga' => $cek_pembelian_det->harga_fix,
					'harga_total' => $this->input->post('harga_total_raw')[$i],
					'created_at' => $timestamp,
				];

				$insert_det = $this->m_global->save($arr_penerimaan_det, 't_penerimaan_det');
				
				#### update pembelian det
				$where_update = ['id_pembelian_det' => $this->input->post('pembelian_det')[$i]];
				$data_update['qty_terima'] = $cek_pembelian_det->qty_terima + $this->input->post('qty')[$i];
				$data_update['tgl_terima'] = $tgl;
				$data_update['reff_terima'] = $kode_penerimaan;

				### cek qty tabel pembelian det, jika qty penerimaan / sum penerimaan sesuai jumlah, set flag is_terima
				if($cek_pembelian_det->qty == $this->t_penerimaan->sum_barang_masuk_pertransaksi($this->input->post('id_barang')[$i], $id_penerimaan)) {
					$data_update['is_terima'] = 1;
				} 

				$update = $this->t_pembelian->updatePembelianDet($where_update, $data_update);

				#### mutasi
				$mutasi = $this->lib_mutasi->mutasiMasuk(
					$this->input->post('id_barang')[$i], 
					$this->input->post('qty')[$i], 
					null, 
					4, 
					null, 
					$cek_pembelian_det->harga_fix, 
					$cek_penerimaan->id_gudang, 
					$cek_penerimaan->kode_penerimaan
				);

				#### insert laporan keuangan (pengurangan piutang pembelian)
				$lap = $this->lib_mutasi->insertDataLap(
					$this->input->post('harga_total_raw')[$i], 
					4, 
					$cek_pembelian_det->kode_pembelian,
					$cek_pembelian_det->is_kredit
				);
				
			}

			### update t_penerimaan (total_harga)
			$data_where = ['id_penerimaan' => $id_penerimaan];
			$this->m_global->update('t_penerimaan', ['total_harga' => $sum_harga_total + $cek_penerimaan->total_harga], $data_where);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menambahkan Penerimaan';
			} else {
				$this->db->trans_commit();

				$data_pembelian_det = $this->m_global->multi_row('*', ['id_pembelian' => $id_pembelian], 't_pembelian_det');
				$arr = [];
				foreach ($data_pembelian_det as $key => $value) {
					if($value->qty == $value->qty_terima) {
						$txt = 'ok';	
					}else{
						$txt = 'belum';
					}

					$arr[] = $txt; 
				}

				### jika tidak ada yg belum
				### update t_pembelian set flag is_terima_all = 1 where is_terima di masing-masing det not null
				if(!in_array('belum', $arr)) {
					$data_where = ['id_pembelian' => $id_pembelian];
					$this->m_global->update('t_penerimaan', ['is_terima_all' => 1], $data_where);
				}
				
				$retval['status'] = true;
				$retval['pesan'] = 'Sukses menambahkan Penerimaan';
			}

			$retval['status'] = true;
			$retval['pesan'] = 'Sukses menambahkan Penerimaan';
			
		} catch (\Throwable $th) {
			// $this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}

		echo json_encode($retval);
	}

	public function change_qty()
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');

		$this->db->trans_begin();
		$id_pembelian_det = $this->input->post('id');
		$qty              = $this->input->post('qty');
		$kodereff		  = $this->input->post('kodereff');

		$pembelian_det     = $this->m_global->getSelectedData('t_pembelian_det', array('id_pembelian_det' => $id_pembelian_det))->row();
		$penerimaan = $this->m_global->getSelectedData('t_penerimaan', ['id_pembelian' => $pembelian_det->id_pembelian, 'kode_penerimaan' => $kodereff])->row();
		$penerimaan_det     =  $this->m_global->getSelectedData('t_penerimaan_det', array('id_penerimaan' => $penerimaan->id_penerimaan, 'id_barang' => $pembelian_det->id_barang))->result();

		$qty_in = 0;
		
		if($penerimaan_det) {
			foreach ($penerimaan_det as $key => $value) {
				$qty_in += $value->qty;
			}
		}

		### jika qty inputan lebih besar dari seharusnya (bisa karena barang sudah diterima)
		### return false
		if(($qty > $qty_in) && ($qty_in > 0)) {
			$this->db->trans_rollback();
			$retval['qty'] = $qty_in;
			$retval['harga_total'] = 'Rp '.number_format($pembelian_det->harga_fix * $qty_in);
			$retval['harga_raw'] = $pembelian_det->harga_fix * $qty_in;
			echo json_encode($retval);
			return;
		}

		$qty_remaining = $pembelian_det->qty - $qty_in;

		### jika qty inputan lebih besar dari seharusnya (bisa karena barang sudah diterima)
		### return false
		if($qty > $qty_remaining) {
			$retval['qty'] = $qty_remaining;
			$retval['harga_total'] = 'Rp '.number_format($pembelian_det->harga_fix * $qty_remaining);
			$retval['harga_raw'] = $pembelian_det->harga_fix * $qty_remaining;
			echo json_encode($retval);
			return;
		}

		$subtotal = $pembelian_det->harga_fix * $qty; 
		
		// #### insert/update penerimaan detail
		// $cek     =  $this->m_global->getSelectedData('t_penerimaan_det', ['id_penerimaan' => $penerimaan->id_penerimaan, 'id_barang' => $pembelian_det->id_barang])->row();
		// if($cek) {
		// 	##### update
		// 	$data = array(
		// 		'id_barang' => $pembelian_det->id_barang,
		// 		'qty' => $qty,
		// 		'harga_total' => $subtotal,
		// 		'harga' => $pembelian_det->harga_fix,
		// 		'updated_at' => $timestamp
		// 	);
					
		// 	$data_where = array('id_pembelian_det' => $id_pembelian_det);
		// 	$update = $this->t_penerimaan->updatePenerimaanDet($data_where, $data);
		// }else{
		// 	##### insert
		// 	$data = array(
		// 		'id_barang' => $pembelian_det->id_barang,
		// 		'qty' => $qty,
		// 		'harga_total' => $subtotal,
		// 		'harga' => $pembelian_det->harga_fix,
		// 		'created_at' => $timestamp
		// 	);
		// 	$insert = $this->t_penerimaan->save($data);
		// }
		
		$retval['qty'] = $qty;
		$retval['harga_total'] = 'Rp '.number_format($pembelian_det->harga_fix * $qty);
		$retval['harga_raw'] = $pembelian_det->harga_fix * $qty;

		echo json_encode($retval);
	}

	public function delete_pembelian()
	{
		try {
			$this->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');
			$id_pembelian = $this->input->post('id');
			
			$cek_header = $this->m_global->single_row("*", ['id_pembelian' => $id_pembelian, 'deleted_at' => null], 't_pembelian');

			if(!$cek_header) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal hapus Pembelian';
				echo json_encode($retval);
				return;
			}

			### harddeletes
			$update = $this->m_global->force_delete(['id_pembelian' => $id_pembelian], 't_pembelian');
			$update_lap = $this->m_global->force_delete(['id_kategori_trans' => 1, 'kode_reff' => $cek_header->kode_pembelian], 't_lap_keuangan');

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

	

	// ===============================================
	private function rule_validasi($is_update = false, $skip_pass = false)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('id_pembelian') == '') {
			$data['inputerror'][] = 'id_pembelian';
			$data['error_string'][] = 'Wajib Memilih Kode Pembelian';
			$data['status'] = FALSE;
		}

		if ($this->input->post('id_gudang') == '') {
			$data['inputerror'][] = 'id_gudang';
			$data['error_string'][] = 'Wajib Memilih Gudang';
			$data['status'] = FALSE;
		}

		if ($this->input->post('kode_penerimaan') == '') {
			$data['inputerror'][] = 'kode_penerimaan';
			$data['error_string'][] = 'Wajib Menginputkan kode penerimaan';
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
			$hpp = $cek_mutasi->hpp;
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
