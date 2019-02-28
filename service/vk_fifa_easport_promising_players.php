<?php
header("Content-Type: text/html; charset=utf-8");
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include "../config.php";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////// FIFA EASPORT ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (((date('H') == '11') or (date('H') == '15') or (date('H') == '19')) and (date('i') == '00')) {
	
	$connect_vk = mysqli_query($dbcnx, "SELECT * FROM `connect_vk` WHERE run='1'");
	$connect_vk_result = mysqli_fetch_assoc($connect_vk);

	$access_token = $connect_vk_result['token'];
	$groupId = $connect_vk_result['id_group']; // fifa_easport

	$result = mysqli_fetch_assoc(mysqli_query($dbcnx, "SELECT * FROM `vk_fifa_easport_promising_players` WHERE `publicated`='0' ORDER BY RAND() LIMIT 1"));
	
	$message_out = $result['text'];
	$attachments = $result['attachments'];
	$album = $result['album'];

		$link = "https://api.vk.com/method/wall.post?access_token=".$access_token."&owner_id=-{$groupId}&message={$message_out}&attachments=".$attachments."&v=5.68";
		$data = file_get_contents($link);
		$data = json_decode($data, true);
		
	mysqli_query($dbcnx, "UPDATE `vk_fifa_easport_promising_players` SET `publicated`='1' WHERE `id`='".$result['id']."'");

	$members = json_decode(file_get_contents("https://api.vk.com/method/wall.get?owner_id=-{$groupId}&access_token=".$access_token."&count=2&v=5.68"),true);
	$id = $members["response"]["items"][1]['id'];
	$caption = "[https://vk.com/fifa_easport?w=wall-31685665_".$id."]"; // fifa_easport

	$med = json_decode(file_get_contents("https://api.vk.com/method/photos.getUploadServer?group_id={$groupId}&v=5.68&access_token={$access_token}&album_id={$album}"),true);

		$link=$med['response']['upload_url'];
		$img_src = '/home/prom114768/promising-players.ru/docs/tmp/public/'.$result['filename'].'.png';
		
		$file = new CURLFile(realpath($img_src));
		$ch = curl_init($link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' => $file));
		$response  = curl_exec($ch);
		curl_close($ch);
					 
		$response1 = json_decode($response);
		$server = $response1->server;
		$photo = $response1->photos_list;
		$hash = $response1->hash;

	$members2 = json_decode(file_get_contents("https://api.vk.com/method/photos.save?group_id={$groupId}&v=5.3&album_id={$album}&access_token=".$access_token."&caption={$caption}&photos_list={$photo}&server=".$server."&hash=".$hash),true);
}