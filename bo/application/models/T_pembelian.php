<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class T_pembelian extends CI_Model
{
	var $table = 't_pembelian';
	var $column_search = ['pb.kode_pembelian', 'pb.tanggal', 'm_agen.nama_perusahaan', 'pb.total_pembelian'];
	
	var $column_order = [
		null, 
		'pb.tanggal',
		'pb.kode_pembelian',
		'm_agen.nama_perusahaan',
		'pb.total_pembelian',
		'metode_bayar',
		'status_lunas',
		'status_terima',
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
			pb.*,
			m_agen.nama_perusahaan,
			CASE WHEN pb.is_kredit = 1 THEN 'Kredit' ELSE 'Cash' END as metode_bayar,
			CASE 
				WHEN pb.is_terima_all = 1 THEN 'Complete' 
				ELSE '-' 
			END as status_terima,
			CASE 
				WHEN pb.is_lunas = 1 THEN 'Lunas' 
				ELSE 'Belum Lunas' 
			END as status_lunas,
			count(pnd.id_barang) as count_terima
		");
		$this->db->from('t_pembelian pb');
		$this->db->join('m_agen', 'pb.id_agen=m_agen.id_agen');
		//  join untuk mengetahui apakah ada transaksi di penerimaan
		$this->db->join('t_penerimaan pn', 'pb.id_pembelian=pn.id_pembelian', 'left');
		$this->db->join('t_penerimaan_det pnd', 'pn.id_penerimaan=pnd.id_penerimaan and pnd.deleted_at is null', 'left');
		$this->db->where('pb.deleted_at is null');
		$this->db->group_by('pb.id_pembelian');
		
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
					if($item == 'status_terima') {
						/**
						 * param both untuk wildcard pada awal dan akhir kata
						 * param false untuk disable escaping (karena pake subquery)
						 */
						$this->db->or_like('(CASE WHEN pb.is_terima_all = 1 THEN \'Complete\' ELSE \'-\' END)', $_POST['search']['value'],'both',false);
					} elseif ($item == 'metode_bayar') {
						$this->db->or_like('(CASE WHEN pb.is_kredit = 1 THEN \'Kredit\' ELSE \'Cash\' END)', $_POST['search']['value'], 'both', false);
					} elseif ($item == 'status_lunas') {
						$this->db->or_like('(CASE WHEN pb.is_lunas = 1 THEN \'Lunas\' ELSE \'Belum Lunas\' END)', $_POST['search']['value'], 'both', false);
					} else{
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

	function get_datatable_pembelian()
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
	
	public function get_max_pembelian()
	{
		$obj_date = new DateTime();
		$tgl = $obj_date->format('Y-m-d');
		$q = $this->db->query("SELECT count(*) as jml FROM t_pembelian WHERE DATE_FORMAT(created_at ,'%Y-%m-%d') = '$tgl' and deleted_at is null");
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