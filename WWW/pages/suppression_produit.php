<?php
	if(!isset($_POST['produit']))
		{
		
		$query = $bdd->prepare('SELECT * FROM produit');
		$query->execute() or die('Erreur SQL (search)');
		
		echo '<div><form method="POST" action="./supprimer_produit.php" id="form" name="form">
								Produit a supprimer: <SELECT name="produit" size="1">';
								while($donnees = $query->fetch()){
									echo'<OPTION id="' . $donnees['libelle'] .'" >' .$donnees['libelle'].'</OPTION>';
									}
								echo'</SELECT></form>
				</div>';							
		}

?>