<?php

	session_start();

	try{

		$db= new PDO('mysql:host=localhost:8889; dbname=site_ecommerce; charset=utf8', 'root', 'root');
		$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); // les noms de champs seront en caractères minuscules
		$db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION); // les erreurs lanceront des exceptions
		$db->exec('SET NAMES utf8');				
	}

	catch(Exception $e){

		die('Veuillez vérifier la connexion à la base de données');

	}
?>

			

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8">
		<link href="style/bootstrap.css" type="text/css" rel="stylesheet"/>
	</head>
	<header>
		<br/><h1>Site E-Commerce</h1><br/>
		<ul class="menu">
			<li><a href="index.php">Accueil</a></li>
			<li><a href="boutique.php">Boutique</a></li>
			<li><a href="panier.php">Panier</a></li>
			<?php if(!isset($_SESSION['user_id'])){?>
			<li><a href="register.php">S'inscrire</a></li>
			<li><a href="connect.php">Se connecter</a></li>
			<?php }else{ ?>
			<li><a href="my_account.php">Mon compte</a></li>
			<?php } ?>
			<li><a href="conditions_generales_de_vente.php">Conditions Generales de Vente</a></li>
			<li><a href="admin/index.php">Administrateur</a></li>
		</ul>
		<p> Search</p>
			<form name="form1" method="post" action="searchresults.php">
				<input name="search" type="text" size="40" maxlength="50"/>
				<input type="submit" name="submit" value="Search"/>


			</form>

	</header>
