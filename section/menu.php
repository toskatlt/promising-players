<?php
if (isset($_POST['exit_cookies'])){
	setcookie("id", '');
	setcookie("hash", '');
	header("Refresh:1"); 
}
?>
<nav class="navigation">
	<ul class='menu'>	
<?php	
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) { 
	$userdata = authorization ($dbcnx, $_COOKIE['id']);
	if($userdata['id'] == $_COOKIE['id']) {
			if  ($userdata['access'] > "3") {
				?>
				<li class="menu-list"><a href='/' class="menu-link">Главная</a></li>
				<li class='menu-list'><p href='check.php' class='menu-link'>Админ панель</p>
					<div class="drop-box">
						<div class="drop-flex">
							<ul class="drop-ul">
								<li class="drop-li"><a href='/personal' class="drop-link">Создание карточек</a></li>
							</ul>
						</div>						
					</div>
			<?php } ?>
		<?php		
	}
}
?> 		
	</ul>
</nav>
			
<?php	
/*	
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {		
	if ($userdata['access'] > "4") { ?>
			<div class='block_left_menu'>
				<?php if ($userdata['access'] > 6) { ?>
					<div class='left_menu bg_lm'><p id='pass' class='pass'></p></div>
				<?php } ?>			
				<div class='left_menu bg_lm2'><a href='/ip_pelican'><img class="left_menu_img" src='/img/ip.png' title='IP Пеликан'></a></div>
				<div class='left_menu bg_lm3'><a href='http://support.neo63.ru/scp/'><img class="left_menu_img" src='/img/scp.png' title='ТехПоддержка'></a></div>
				<div class='left_menu bg_lm4'><a href='/jabber'><img class="left_menu_img"  src='/img/jabber.png' title='Jabber'></a></div>
				<div class='left_menu bg_lm5'><a href='http://forum.neo63.ru/'><img class="left_menu_img" src='/img/forum.png' title='Форум Пеликан'></a></div>
				<div class='left_menu bg_lm6'><a href='/terminals'><img class="left_menu_img" src='/img/terminal.png' title='Терминалы'></a></div>
				<?php	$sumAllJacarta = sumAllJacarta ($dbcnx);
				if ($sumAllJacarta > 0) { ?>
					<div class='left_menu bg_lm7'><a href='/rsa' class="rsa left_menu_flex"><p class="sum_rsa"><?=$sumAllJacarta?></p></a></div>
				<?php } ?>
				<div class='left_menu bg_lm8'><a href='/zapravka'><img class="left_menu_img" src='/img/zapravka.png' title='Заправка картриджей'></a></div>
			</div>
		<?php
	}	
}
*/
?>