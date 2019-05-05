<?php
session_start();
require_once('includes/functions_panier.php');
try{

	$db = new PDO('mysql:host=127.0.0.1;dbname=site_ecommerce', 'root','');
	$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER); // les noms de champs seront en caractères minuscules
	$db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION); // les erreurs lanceront des exceptions
						
}

catch(Exception $e){

	die('Veuillez vérifier la connexion à la base de données');

}

$name = $_POST['name'];
$street = $_POST['street'];
$city = $_POST['city'];
$country_code = $_POST['country_code'];
$date = $_POST['date'];
$transaction_id = $_POST['transaction_id'];
$price = $_POST['price'];
$currency_code = $_POST['currency_code'];
$user_id = $_SESSION['user_id'];

$db->query("INSERT INTO transactions (name, street, city, country, date, transaction_id, amount, currency_code, user_id) VALUES('$name', '$street', '$city', '$country_code', '$date', '$transaction_id', '$price', '$currency_code', '$user_id')");


for($i = 0; $i<count($_SESSION['panier']['libelleProduit']); $i++){
	$product = $_SESSION['panier']['libelleProduit'][$i];
	$quantity = $_SESSION['panier']['qteProduit'][$i];
	$insert = $db->query("INSERT INTO products_transactions (product, quantity, transaction_id) VALUES('$product','$quantity', '$transaction_id')");
	$select = $db->query("SELECT * FROM products WHERE title='$product'");
	$r = $select->fetch(PDO::FETCH_OBJ);
	$stock = $r->stock;
	$stock = $stock-$quantity;
	$update = $db->query("UPDATE products SET stock='$stock' WHERE title='$product'");
}

supprimerPanier();

?>