<?php
/*
**		provides actively online radio information
**		OmerAti - Omer ATABER omerati6363@gmail.com
**		You need to have a new block in your gateway.config.php
**		like this

'radio' => [
		'station_name' => 'Radyo Valware', 
		'station_link' => 'http://www.domainname.com', 
		'ip' => [
			1 => '46.105.210.237',
			// 2 => "127.0.0.1", example of second ip"
		],
		//corresponds with ip number
		'port' => [
				1 => '8902',
		],
	],

*/

hook::func("privmsg", function ($u)
{
	
	global $gw, $sql, $me, $cf;

	// convenience identifiers
	$nick = $u['nick'];
	$parv = explode(" ", $u['parc']);
	$cmd = $parv[0];
	$target = $u['dest'];

	if ($target == $me)
	{
		return;
	}

	if ($cmd !== "!radio")
	{
		return;
	}

	$station_name = $cf['radio']['station_name'];
	$station_link = $cf['radio']['station_link'];
	$refresh = "20";
	$timeout = "1";
	$ip = $cf['radio']['ip'];
	$port = $cf['radio']['port'];

	$servers = count($ip);

	$i = "1";
	while ($i <= $servers)
	{
		$fp = @fsockopen($ip[$i], $port[$i], $errno, $errstr, $timeout);
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
			if (empty($stats[1]))
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
					$max[$i] = $stats[3];
					$bitrate[$i] = $stats[5];
					$peak[$i] = $stats[2];
					if ($stats[0] == $max[$i])
					{

					}

				}

				{
					$listeners[$i] = "0";
					$msg[$i] = "	<span class=\"red\">Error [Unable to Connect Radio]</span>";
					$error[$i] = "1";
				}
			}
		}
		$i++;
	}

	$gw->msg($target, "0,1 Radio:11 $station_name 11,11|");
	$gw->msg($target, "0,1 Max:11 $max[1] 11,11|");
	$gw->msg($target, "0,1 Peak:11 $peak[1] 11,11|");
	$gw->msg($target, "0,1 Bitrate:11 $bitrate[1] 11,11|");
	$gw->msg($target, "0,1 Listening:11 $song[1] 11,11|");
	$gw->msg($target, "0,1 Publication WEB 11 $station_link 0Click on the link. 11,11|");
	$gw->msg($target, "0,1 Publication Winamp 9http://$ip[1]:$port[1]/listen.pls 0Click on the link. 9,9|");
	return;
});

// the help hook which is from a /third module
hook::func("help", function ($u)
{
	global $gw, $me;
	$nick = $u['nick'];
	$parv = explode(" ", $u['parc']);

	if (!isset($parv[1]))
	{
		$gw->notice($nick, "!radio		 gives general information about the radio");
	}
	elseif ($parv[1] == "!radio")
	{
		$gw->notice($nick, "Syntax: !radio");
	}
	return;
});

