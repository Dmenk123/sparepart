<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_log_harga extends CI_Model
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

	private function _get_datatables_query($term='')
	{

		$this->db->select('id_barang, MAX(tanggal) as maxtanggal');
		$this->db->from('t_log_harga_jual');
		$this->db->group_by('id_barang');
		$subquery = $this->db->get_compiled_select();


		$this->db->select('t.harga_jual, b.nama, r.maxtanggal, t.id_barang');
		$this->db->from('('.$subquery.') as r');
		$this->db->join('t_log_harga_jual t', 't.id_barang = r.id_barang AND t.tanggal = r.maxtanggal');
		$this->db->join('m_barang b', 't.id_barang = b.id_barang', 'left');
		// $result = $this->db->get()->result_array();

		// $query = $this->db->query("
		// 	SELECT t.harga_jual, b.nama, r.maxtanggal
		// 	FROM (
		// 		SELECT id_barang, MAX(tanggal) as maxtanggal
		// 		FROM t_log_harga_jual
		// 		GROUP BY id_barang
		// 	) r
		// 	INNER JOIN t_log_harga_jual t
		// 	left join m_barang b on t.id_barang = b.id_barang
		// 	ON t.id_barang = r.id_barang AND t.tanggal = r.maxtanggal
		// ");

		// $this->db->get($query);
		
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

	function get_datatable()
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
		$this->db->where('id_agen',$id);
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
		$where = ['id_agen'=> $id];
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

	public function get_datatable_detail($id)
	{
		$this->db->select('log.*,m.nama as nama_user, b.nama as nama_barang');
		$this->db->from('t_log_harga_jual log');
		$this->db->join('m_user m', 'm.id=log.created_by', 'left');
		$this->db->join('m_barang b', 'b.id_barang=log.id_barang');
		$this->db->where('log.id_barang', $id);
		$this->db->order_by('id_log_harga_jual', 'desc');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}
}