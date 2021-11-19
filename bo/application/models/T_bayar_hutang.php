<?php
defined('BASEPATH') or exit('No direct script access allowed');
class T_bayar_hutang extends CI_Model
{
	var $table = 't_bayar_hutang';

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
			$kategori_fix = ($param['kategori'] == '2') ? null : 1;
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


		$this->db->select('tb.*, pb.kode_pembelian, pb.is_lunas, user.nama as nama_user');
		$this->db->from('t_bayar_hutang tb');
		$this->db->join('t_pembelian pb', 'tb.id_pembelian = pb.id_pembelian', 'left');
		$this->db->join('m_user user', 'tb.id_user = user.id', 'left');
		// $this->db->join('m_kategori_transaksi kat', 'tb.id_kategori_trans = kat.id_kategori_trans', 'left');

		if ($is_filter_tgl) {
			$this->db->where('tb.tanggal >=', $tgl_awal);
			$this->db->where('tb.tanggal <=', $tgl_akhir);
		}

		if ($is_filter_kategori) {
			$this->db->where('pb.is_lunas', $kategori_fix);
		}

		$this->db->where('tb.deleted_at', null);

		$query = $this->db->get();

		return $query->result();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_by_condition($where, $is_single = false)
	{
		$this->db->from($this->table);
		$this->db->where($where);
		$query = $this->db->get();
		if ($is_single) {
			return $query->row();
		} else {
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
		$q = $this->db->query("SELECT count(*) as jml FROM t_bayar_hutang WHERE DATE_FORMAT(created_at ,'%Y-%m-%d') = '$tgl' and deleted_at is null");
		$kd = "";
		if ($q->num_rows() > 0) {
			$kd = $q->row();
			return (int)$kd->jml + 1;
		} else {
			return '1';
		}
	}

	function sum_pembayaran_by_id($id)
	{
		$query = "
			SELECT COALESCE(SUM(nilai_bayar),0) as total_harga
			FROM t_bayar_hutang
			WHERE id = '$id' AND deleted_at is null
		";
		$q = $this->db->query($query)->row();
		return $q->total_harga;
	}


	public function getDataLapKeuangan()
	{
		$q = $this->db->query("SELECT
				kode_reff AS kode,
				IFNULL((select sum(hutang) from t_lap_keuangan b where b.kode_reff = a.kode_reff and b.deleted_at is null), a.hutang) as hutang_fix
			FROM
				t_lap_keuangan a
			WHERE
				IFNULL((select sum(hutang) from t_lap_keuangan b where b.kode_reff = a.kode_reff and b.deleted_at is null), a.hutang) > 0 AND 
				a.id_kategori_trans = 1	
		");

		return $q->result();
	}


	// public function getDataLapKeuangan()
	// {
	// 	$q = $this->db->query("SELECT
	// 			kode_reff AS kode,
	// 			IFNULL(((select sum(hutang) from t_lap_keuangan b where b.kode_reff2 = a.kode_reff) + a.hutang), a.hutang) as hutang_fix
	// 		FROM
	// 			t_lap_keuangan a
	// 		WHERE
	// 			IFNULL(((select sum(hutang) from t_lap_keuangan b where b.kode_reff2 = a.kode_reff) + a.hutang), a.hutang) > 0 AND 
	// 			a.id_kategori_trans = 1	
	// 	");

	// 	return $q->result();
	// }

	public function getPenerimaan($kode)
	{
		$this->db->select('
			pl.*,
			mu.username,
			mu.nama as nama_user,
		');
		$this->db->from('t_penerimaan_lain pl');
		// $this->db->join('t_pengeluaran_lain_det pld', 'pl.id = pld.id_pengeluaran_lain');
		$this->db->join('m_user mu', 'mu.id = pl.id_user');
		$this->db->join('m_kategori_transaksi mk', 'pl.id_kategori_trans = mk.id_kategori_trans');
		$this->db->where('pl.kode', $kode);
		$this->db->where('pl.deleted_at', null);
		$q = $this->db->get();
		return $q;
	}

	function getPenerimaanDet($id)
	{
		$this->db->select('
			pd.*,
			mb.nama,
			mb.sku
		');
		$this->db->from('t_penerimaan_lain_det pd');
		$this->db->join('t_penerimaan_lain p', 'pd.id_penerimaan_lain=p.id');
		$this->db->join('m_barang mb', 'mb.id_barang=pd.id_barang');
		$this->db->where('pd.id_penerimaan_lain', $id);
		$this->db->where('pd.deleted_at', null);
		$this->db->order_by('pd.created_at', 'ASC');
		$q = $this->db->get();
		return $q;
	}

	

	############################################################################################
}
