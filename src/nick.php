<?php

// Follow own nick change lol

hook::func("nick", function($u){
	
	global $me,$cf;
	
	if ($nick == $me){
		
		$me = $u['parc'];
		$cf['nick'] = $u['parc'];
	}
});