<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class T_penerimaan extends CI_Model
{
	var $table = 't_penerimaan';
	var $column_search = ['pj.no_faktur'];
	
	var $column_order = [
		null, 
		'pn.tanggal',
		'pn.kode_penerimaan',
		'm_agen.nama_perusahaan',
		'pb.kode_pembelian',
		'pn.total_harga',
		null
	];

	var $order = ['pb.tanggal' => 'desc']; 

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	private function _get_datatables_query($term='')
	{
		$this->db->select("
			pn.*,
			pb.kode_pembelian,
			m_agen.nama_perusahaan
		");
		$this->db->from('t_penerimaan pn');
		$this->db->join('t_pembelian pb', 'pn.id_pembelian = pb.id_pembelian');
		$this->db->join('m_agen', 'pb.id_agen = m_agen.id_agen');
		$this->db->where('pn.deleted_at is null');
		
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
					if($item == 'penjamin') {
						/**
						 * param both untuk wildcard pada awal dan akhir kata
						 * param false untuk disable escaping (karena pake subquery)
						 */
						$this->db->or_like('(CASE WHEN pl.is_terima_all = 1 THEN \'Lunas\' ELSE \'-\' END)', $_POST['search']['value'],'both',false);
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

	function get_datatable_penerimaan()
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

	public function updatePembelianDet($where, $data)
	{
		return $this->db->update('t_pembelian_det', $data, $where);
	}

	public function softdelete_by_id($id)
	{
		$obj_date = new DateTime();
		$timestamp = $obj_date->format('Y-m-d H:i:s');
		$where = ['id_agen'=> $id];
		$data = ['deleted_at' => $timestamp];
		return $this->db->update($this->table, $data, $where);
	}
	
	public function get_max_penerimaan()
	{
		$obj_date = new DateTime();
		$tgl = $obj_date->format('Y-m-d');
		$q = $this->db->query("SELECT count(*) as jml FROM t_penerimaan WHERE DATE_FORMAT(created_at ,'%Y-%m-%d') = '$tgl' and deleted_at is null");
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

	public function getPembelian($no_faktur)
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
		$q = $this->db->get();
		return $q;
	}

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
			mb.nama,
			mb.sku
		');
		$this->db->from('t_penerimaan_det pd');
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
}