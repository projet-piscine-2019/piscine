<?php

require_once('includes/header.php');
?>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<?php
require_once('includes/sidebar.php');
require_once('includes/functions_panier.php');

$prixfinal = 0;

$erreur = false;

$action = (isset($_POST['action'])?$_POST['action']:(isset($_GET['action'])?$_GET['action']:null));

if($action!==null){

if(!in_array($action, array('ajout','suppression','refresh')))

$erreur = true;

$l = (isset($_POST['l'])?$_POST['l']:(isset($_GET['l'])?$_GET['l']:null));
$q = (isset($_POST['q'])?$_POST['q']:(isset($_GET['q'])?$_GET['q']:null));
$p = (isset($_POST['p'])?$_POST['p']:(isset($_GET['p'])?$_GET['p']:null));

$l = preg_replace('#\v#', '', $l);

$p = floatval($p);

if(is_array($q)){

$QteArticle= array();

$i = 0;

foreach($q as $contenu){

$QteArticle[$i++] = intval($contenu);

}

}else{

$q = intval($q);

}

}

if(!$erreur){

	switch($action){

		Case "ajout":

		ajouterArticle($l,$q,$p);

		break;

		Case "suppression":


		supprimerArticle($l);

		break;

		Case "refresh":

		for($i = 0;$i<count($QteArticle);$i++){

			modifierQTeArticle($_SESSION['panier']['slugProduit'][$i], round($QteArticle[$i]));

		}

		break;

		Default:

		break;

	}

}

?>

<form method="post" action="">
	<table width="400">
		<tr>
			<td colspan="4">Votre panier</td>
		</tr>
		<tr>
			<td>Libellé produit</td>
			<td>Prix unitaire</td>
			<td>Quantité</td>
			<td>TVA</td>
			<td>Action</td>
		</tr>
		<?php

			if(isset($_GET['deletepanier']) && $_GET['deletepanier'] == true){

				supprimerPanier();

			}

			if(creationPanier()){

			$nbProduits = count($_SESSION['panier']['libelleProduit']);

			if($nbProduits <= 0){

				echo'<br/><p style="font-size:20px; color:Red;">Oops, panier vide !</p>';

			}else{

				$total = MontantGlobal();
				$totaltva = MontantGlobalTVA();
				$shipping = CalculFraisPorts();
				$prixfinal = $totaltva + $shipping;

				for($i = 0; $i<$nbProduits; $i++){

					?>

					<tr>

						<td><br/><?php echo $_SESSION['panier']['libelleProduit'][$i]; ?></td>
						<td><br/><?php echo $_SESSION['panier']['prixProduit'][$i];?></td>
						<td><br/><input name="q[]" value="<?php echo $_SESSION['panier']['qteProduit'][$i]; ?>" size="5"/></td>
						<td><br/><?php echo $_SESSION['panier']['tva']." %"; ?></td>
						<td><br/><a href="panier.php?action=suppression&amp;l=<?php echo $_SESSION['panier']['slugProduit'][$i]; ?>">X</a></td>

					</tr>
					<?php } ?>
					<tr>

						<td colspan="2"><br/>
							<p>Total : <?php echo $total." €"; ?></p><br/>
							<p>Total avec TVA : <?php echo $totaltva." €"; ?></p>
							<p>Calcul des frais de port : <?php echo $shipping." €"; ?></p>
							<?php if(isset($_SESSION['user_id'])){ ?><div id="paypal-button"></div><?php }else{?><h4 style="color:red;">Vous devez être connecté pour payer votre commande. <a href="connect.php">Se connecter</a></h4><?php } ?>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<input type="submit" value="rafraichir"/>
							<input type="hidden" name="action" value="refresh"/>
							<a href="?deletepanier=true">Supprimer le panier</a>
						</td>
					</tr>

					<?php


			}

		}

		?>
	</table>
</form>
<script>
	paypal.Button.render({

	    env: 'sandbox', // sandbox | production, si c'est pour tester : sandbox, si c'est pour de vrai : production

	    // PayPal Client IDs - replace with your own
	    // Create a PayPal app: https://developer.paypal.com/developer/applications/create
	    // Remplacez par le vôtre
	    client: {
	        sandbox:    'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',
	        production: '<insert production client id>'
	    },

	    // Vous pouvez changer le style du bouton en vous référant à l'API et en cherchant dans le menu "Button styles":
	    // https://developer.paypal.com/demo/checkout/#/pattern/checkout

	    style: {
            layout: 'vertical',  // horizontal | vertical
            size:   'medium',    // medium | large | responsive
            shape:  'rect',      // pill | rect
            color:  'gold'       // gold | blue | silver | black
        },

	    // Show the buyer a 'Pay Now' button in the checkout flow
	    commit: true,

	    // payment() is called when the button is clicked
	    payment: function(data, actions) {

	        // Make a call to the REST api to create the payment
	        // Changez la devise si vous le souhaitez
	        return actions.payment.create({
	            payment: {
	                transactions: [
	                    {
	                        amount: { total: <?= $prixfinal ?>, currency: 'EUR' }
	                    }
	                ]
	            },
	        });
	    },

	    // onAuthorize() is called when the buyer approves the payment
	    onAuthorize: function(data, actions) {

	        return actions.payment.get().then(function(data) {

                // Ici on récupère les informations sur la transaction, vous êtes libres d'en ajouter en vous servant du console.log ci-dessous et en les rajoutant dans process.php et dans la structure de la base de données (table transactions).

                console.log(data);

                var shipping = data.payer.payer_info.shipping_address;

                var name = shipping.recipient_name;
                var street = shipping.line1;
                var country_code = shipping.country_code;
                var city = shipping.city;
                var date = '<?= date("Y/m/d") ?>';
                var transaction_id = data.id;
                var price = data.transactions[0].amount.total;
                var currency_code = 'EUR';

                $.post(
         			"process.php",
         			{
         				name : name,
         				street: street,
         				city: city,
         				country_code : country_code,
         				date: date,
         				transaction_id: transaction_id,
         				price: price,
         				currency_code: currency_code,
         			}
				);

                //Redirection après le paiement
                return actions.payment.execute().then(function() {
                	$(location).attr("href", '<?= "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI'])."/success.php"; ?>');
            	});
            });
	    },

	}, '#paypal-button');
</script>
<?php

require_once('includes/footer.php');

?>