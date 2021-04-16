<?php


function get_string_between($string,$start, $end){
			$string = ' ' . $string;
			 $ini = strpos($string, $start);
			 if ($ini == 0) return '';
			 $ini += strlen($start);
			 $len = strpos($string, $end, $ini) - 			$ini;
  			return substr($string, $ini, $len);
}

function ircstrip($string){

	$_ircstrip = str_replace(array(
                chr(10),
                chr(13)
            ), '', $string);
	return $_ircstrip;
}

	
function IsServer($data){
	if (strpos($data,"@")) { return 'false'; }
	else return 'true';
}
	




?>