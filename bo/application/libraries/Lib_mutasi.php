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
				$this->db->trans_rollback();
				return false;
			}

			### ambil data barang pada t_stok_mutasi  
			$getBarang = $this->_ci->m_global->multi_row('*', ['qty_sisa >' => 0, 'id_barang' => $id_barang], 't_stok_mutasi'); 

			$newMutasi = [];
         	$updateMutasi = [];

			for ($k = 0; $k < count($getBarang); $k++) 
         	{
				### ambil last id pada t_stok_mutasi
				// $id_stok_mutasi_det = d_stock_mutation::where('sm_item',$item)->where('sm_comp',$comp)->where('sm_position',$position)->max('sm_detailid')+$k+1;
				$max_mutasi_det = $this->_ci->m_global->max('id_stok_mutasi_det', 't_stok_mutasi', ['id_stok_mutasi' => $getBarang[$k]->id_stok_mutasi, 'id_barang' => $id_barang]);
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

			// insert batch
			DB::table('d_stock_mutation')->insert($newMutasi);
			return true;

		} catch (\Throwable $th) {
			$this->_ci->db->trans_rollback();
		}

		/* $select = "m.*, md.id_mutasi_det, md.id_barang, md.qty, md.harga, md.subtotal";
		$join = [ 
			['table' => 't_mutasi_det as md', 'on' => 'm.id_mutasi = md.id_mutasi'],
		];
		$data = $this->_ci->m_global->single_row($select, ['m.id_kategori_trans' => $id_kategori_trans, 'm.tanggal' => $tgl, 'deleted_at' => null], 't_mutasi as m', $join);
		
		$data_kategori_trans = $this->_ci->m_global->single_row('*', ['id_kategori_trans' => $id_kategori_trans, 'deleted_at' => null], 'm_kategori_transaksi');

		if(!$data){	
			###insert
			$data['tanggal'] = $tgl;
			$data['id_kategori_trans'] = $id_kategori_trans;
			$data['id_user'] = $this->_ci->session->userdata('id_user');
			
			## jika transaksi penerimaan/pengeluaran
			if($data_kategori_trans->is_penerimaan == 1) {
				$flag_transaksi = 1;
				$data['total_penerimaan'] = $datanya['harga_total'];
			}else{
				$flag_transaksi = 2;
				$data['total_pengeluaran'] = $datanya['harga_total'];	
			}
			
			$data['flag_transaksi'] = $flag_transaksi;
			$data['created_at'] = $timestamp;
						
			$insert = $this->_ci->m_global->save($data, 't_mutasi');

			if($insert) {
				// $this->insert_data_det($datanya);
				$retval = true;
			}else{
				$retval = false;
			}
		}else{
			###update
			if($data_kategori_trans->is_penerimaan == 1) {
				$data_upd = [
					'total_penerimaan' => $datanya['harga_total'],
					'id_user' => $this->_ci->session->userdata('id_user')
				];
			}else{
				$data_upd = [
					'total_pengeluaran' => $datanya['harga_total'],
					'id_user' => $this->_ci->session->userdata('id_user')
				];
			}

			$update = $this->_ci->m_global->update(['id' => $data->id], $data_upd, 't_mutasi');
			
			if($update) {
				$retval = true;
			}else{
				$retval = false;
			}
		}

		return $retval; */
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
}