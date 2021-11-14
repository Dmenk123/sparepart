<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_laporan extends CI_Model
{
	var $table = 't_log_harga_jual';
	var $column_search = ['b.nama','t.harga_jual'];
	
	var $column_order = [
		null, 
		't_log_harga_jual.id_barang',
		't_log_harga_jual.harga_jual',
		null
	];

	var $order = ['m_agen.id_agen' => 'desc']; 

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	public function get_laporan_penjualan($model, $tahun2 = null, $tahun = null, $bulan = null, $start = null, $end=null)
    {

		$this->db->select('det.*, b.nama as nama_barang, pl.nama_pembeli as nama_pelanggan, p.created_at as tanggal_order');
		$this->db->from('t_penjualan_det det');
		$this->db->join('t_penjualan p', 'p.id_penjualan=det.id_penjualan', 'left');
		$this->db->join('m_barang b', 'b.id_barang=det.id_barang', 'left');
		$this->db->join('m_pelanggan pl', 'pl.id_pelanggan=p.id_pelanggan');


        if ($model == 2) {
            if ($tahun2) {
                $this->db->where('YEAR(p.created_at)', $tahun2);
            }
        }elseif ($model == 1) {
            if ($tahun) {
                $this->db->where('YEAR(p.created_at)', $tahun);
            }

            if ($bulan) {
                $this->db->where('MONTH(p.created_at)', $bulan);
            }
        }elseif ($model == 3) {
            if ($start) {
                $this->db->where('DATE(p.created_at) >=', $start);
            }
    
            if ($end) {
                $this->db->where('DATE(p.created_at) <=', $end);
            }
        }
       
		$this->db->where('det.id_barang !=', 0);
		$this->db->order_by('det.id_penjualan_det', 'desc');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	
    }
}