<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class T_retur_masuk extends CI_Model
{
	var $table = 't_retur_masuk';
	
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}


	function get_datatable_transaksi($param)
	{
		$obj_date = new DateTime();
		$is_filter_tgl = false;
		$is_filter_bln = false;
		$is_filter_thn = false;

		if ($param['bulan'] != 'all') {
			$is_filter_bln =  true;
		}

		if ($param['tahun'] != 'all') {
			$is_filter_thn =  true;
		}

		if ($is_filter_bln && $is_filter_thn) {
			$bulan = str_pad($param['bulan'], 2, '0', STR_PAD_LEFT);
			$tgl_awal = $param['tahun'] . '-' . $bulan . '-01';
			$tgl_akhir = DateTime::createFromFormat('Y-m-d', $tgl_awal)->modify('last day of this month')->format('Y-m-d');
			$is_filter_tgl = true;
		}
		#### jika hanya tahun saja
		elseif (!$is_filter_bln && $is_filter_thn) {
			$tgl_awal = $param['tahun'] . '-01-01';
			$tgl_akhir = $param['tahun'] . '-12-31';
			$is_filter_tgl = true;
		}
		#### jika hanya bulan saja (menggunakan tahun saat ini)
		elseif ($is_filter_bln && !$is_filter_thn) {
			$bulan = str_pad($param['bulan'], 2, '0', STR_PAD_LEFT);
			$tgl_awal = $obj_date->format('Y') . '-' . $bulan . '-01';
			$tgl_akhir = $obj_date->format('Y') . '-' . $bulan . '-31';
			$is_filter_tgl = true;
		}
				
		$this->db->select('rm.*, rb.kode_retur, user.nama as nama_user, ma.nama_perusahaan');
		$this->db->from('t_retur_masuk rm');
		$this->db->join('t_retur_beli rb', 'rm.id_retur_beli = rb.id');
		$this->db->join('m_agen ma', 'rm.id_agen = ma.id_agen');
		$this->db->join('m_user user', 'rm.id_user = user.id', 'left');
		
		if($is_filter_tgl) {
			$this->db->where('rm.tanggal >=', $tgl_awal);
			$this->db->where('rm.tanggal <=', $tgl_akhir);
		}

		$this->db->where('rm.deleted_at', null);
		
		$query = $this->db->get();

		return $query->result();
	}
	
	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_by_condition($where, $is_single = false)
	{
		$this->db->from($this->table);
		$this->db->where($where);
		$query = $this->db->get();
		if($is_single) {
			return $query->row();
		}else{
			return $query->result();
		}
	}

	public function get_max_transaksi()
	{
		$obj_date = new DateTime();
		$tgl = $obj_date->format('Y-m-d');
		$q = $this->db->query("SELECT count(*) as jml FROM $this->table WHERE tanggal = '$tgl' and deleted_at is null");
		$kd = "";
		if ($q->num_rows() > 0) {
			$kd = $q->row();
			return (int)$kd->jml + 1;
		} else {
			return '1';
		}
	}

	public function getDataHeader($kode)
	{
		$this->db->select('
			rm.*,
			rb.kode_retur,
			rb.tanggal as tanggal_retur,
			ma.nama_perusahaan,
			mu.username,
			mu.nama as nama_user,
		');
		$this->db->from('t_retur_masuk rm');
		$this->db->join('t_retur_beli rb', 'rm.id_retur_beli = rb.id');
		$this->db->join('m_agen ma', 'rm.id_agen = ma.id_agen');
		$this->db->join('m_user mu', 'mu.id = rm.id_user');
		$this->db->where('rm.kode', $kode);
		$this->db->where('rm.deleted_at', null);
		$q = $this->db->get();
		return $q;
	}

	function getDataDetail($id)
	{
		$this->db->select('
			rmd.*,
			mb.nama as nama_barang,
			mb.id_barang,
			mg.nama_gudang,
			mg.id_gudang
		');
		$this->db->from('t_retur_masuk_det rmd');
		$this->db->join('t_retur_masuk rm', 'rmd.id_retur_masuk = rm.id', 'left');
		$this->db->join('t_stok s', 'rmd.id_stok = s.id_stok');
		$this->db->join('m_barang mb', 's.id_barang = mb.id_barang');
		$this->db->join('m_gudang mg', 's.id_gudang = mg.id_gudang');
		$this->db->where('rmd.id_retur_masuk', $id);
		$this->db->where('rmd.deleted_at', null);
		$this->db->order_by('rmd.created_at', 'ASC');
		$q = $this->db->get();
		return $q;
	}

	function getDetailRetur($id)
	{
		$this->db->select('
			rbd.*,
			rb.kode_retur,
			rb.total_nilai_retur,
			mg.nama_gudang,
			mb.nama as nama_barang,
			ts.id_stok,
			ts.id_gudang,
			ts.id_barang
		');
		$this->db->from('t_retur_beli_det rbd');
		$this->db->join('t_retur_beli rb', 'rbd.id_retur_beli=rb.id');
		$this->db->join('t_stok ts', 'rbd.id_stok = ts.id_stok');
		$this->db->join('m_gudang mg', 'ts.id_gudang = mg.id_gudang');
		$this->db->join('m_barang mb', 'ts.id_barang=mb.id_barang');
	
		$this->db->where('rbd.id_retur_beli', $id);
		$this->db->where('rbd.deleted_at', null);
		
		$this->db->order_by('rbd.id', 'ASC');
		$q = $this->db->get();
		return $q;
	}

	function getTotalTransaksiDet($id)
	{
		$query = "
			SELECT SUM(harga_total) as total
			FROM t_retur_masuk_det
			WHERE id_retur_masuk = $id and deleted_at is NULL
		";
		return $this->db->query($query);
	}
	

	public function save($data)
	{
		return $this->db->insert($this->table, $data);	
	}

	public function update($where, $data)
	{
		return $this->db->update($this->table, $data, $where);
	}

	public function softdelete_by_id($id)
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$where = ['id_agen' => $id];
		$data = ['deleted_at' => $timestamp];
		return $this->db->update($this->table, $data, $where);
	}

	

	

	

	############################################################################################
}