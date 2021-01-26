<?php defined('BASEPATH') or exit('No direct script access allowed');
use Picqer\Barcode\BarcodeGeneratorHTML;
class Qrcode
{
	public function generate_qrcode($value = '')
	{

		$generator = new BarcodeGeneratorHTML();
		echo $generator->getBarcode($value, $generator::TYPE_CODE_128);

		##### cara manggil e nng controller ngene masnur
		// $this->load->library('Qrcode');
		// $this->qrcode->generate_qrcode('iki nomor sku barang e');
		##### cara manggil e nng controller ngene masnur
	}
}
