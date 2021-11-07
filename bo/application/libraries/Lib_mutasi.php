<?php
class Lib_mutasi extends CI_Controller {
    protected $_ci;
    
    function __construct(){
		$this->_ci = &get_instance();
		$this->_ci->load->model('m_global');  //<-------Load the Model first
    }
	
	/**
	 * $id_barang = adalah id dari m_barang
	 * $totalPermintaan = adalah qty barang
	 * id_kategori_trans = id kategori transaksi
	 * tanggal = optional, jika null is date now
	 * id_gudang = optional (jika terdapat multi gudang)
	 */
	function simpan_mutasi(
		$id_barang, 
		$totalPermintaan, 
		$id_kategori_trans, 
		$kode_reff,
		$id_gudang,
		$tanggal = null
	) {
		try {
			$this->_ci->db->trans_begin();
			
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			
			if($tanggal) {
				$tgl = $tanggal;
			}else{
				$tgl = $obj_date->format('Y-m-d');
			}

			$totalHpp = '';

			#### ambil data dari t_stok
			$data_stok = $this->_ci->m_global->single_row('*', ['id_barang' => $id_barang, 'id_gudang' => $id_gudang, 'deleted_at' => null], 't_stok');
			
			if(!$data_stok) {
				$this->_ci->db->trans_rollback();
				$retval = ['status' => false, 'pesan' => 'Data Stok Tidak Diketemukan'];		
				return $retval;
			}
			
			### cek jika qty stock >= qty
			if($data_stok->qty >= $totalPermintaan) {
				### update tabel d_stock
				$qty = $data_stok->qty - $totalPermintaan;            
				### update data t_stok
				$this->_ci->m_global->update('t_stok', ['qty' => $qty], ['id_barang' => $id_barang, 'deleted_at' => null]);

			} else {
				### end of function
				$this->_ci->db->trans_rollback();
				return false;
			}
			
			### ambil data barang pada t_stok_mutasi  
			$getBarang = $this->_ci->m_global->multi_row('*', ['qty_sisa > ' => 0, 'id_barang' => $id_barang, 'id_gudang' => $id_gudang], 't_stok_mutasi', null, 'created_at asc'); 
			
			$newMutasi = [];
         	$updateMutasi = [];
			
			for ($k = 0; $k < count($getBarang); $k++) 
         	{
				### ambil last id pada t_stok_mutasi
				// $id_stok_mutasi_det = d_stock_mutation::where('sm_item',$item)->where('sm_comp',$comp)->where('sm_position',$position)->max('sm_detailid')+$k+1;
				$max_mutasi_det = $this->_ci->m_global->max('id_stok_mutasi_det', 't_stok_mutasi', [
					'id_stok' => $getBarang[$k]->id_stok, 
					'id_barang' => $id_barang,
					'id_gudang' => $id_gudang,
				]);

				$id_max_stok_mutasi_det = $max_mutasi_det->id_stok_mutasi_det + $k +1;

				$totalQty = $getBarang[$k]->qty_sisa;                                  
            	
				#### jika permintaan barang <= dari stok sisa tiap loop
				if ($totalPermintaan <= $totalQty) {
					$qty_pakai = $getBarang[$k]->qty_pakai + $totalPermintaan;
					$qty_sisa = $getBarang[$k]->qty_sisa - $totalPermintaan;
					$id_stok = $getBarang[$k]->id_stok;
					$id_stok_mutasi_det = $getBarang[$k]->id_stok_mutasi_det;

					### update data t_stok_mutasi
					$this->_ci->m_global->update('t_stok_mutasi', [
						'qty_pakai' => $qty_pakai,
						'qty_sisa' => $qty_sisa
					], ['id_stok' => $id_stok, 'id_stok_mutasi_det' => $id_stok_mutasi_det, 'deleted_at' => null]);
					
					$newMutasi[$k]['id_stok'] = $getBarang[$k]->id_stok;
					$newMutasi[$k]['id_stok_mutasi_det'] = $id_max_stok_mutasi_det; 
					$newMutasi[$k]['tanggal'] = $tgl;
					$newMutasi[$k]['hpp'] = $getBarang[$k]->hpp;
					$newMutasi[$k]['id_gudang'] = $id_gudang;
					$newMutasi[$k]['kode_reff'] = $kode_reff;
					$newMutasi[$k]['id_kategori_trans'] = $id_kategori_trans;
					$newMutasi[$k]['id_barang'] = $id_barang;
					$newMutasi[$k]['qty'] = -$totalPermintaan;
					$newMutasi[$k]['keterangan'] = 'PENGURANGAN';
					$newMutasi[$k]['created_at'] = $timestamp;

					// $newMutasi[$k]['sm_comp'] = $comp;
					            
					## set index loop ke akhir loop, agar tidak diloop kembali
					$k = count($getBarang);
            	} 
				#### jika permintaan barang > dari stok sisa tiap loop
				#### update data stok mutasi dengan sisa value yg ada
				elseif ($totalPermintaan > $totalQty) {
					$qty_pakai = $getBarang[$k]->qty_pakai + $totalQty;
					$qty_sisa = $getBarang[$k]->qty_sisa - $totalQty;
					$id_stok = $getBarang[$k]->id_stok;
					$id_stok_mutasi_det = $getBarang[$k]->id_stok_mutasi_det;

					### update data t_stok_mutasi
					$this->_ci->m_global->update('t_stok_mutasi', [
						'qty_pakai' => $qty_pakai,
						'qty_sisa' => $qty_sisa
					], ['id_stok' => $id_stok, 'id_stok_mutasi_det' => $id_stok_mutasi_det, 'deleted_at' => null]);

					$newMutasi[$k]['id_stok'] = $getBarang[$k]->id_stok;
					$newMutasi[$k]['id_stok_mutasi_det'] = $id_max_stok_mutasi_det;
					$newMutasi[$k]['tanggal'] = $tgl;
					$newMutasi[$k]['hpp'] = $getBarang[$k]->hpp;
					$newMutasi[$k]['qty'] = -$totalQty;
					$newMutasi[$k]['id_kategori_trans'] = $id_kategori_trans;
					$newMutasi[$k]['id_barang'] = $id_barang;
					$newMutasi[$k]['keterangan'] = 'PENGURANGAN';
					$newMutasi[$k]['kode_reff'] = $kode_reff; 
					$newMutasi[$k]['id_gudang'] = $id_gudang;
					$newMutasi[$k]['created_at'] = $timestamp;

					// $newMutasi[$k]['sm_comp'] = $comp;
					
					### kurangi total qty permintaan, agar sisa kurang diproses lagi di next loop
					$totalPermintaan = $totalPermintaan - $totalQty;
				}
         	}
			 
			//  echo "<pre>";
			//  print_r ($newMutasi);
			//  echo "</pre>";
			//  exit;

			### insert batch
			$this->_ci->db->insert_batch('t_stok_mutasi', $newMutasi); 

			if ($this->_ci->db->trans_status() === FALSE) {
				$this->_ci->db->trans_rollback();
				return FALSE;
			} 
			else {
				$this->_ci->db->trans_commit();
				return TRUE;
			}

		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
			return FALSE;
		}
	}

	/**
	 * $id_barang = adalah id dari m_barang
	 * $totalPermintaan = adalah qty barang
	 * id_kategori_trans = id kategori transaksi
	 * tanggal = optional, jika null is date now
	 * id_gudang = optional (jika terdapat multi gudang)
	 */
	function updateMutasi($id_barang, $totalPermintaan, $id_kategori_trans, $kode_reff,  $id_gudang = null, $tanggal = null)
   	{
		try {
			$this->_ci->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			
			if($tanggal) {
				$tgl = $tanggal;
			}else{
				$tgl = $obj_date->format('Y-m-d');
			}

			### jika permintaan > 0, panggil fungsi insert mutasi stok 
			if ($totalPermintaan > 0) {            
				
				$this->simpan_mutasi($id_barang, $totalPermintaan, $id_kategori_trans, $kode_reff, $tanggal);

			}else{
				
				### ambil data barang pada t_stok_mutasi  
				$getBarang = $this->_ci->m_global->multi_row('*', ['qty <' => 0, 'id_barang' => $id_barang, 'id_gudang' => $id_gudang], 't_stok_mutasi', null, 'id_stok_mutasi_det desc'); 

				$hapusMutasi = [];
				$updateMutasi = [];
				$sm_hpp = [];

				// jadikan bilangan positif
				$awaltotalPermintaan = abs($totalPermintaan);
				$totalPermintaan = abs($awaltotalPermintaan);
				
				for ($k = 0; $k < count($getBarang); $k++) 
				{
					$totalQty = abs($getBarang[$k]->qty);                
					if ($totalPermintaan <= $totalQty) 
					{
						$hapusMutasi[$k]['id_stok'] = $getBarang[$k]->id_stok;
						$hapusMutasi[$k]['id_stok_mutasi_det'] = $getBarang[$k]->id_stok_mutasi_det;
						$hapusMutasi[$k]['qty'] =-(abs($getBarang[$k]->qty)-$totalPermintaan);
						$hapusMutasi[$k]['hpp'] = $getBarang[$k]->hpp;
						
						$k = count($getBarang);
					}
					elseif ($totalPermintaan  > $totalQty) 
					{
						$hapusMutasi[$k]['id_stok']    =$getBarang[$k]->id_stok;
						$hapusMutasi[$k]['id_stok_mutasi_det'] = $getBarang[$k]->id_stok_mutasi_det;
						$hapusMutasi[$k]['qty'] = abs($getBarang[$k]->qty) - $totalQty;         

						// $sm_hpp[$k]=$getBarang[$k]->sm_hpp;
						
						$totalPermintaan = $totalPermintaan - $totalQty;
					}
				}

				$getBarangx = $this->_ci->m_global->multi_row('*', ['qty_pakai >' => 0, 'id_barang' => $id_barang, 'id_gudang' => $id_gudang], 't_stok_mutasi', null, 'id_stok_mutasi_det desc'); 

				$totalPermintaan = abs($awaltotalPermintaan);
				
				for ($k = 0; $k < count($getBarangx); $k++) 
				{
					$totalQty = abs($getBarangx[$k]->qty_pakai);  
					if ($totalPermintaan <= $totalQty) 
					{
						$qty_pakai = $getBarangx[$k]->qty_pakai- $totalPermintaan;
						$qty_sisa = $getBarangx[$k]->qty_sisa - $totalPermintaan;

						$updateMutasi[$k]['id_stok'] = $getBarangx[$k]->id_stok;
						$updateMutasi[$k]['id_stok_mutasi_det'] = $getBarangx[$k]->id_stok_mutasi_det;
						$updateMutasi[$k]['qty_pakai'] =$getBarangx[$k]->qty_pakai - $totalPermintaan;
						$updateMutasi[$k]['qty_sisa'] = $totalPermintaan + $getBarangx[$k]->qty_sisa;                                
						$updateMutasi[$k]['sm'] = $totalPermintaan; 
						$updateMutasi[$k]['s'] ='x'; 

						$k = count($getBarangx);
					}
					elseif ($totalPermintaan > $totalQty) 
					{
						$updateMutasi[$k]['id_stok'] = $getBarangx[$k]->id_stok;
						$updateMutasi[$k]['id_stok_mutasi_det'] = $getBarangx[$k]->id_stok_mutasi_det;
						$updateMutasi[$k]['qty_pakai'] = 0;
						$updateMutasi[$k]['qty_sisa'] =$getBarangx[$k]->qty_sisa+$getBarangx[$k]->sm_qty_used;
						$updateMutasi[$k]['sm'] =$totalPermintaan + $getBarangx[$k]->qty_pakai; 
						$updateMutasi[$k]['s'] ='c2'; 

						$totalPermintaan = $totalPermintaan - $totalQty;
					}
				}

				for ($sm=0; $sm <count($hapusMutasi); $sm++) 
				{
					if($hapusMutasi[$sm]['qty'] == 0) {
						### delete data t_stok_mutasi
						$this->_ci->m_global->delete(['id_stok' => $hapusMutasi[$sm]['id_stok'], 'id_stok_mutasi_det' => $hapusMutasi[$sm]['id_stok_mutasi_det'], 'deleted_at' => null], 't_stok_mutasi');
					}
					else
					{
						### update data t_stok_mutasi
						$this->_ci->m_global->update('t_stok_mutasi', ['qty' => $hapusMutasi[$sm]['qty']], ['id_stok' => $hapusMutasi[$sm]['id_stok'], 'id_stok_mutasi_det' => $hapusMutasi[$sm]['id_stok_mutasi_det'], 'deleted_at' => null]);
					}
				}

				for ($sm=0; $sm <count($updateMutasi); $sm++) 
				{
					### update data t_stok_mutasi
					$this->_ci->m_global->update(
						't_stok_mutasi', 
						['qty_pakai' => $updateMutasi[$sm]['qty_pakai'], 'qty_sisa' => $updateMutasi[$sm]['qty_sisa']], 
						['id_stok' => $updateMutasi[$sm]['id_stok'], 'id_stok_mutasi_det' => $updateMutasi[$sm]['id_stok_mutasi_det'], 'deleted_at' => null]
					);
				}

				#### ambil data dari t_stok
				$data_stok = $this->_ci->m_global->single_row('*', ['id_barang' => $id_barang, 'deleted_at' => null], 't_stok');
				### update stok
				$this->_ci->m_global->update('t_stok', ['qty' => $data_stok->qty + $awaltotalPermintaan], ['id_barang' => $id_barang, 'deleted_at' => null]);
				
				if ($this->_ci->db->trans_status() === FALSE) {
					$this->_ci->db->trans_rollback();
					return FALSE;
				} 
				else {
					$this->_ci->db->trans_commit();
					return TRUE;
				}
			}
		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
			return FALSE;
		}
   	}

	/**
	 * rollback digunakan ketika menghapus transaksi
	 * $kode_reff = kode referensi transaksi
	 */
	public function rollBack($kode_reff, $id_barang = null, $qty = null)
	{
		try {
			$this->_ci->db->trans_begin();
			### ambil data pada t_stok_mutasi
			if($id_barang) {
				$qty_minus = -$qty;
				$getMutasi = $this->_ci->m_global->multi_row('*', ['kode_reff' => $kode_reff, 'id_barang' => $id_barang, 'qty' => $qty_minus], 't_stok_mutasi', null, 'created_at asc');
			}else{
				$getMutasi = $this->_ci->m_global->multi_row('*', ['kode_reff' => $kode_reff], 't_stok_mutasi', null, 'created_at asc');
			} 
			
			if (count($getMutasi) < 1) {
				return FALSE;
			}

			foreach ($getMutasi as $value) {
				// delete stok mutation
				$this->_ci->m_global->force_delete(['id_stok' => $value->id_stok, 'id_stok_mutasi_det' => $value->id_stok_mutasi_det, 'deleted_at' => null], 't_stok_mutasi');
				#### update t_stok
				$data_stok = $this->_ci->m_global->single_row('*', ['id_stok' => $value->id_stok], 't_stok');
				
				// if ($value->keterangan == 'PENGURANGAN') {
				// 	$stok_qty = ($data_stok->qty - $value->qty);
				// }elseif ($value->keterangan == 'PENAMBAHAN'){
				// 	$stok_qty = ($data_stok->qty + $value->qty);
				// }else{
				// 	$stok_qty = $data_stok->qty;
				// }
				
				// update curent stock
				// $this->_ci->m_global->update('t_stok', ['qty' => $stok_qty], ['id_stok' => $value->id_stok]);
				
				// update t_log_laporan


				// update peneriman yg dipakai
				$this->updateMutasi($value->id_barang, $value->qty, $value->id_kategori_trans, $value->kode_reff,  $value->id_gudang);
			}

			if ($this->_ci->db->trans_status() === FALSE) {
				$this->_ci->db->trans_rollback();
				$retval = ['status' => false];		
			} 
			else {
				$this->_ci->db->trans_commit();
				$retval = ['status' => true];		
			}

			return $retval;
		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
			$retval = ['status' => false, 'pesan' => $th];		
			return $retval;
		}
	}

	public function insert_data_det($datanya)
	{
		return;
	}
	
	function new_id(){
		$queryNewId	= $this->_ci->db->query("select * from uuid_generate_v1() as newid");
		$dataNewId = $queryNewId->row();
		
		return $dataNewId->newid;
	}


	/**
	 * $id_barang = adalah id dari m_barang
	 * $totalPermintaan = adalah qty barang
	 * $totalPermintaanMin = adalah qty min barang
	 * id_kategori_trans = id kategori transaksi
	 * tanggal = optional, jika null is date now
	 * id_gudang = optional (jika terdapat multi gudang)
	 * kode_ref = kode referal transaksi
	 */
	public function mutasiMasuk(
		$id_barang, 
		$totalPermintaan, 
		$totalPermintaanMin, 
		$id_kategori_trans, 
		$tanggal = null, 
		$hpp, 
		$id_gudang, 
		$kode_reff
	) {
		try {
			$this->_ci->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			
			if($tanggal) {
				$tgl = $tanggal;
			}else{
				$tgl = $obj_date->format('Y-m-d');
			}

			$totalHpp = '';

			#### ambil data dari t_stok
			$data_stok = $this->_ci->m_global->single_row('*', ['id_barang' => $id_barang, 'id_gudang' => $id_gudang, 'deleted_at' => null], 't_stok');

			if(!$data_stok) {
				### buat stok baru
				$arr_ins_stok = [
					'id_barang' => $id_barang,
					'id_gudang' => $id_gudang,
					'qty' 		=> $totalPermintaan,
					'qty_min' 	=> $totalPermintaanMin,
					'created_at'=> $timestamp
				];
				
				$id_stok = $this->_ci->t_stok->store_id($arr_ins_stok, 't_stok');

			}else{

				$id_stok = $data_stok->id_stok;
				### update tabel d_stock
				$qty = $data_stok->qty + $totalPermintaan;            
				### update data t_stok
				$this->_ci->m_global->update('t_stok', ['qty' => $qty], ['id_barang' => $id_barang, 'id_gudang' => $id_gudang, 'deleted_at' => null]);
			}

			$max_mutasi_det = $this->_ci->m_global->max('id_stok_mutasi_det', 't_stok_mutasi', ['id_stok' => $id_stok]);
			$id_max_stok_mutasi_det = $max_mutasi_det->id_stok_mutasi_det +1;

			$newMutasi['id_stok'] = $id_stok;
			$newMutasi['id_stok_mutasi_det'] = $id_max_stok_mutasi_det; 
			$newMutasi['tanggal'] = $tgl;
			$newMutasi['hpp'] = $hpp;
			$newMutasi['id_gudang'] = $id_gudang;
			$newMutasi['kode_reff'] = $kode_reff; 

			// $newMutasi['sm_comp'] = $comp;
			
			$newMutasi['id_kategori_trans'] = $id_kategori_trans;
			$newMutasi['id_barang'] = $id_barang;
			$newMutasi['qty'] = $totalPermintaan;
			$newMutasi['qty_sisa'] = $totalPermintaan;
			$newMutasi['keterangan'] = 'PENAMBAHAN';
			$newMutasi['created_at'] = $timestamp;  

			$totalHpp = $hpp * $totalPermintaan;
				
			$this->_ci->m_global->save($newMutasi, 't_stok_mutasi');

			if ($this->_ci->db->trans_status() === FALSE) {
				$this->_ci->db->trans_rollback();
				$retval = ['status' => false];		
			} 
			else {
				$this->_ci->db->trans_commit();
				$retval = ['status' => true, 'totalHpp' => $totalHpp];		
			}

			return $retval;

		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
			$retval = ['status' => false, 'pesan' => $th];		
			return $retval;
		}
	}

	/**
	 * $id_barang = adalah id dari m_barang
	 * $totalPermintaan = adalah qty barang
	 * $totalPermintaanMin = adalah qty min barang
	 * id_kategori_trans = id kategori transaksi
	 * tanggal = optional, jika null is date now
	 * id_gudang = optional (jika terdapat multi gudang)
	 * kode_ref = kode referal transaksi
	 */
	public function perbaruiMutasiMasuk(
		$id_barang, 
		$totalPermintaan, 
		$totalPermintaanMin, 
		$id_kategori_trans, 
		$tanggal = null, 
		$hpp, 
		$id_gudang, 
		$kode_reff
	) {
		try {
			$this->_ci->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			
			if($tanggal) {
				$tgl = $tanggal;
			}else{
				$tgl = $obj_date->format('Y-m-d');
			}

			$totalHpp = '';

			#### ambil data dari t_stok
			$data_stok = $this->_ci->m_global->single_row('*', ['id_barang' => $id_barang, 'id_gudang' => $id_gudang, 'deleted_at' => null], 't_stok');

			#### ambil data t_stok_mutasi 
			$data_stok_mutasi = $this->_ci->m_global->single_row('*', [
				'id_barang' => $id_barang, 
				'id_gudang' => $id_gudang, 
				'kode_reff' => $kode_reff,
				'qty >' => 0,  
				'deleted_at' => null
			], 't_stok_mutasi');
			
			$qty = $totalPermintaan;

			### update data t_stok
			$this->_ci->m_global->update('t_stok', [
				'id_barang' 	=> $id_barang,
				'id_gudang' 	=> $id_gudang,
				'qty' 			=> $qty,
				'qty_min' 		=> $totalPermintaanMin,
				'updated_at'	=> $timestamp
			], 
			[
				'id_barang' => $id_barang, 
				'id_gudang' => $id_gudang, 
				'deleted_at' => null
			]);

			### update data t_stok_mutasi
			$this->_ci->m_global->update('t_stok_mutasi', [
				'qty' => $qty, 
				'qty_sisa' => $qty,
				'hpp' => $hpp
			], [
				'id_barang' => $id_barang, 
				'id_gudang' => $id_gudang, 
				'kode_reff' => $kode_reff,
				'id_kategori_trans' => $id_kategori_trans,
				'qty >' => 0,  
				'deleted_at' => null
			]);

			$totalHpp = $hpp * ($data_stok_mutasi->qty + $totalPermintaan);

			if ($this->_ci->db->trans_status() === FALSE) {
				$this->_ci->db->trans_rollback();
				$retval = ['status' => false];		
			} 
			else {
				$this->_ci->db->trans_commit();
				$retval = ['status' => true, 'totalHpp' => $totalHpp];		
			}

			return $retval;
		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
			$retval = ['status' => false, 'pesan' => $th];		
			return $retval;
		}
	}
	
	public function hapusMutasiMasuk(
		$id_barang, 
		$totalPermintaan, 
		$id_kategori_trans, 
		$tanggal = null, 
		$id_gudang, 
		$kode_reff
	)
	{
		try {
			$this->_ci->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			
			if($tanggal) {
				$tgl = $tanggal;
			}else{
				$tgl = $obj_date->format('Y-m-d');
			}

			$totalHpp = '';
			#### ambil data dari t_stok
			$data_stok = $this->_ci->m_global->single_row('*', ['id_barang' => $id_barang, 'id_gudang' => $id_gudang, 'deleted_at' => null], 't_stok');

			#### ambil data t_stok_mutasi 
			$data_stok_mutasi = $this->_ci->m_global->single_row('*', [
				'id_barang' => $id_barang, 
				'id_gudang' => $id_gudang, 
				'kode_reff' => $kode_reff,
				'id_kategori_trans' => $id_kategori_trans,
				'qty >' => 0,  
				'deleted_at' => null
			], 't_stok_mutasi');

			$qty = $data_stok->qty - $totalPermintaan;

			### update data t_stok
			$this->_ci->m_global->update('t_stok', [
				'id_barang' 	=> $id_barang,
				'id_gudang' 	=> $id_gudang,
				'qty' 			=> $qty,
				'updated_at'	=> $timestamp
			], 
			[
				'id_barang' => $id_barang, 
				'id_gudang' => $id_gudang, 
				'deleted_at' => null
			]);

			### delete t_stok_mutasi
			$this->_ci->m_global->delete([
				'id_barang' => $id_barang, 
				'id_gudang' => $id_gudang, 
				'kode_reff' => $kode_reff,
				'id_kategori_trans' => $id_kategori_trans,
				'qty >' => 0,  
				'deleted_at' => null
			], 't_stok_mutasi');

			if ($this->_ci->db->trans_status() === FALSE) {
				$this->_ci->db->trans_rollback();
				$retval = ['status' => false];		
			} 
			else {
				$this->_ci->db->trans_commit();
				$retval = ['status' => true];		
			}

			return $retval;
		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
			$retval = ['status' => false, 'pesan' => $th];		
			return $retval;
		}
	}


	############################## KEUANGAN ###################################
	public function insertDataLap(
		$nilaiRupiah,
		$id_kategori_trans,
		$kode_reff,
		$is_kredit = null,
		$tanggal=null
	) {
		try {
			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			
			if($tanggal) {
				$obj_tanggal = DateTime::createFromFormat('Y-m-d', $tanggal);
				$tgl = $obj_tanggal->format('Y-m-d');
				$bulan = $obj_tanggal->format('m');
				$tahun = $obj_tanggal->format('Y');
			}else{
				$tgl = $obj_date->format('Y-m-d');
				$bulan = $obj_date->format('m');
				$tahun = $obj_date->format('Y');
			}

			###### cek jika sudah ada transaksi, maka lakukan update
			$cek = $this->_ci->m_global->single_row('*', ['kode_reff' => $kode_reff, 'deleted_at' => null], 't_lap_keuangan');
			
			if($cek) {
				#### jika kode transaksi (4) penerimaan pembelian maka insert sebagai pengurangan piutang
				if($id_kategori_trans == 4) {
					$this->_ci->db->trans_begin();
					$max_mutasi_det = $this->_ci->m_global->max('id_laporan_det', 't_lap_keuangan', ['kode_reff' => $kode_reff]);
					$id_laporan_det = $max_mutasi_det->id_laporan_det +1;

					$arr_ins_laporan['id_laporan'] = $cek->id_laporan;
					$arr_ins_laporan['id_laporan_det'] = $id_laporan_det;
					$arr_ins_laporan['tgl_laporan'] = $tgl;
					$arr_ins_laporan['bulan_laporan'] = $bulan;
					$arr_ins_laporan['tahun_laporan'] = $tahun;
					$arr_ins_laporan['pengeluaran'] = 0;
					$arr_ins_laporan['piutang'] = -$nilaiRupiah;
					$arr_ins_laporan['hutang'] = 0;
					$arr_ins_laporan['kode_reff'] = $kode_reff;
					$arr_ins_laporan['id_kategori_trans'] = $id_kategori_trans;
					$arr_ins_laporan['created_at'] = $timestamp;

					$this->_ci->m_global->save($arr_ins_laporan, 't_lap_keuangan');

					if ($this->_ci->db->trans_status() === FALSE) {
						$this->_ci->db->trans_rollback();
						$retval = ['status' => false];		
					} 
					else {
						$this->_ci->db->trans_commit();
						$retval = ['status' => true];		
					}
	
					return $retval;
				}else{
					return $this->updateDataLap(
						$nilaiRupiah,
						$id_kategori_trans,
						$kode_reff,
						$is_kredit,
						$tanggal
					);
				}
			}else{
				$this->_ci->db->trans_begin();
				#### jika pembelian
				#### lakukan insert saja ke tabel laporan
				if($id_kategori_trans == 1) {
					$max_mutasi = $this->_ci->m_global->max('id_laporan', 't_lap_keuangan');
					$max_mutasi_det = $this->_ci->m_global->max('id_laporan_det', 't_lap_keuangan', ['kode_reff' => $kode_reff]);

					$id_laporan = $max_mutasi->id_laporan +1;
					$id_laporan_det = $max_mutasi_det->id_laporan_det +1;

					$arr_ins_laporan['id_laporan'] = $id_laporan;
					$arr_ins_laporan['id_laporan_det'] = $id_laporan_det;
					$arr_ins_laporan['tgl_laporan'] = $tgl;
					$arr_ins_laporan['bulan_laporan'] = $bulan;
					$arr_ins_laporan['tahun_laporan'] = $tahun;
					
					$arr_ins_laporan['piutang'] = $nilaiRupiah;
					$arr_ins_laporan['kode_reff'] = $kode_reff;
					$arr_ins_laporan['id_kategori_trans'] = $id_kategori_trans;
					$arr_ins_laporan['created_at'] = $timestamp;

					if($is_kredit) {
						$arr_ins_laporan['hutang'] = $nilaiRupiah;
						$arr_ins_laporan['pengeluaran'] = 0;
					}else{
						$arr_ins_laporan['hutang'] = 0;
						$arr_ins_laporan['pengeluaran'] = $nilaiRupiah;
					}

					// echo "<pre>";
					// print_r ($arr_ins_laporan);
					// echo "</pre>";
					// exit;
					
					$this->_ci->m_global->save($arr_ins_laporan, 't_lap_keuangan');
					
				}
				#### jika penjualan
				#### search laporan by kode_reff, ambil last id_det
				#### isi kolom pengeluaran
				else if($id_kategori_trans == 2) {
					$max_mutasi = $this->_ci->m_global->max('id_laporan', 't_lap_keuangan');
					$max_mutasi_det = $this->_ci->m_global->max('id_laporan_det', 't_lap_keuangan', ['kode_reff' => $kode_reff]);

					$id_laporan = $max_mutasi->id_laporan +1;
					$id_laporan_det = $max_mutasi_det->id_laporan_det +1;

					$arr_ins_laporan['id_laporan'] = $id_laporan;
					$arr_ins_laporan['id_laporan_det'] = $id_laporan_det;
					$arr_ins_laporan['tgl_laporan'] = $tgl;
					$arr_ins_laporan['bulan_laporan'] = $bulan;
					$arr_ins_laporan['tahun_laporan'] = $tahun;
					
					$arr_ins_laporan['kode_reff'] = $kode_reff;
					$arr_ins_laporan['id_kategori_trans'] = $id_kategori_trans;
					$arr_ins_laporan['created_at'] = $timestamp;

					if($is_kredit) {
						$arr_ins_laporan['piutang'] = $nilaiRupiah;
						$arr_ins_laporan['penerimaan'] = 0;
					}else{
						$arr_ins_laporan['piutang'] = 0;
						$arr_ins_laporan['penerimaan'] = $nilaiRupiah;
					}

					$this->_ci->m_global->save($arr_ins_laporan, 't_lap_keuangan');
				}

				/* 
				else if($id_kategori_trans == 4) {

				} */

				if ($this->_ci->db->trans_status() === FALSE) {
					$this->_ci->db->trans_rollback();
					$retval = ['status' => false];		
				} 
				else {
					$this->_ci->db->trans_commit();
					$retval = ['status' => true];		
				}

				return $retval;
			}
		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
			$retval = ['status' => false, 'pesan' => $th];		
			return $retval;
		}
	}

	public function updateDataLap(
		$nilaiRupiah,
		$id_kategori_trans,
		$kode_reff,
		$is_kredit,
		$tanggal=null
	)
	{
		try {
			$this->_ci->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			if($tanggal) {
				$obj_tanggal = DateTime::createFromFormat('Y-m-d', $tanggal);
				$tgl = $obj_tanggal->format('Y-m-d');
				$bulan = $obj_tanggal->format('m');
				$tahun = $obj_tanggal->format('Y');
			}else{
				$tgl = $obj_date->format('Y-m-d');
				$bulan = $obj_date->format('m');
				$tahun = $obj_date->format('Y');
			}

			#### jika pembelian
			#### lakukan update saja ke tabel laporan
			if($id_kategori_trans == 1) {
				$arr_update['piutang'] = $nilaiRupiah;
				$arr_update['kode_reff'] = $kode_reff;
				$arr_update['updated_at'] = $timestamp;

				if ($is_kredit) {
					$arr_update['hutang'] = $nilaiRupiah;
					$arr_update['pengeluaran'] = 0;
				} else {
					$arr_update['hutang'] = 0;
					$arr_update['pengeluaran'] = $nilaiRupiah;
				}

				$this->_ci->m_global->update('t_lap_keuangan', $arr_update, ['id_kategori_trans' => $id_kategori_trans, 'kode_reff' => $kode_reff]);
				
			}
			elseif($id_kategori_trans == 2) 
			{
				$header = $this->_ci->m_global->single_row('*', ['kode_reff' => $kode_reff, 'deleted_at' => null], 't_lap_keuangan');
				
				$arr_update['kode_reff'] = $kode_reff;
				$arr_update['updated_at'] = $timestamp;

				if ($is_kredit) {
					$arr_update['piutang'] = $nilaiRupiah + $header->piutang;
					$arr_update['penerimaan'] = 0;
				} else {
					$arr_update['piutang'] = 0;
					$arr_update['penerimaan'] = $nilaiRupiah + $header->penerimaan;
				}

				$this->_ci->m_global->update('t_lap_keuangan', $arr_update, ['id_kategori_trans' => $id_kategori_trans, 'kode_reff' => $kode_reff]);
			}

			/* #### jika penerimaan pembelian
			#### search laporan by kode_reff, ambil last id_det
			#### isi kolom piutang dengan minus harga barang
			else if($id_kategori_trans == 4) {

			} */

			if ($this->_ci->db->trans_status() === FALSE) {
				$this->_ci->db->trans_rollback();
				$retval = ['status' => false];		
			} 
			else {
				$this->_ci->db->trans_commit();
				$retval = ['status' => true];		
			}

			return $retval;

		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
			$retval = ['status' => false, 'pesan' => $th];		
			return $retval;
		}
	}

	public function deleteDataLap(
		$id_kategori_trans,
		$kode_reff,
		$tanggal=null
	)
	{
		try {
			$this->_ci->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			
			if($tanggal) {
				$obj_tanggal = DateTime::createFromFormat('Y-m-d', $tanggal);
				$tgl = $obj_tanggal->format('Y-m-d');
				$bulan = $obj_tanggal->format('m');
				$tahun = $obj_tanggal->format('Y');
			}else{
				$tgl = $obj_date->format('Y-m-d');
				$bulan = $obj_date->format('m');
				$tahun = $obj_date->format('Y');
			}

			$this->_ci->m_global->force_delete(['id_kategori_trans' => $id_kategori_trans, 'kode_reff' => $kode_reff], 't_lap_keuangan');

			if ($this->_ci->db->trans_status() === FALSE) {
				$this->_ci->db->trans_rollback();
				$retval = ['status' => false];		
			} 
			else {
				$this->_ci->db->trans_commit();
				$retval = ['status' => true];		
			}

			return $retval;

		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
			$retval = ['status' => false, 'pesan' => $th];		
			return $retval;
		}
	}
}