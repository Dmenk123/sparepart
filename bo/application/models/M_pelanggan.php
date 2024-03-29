<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_pelanggan extends CI_Model
{
	var $table = 'm_pelanggan';
	var $column_search = [
        'm_pelanggan.nama_pembeli',
        'm_pelanggan.alamat',
        'm_pelanggan.id_provinsi', 
        'm_pelanggan.id_kota',
        'm_pelanggan.kecamatan',
        'm_pelanggan.no_telp',
        'm_pelanggan.email',
        'm_pelanggan.nama_toko'
    ];
	
	var $column_order = [
		null, 
		'm_pelanggan.nama_pembeli',
		'm_pelanggan.alamat',
		'm_pelanggan.id_provinsi',
        'm_pelanggan.id_kota',
        'm_pelanggan.kecamatan',
        'm_pelanggan.no_telp',
        'm_pelanggan.email',
        'm_pelanggan.nama_toko',
		null
	];

	var $order = ['m_pelanggan.id_pelanggan' => 'desc']; 

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	private function _get_datatables_query($term='')
	{
		$this->db->select('
			m_pelanggan.*, 
			t_provinsi.nama_provinsi, 
			t_kota.nama_kota
		');
		$this->db->join('t_provinsi', 't_provinsi.id_provinsi=m_pelanggan.id_provinsi', 'left');
		$this->db->join('t_kota', 't_kota.id_kota=m_pelanggan.id_kota', 'left');
		$this->db->from('m_pelanggan');	
		$this->db->where('m_pelanggan.deleted_at is null');
		
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
					$this->db->or_like($item, $_POST['search']['value']);
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

	function get_datatable_user()
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatables_query($term);
		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_detail_user($id_user)
	{
		$this->db->select('*');
		$this->db->from('m_user');
		$this->db->where('id', $id_user);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}
	
	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_pelanggan',$id);
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
		$where = ['id_pelanggan'=> $id];
		$data = ['deleted_at' => $timestamp];
		return $this->db->update($this->table, $data, $where);
	}

	//dibutuhkan di contoller login untuk ambil data user
	function login($data){
		return $this->db->select('*')
			->where('username',$data['data_user'])
			->where('password',$data['data_password'])
			->where('status', 1 )
			->get($this->table)->row();
	}

	//dibutuhkan di contoller login untuk set last login
	function set_lastlogin($id){
		$this->db->where('id',$id);
		$this->db->update(
			$this->table, 
			['last_login'=>date('Y-m-d H:i:s')]
		);			
	}

	function get_kode_user(){
            $q = $this->db->query("select MAX(RIGHT(kode_user,5)) as kode_max from m_user");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $k){
                    $tmp = ((int)$k->kode_max)+1;
                    $kd = sprintf("%05s", $tmp);
                }
            }else{
                $kd = "00001";
            }
            return "USR-".$kd;
	}
	
	public function get_max_id_user()
	{
		$q = $this->db->query("SELECT MAX(id) as kode_max from m_user");
		$kd = "";
		if($q->num_rows()>0){
			$kd = $q->row();
			return (int)$kd->kode_max + 1;
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

	public function get_datatable_monitoring($id_pelanggan, $id_barang=null, $start=null, $end=null)
	{
		$this->db->select('det.*, b.nama as nama_barang, pl.nama_pembeli as nama_pelanggan, p.created_at as tanggal_order');
		$this->db->from('t_penjualan_det det');
		$this->db->join('t_penjualan p', 'p.id_penjualan=det.id_penjualan', 'left');
		$this->db->join('m_barang b', 'b.id_barang=det.id_barang', 'left');
		$this->db->join('m_pelanggan pl', 'pl.id_pelanggan=p.id_pelanggan');
		$this->db->where('p.id_pelanggan', $id_pelanggan);
		if ($id_barang != null && $id_barang != '') {
			$this->db->where('det.id_barang', $id_barang);
		}
		if ($start) {
			$this->db->where('DATE(p.created_at) >=', $start);
		}

		if ($end) {
			$this->db->where('DATE(p.created_at) <=', $end);
		}
		$this->db->where('det.id_barang !=', 0);
		$this->db->order_by('det.id_penjualan_det', 'desc');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function monitoring_cart($id_pelanggan, $id_barang, $start, $end)
	{
		$query = "
				select
					b.nama,
					sum(det.qty) as total,
					count(*) as jumlah
				FROM
					t_penjualan_det det
				LEFT JOIN
					t_penjualan p ON p.id_penjualan=det.id_penjualan
				LEFT JOIN
					m_barang b ON b.id_barang=det.id_barang
				WHERE
					p.id_pelanggan = $id_pelanggan
					AND (det.id_barang IS NOT NULL AND det.id_barang <> 0)
			";
		if ($id_barang) {
			$query .= " and det.id_barang = $id_barang";
		}

		if ($start) {
			$query .= " and DATE(p.created_at) >= '$start'";
		}

		if ($end) {
			$query .= " and DATE(p.created_at) <= '$end'";
		}

		$query .= " group by
							det.id_barang
					";
        
        return $this->db->query($query);

	}

	public function get_barang($id_pelanggan)
	{
		$this->db->distinct('b.id_barang');
		$this->db->select('b.id_barang, b.nama as nama_barang');
		$this->db->from('t_penjualan_det det');
		$this->db->join('t_penjualan p', 'p.id_penjualan=det.id_penjualan', 'left');
		$this->db->join('m_barang b', 'b.id_barang=det.id_barang', 'left');
		$this->db->where('p.id_pelanggan', $id_pelanggan);
		$this->db->where('det.id_barang !=', 0);
		$this->db->order_by('det.id_penjualan_det', 'desc');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}
}