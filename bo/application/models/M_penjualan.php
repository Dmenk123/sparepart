<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_penjualan extends CI_Model
{
	var $table = 't_penjualan';
	
	var $column_search = [
		'pj.no_faktur',
		'pj.created_at',
		'pl.nama_pembeli',
		'pl.alamat',
		'mu.nama',
		'metode',
	];
	
	var $column_order = [
		null, 
		'pj.no_faktur',
		'pj.created_at',
		'pl.nama_pembeli',
		'pl.alamat',
		'mu.nama',
		'metode',
		null
	];

	var $order = ['pj.id_penjualan' => 'desc']; 

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	private function _get_datatables_query($term='', $param)
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

		$this->db->select('
			pj.id_penjualan,
			pj.no_faktur,
			pj.tgl_jatuh_tempo,
			pj.created_at,
			(CASE WHEN pj.is_kredit = 1 THEN \'Kredit\' ELSE \'Cash\' END) as metode,
			mu.nama as nama_sales,
			pl.nama_pembeli,
			pl.alamat,
			pl.no_telp,
			pl.email,
			pl.nama_toko
		');
		$this->db->from('t_penjualan pj');
		$this->db->join('m_user mu', 'mu.id=pj.id_sales');
		$this->db->join('m_pelanggan pl', 'pl.id_pelanggan=pj.id_pelanggan');
		$this->db->where('pj.deleted_at is null');

		if ($is_filter_tgl) {
			$this->db->where('pj.created_at >=', $tgl_awal);
			$this->db->where('pj.created_at <=', $tgl_akhir);
		}
		
		$i = 0;
		// loop column 
		foreach ($this->column_search as $item) 
		{
			// if datatable send POST for search
			if($_POST['search']['value']) 
			{
				// first loop
				if($i===0) 
				{
					// open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					if($item == 'metode') {
						/**
						 * param both untuk wildcard pada awal dan akhir kata
						 * param false untuk disable escaping (karena pake subquery)
						 */
						$this->db->or_like('(CASE WHEN pj.is_kredit = 1 THEN \'Kredit\' ELSE \'Cash\' END)', $_POST['search']['value'],'both',false);
					}else{
						$this->db->or_like($item, $_POST['search']['value']);
					}
				}
				//last loop
				if(count($this->column_search) - 1 == $i) 
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatable_user($param)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatables_query($term, $param);
		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($param)
	{
		$this->_get_datatables_query(null, $param);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($param)
	{
		$this->_get_datatables_query(null, $param);
		$query = $this->db->get();
		return $this->db->count_all_results();
	}
	
	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_penjualan',$id);
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

	public function updatePenjualandet($where, $data)
	{
		return $this->db->update('t_penjualan_det', $data, $where);
	}

	public function softdelete_by_id($id)
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$where = ['id_agen'=> $id];
		$data = ['deleted_at' => $timestamp];
		return $this->db->update($this->table, $data, $where);
	}
	
	public function get_max_penjualan()
	{
		$obj_date = new DateTime();
		$tgl = $obj_date->format('Y-m-d');
		$q = $this->db->query("SELECT count(*) as jml FROM t_penjualan WHERE DATE_FORMAT(created_at ,'%Y-%m-%d') = '$tgl'");
		$kd = "";
		if($q->num_rows()>0){
			$kd = $q->row();
			return (int)$kd->jml + 1;
		}else{
			return '1';
		} 
	}

	public function get_id_pegawai_by_name($nama)
	{
		$this->db->select('id');
		$this->db->from('m_pegawai');
		$this->db->where('LCASE(nama)', $nama);
		$q = $this->db->get();
		if ($q) {
			return $q->row();
		}else{
			return false;
		}
	}

	public function get_id_role_by_name($nama)
	{
		$this->db->select('id');
		$this->db->from('m_role');
		$this->db->where('LCASE(nama)', $nama);
		$q = $this->db->get();
		if ($q) {
			return $q->row();
		}else{
			return false;
		}
	}

	public function trun_master_user()
	{
		$this->db->query("truncate table m_user");
	}

	public function getPenjualan($no_faktur)
	{
		$this->db->select('
			pj.id_penjualan,
			pj.no_faktur,
			pj.tgl_jatuh_tempo,
			pj.created_at,
			mu.username,
			pl.nama_pembeli,
			pl.alamat,
			pl.no_telp,
			pl.email,
			pl.nama_toko
		');
		$this->db->from('t_penjualan pj');
		$this->db->join('m_user mu', 'mu.id=pj.id_sales');
		$this->db->join('m_pelanggan pl', 'pl.id_pelanggan=pj.id_pelanggan');
		$this->db->where('pj.no_faktur', $no_faktur);
		$this->db->where('pj.deleted_at', null);
		
		$q = $this->db->get();
		return $q;
	}

	function getTotalOrder($id)
	{
		$query = "
				SELECT SUM(sub_total) as total
				FROM t_penjualan_det
				WHERE id_penjualan = $id AND deleted_at is null";
		return $this->db->query($query);
	}

	function getPenjualanDet($id)
	{
		$this->db->select('
			pd.*,
			mb.nama,
			mb.sku
		');
		$this->db->from('t_penjualan_det pd');
		$this->db->join('m_barang mb', 'mb.id_barang=pd.id_barang');
		$this->db->where('pd.id_penjualan', $id);
		$this->db->where('pd.deleted_at', null);
		
		$this->db->order_by('pd.id_penjualan_det', 'ASC');
		$q = $this->db->get();
		return $q;
	}
}