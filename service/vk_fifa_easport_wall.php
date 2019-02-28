<?php
header("Content-Type: text/html;charset=utf-8");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Дата в прошлом

///////// СКРИПТ УДАЛЕНИЯ КОММЕНТАРИЕВ НА СТЕНЕ СООБЩЕСТВА VK.COM/FIFA_EASPORT ОТ ПОЛЬЗОВАТЕЛЕЙ НЕ ЯВЛЯЮЩИЕСЯ ПОДПИСЧИКОМ /////////

$connect_vk = mysql_query("SELECT * FROM `connect_vk` WHERE run='1'", $dbcnx_s);
$connect_vk_result = mysql_fetch_assoc($connect_vk);

$access_token = $connect_vk_result['token'];
$groupId = $connect_vk_result['id_group']; // fifa_easport

$members = json_decode(file_get_contents("https://api.vk.com/method/wall.get?owner_id=-{$groupId}&access_token={$access_token}&count=20&v=5.68"),true);

for ($y=0; $y<count($members["response"]["items"]); $y++) {
	
	echo "<b> ".$members["response"]["items"][$y]["id"]." - id сообщения </b><br>";
	$post_id = $members["response"]["items"][$y]["id"];
	
	$comment = json_decode(file_get_contents("https://api.vk.com/method/wall.getComments?owner_id=-{$groupId}&post_id={$post_id}&count=50&sort=desc&v=5.68"),true);
	
	echo "<b> ".$comment["response"]["count"]." - комментариев к записи </b><br>";
	echo "--------------------------------";
	echo "<br>";
	//var_dump($comment);
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
}	
