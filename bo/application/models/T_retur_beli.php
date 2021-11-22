<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class T_retur_beli extends CI_Model
{
	var $table = 't_retur_beli';
	
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}


	function get_datatable_transaksi($param)
	{
		$obj_date = new DateTime();
		$is_filter_kategori = false;
		$is_filter_tgl = false;
		$is_filter_bln = false;
		$is_filter_thn = false;

		if ($param['bulan'] != 'all') {
			$is_filter_bln =  true;
		}

		if ($param['tahun'] != 'all') {
			$is_filter_thn =  true;
		}

		if ($param['kategori'] != 'all') {
			$is_filter_kategori = true;
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
				

		$this->db->select('tr.*, user.nama as nama_user, ma.nama_perusahaan');
		$this->db->from('t_retur_beli tr');
		$this->db->join('m_user user', 'tr.id_user = user.id', 'left');
		$this->db->join('t_penerimaan tp', 'tr.id_penerimaan = tp.id_penerimaan');
		$this->db->join('t_pembelian tpb', 'tp.id_pembelian = tpb.id_pembelian');
		$this->db->join('m_agen ma', 'tpb.id_agen = ma.id_agen');

		if($is_filter_tgl) {
			$this->db->where('tr.tanggal >=', $tgl_awal);
			$this->db->where('tr.tanggal <=', $tgl_akhir);
		}

		if($is_filter_kategori) {
			$this->db->where('tr.jenis_retur', $param['kategori']);
		}

		$this->db->where('tr.deleted_at', null);
		
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
			rb.*,
			tp.kode_penerimaan,
			ma.nama_perusahaan,
			mu.username,
			mu.nama as nama_user,
		');
		$this->db->from('t_retur_beli rb');
		$this->db->join('t_penerimaan tp', 'rb.id_penerimaan = tp.id_penerimaan');
		$this->db->join('t_pembelian tpb', 'tp.id_pembelian = tpb.id_pembelian');
		$this->db->join('m_agen ma', 'tpb.id_agen = ma.id_agen');
		$this->db->join('m_user mu', 'mu.id = rb.id_user');
		$this->db->where('rb.kode_retur', $kode);
		$this->db->where('rb.deleted_at', null);
		$q = $this->db->get();
		return $q;
	}

	function getDataDetail($id)
	{
		$this->db->select('
			rd.*,
			mb.nama as nama_barang,
			mb.id_barang,
			mg.nama_gudang,
			mg.id_gudang
		');
		$this->db->from('t_retur_beli_det rd');
		$this->db->join('t_stok s', 'rd.id_stok = s.id_stok');
		$this->db->join('m_barang mb', 's.id_barang = mb.id_barang');
		$this->db->join('m_gudang mg', 's.id_gudang = mg.id_gudang');
		$this->db->where('rd.id_retur_beli', $id);
		$this->db->where('rd.deleted_at', null);
		$this->db->order_by('rd.created_at', 'ASC');
		$q = $this->db->get();
		return $q;
	}

	function getDetailPenerimaan($id)
	{
		$this->db->select('
			pd.*,
			p.id_gudang,
			mg.nama_gudang,
			mb.nama as nama_barang,
			ts.id_stok
		');
		$this->db->from('t_penerimaan_det pd');
		$this->db->join('t_penerimaan p', 'pd.id_penerimaan=p.id_penerimaan');
		$this->db->join('m_gudang mg', 'p.id_gudang = mg.id_gudang');
		$this->db->join('m_barang mb', 'mb.id_barang=pd.id_barang');
		$this->db->join('t_stok ts', 'mb.id_barang = ts.id_barang and p.id_gudang = ts.id_gudang');
		$this->db->where('pd.id_penerimaan', $id);
		$this->db->where('pd.deleted_at', null);
		
		$this->db->order_by('pd.id_penerimaan_det', 'ASC');
		$q = $this->db->get();
		return $q;
	}

	function getTotalTransaksiDet($id)
	{
		$query = "
			SELECT SUM(harga_total) as total
			FROM t_retur_beli_det
			WHERE id_retur_beli = $id and deleted_at is NULL
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