<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class T_stok extends CI_Model
{
	var $table = 't_stok';

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	function get_datatable_stok()
	{
		$this->db->select('
			t_stok.*,
			m_barang.nama as nama_barang,
			m_barang.gambar,
			m_satuan.nama_satuan,
			m_kategori.nama_kategori,
			m_gudang.nama_gudang,
		');

		$this->db->from('t_stok');
		$this->db->join('m_barang', 't_stok.id_barang=m_barang.id_barang');
		$this->db->join('m_kategori', 'm_barang.id_kategori=m_kategori.id_kategori');
		$this->db->join('m_satuan', 'm_barang.id_satuan = m_satuan.id_satuan');	
		$this->db->join('m_gudang', 't_stok.id_gudang=m_gudang.id_gudang');
		$this->db->where('t_stok.deleted_at is null');
		$this->db->order_by('m_barang.nama', 'asc');
		$this->db->order_by('m_gudang.nama_gudang', 'asc');
		$query = $this->db->get();
		
		return $query->result();
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
		$this->db->where('id_barang',$id);
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

	function store_id($data,$table){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

	public function update($where, $data)
	{
		return $this->db->update($this->table, $data, $where);
	}

	public function softdelete_by_id($id)
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$where = ['id_barang' => $id];
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
}