<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('timeAgo'))
{
	function timeAgo($timestamp){
        $time = time() - $timestamp;
 
        if ($time < 60)
        return ( $time > 1 ) ? $time . ' detik yang lalu' : 'satu detik';
        elseif ($time < 3600) {
        $tmp = floor($time / 60);
        return ($tmp > 1) ? $tmp . ' menit yang lalu' : ' satu menit yang lalu';
        }
        elseif ($time < 86400) {
        $tmp = floor($time / 3600);
        return ($tmp > 1) ? $tmp . ' jam yang lalu' : ' satu jam yang lalu';
        }
        elseif ($time < 2592000) {
        $tmp = floor($time / 86400);
        return ($tmp > 1) ? $tmp . ' hari lalu' : ' satu hari lalu';
        }
        elseif ($time < 946080000) {
        $tmp = floor($time / 2592000);
        return ($tmp > 1) ? $tmp . ' bulan lalu' : ' satu bulan lalu';
        }
        else {
        $tmp = floor($time / 946080000);
        return ($tmp > 1) ? $tmp . ' years' : ' a year';
        }
    }
}

if ( ! function_exists('contul'))
{
	function contul($string){
        if($string == '') {
            return null;
        }else{
            return $string;
        }
    }
}

if ( ! function_exists('no_faktur'))
{
	function no_faktur($date, $counter){
        $y = DateTime::createFromFormat('Y-m-d', $date)->format('Y');
        $m = DateTime::createFromFormat('Y-m-d', $date)->format('m');
        $d = DateTime::createFromFormat('Y-m-d', $date)->format('d');
        
        switch ($m) {
            case '01':
               $txt_bln = 'A';
               break;
            
            case '02':
                $txt_bln = 'B';
                break;
            
            case '03':
                $txt_bln = 'C';
                break;
                
            case '04':
                $txt_bln = 'D';
                break;

            case '05':
                $txt_bln = 'E';
                break;
                
            case '06':
                $txt_bln = 'F';
                break;
            
            case '07':
                $txt_bln = 'G';
                break;
                
            case '08':
                $txt_bln = 'H';
                break;

            case '09':
                $txt_bln = 'I';
                break;

            case '10':
                $txt_bln = 'J';
                break;

            case '11':
                $txt_bln = 'K';
                break;
           
           default:
                $txt_bln = 'L';
               break;
        }

        $txt_urut = sprintf("%03s", $counter);
        return $txt_bln.$d.$txt_urut.$y;
    }
}

if ( ! function_exists('generate_kode_transaksi'))
{
	function generate_kode_transaksi($date, $counter, $singkatan){
        $y = DateTime::createFromFormat('Y-m-d', $date)->format('Y');
        $m = DateTime::createFromFormat('Y-m-d', $date)->format('m');
        $d = DateTime::createFromFormat('Y-m-d', $date)->format('d');
        
        switch ($m) {
            case '01':
               $txt_bln = 'A';
               break;
            
            case '02':
                $txt_bln = 'B';
                break;
            
            case '03':
                $txt_bln = 'C';
                break;
                
            case '04':
                $txt_bln = 'D';
                break;

            case '05':
                $txt_bln = 'E';
                break;
                
            case '06':
                $txt_bln = 'F';
                break;
            
            case '07':
                $txt_bln = 'G';
                break;
                
            case '08':
                $txt_bln = 'H';
                break;

            case '09':
                $txt_bln = 'I';
                break;

            case '10':
                $txt_bln = 'J';
                break;

            case '11':
                $txt_bln = 'K';
                break;
           
           default:
                $txt_bln = 'L';
               break;
        }

        $txt_urut = sprintf("%03s", $counter);
        return $singkatan.'-'.$txt_bln.$d.$txt_urut.$y;
    }
}
?>