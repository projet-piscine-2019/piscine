<?php 

require_once('includes/header.php');
require_once('includes/sidebar.php');

if(!isset($_SESSION['user_id'])){

	if(isset($_POST['submit'])){

		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$repeatpassword = $_POST['repeatpassword'];

		if($username&&$email&&$password&&$repeatpassword){
			if($password==$repeatpassword){
				$db->query("INSERT INTO users (username, email, password) VALUES('$username', '$email', '$password')");
				echo '<br/><h3 style="color:green;">Vous avez créé votre compte, vous pouvez maintenant vous <a href="connect.php">connecter</a>.</h3>';
			}else{
				echo '<br/><h3 style="color:red;">Les mot-de-passes ne sont pas identiques.</h3>';
			}
		}else{
			echo '<br/><h3 style="color:red;">Veuillez remplir tous les champs.</h3>';
		}

	}

	?>
	<br/>
	<h1>S'enregister</h1>

	<form action="" method="POST">
		<h4>Votre pseudo <input type="text" name="username"/></h4>
		<h4>Votre email <input type="email" name="email"/></h4>
		<h4>Votre mot-de-passe <input type="password" name="password"/></h4>
		<h4>Répétez votre mot-de-passe <input type="password" name="repeatpassword"/></h4>
		<input type="submit" name="submit"/>
	</form>
	<a href="connect.php">Se connecter</a>
	<br/>
<?php

}else{
	header('Location:my_account.php');
}
?>
<?php
require_once('includes/footer.php');

?>