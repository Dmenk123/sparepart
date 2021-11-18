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

				if ($this->input->get('start')) {
					$start = date('Y-d-m', strtotime($this->input->get('start')));
				}
		
				if ($this->input->get('end')) {
					$exp_date = str_replace('/', '-', $this->input->get('end'));
					$end = date('Y-m-d', strtotime($exp_date));
				}
		
				if ($model == 2) {
					$data['table'] = $this->m_laporan->get_laporan_penjualan($model, $tahun2);
				}elseif ($model == 1) {
					$data['table'] = $this->m_laporan->get_laporan_penjualan($model, null, $tahun, $bulan );
				}elseif ($model == 3) {
					$data['table'] = $this->m_laporan->get_laporan_penjualan($model, null, null, null, $start, $end );
				}
				$data['title'] = 'Laporan Penjualan';
				$data_pdf['content'] = $this->load->view('laporan_penjualan/pdf', $data, true);
				break;
		}
		

		$html = $this->load->view('merger', $data_pdf, true);

	    $filename = $this->input->get('jenis').time();
	    $this->lib_dompdf->generate($html, $filename, true, 'A4', 'landscape');
	}

	
}
