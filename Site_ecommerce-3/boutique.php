<?php

	require_once('includes/header.php');

	require_once('includes/sidebar.php');

	if(isset($_GET['show'])){

		$product = htmlentities($_GET['show']);

		$select = $db->prepare("SELECT * FROM products WHERE slug='$product'");
		$select->execute();	

		$s = $select->fetch(PDO::FETCH_OBJ);

		$description = $s->description;

		$description_finale=wordwrap($description,100,'<br />', false);

		?>

		<br/><div style="text-align:center;">
		<img src="imgs/<?php echo $s->slug; ?>.jpg"/>
		<h1><?php echo $s->title; ?></h1>
		<h5><?php echo $description_finale; ?></h5>
		<h5>Stock : <?php echo $s->stock; ?></h5>
		<?php if ($s->stock>0){ ?><a href="panier.php?action=ajout&amp;l=<?php echo $s->slug; ?>&amp;q=1&amp;p=<?php echo $s->price; ?>">Ajouter au panier</a><?php }else{echo'<h5 style="color:red;">Stock épuisé !</h5>';} ?>
		</div><br/>

		<?php 

	}else{

	if(isset($_GET['category'])){

		$category_slug=$_GET['category'];
		$select = $db->query("SELECT name FROM category WHERE slug='$category_slug'");
		$results = $select->fetch(PDO::FETCH_OBJ);
		$category = addslashes($results->name);
		$select = $db->prepare("SELECT * FROM products WHERE category='$category'");
		$select->execute();

		while($s=$select->fetch(PDO::FETCH_OBJ)){


			$lenght=75;

			$description = $s->description;

			$new_description=substr($description,0,$lenght)."...";

			$description_finale=wordwrap($new_description,50,'<br />', false);

			?>
			<br/>
			<a href="?show=<?php echo $s->slug; ?>"><img src="imgs/<?php echo $s->slug; ?>.jpg"/></a>
			<a href="?show=<?php echo $s->slug; ?>"><h2><?php echo $s->title;?></h2></a>
			<h5><?php echo $description_finale; ?></h5>
			<h4><?php echo $s->price; ?> Euros</h4>
			<h5>Stock : <?php echo $s->stock; ?></h5>
			<?php if ($s->stock>0){ ?><a href="panier.php?action=ajout&amp;l=<?php echo $s->slug; ?>&amp;q=1&amp;p=<?php echo $s->price; ?>">Ajouter au panier</a><?php }else{echo'<h5 style="color:red;">Stock épuisé !</h5>';} ?>

			<br/><br/>

			<?php

		}
	?>

	<br/><br/><br/><br/>

	<?php

	}else{
		?>
		<br/><h1>Catégories :</h1>
		<?php
	$select = $db->query("SELECT * FROM category");

	while($s = $select->fetch(PDO::FETCH_OBJ)){

		?>

		<a href="?category=<?php echo $s->slug;?>"><h3><?php echo $s->name ?></h3></a>

		<?php

	}

}

}

	require_once('includes/footer.php');

?>

<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>