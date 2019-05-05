<?php 

require_once('includes/header.php');
require_once('includes/sidebar.php');

if(!isset($_SESSION['user_id'])){

	if(isset($_POST['submit'])){

		$email = $_POST['email'];
		$password = $_POST['password'];

		if($email&&$password){
			$select = $db->query("SELECT id FROM users WHERE email='$email' AND password='$password'");
			if($select->fetchColumn()){
				$select = $db->query("SELECT * FROM users WHERE email='$email'");
				$result = $select->fetch(PDO::FETCH_OBJ);
				$_SESSION['user_id'] = $result->id;
				$_SESSION['user_name'] = $result->username;
				$_SESSION['user_email'] = $result->email;
				$_SESSION['user_password'] = $result->password;
				header('Location: my_account.php');
			}else{
				echo '<br/><h3 style="color:red;">Mauvais identifiants.</h3>';
			}
		}else{
			echo '<br/><h3 style="color:red;">Veuillez remplir tous les champs.</h3>';
		}

	}

	?>
	<br/>
	<h1>Se connecter</h1>

	<form action="" method="POST">
		<h4>Votre email <input type="email" name="email"/></h4>
		<h4>Votre mot-de-passe <input type="password" name="password"/></h4>
		<input type="submit" name="submit"/>
	</form>
	<a href="register.php">S'inscrire</a>
	<br/>
<?php

}else{
	header('Location:my_account.php');
}

require_once('includes/footer.php');

?>