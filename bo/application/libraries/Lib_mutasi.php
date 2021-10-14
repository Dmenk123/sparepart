<?php
class Lib_mutasi extends CI_Controller {
    protected $_ci;
    
    function __construct(){
		$this->_ci = &get_instance();
		$this->_ci->load->model('m_global');  //<-------Load the Model first
    }

	public function tes()
	{
		return 'asa';
	}
	
	/**
	 * $id_barang = adalah id dari m_barang
	 * $totalPermintaan = adalah qty barang
	 * id_kategori_trans = id kategori transaksi
	 * tanggal = optional, jika null is date now
	 * id_gudang = optional (jika terdapat multi gudang)
	 */
	function simpan_mutasi($id_barang, $totalPermintaan, $id_kategori_trans, $tanggal = null, $id_gudang = null) {
		try {
			$this->_ci->db->trans_begin();

			$obj_date = new DateTime();
			$timestamp = $obj_date->format('Y-m-d H:i:s');
			
			if($tanggal) {
				$tgl = $tanggal;
			}else{
				$tgl = $obj_date->format('Y-m-d');
			}

			#### ambil data dari t_stok
			$data_stok = $this->_ci->m_global->single_row('*', ['id_barang' => $id_barang, 'deleted_at' => null], 't_stok');
			
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
			$getBarang = $this->_ci->m_global->multi_row('*', ['qty_sisa > ' => 0, 'id_barang' => $id_barang], 't_stok_mutasi'); 
			// echo $this->_ci->db->last_query();exit;
			
			$newMutasi = [];
         	$updateMutasi = [];
			
			for ($k = 0; $k < count($getBarang); $k++) 
         	{
				### ambil last id pada t_stok_mutasi
				// $id_stok_mutasi_det = d_stock_mutation::where('sm_item',$item)->where('sm_comp',$comp)->where('sm_position',$position)->max('sm_detailid')+$k+1;
				$max_mutasi_det = $this->_ci->m_global->max('id_stok_mutasi_det', 't_stok_mutasi', ['id_stok_mutasi' => $getBarang[$k]->id_stok, 'id_barang' => $id_barang]);
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

					// $newMutasi[$k]['sm_comp'] = $comp;
					// $newMutasi[$k]['sm_position'] = $position;
					// $newMutasi[$k]['sm_hpp'] = $hpp;
					// $newMutasi[$k]['sm_reff'] = $sm_reff; 

					$newMutasi[$k]['id_kategori_trans'] = $id_kategori_trans;
					$newMutasi[$k]['id_barang'] = $id_barang;
					$newMutasi[$k]['qty'] = -$totalPermintaan;
					$newMutasi[$k]['keterangan'] = 'PENGURANGAN';
					$newMutasi[$k]['created_at'] = $timestamp;              
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

					// $newMutasi[$k]['sm_comp'] = $comp;
					// $newMutasi[$k]['sm_position'] = $position;
					// $newMutasi[$k]['sm_hpp'] = $getBarang[$k]->sm_hpp;
					// $newMutasi[$k]['sm_reff'] = $sm_reff; 
					
					$newMutasi[$k]['qty'] = -$totalQty;
					$newMutasi[$k]['id_kategori_trans'] = $id_kategori_trans;
					$newMutasi[$k]['id_barang'] = $id_barang;
					$newMutasi[$k]['keterangan'] = 'PENGURANGAN';
					$newMutasi[$k]['created_at'] = $timestamp;
					$totalPermintaan = $totalPermintaan - $totalQty;
				}
         	}

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
	function updateMutasi($id_barang, $totalPermintaan, $id_kategori_trans, $tanggal = null, $id_gudang = null)
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
				
				$this->simpan_mutasi($id_barang, $totalPermintaan, $id_kategori_trans, $tanggal);

			}else{
				
				### ambil data barang pada t_stok_mutasi  
				$getBarang = $this->_ci->m_global->multi_row('*', ['qty <' => 0, 'id_barang' => $id_barang], 't_stok_mutasi', null, 'id_stok_mutasi_det desc'); 

				$hapusMutasi = [];
				$updateMutasi = [];
				$sm_hpp = [];

				$awaltotalPermintaan = abs($totalPermintaan);
				$totalPermintaan = abs($awaltotalPermintaan);
				
				for ($k = 0; $k < count($getBarang); $k++) 
				{
					$totalQty = abs($getBarang[$k]->qty);                
					if ($totalPermintaan <= $totalQty) 
					{
						$hapusMutasi[$k]['id_stok']    =$getBarang[$k]->id_stok;
						$hapusMutasi[$k]['id_stok_mutasi_det'] = $getBarang[$k]->id_stok_mutasi_det;
						$hapusMutasi[$k]['qty'] =-(abs($getBarang[$k]->qty)-$totalPermintaan);

						// $sm_hpp[$k] = $getBarang[$k]->sm_hpp;
						
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

				
				$getBarangx = $this->_ci->m_global->multi_row('*', ['qty_pakai >' => 0, 'id_barang' => $id_barang], 't_stok_mutasi', null, 'id_stok_mutasi_det desc'); 

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

	public function insert_data_det($datanya)
	{
		return;
	}
	
	function new_id(){
		$queryNewId	= $this->_ci->db->query("select * from uuid_generate_v1() as newid");
		$dataNewId = $queryNewId->row();
		
		return $dataNewId->newid;
	}


	public static function mutasiMasuk(
		$date,
		$comp,
		$position,
		$item,
		$totalPermintaan,
		$sm_detail,
		$mutcat,
		$sm_reff,
		$hpp,
		$sell
	) {
		return DB::transaction(function () use (
			$date,
			$comp,
			$position,
			$item,
			$totalPermintaan,
			$sm_detail,
			$mutcat,
			$sm_reff,
			$hpp
		) {

			$totalHpp = '';

			$updateStock = d_stock::where('s_item', $item)->where('s_comp', $comp)->where('s_position', $position);
			if (!$updateStock->first()) {

				$idStock = d_stock::max('s_id') + 1;
				d_stock::create([
					's_id' => $idStock,
					's_comp' => $comp,
					's_position' => $position,
					's_item' => $item,
					's_qty' => $totalPermintaan,
				]);
			} else {
				$qty = $updateStock->first()->s_qty + $totalPermintaan;
				$updateStock->update([
					's_qty' => $qty
				]);
			}



			$sm_detailid = d_stock_mutation::where('sm_stock', $updateStock->first()->s_id)->max('sm_detailid') + 1;
			d_stock_mutation::create([
				'sm_stock' => $updateStock->first()->s_id,
				'sm_detailid' => $sm_detailid,
				'sm_date' => $date,
				'sm_comp' => $comp,
				'sm_position' => $position,
				'sm_mutcat' => $mutcat,
				'sm_item' => $item,
				'sm_qty' => $totalPermintaan,
				'sm_qty_used' => 0,
				'sm_qty_sisa' => $totalPermintaan,
				'sm_qty_expired' => 0,
				'sm_detail' => $sm_detail,
				'sm_hpp' => $hpp,
				'sm_reff' => $sm_reff,
			]);
			$totalHpp = $hpp * $totalPermintaan;
			$data = ['true' => true, 'totalHpp' => $totalHpp];
			return $data;
		});
	}

	public static function perbaruiMutasiMasuk($comp, $position, $item, $totalPermintaan, $sm_detail, $mutcat, $sm_reff, $hpp)
	{
		return DB::transaction(function () use ($comp, $position, $item, $totalPermintaan, $sm_detail, $mutcat, $sm_reff, $hpp) {
			$totalHpp = '';
			$updateMutasi = d_stock_mutation::where('sm_reff', $sm_reff)->where('sm_item', $item)->where('sm_qty', '>', 0);
			$updateStock = d_stock::where('s_item', $item)->where('s_comp', $comp)->where('s_position', $position);

			$qty = $updateStock->first()->s_qty + $totalPermintaan;

			$updateStock->update([
				's_qty' => $qty
			]);
			$updateMutasi->update([
				'sm_stock' => $updateStock->first()->s_id,
				'sm_qty' => $updateMutasi->first()->sm_qty + $totalPermintaan,
				'sm_qty_sisa' => $updateMutasi->first()->sm_qty + $totalPermintaan,
				'sm_hpp' => $hpp,
			]);

			$totalHpp = $hpp * ($updateMutasi->first()->sm_qty + $totalPermintaan);
			$data = ['true' => true, 'totalHpp' => $totalHpp];
			return $data;
		});
	}
	
	public static function hapusMutasiMasuk($comp, $position, $item, $permintaan, $sm_reff)
	{
		return DB::transaction(function () use ($comp, $position, $item, $permintaan, $sm_reff) {
			$totalHpp = 0;
			$updateStock = d_stock::where('s_item', $item)->where('s_comp', $comp)->where('s_position', $position);
			$qty = $updateStock->first()->s_qty - $permintaan;
			$updateStock->update([
				's_qty' => $qty
			]);
			$d_stock_mutation = d_stock_mutation::where('sm_reff', $sm_reff)->where('sm_item', $item)->where('sm_qty', $permintaan);
			$d_stock_mutation->delete();
			$data = ['true' => true, 'totalHpp' => $totalHpp];
			return $data;
		});
	}
}