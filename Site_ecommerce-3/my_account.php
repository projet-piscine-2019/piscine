<?php

require_once('includes/header.php');
require_once('includes/sidebar.php');
?>
<h1>Mon compte</h1>
<h2>Mes informations</h2>
<?php
$user_id = $_SESSION['user_id'];
$select = $db->query("SELECT * FROM users WHERE id = '$user_id'");

while($s = $select->fetch(PDO::FETCH_OBJ)){
	?>
	<h4>Pseudo : <?php echo $s->username; ?></h4>
	<h4>Email : <?php echo $s->email; ?></h4>
	<h4>Password : <?php echo $s->password; ?></h4>
	<?php
}

?>
<h2>Mes transactions</h2>
<?php
$select = $db->query("SELECT * FROM transactions WHERE user_id = '$user_id'");

while($s = $select->fetch(PDO::FETCH_OBJ)){

	$transaction_id = $s->transaction_id;
	$select2 = $db->query("SELECT * FROM products_transactions WHERE transaction_id='$transaction_id'");

	?>

	<h4>Nom et prénom : <?php echo $s->name; ?></h4>
	<h4>Rue : <?php echo $s->street; ?></h4>
	<h4>Ville : <?php echo $s->city; ?></h4>
	<h4>Pays : <?php echo $s->country; ?></h4>
	<h4>Date de transaction : <?php echo $s->date; ?></h4>
	<h4>ID de transaction : <?php echo $s->transaction_id; ?></h4>
	<h4>Prix total : <?php echo $s->amount; ?></h4>
	<h4>Produits : </h4>
	<?php while($r = $select2->fetch(PDO::FETCH_OBJ)){?> 
	<h5>Nom : <?php echo $r->product; ?></h5>
	<h5>Quantité : <?php echo $r->quantity; ?></h5>
	<?php } ?>
	<h4>Devise utilisée : <?php echo $s->currency_code; ?></h4>
	
	<?php
}
?>
<a href="disconnect.php">Se déconnecter</a>
<?php

require_once('includes/footer.php');

?>