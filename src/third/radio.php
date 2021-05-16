<?php

/*

** provides actively online radio information
** shoutcast v1
** OmerAti - Omer ATABER omerati6363@gmail.com


*/

// Radio Name and Link
$station_name = "Radyo Valware"; 
$station_link = "http://www.domainname.com"; 

//connection timeout values
$refresh = "20";  
$timeout = "1";  

//radio connection information
//This radio is active now
$ip[1] = "46.105.210.237";  
$port[1] = "8902"; 

$servers = count($ip); 

$i = "1"; 
while($i<=$servers) 
    { 
    $fp = @fsockopen($ip[$i],$port[$i],$errno,$errstr,$timeout); 
    if (!$fp)  
        {  
        $listeners[$i] = "0"; 
        $msg[$i] = "<span class=\"red\">ERROR [Connection refused / Server down]</span>"; 
        $error[$i] = "1"; 
        }  
    else 
        {  
        fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: Mozilla\r\n\r\n"); 
        while (!feof($fp))  
            { 
            $info = fgets($fp); 
            } 
        $info = str_replace('<HTML><meta http-equiv="Pragma" content="no-cache"></head><body>', "", $info); 
        $info = str_replace('</body></html>', "", $info); 
        $stats = explode(',', $info); 
        if (empty($stats[1]) ) 
            { 
            $listeners[$i] = "0"; 
            $msg[$i] = "<span class=\"red\">Error [Unable to Connect Radio]</span>"; 
            $error[$i] = "1"; 
            } 
        else 
            { 
            if ($stats[1] == "1") 
                { 
                $song[$i] = $stats[6]; 
                $listeners[$i] = $stats[0]; 
                $max[$i] =  $stats[3]; 
                $bitrate[$i] = $stats[5]; 
                $peak[$i] = $stats[2]; 
                if ($stats[0] == $max[$i])  
                    {  
                     
                    } 
                 
                } 
         
        { 
                $listeners[$i] = "0"; 
                $msg[$i] = "    <span class=\"red\">Error [Unable to Connect Radio]</span>"; 
                $error[$i] = "1"; 
                } 
            } 
        } 
    $i++; 

} 

hook::func("privmsg", function($u){
	
	global $gw,$sql,$me;

    // convenience identifiers
    $nick = $u['nick'];
    $parv = explode(" ",$u['parc']);
    $cmd = $parv[0];
    $target = $u['dest'];
	
	
    if ($target == $me) { return; }

    if ($cmd !== "!radio") { return; }
	
	$gw->msg($target,"0,1 Radio:11 $station_name 11,11|");
    $gw->msg($target,"0,1 Playing Song:11 $song[1] 11,11|");
	$gw->msg($target,"0,1 Publication WEB 11 $station_link 0Click on the link. 11,11|");
	$gw->msg($target,"0,1 Publication Winamp 9http://$ip[1]:$port[1]/listen.pls 0Click on the link. 9,9|");
    return;
});

// the help hook which is from a /third module
hook::func("help", function($u) {
	global $gw,$me;
	$nick = $u['nick'];
	$parv = explode(" ",$u['parc']);
	
	if (!isset($parv[1])) {
		$gw->notice($nick,"!radio         gives general information about the radio");
	}
	elseif ($parv[1] == "!radio") {
		$gw->notice($nick,"Syntax: !radio");
	}
    return;
});
