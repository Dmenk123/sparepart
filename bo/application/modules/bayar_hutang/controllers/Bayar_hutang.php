<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bayar_hutang extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('t_bayar_hutang');
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
			'title' => 'Pengelolaan Pembayaran Hutang Pembelian',
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
			'modal' => 'modal_detail',
			'js'	=> 'bayar_hutang.js',
			'view'	=> 'view_list'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_bayar_hutang()
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$bulan = ($this->input->post('bulan') == '') ? (int)$obj_date->format('m') : $this->input->post('bulan');
		$tahun = ($this->input->post('tahun') == '') ? (int)$obj_date->format('Y') : $this->input->post('tahun');
		$kategori = ($this->input->post('kategori') == '') ? 'all' : $this->input->post('kategori');

		$paramdata = [
			'bulan' => $bulan,
			'tahun' => $tahun,
			'kategori' => $kategori
		];

		$listData = $this->t_bayar_hutang->get_datatable_transaksi($paramdata);
		$datas = [];
		$i = 1;
		foreach ($listData as $key => $value) {
			$datas[$key][] = $i++;
			$datas[$key][] = tanggal_indo($value->tanggal);
			$datas[$key][] = $value->kode;
			$datas[$key][] = ($value->is_lunas == 1) ? 'Lunas' : 'Belum Lunas';
			$datas[$key][] = number_format($value->nilai_bayar, 0, ',', '.');
			$datas[$key][] = $value->nama_user;
			$str_aksi = '
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opsi</button>
					<div class="dropdown-menu">
						<button class="dropdown-item" onclick="edit_transaksi(\'' . $value->kode . '\',\'' . $value->id . '\')">
							<i class="la la-pencil"></i> Edit Transaksi
						</button>
						<button class="dropdown-item" onclick="detail_transaksi(\'' . $value->kode . '\',\'' . $value->id . '\')">
							<i class="la la-desktop"></i> Lihat Detail
						</button>
						<button class="dropdown-item" onclick="delete_transaksi(\'' . $value->kode . '\',\'' . $value->id . '\')">
							<i class="la la-trash"></i> Hapus
						</button>
						<button class="dropdown-item" onclick="cetak_invoice(\'' . $value->kode . '\',\'' . $value->id . '\')">
							<i class="la la-print"></i> Cetak
						</button>
					</div>
				</div>
			';
			$datas[$key][] =  $str_aksi;
		}

		$data = [
			'data' => $datas
		];

		echo json_encode($data);
	}

	public function new_bayar_utang()
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$tgl = $obj_date->format('Y-m-d');
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->m_user->get_detail_user($id_user);
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');
		$counter_trans = $this->t_bayar_hutang->get_max_transaksi();
		/**
		 * data passing ke halaman view content
		 */
		$data = array(
			'title' => 'Pambayaran Hutang Baru',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'data_hutang' => $this->t_bayar_hutang->getDataLapKeuangan(),
			'kode_trans'=> generate_kode_transaksi($tgl, $counter_trans, 'BYR'),
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
			'js'	=> 'bayar_hutang.js',
			'view'	=> 'view_new'
		];

		$this->template_view->load_view($content, $data);
	}

	public function simpan_pembayaran()
	{
		try {
			$this->db->trans_begin();
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');

			$id_trans = $this->input->post('id_trans');
			$id_trans_det = $this->input->post('id_trans_det');
			$kode_trans = $this->input->post('kode_trans');
			$tanggal = $this->input->post('tanggal');
			$tanggal = DateTime::createFromFormat('d/m/Y', $tanggal)->format('Y-m-d');
			
			$kode_pembelian = $this->input->post('kode_pembelian');
			$data_beli = $this->m_global->single_row('*', ['kode_pembelian' => $kode_pembelian, 'deleted_at' => null], 't_pembelian');
			if(!$data_beli) {
				$retval['status'] = false;
				$retval['pesan'] = 'Data Pembelian tidak ditemukan';
				echo json_encode($retval);
				return;
			}

			$hutang = $this->input->post('hutang');
			$pembayaran = trim($this->input->post('pembayaran'));
			
			$hutang = (float)str_replace('.', '', $hutang);
			$pembayaran = (float)str_replace('.', '', $pembayaran);
			$keterangan = $this->input->post('keterangan');

			$arr_valid = $this->rule_validasi($hutang, $pembayaran);
			if ($arr_valid['status'] == FALSE) {
				echo json_encode($arr_valid);
				return;
			}

			if($id_trans == '') {
				$is_update = false;
			}else{
				$is_update = true;
			}

			if($is_update == false) {
				$max_transaksi = $this->m_global->max('id', 't_bayar_hutang', [
					'id_pembelian' => $data_beli->id_pembelian,
					'deleted_at' => null
				]);

				if($max_transaksi->id != null) {
					$id_transaksi = $max_transaksi->id;
				}else{
					$new_id = $this->m_global->max('id', 't_bayar_hutang', [
						'deleted_at' => null
					]);

					if($new_id->id != null) {
						$id_transaksi = $new_id->id+1;
					}else{
						$id_transaksi = 1;
					}
				}
	
				$max_transaksi_det = $this->m_global->max('id_bayar_det', 't_bayar_hutang', [
					'id_pembelian' => $data_beli->id_pembelian
				]);
	
				if($max_transaksi_det != null) {
					$id_transaksi_det = $max_transaksi_det->id_bayar_det+1;
				}else{
					$id_transaksi_det = 1;
				}
			}else{
				$id_transaksi = $id_trans;
				$id_transaksi_det = $id_trans_det;
			}

			### insert pembayaran
			$arr_pembayaran = [
				'id_pembelian' => $data_beli->id_pembelian,
				'kode' => $kode_trans,
				'tanggal' => $tanggal,
				'nilai_bayar' => $pembayaran,
				'keterangan' => $keterangan,
				'id_user' => $this->session->userdata('id_user'),
			];

			if(!$is_update) {
				$arr_pembayaran += [
					'id' => $id_transaksi,
					'id_bayar_det' => $id_transaksi_det,
					'created_at' => $timestamp,
				];
			}else{
				$arr_pembayaran += [
					'updated_at' => $timestamp,
				];
			}

			if(!$is_update) {
				$simpan = $this->m_global->save($arr_pembayaran, 't_bayar_hutang');
			}else{
				$simpan = $this->m_global->update('t_bayar_hutang', $arr_pembayaran, ['id' => $id_transaksi, 'id_bayar_det' => $id_transaksi_det]);
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menambahkan Pembayaran';
			} else {
				$this->db->trans_commit();

				#### insert laporan keuangan (pengurangan piutang pembelian)
				$lap = $this->lib_mutasi->insertDataLap(
					$pembayaran, 
					16,
					$data_beli->kode_pembelian,
					null,
					$tanggal,
					$kode_trans,
				);

				if($lap['status']) {

					### cek data pembayaran disamakan dengan total pembelian apakah sudah sama
					if ((float)$data_beli->total_pembelian == (float)$this->t_bayar_hutang->sum_pembayaran_by_id($id_transaksi)) {
						$data_update['is_lunas'] = 1;
						$data_update['tgl_lunas'] = $tanggal;
						$update = $this->t_pembelian->update(['id_pembelian' => $data_beli->id_pembelian], ['is_lunas' => 1, 'tgl_lunas' => $tanggal]);
					} 

					$retval['status'] = true;
					$retval['pesan'] = 'Sukses menambahkan Penerimaan';
				}else{
					$retval['status'] = false;
					$retval['pesan'] = 'Gagal menambahkan Penerimaan';
				}
				
			}
						
		} catch (\Throwable $th) {
			// $this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
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

	public function delete_penerimaan()
	{
		try {
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			$tgl = $obj_date->format('Y-m-d');
			$id_penerimaan = $this->input->post('id');
			$kode_penerimaan = $this->input->post('kode');
			
			$cek_header = $this->m_global->single_row("*", ['id_penerimaan' => $id_penerimaan, 'deleted_at' => null], 't_penerimaan');

			if(!$cek_header) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal hapus Penerimaan';
				echo json_encode($retval);
				return;
			}

			$this->db->trans_begin();

			$cek_lap_keu = $this->m_global->single_row("*", ['id_kategori_trans' => 4, 'kode_reff' => $cek_header->kode_penerimaan], 't_lap_keuangan');
			if($cek_lap_keu) {
				$del_lap = $this->m_global->soft_delete(['id_kategori_trans' => 4, 'kode_reff' => $cek_header->kode_penerimaan], 't_lap_keuangan');
			}

			$data_detail = $this->t_penerimaan->getPenerimaanDet($cek_header->id_penerimaan)->result();
			
			$arr_temp_detail = null;
			
			if($data_detail) {
				$arr_temp_detail = $data_detail;
			}
			
			foreach ($arr_temp_detail as $key => $value) {
				#### hapus stok mutasi
				$mutasi = $this->lib_mutasi->hapusMutasiMasuk(
					$value->id_barang, 
					$value->qty, 
					4,  
					null, 
					$cek_header->id_gudang, 
					$cek_header->kode_penerimaan,
				);

				// rollback pembelian
				$joni = [ 
					['table' => 't_pembelian', 'on'	=> 't_pembelian_det.id_pembelian = t_pembelian.id_pembelian'],
				];

				$cek_pembelian_det = $this->m_global->single_row(
					"t_pembelian_det.*, t_pembelian.kode_pembelian, t_pembelian.is_kredit", 
					['t_pembelian_det.id_pembelian' => $value->id_pembelian, 't_pembelian_det.id_barang' => $value->id_barang, 't_pembelian_det.deleted_at' => null], 
					't_pembelian_det', 
					$joni
				);

				if($cek_pembelian_det) {
					$where_update = ['id_pembelian' => $value->id_pembelian, 'id_barang' => $value->id_barang, 'deleted_at' => null];
					$data_update['is_terima'] = null;
					$data_update['qty_terima'] = $cek_pembelian_det->qty_terima - $value->qty;

					// $data_update['tgl_terima'] = $cek_pembelian_det->qty_terima - $value->qty;
					// $data_update['reff_terima'] = $cek_pembelian_det->qty_terima - $value->qty;
					
					$update = $this->t_pembelian->updatePembelianDet($where_update, $data_update);
				}
			}

			### soft_delete
			$del = $this->m_global->soft_delete(['id_penerimaan' => $cek_header->id_penerimaan], 't_penerimaan');
			$del_det = $this->m_global->soft_delete(['id_penerimaan' => $cek_header->id_penerimaan], 't_penerimaan_det');
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$retval['status'] = false;
				$retval['pesan'] = 'Gagal menghapus Penerimaan';
			} else {
				$this->db->trans_commit();

				$data_pembelian_det = $this->m_global->multi_row('*', ['id_pembelian' => $cek_header->id_pembelian], 't_pembelian_det');
				$arr = [];

				foreach ($data_pembelian_det as $key => $value) {
					if ($value->qty == $value->qty_terima) {
						$txt = 'ok';
					} else {
						$txt = 'belum';
					}

					$arr[] = $txt;
				}

				### jika tidak ada yg belum
				### update t_pembelian set flag is_terima_all = 1 where is_terima di masing-masing det not null
				if (!in_array('belum', $arr)) {
					$data_upd = ['is_terima_all' => 1];
				}else{
					$data_upd = ['is_terima_all' => null];
				}

				$data_where = ['id_pembelian' => $cek_header->id_pembelian];
				$this->m_global->update('t_pembelian', $data_upd, $data_where);

				// update data lap keuangan
				$keu = $this->lib_mutasi->deleteDataLap(
					4,
					$cek_header->kode_penerimaan,
				);

				$retval['status'] = true;
				$retval['pesan'] = 'Sukses menghapus Penerimaan';
			}

		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			$retval['status'] = false;
			$retval['pesan'] = $th;
		}

		echo json_encode($retval);
	}

	

	// ===============================================
	private function rule_validasi($hutang=null, $pembayaran=null)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('kode_trans') == '') {
			$data['inputerror'][] = 'kode_trans';
			$data['error_string'][] = 'Wajib Mengisi Kode Transaksi';
			$data['status'] = FALSE;
		}

		if ($this->input->post('tanggal') == '') {
			$data['inputerror'][] = 'tanggal';
			$data['error_string'][] = 'Wajib Memilih Tanggal';
			$data['status'] = FALSE;
		}

		if ($this->input->post('kode_pembelian') == '') {
			$data['inputerror'][] = 'kode_pembelian';
			$data['error_string'][] = 'Wajib Menginputkan Kode Pembelian';
			$data['status'] = FALSE;
		}

		if ($this->input->post('pembayaran') == '') {
			$data['inputerror'][] = 'pembayaran';
			$data['error_string'][] = 'Wajib Menginputkan Pembayaran';
			$data['status'] = FALSE;
		}

		if ($pembayaran > $hutang) {
			$data['inputerror'][] = 'pembayaran';
			$data['error_string'][] = 'Pembayaran Melebihi Nilai Hutang';
			$data['status'] = FALSE;
		}

		if ($this->input->post('hutang_txt') == '') {
			$data['inputerror'][] = 'hutang_txt';
			$data['error_string'][] = 'Wajib Menginputkan Hutang';
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
