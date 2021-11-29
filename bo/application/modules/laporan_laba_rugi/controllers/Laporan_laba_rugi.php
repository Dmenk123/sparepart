<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_laba_rugi extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		// $this->load->model('m_laporan');
		$this->load->model('m_user');
		$this->load->model('m_global');
		$this->load->model('set_role/m_set_role', 'm_role');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_user->get_detail_user($id_user);
		$profil = $this->m_global->single_row('*', ['deleted_at' => null] ,'m_profil');
		$data_role = $this->m_role->get_data_all(['aktif' => '1'], 'm_role');

		$passing = [
			'model' => $this->input->get('model'),
			'tahun2' => $this->input->get('tahun2'),
			'tahun' => $this->input->get('tahun'),
			'bulan' => $this->input->get('bulan'),
			'start' => $this->input->get('start'),
			'end' => $this->input->get('end'),
		]; 

		
		if($this->input->get('model') != '') {
			$data = $this->load_tabel($passing);
		}else{
			$data['data_table'] = null;
			$data['txt_judul_laporan'] = null;
		}
		
		
		// echo "<pre>";
		// print_r ($data);
		// echo "</pre>";
		// exit;
			
		/**
		 * data passing ke halaman view content
		 */
		$retval = array(
			'title' => 'Laporan Laba Rugi',
			'data_user' => $data_user,
			'data_role'	=> $data_role,
			'data_profil' => $profil,
			'data' => $data['data_table'],
			'txt_judul' => $data['txt_judul_laporan'],
		);

		/**
		 * content data untuk template
		 * param (css : link css pada direktori assets/css_module)
		 * param (modal : modal komponen pada modules/nama_modul/views/nama_modal)
		 * param (js : link js pada direktori assets/js_module)
		 */
		$content = [
			'css' 	=> null,
			'modal' => '',
			'js'	=> 'laporan_laba_rugi.js',
			'view'	=> 'view_laporan'
		];

		$this->template_view->load_view($content, $retval);
	}

	public function load_tabel($data)
	{
		if ($data['start']) {
			$start = date('Y-d-m', strtotime($data['start']));
		}

		if ($data['end']) {
			$exp_date = str_replace('/', '-', $data['end']);
			$end = date('Y-m-d', strtotime($exp_date));
		}

		if ($data['model'] == 2) {
			$data_table = $this->get_laporan_penjualan($data['model'], $data['tahun2']);
			$txt_judul_laporan = 'Periode '.$data['tahun2'];
		}elseif ($data['model'] == 1) {
			$data_table = $this->get_laporan_penjualan($data['model'], null, $data['tahun'], $data['bulan']);
			$txt_judul_laporan = 'Periode '.bulan_indo($data['bulan']).' '.$data['tahun'];
		}elseif ($data['model'] == 3) {
			$data_table = $this->get_laporan_penjualan($data['model'], null, null, null, $start, $end);
			$txt_judul_laporan = 'Periode '.tanggal_indo($start).' s/d '.tanggal_indo($end);
		}

		
		// echo "<pre>";
		// print_r ($data_table);
		// echo "</pre>";
		// exit;

		return [
			'data_table' => $data_table,
			'txt_judul_laporan' => $txt_judul_laporan
		];

		// var_dump($data_table); die();
		// $data = [];
		// if ($data_table) {
		// 	foreach ($data_table as $key => $value) {
			
		// 		$data[$key][] = $key+1;
		// 		$data[$key][] = $value->nama_pelanggan;
		// 		$data[$key][] = $value->nama_barang;
		// 		$data[$key][] = $value->qty.' Pcs';
		// 		$data[$key][] = 'Rp '.number_format($value->sub_total); 
		// 		$data[$key][] = date('d-m-Y H:i:s', strtotime($value->tanggal_order));	
				
		// 	}
		// }
        
        // echo json_encode([
        //     'data' => $data
        // ]);
	} 

	public function get_laporan_penjualan($model, $tahun2 = null, $tahun = null, $bulan = null, $start = null, $end=null)
    {

		$this->db->select('
			a.id_laporan,
			a.tgl_laporan,
			a.bulan_laporan,
			a.tahun_laporan,
			sum(a.penerimaan) as penerimaan,
			sum(a.pengeluaran) as pengeluaran,
			sum(a.hutang) as hutang,
			sum(a.piutang) as piutang,
			b.nama_kategori_trans
		');
		$this->db->from('t_lap_keuangan a');
		$this->db->join('m_kategori_transaksi b', 'a.id_kategori_trans = b.id_kategori_trans');


        if ($model == 2) {
            if ($tahun2) {
                $this->db->where('tahun_laporan', $tahun2);
            }
        }elseif ($model == 1) {
            if ($tahun) {
                $this->db->where('tahun_laporan', $tahun);
            }

            if ($bulan) {
				$bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                $this->db->where('bulan_laporan', $bulan);
            }
        }elseif ($model == 3) {
            if ($start) {
                $this->db->where('tgl_laporan >=', $start);
            }
    
            if ($end) {
                $this->db->where('tgl_laporan <=', $end);
            }
        }
       
		$this->db->where(['a.deleted_at' => null, 'b.deleted_at' => null]);
		$this->db->group_by('a.id_kategori_trans');
		$this->db->order_by('a.tgl_laporan', 'asc');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	
    }

	public function datatable()
	{
		$model = $this->input->post('model');
		$tahun2 = $this->input->post('tahun2');

		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$start = $this->input->post('start');
		$end = $this->input->post('end');

		if ($start) {
			$start = date('Y-d-m', strtotime($start));
		}

		if ($end) {
			$exp_date = str_replace('/', '-', $end);
			$end = date('Y-m-d', strtotime($exp_date));
		}

		if ($model == 2) {
			$data_table = $this->m_laporan->get_laporan_penjualan($model, $tahun2);
		}elseif ($model == 1) {
			$data_table = $this->m_laporan->get_laporan_penjualan($model, null, $tahun, $bulan );
		}elseif ($model == 3) {
			$data_table = $this->m_laporan->get_laporan_penjualan($model, null, null, null, $start, $end );
		}

		// echo $this->db->last_query(); die();
		
		// var_dump($data_table); die();
		$data = [];
		if ($data_table) {
			foreach ($data_table as $key => $value) {
			
				$data[$key][] = $key+1;
				$data[$key][] = $value->nama_pelanggan;
				$data[$key][] = $value->nama_barang;
				$data[$key][] = $value->qty.' Pcs';
				$data[$key][] = 'Rp '.number_format($value->sub_total); 
				$data[$key][] = date('d-m-Y H:i:s', strtotime($value->tanggal_order));	
				
			}
		}
        
		
		// $this->output->enable_profiler(TRUE);

        echo json_encode([
            'data' => $data
        ]);
	} 


	
}
