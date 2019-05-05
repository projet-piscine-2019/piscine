<div class="sidebar">
<h4>Nouveau produit</h4>

<?php


	$select = $db->prepare("SELECT * FROM products ORDER BY id DESC LIMIT 0,2");
	$select->execute();

	while($s=$select->fetch(PDO::FETCH_OBJ)){

		$lenght=35;

		$description = $s->description;

		$new_description=substr($description,0,$lenght)."...";

		$description_finale=wordwrap($new_description,50,'<br />', false);

		?>

		<div style="text-align:center;">
		<a style="color:white;" href="boutique.php?show=<?= $s->slug; ?>"><img height="80" width="200" src="imgs/<?php echo $s->slug; ?>.jpg"/>
		<h2 style="color:white;"><?php echo $s->title;?></a></h2>
		<h5 style="color:white;"><?php echo $description_finale; ?></h5>
		<h4 style="color:white"><?php echo $s->price; ?> euros</h4></div>
		<br/><br/>

		<?php

	}

?>
</div>