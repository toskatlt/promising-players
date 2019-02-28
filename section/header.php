<html lang="en">
<head>
	<meta charset="UTF-8">
	<title> 
		<?php
			include $_SERVER["DOCUMENT_ROOT"]."/config.php";
			require_once($_SERVER["DOCUMENT_ROOT"]."/function/function_object.php");

			if (isset($_GET['id'])) {	
				$selectObject = selectObject ($dbcnx, $_GET['id']);
				echo $selectObject['name'];
			} elseif (isset($_GET['email'])) {
				$objectFromEmail = objectFromEmail($dbcnx, $_GET['email']);
				echo $objectFromEmail['name'];
			} else {
			}
			
			$date_time = date("y-m-d H:i:s");
			if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) { 
				$userdata = authorization ($dbcnx, $_COOKIE['id']);
			} else {
				$userdata['access'] = 0;
			}
		
			$randval = rand();
		?>	
	</title>
	<link rel='stylesheet' href='/css/table.css?ver=<?=$randval?>'/>
	<link rel='stylesheet' href='/css/style.css?ver=<?=$randval?>'/>
	<link rel='stylesheet' href='/css/input.css?ver=<?=$randval?>'/>
	<script src='/js/main.js?ver=<?=$randval?>'></script>
</head>
<body class='gradient'>
	<header class='header_block container header-flex'>
		<div class='logo'><a href='/'><img src='/img/logo.png' class='logo' title='Перспективные футболисты'></a></div>
		<div class='login_online'>
			<?php if ($userdata['access'] > 3) { ?>
			<form method='post' name='exit_cookies'>
				<p><a href='personal'><?=$userdata['fio']?></a><a class="exit" onclick="clearCookie()">X</a></p>
			</form>
			<?php } ?>
		</div>	
	</header>