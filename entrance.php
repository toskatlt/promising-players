<?php
include $_SERVER["DOCUMENT_ROOT"]."/config.php";
require_once($_SERVER["DOCUMENT_ROOT"]."/function/function_object.php");

if (isset($_POST['enter'])) {
	
    if($_POST['name'] != "" and $_POST['pass'] != "") {
		
		$name = mysqli_real_escape_string($dbcnx, trim($_POST['name']));
		$pass = mysqli_real_escape_string($dbcnx, trim($_POST['pass']));
		
        $query = mysqli_query($dbcnx, "SELECT id, password FROM user WHERE username='".$name."'") or die(mysqli_error());
		$data = mysqli_fetch_assoc($query);
		# Сравниваем пароли
		if($data['password'] === md5(md5($pass))) {
			$hash = md5(generateCode(10));           
			# Записываем в БД новый хеш авторизации и IP
			mysqli_query($dbcnx, "UPDATE user SET hash='".$hash."' WHERE id='".$data['id']."'");	
			setcookie("id", $data['id'], time()+3600*24*30*12);
			setcookie("hash", $hash, time()+3600*24*30*12);
			header("Location: index.php"); exit();
		}
		else {
			print "<div class='login'>Вы ввели неправильный логин/пароль</div>";
		}
		
	}
}

include ($_SERVER["DOCUMENT_ROOT"]."/section/header.php"); /* HEADER */
include ($_SERVER["DOCUMENT_ROOT"]."/section/menu.php"); /* MENU */

	echo "<div class='login'>";
		echo "<div class='block'>";
			echo "<form  method='POST'>";
			echo "<span class='input input--kaede'>";
			echo "<input class='input__field input__field--kaede' type='text' name='name' id='input-1'/>";
			echo "<label class='input__label input__label--kaede' for='input-1'>";
			echo "<span class='input__label-content input__label-content--kaede'>Login</span>";
			echo "</label>";
			echo "</span>";

			echo "<span class='input input--kaede'>";
			echo "<input class='input__field input__field--kaede' type='password' name='pass' id='input-2'/>";
			echo "<label class='input__label input__label--kaede' for='input-2'>";
			echo "<span class='input__label-content input__label-content--kaede'>Password</span>";
			echo "</label>";
			echo "</span>";
			echo "<center><input type='submit' name='enter' value='Подтвердить' class='button'></center>";
			echo "</form>";
		echo "</div>";
	echo "</div>";


include ($_SERVER["DOCUMENT_ROOT"]."/section/footer.php"); /* FOOTER */  ?>