<?php
class Lib_mutasi extends CI_Controller {
    protected $_ci;
    
    function __construct(){
		$this->_ci = &get_instance();
		$this->_ci->load->model('m_global');  //<-------Load the Model first
    }
	
	/**
	 * $id_jenis_trans = adalah id dari m jenis transaksi
	 * $datanya = data array dari inputan
	 */
	function simpan_mutasi($id_kategori_trans, $datanya=null, $tanggal=null) {
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		
		if($tanggal) {
			$tgl = $tanggal;
		}else{
			$tgl = $obj_date->format('Y-m-d');
		}
		 
		$select = "m.*, md.id_mutasi_det, md.id_barang, md.qty, md.harga, md.subtotal";
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

		return $retval;
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