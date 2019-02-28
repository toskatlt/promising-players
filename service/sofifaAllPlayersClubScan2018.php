<?php

// СОХРАНЕНИЕ СПИСКА ВСЕХ КОМАНД В ТАБЛИЦУ 'FUT_LIGA18' С САЙТА SOFIFA | FIFA18 | 03.10.17

header("Content-Type: text/html;charset=utf-8");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Дата в прошлом

echo "<link type='text/css' rel='stylesheet' href='css/style.css'/>";
echo "<link type='text/css' rel='stylesheet' href='css/min.css'/>";

include "../config.php";

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

$proxy_ip = '192.168.93.29';
$proxy_port = '3129';

function allSquads ($dbcnx_s) {
	$query = mysql_query("SELECT `id`,`id_squad` FROM `fut_liga18` WHERE `stars`=0", $dbcnx_s);
	$n = mysql_num_rows($query);
	for ($i = 0; $i < $n; $i++) { $result[] = mysql_fetch_assoc($query); }
	return $result;
}

function make_seed() {
	list($usec, $sec) = explode(' ', microtime());
	return (float) $sec + ((float) $usec * 100000);
}
srand(make_seed());
$randval = rand();

	$count = 0;
	$sq = 0;
	
/*
//СНАЧАЛО ВЫТАСКИВАЕМ ВСЕ ID КОМАНД С САЙТА
for ($z=0; $z<9; $z++) {
	echo "Начинаем с ".$z."<br>";
	
	$ch3 = curl_init();
	curl_setopt($ch3, CURLOPT_URL, 'https://sofifa.com/teams/club?v=18&e=158620&offset='.$count);
	curl_setopt($ch3, CURLOPT_HEADER, 0);
	curl_setopt($ch3, CURLOPT_RETURNTRANSFER, '1');
	curl_setopt($ch3, CURLOPT_PROXYPORT, $proxy_port);
	curl_setopt($ch3, CURLOPT_PROXYTYPE, 'HTTP');
	curl_setopt($ch3, CURLOPT_PROXY, $proxy_ip);
	curl_setopt($ch3, CURLOPT_TIMEOUT_MS, 10500);
	$text1 = curl_exec($ch3);
	curl_close($ch3);
	
//	var_dump($text1);
	
	preg_match_all( '@data-src="https://cdn.sofifa.org/48/18/teams/(\d+).png"@usi', $text1, $club );
	
	//var_dump($club)."<br>";
	
	for ($i=0; $i<=count($club[1]); $i++){			
		$filename = '/var/www/html/www/tmp/logoClub/'.$club[1][$i].'.png';
		echo $filename." - filename<br>";	
		if (file_exists($filename)) {
			echo "nosave <br>";
		} else {
			echo "save <br>";
			$pic = file_get_contents('https://cdn.sofifa.org/18/teams/'.$club[1][$i].'.png');
			file_put_contents("/var/www/html/www/tmp/logoClub/".$club[1][$i].".png", $pic);
		}	
		echo "<img src='tmp/logoClub/".$club[1][$i].".png'> ".$club[1][$i]." - id logo ".$club[2][$i]." - id squad<br><br>";					
		mysql_query("INSERT INTO `fut_liga18` (`id_squad`) VALUES ('".$club[1][$i]."')", $dbcnx_s);
	}
	$count += 80;
}
*/

$allSquads = allSquads ($dbcnx_s);

foreach ($allSquads as $aS) {
	$id = $aS['id'];
	$id_squad = $aS['id_squad'];
	
	echo $id_squad." - id клуба <br>";
	
//	echo "http://sofifa.com/team/".$id_squad."?hl=ru-RU <br>";
	/*
	$ch3 = curl_init();
	curl_setopt($ch3, CURLOPT_URL, 'http://sofifa.com/team/'.$id_squad.'?v=17&e=158620');
	curl_setopt($ch3, CURLOPT_HEADER, 0);
	curl_setopt($ch3, CURLOPT_RETURNTRANSFER, '1');
	curl_setopt($ch3, CURLOPT_PROXYPORT, $proxy_port);
	curl_setopt($ch3, CURLOPT_PROXYTYPE, 'HTTP');
	curl_setopt($ch3, CURLOPT_PROXY, $proxy_ip);
	curl_setopt($ch3, CURLOPT_TIMEOUT_MS, 100500);
	$text1 = curl_exec($ch3);
	curl_close($ch3);
	*/
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://sofifa.com/team/'.$id_squad.'?hl=ru-RU&v=18&e=158620');
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, '1');
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5500);
	$text1 = curl_exec($ch);
	curl_close($ch);
	
	preg_match( '@<div class="player">\s(.*?)\s<div class="operation">@usi' , $text1, $all );
	
	preg_match( '@<h1>(.*?)\s\(@usi' , $all[1], $club );
	$club = str_replace ("'", "", $club[1]);
	echo $club." - клуб <br>";
	preg_match( '@href="/teams\?lg=\d+">(.*?)\s?(\(\d\)|)</a>@usi' , $all[1], $liga );
	
	// ВЫРЕЗАЕМ ИЗ СТРОКИ ЛИГИ СТРАНУ
	//$country = mb_substr($liga[1], mb_strpos($liga[1], ' '));
	
	preg_match( '@(.*?)\s@usi' , $liga[1], $country );
	
	// Создание папки лиги и сохранение логотипов команд в соответствующую папку
	//$structure = "logosf/".$liga[1];
	//mkdir($structure, 0777, true);
	//$pic = file_get_contents('http://www.futwiz.com/assets/img/fifa17/badges/'.$id_squad.'.png');
	//file_put_contents("logosf/".$liga[1]."/".$id_squad.".png", $pic);
		
	echo "<img src='logosf/".$liga[1]."/".$id_squad.".png?n=".$randval."'><br>";
	
	echo $liga[1]." - лига <br>";
	
	preg_match( '@Общ\.&nbsp;<span class="label p\d+">(.*?)<@usi' , $all[1], $stars );
	echo $stars[1]." - рейтинг <br>";
	
	$command = ["Portuguese","Russian","English","French","German","Austria","Italian","Spanish","Swiss","Holland","Turkish","Belgian","Danish","Scottish","Austrian","Polish","Swedish","Norwegian"];
	$command2 = ["Argentina","Mexican","Chilian","Colombian","Campeonato"];
	if( in_array($country[1], $command) ) {
		$cup = '1';
	} elseif( in_array($country[1], $command2) ) {
		$cup = '2';
	} else {
		$cup = '3';
	}
	
	echo $cup." - кубок <br>";
	echo "<br>";
	
	mysql_query("UPDATE `fut_liga18` SET `club`='".$club."',`liga`='".$liga[1]."',`cup`='".$cup."',`stars`='".$stars[1]."' WHERE `id_squad`='".$id_squad."'", $dbcnx_s);	
}
			
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.'."\n";			