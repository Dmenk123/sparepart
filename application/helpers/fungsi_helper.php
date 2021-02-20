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

if ( ! function_exists('seourl'))
{   
    function seourl( $string, $separator = '-' )
	{
		$accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
		$special_cases = array( '&' => 'and', "'" => '');
		$string = mb_strtolower( trim( $string ), 'UTF-8' );
		$string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
		$string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
		$string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
		$string = preg_replace("/[$separator]+/u", "$separator", $string);
		return $string;
	}
}


?>