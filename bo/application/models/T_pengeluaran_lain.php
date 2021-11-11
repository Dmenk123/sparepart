<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class T_pengeluaran_lain extends CI_Model
{
	var $table = 't_pengeluaran_lain';
	
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}


	function get_datatable_pengeluaran($param)
	{
		$this->db->select('tp.*, kat.nama_kategori_trans, user.nama as nama_user');
		$this->db->from('t_pengeluaran_lain tp');
		$this->db->join('m_kategori_transaksi kat', 'tp.id_kategori_trans = kat.id_kategori_trans', 'left');
		$this->db->join('m_user user', 'tp.id_user = user.id', 'left');
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

	public function get_max_transaksi()
	{
		$obj_date = new DateTime();
		$tgl = $obj_date->format('Y-m-d');
		$q = $this->db->query("SELECT count(*) as jml FROM t_penerimaan WHERE DATE_FORMAT(created_at ,'%Y-%m-%d') = '$tgl' and deleted_at is null");
		$kd = "";
		if ($q->num_rows() > 0) {
			$kd = $q->row();
			return (int)$kd->jml + 1;
		} else {
			return '1';
		}
	}
	

	public function getPengeluaran($kode)
	{
		$this->db->select('
			pl.*,
			mu.username,
			mu.nama as nama_user,
		');
		$this->db->from('t_pengeluaran_lain pl');
		// $this->db->join('t_pengeluaran_lain_det pld', 'pl.id = pld.id_pengeluaran_lain');
		$this->db->join('m_user mu', 'mu.id = pl.id_user');
		$this->db->join('m_kategori_transaksi mk', 'pl.id_kategori_trans = mk.id_kategori_trans');
		$this->db->where('pl.kode', $kode);
		$q = $this->db->get();
		return $q;
	}

	############################################################################################

	/* public function updatePembelianDet($where, $data)
	{
		return $this->db->update('t_pembelian_det', $data, $where);
	} */

	

	function getTotalPembelian($id)
	{
		$query = "
			SELECT SUM(harga_total_fix) as total
			FROM t_pembelian_det
			WHERE id_pembelian = $id
		";
		return $this->db->query($query);
	}
	

	function getTotalDiskon($id)
	{
		$query = "
			SELECT SUM(disc) as disc_total
			FROM t_pembelian_det
			WHERE id_pembelian = $id
		";
		return $this->db->query($query);
	}


	function getPenerimaanDet($id)
	{
		$this->db->select('
			pd.*,
			p.id_pembelian,
			mb.nama,
			mb.sku
		');
		$this->db->from('t_penerimaan_det pd');
		$this->db->join('t_penerimaan p', 'pd.id_penerimaan=p.id_penerimaan');
		$this->db->join('m_barang mb', 'mb.id_barang=pd.id_barang');
		$this->db->where('pd.id_penerimaan', $id);
		$this->db->order_by('pd.id_penerimaan_det', 'ASC');
		$q = $this->db->get();
		return $q;
	}

	function getPembelianDet($id)
	{
		$this->db->select('
			pd.*,
			mb.nama,
			mb.sku
		');
		$this->db->from('t_pembelian_det pd');
		$this->db->join('m_barang mb', 'mb.id_barang=pd.id_barang');
		$this->db->where('pd.id_pembelian', $id);
		$this->db->order_by('pd.id_pembelian_det', 'ASC');
		$q = $this->db->get();
		return $q;
	}

	function sum_barang_masuk_pertransaksi($id_barang, $id_pembelian)
	{
		$query = "
			SELECT COALESCE(SUM(pd.qty),0) as total_qty_masuk
			FROM t_penerimaan p
			LEFT JOIN t_penerimaan_det pd on pd.id_penerimaan = p.id_penerimaan
			WHERE p.id_pembelian = '$id_pembelian' AND pd.id_barang = '$id_barang'
		";
		$q = $this->db->query($query)->row();
		return $q->total_qty_masuk;
	}
}