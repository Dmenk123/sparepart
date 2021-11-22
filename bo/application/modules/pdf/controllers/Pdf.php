<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('logged_in') === false) {
			return redirect('login');
		}

		$this->load->model('m_global');
		$this->load->model('m_laporan');
		$this->load->model('set_role/m_set_role', 'm_role');
	}

	public function index()
	{
		
	}

	public function cetak_laporan()
	{

		$data['profil'] = $this->m_global->getSelectedData('m_profil', null)->row();
		$data_pdf['header'] = $this->load->view('header', $data, true);

		$jenis = $this->input->get('jenis');
		switch ($jenis) {
			case "laporan_penjualan":
				$model = $this->input->get('model');
				$bulan = $this->input->get('bulan');
				$tahun = $this->input->get('tahun');
				$tahun2 = $this->input->get('tahun2');
					#yantoss
				if ($this->input->get('start')) {
					$exp_date2 = str_replace('/', '-', $this->input->get('start'));
					$start = date('Y-m-d', strtotime($exp_date2));
				}
		
				if ($this->input->get('end')) {
					$exp_date = str_replace('/', '-', $this->input->get('end'));
					$end = date('Y-m-d', strtotime($exp_date));
				}
		
				$title = 'Laporan Penjualan';
				if ($model == 2) {
					$data['table'] = $this->m_laporan->get_laporan_penjualan($model, $tahun2);
					$title .= '<br>Tahun '.$tahun2;
				}elseif ($model == 1) {
					$data['table'] = $this->m_laporan->get_laporan_penjualan($model, null, $tahun, $bulan );

					$nama_bulan = [
						'1' => 'Januari',
						'2' => 'Februari',
						'3' => 'Maret',
						'4' => 'April',
						'5' => 'Mei',
						'6' => 'Juni',
						'7' => 'Juli',
						'8' => 'Agustus',
						'9' => 'September',
						'10' => 'Oktober',
						'11' => 'Nopember',
						'12' => 'Desember',
					];
					$title .= '<br>Bulan '.$nama_bulan[$bulan].' Tahun '.$tahun;
				}elseif ($model == 3) {
					$data['table'] = $this->m_laporan->get_laporan_penjualan($model, null, null, null, $start, $end );
					$title .= '<br>per tanggal '.tanggal_indo($start).' s/d tanggal '.tanggal_indo($end);
				}
				$data['title'] = $title;
				$data_pdf['content'] = $this->load->view('laporan_penjualan/pdf', $data, true);
				break;
		}
		

		$html = $this->load->view('merger', $data_pdf, true);

	    $filename = $this->input->get('jenis').time();
	    $this->lib_dompdf->generate($html, $filename, true, 'A4', 'landscape');
	}

	
}
