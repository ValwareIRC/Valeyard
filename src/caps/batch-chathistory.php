<?php

hook::func("batch", function($u){
	global $gw,$batch,$reversebatch;
	
	echo $u['parc'];
	$parv = explode(" ",$u['parc']);
	$batchtag = $parv[1];
	$batchtarget = $parv[2] ?? NULL;
	$batchkey = $parv[0];
	
	if ($batchkey[0] == "+"){
		
		$batchkey = ltrim($batchkey,"-");
		
		$batch[$batchtarget] = $batchkey;
		
		$reversebatch[$batch[$batchtarget]] = $batchtarget;
	}
	elseif ($batchkey[0] == "-"){
		$batchkey =  ltrim($batchkey,"-");
		$batch[$batchkey] = NULL;
	}
	return;
});


function IsChatHistoryBatch($string){
	
	global $batch;
	$exists = $batch[$string] ?? false;
	return $exists;
}

	
hook::func("privmsg", function($u){
	global $gw,$batch;
	
	$mtags = explode(";",$u['mtags']);
	$dest = $u['dest'];
	for ($i = 0; isset($mtags[$i]); $i++){
		if ($mtags[$i][0] == "@"){ $mtags[$i] = ltrim($mtags[$i],"@"); }
		
		$mtag_tok = explode("=",$mtags[$i]);
		
		if ($mtag_tok[0] == "batch"){ $batch[$dest] = $mtag_tok[1]; }
	}
		
	
	
});


