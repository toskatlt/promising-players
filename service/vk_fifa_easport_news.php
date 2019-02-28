<?php
header("Content-Type: text/html; charset=utf-8");
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include "../config.php";
require_once($_SERVER["DOCUMENT_ROOT"]."/function/function_object.php");


$access_token = 'c8975b40cae38bf6ca80b53669573381f9963c476c7a07ef74c80c34ea4b5083f1c8820953896dd076f90';
$groupId = 41099382; // fifa_easport

/*
$link = "https://api.vk.com/method/wall.post?access_token=".$access_token."&owner_id=-{$groupId}&message={$message_out}&attachments=".$attachments."&v=5.68";
$data = file_get_contents($link);
$data = json_decode($data, true);
*/

$members = json_decode(file_get_contents("https://api.vk.com/method/wall.get?owner_id=-{$groupId}&access_token={$access_token}&count=20&v=5.68"),true);

for ($y=0; $y<count($members["response"]["items"]); $y++) {
	
	echo "<b> ".$members["response"]["items"][$y]["id"]." - id сообщения </b><br>";
	$post_id = $members["response"]["items"][$y]["id"];
	
	$comment = json_decode(file_get_contents("https://api.vk.com/method/wall.getComments?owner_id=-{$groupId}&post_id={$post_id}&count=50&sort=desc&v=5.68"),true);
	
	echo "<b> ".$comment["response"]["count"]." - комментариев к записи </b><br>";
	echo "--------------------------------";
	echo "<br>";
	
	/*
	if ($comment["response"]["count"] > 0) {
		foreach ($comment["response"]["items"] as $com) {
			$id = $com['id'];
			$from_id = $com['from_id'];
			
			$in = json_decode(file_get_contents("https://api.vk.com/method/groups.isMember?group_id={$groupId}&user_id={$from_id}&access_token={$access_token}&v=5.68"),true);
			//var_dump($in);	
			
			echo $in["response"]." - in['response'] <br>";
			if ($in["response"] == 0) { 
				echo $com['id']." - id комментария <br>";
				echo $com['from_id']." - id пользователя <br>";
				echo "<b style='color: red;'>удалить комментарий</b>"; 
				$del = json_decode(file_get_contents("https://api.vk.com/method/wall.deleteComment?owner_id=-{$groupId}&comment_id={$id}&access_token={$access_token}&v=5.68"),true);
				var_dump($del);
				echo "<br>";
			}
			sleep(1);
		}
	}
	*/
}	


/*
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////// FIFA EASPORT ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) { 
	$userdata = authorization_lite ($dbcnx, $_COOKIE['id']);
	if($userdata['id'] == $_COOKIE['id']) {
			if  ($userdata['access'] > "3") {	
				$connect_vk = mysql_query("SELECT * FROM `connect_vk` WHERE run='1'", $dbcnx_s);
				$connect_vk_result = mysql_fetch_assoc($connect_vk);

				$access_token = $connect_vk_result['token'];
				$groupId = $connect_vk_result['id_group']; // fifa_easport

				$result = mysql_fetch_assoc(mysql_query("SELECT * FROM `vk_fifa_easport_news` WHERE `publicated`='0' ORDER BY RAND() LIMIT 1", $dbcnx_s));

				$message_out = $result['text'];
				$attachments = $result['attachments'];
				$album = $result['album'];

					$link = "https://api.vk.com/method/wall.post?access_token=".$access_token."&owner_id=-{$groupId}&message={$message_out}&attachments=".$attachments."&v=5.68";
					$data = file_get_contents($link);
					$data = json_decode($data, true);

				mysql_query("UPDATE `vk_fifa_easport_news` SET `publicated`='1' WHERE `id`='".$result['id']."'", $dbcnx_s);

				$members = json_decode(file_get_contents("https://api.vk.com/method/wall.get?owner_id=-{$groupId}&access_token=".$access_token."&count=2&v=5.68"),true);
				$id = $members["response"]["items"][1]['id'];
				$caption = "[https://vk.com/fifa_easport?w=wall-31685665_".$id."]"; // fifa_easport

				$med = json_decode(file_get_contents("https://api.vk.com/method/photos.getUploadServer?group_id={$groupId}&v=5.68&access_token={$access_token}&album_id={$album}"),true);

					$link=$med['response']['upload_url'];
					$img_src = '/var/www/html/www/tmp/public/'.$result['filename'].'.png';

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
	}
}
*/