<?php

	require_once('includes/header.php');

	require_once('includes/sidebar.php');

<h2> Vente Flash <h2>

<h4> Vetement <h4>
$select = $db->prepare("SELECT * FROM products WHERE categorie='vetement'ORDER BY quantity DESC LIMIT 0,4");
	$select->execute();

<h4> Livre <h4>
$select = $db->prepare("SELECT * FROM products WHERE categorie='livre' ORDER BY quantity DESC LIMIT 0,4");
	$select->execute();

<h4> Music <h4>
$select = $db->prepare("SELECT * FROM products WHERE categorie='music' ORDER BY quantity DESC LIMIT 0,4");
	$select->execute();

<h4> Sport et loisir <h4>
$select = $db->prepare("SELECT * FROM products WHERE categorie='sportetloisir' ORDER BY quantity DESC LIMIT 0,4");
	$select->execute();

	

?>


<?php
if(!isset($_POST['search'])){
	header("Location:index.php");
	}
$search_sql="SELECT * FROM products WHERE name LIKE '%".$_POST['search']."%' OR description like'%".$_POST['search']."%'";
$search_query=mysql_query($search_sql);
if(mysql_num_rows($search_query)!=0){
$search_rs=mysql_fetch_assoc($search_query);
}
?>
<p> Search</p>
			<form name="form10" method="post" action="searchresults.php">
				<input name="search" type="text" size="40" maxlength="50"/>
				<input type="submit" name="submit" value="Search"/>

			</form>
			

<p>Resultat de recherche<p>
				<?php if(mysql_num_rows($search_query!=0){
					do{

						<p><?php echo $search_rs['name']; ?></p>;
					}while($search_rs=mysql_fetch_assoc($search_query));

					
				}else{
					echo"No results found";
				}
				?>