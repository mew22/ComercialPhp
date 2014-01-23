<?php
        session_start();
        include 'PDO.php';
		
		if(!isset($_POST['categorie']) && !isset($_POST['new_categorie']))
		{
		
		$query = $bdd->prepare('SELECT * FROM categorie');
		$query->execute() or die('Erreur SQL (search)');
        
		
        echo '<div><form method="POST" action="./modif_categorie.php" id="form" name="form">
						Categorie a modifier: <SELECT name="categorie" size="1">';
						while($donnees = $query->fetch()){
							echo'<OPTION id="' . $donnees['libelle'] .'" >' .$donnees['libelle'].'</OPTION>';
							}
							echo'</SELECT>';
						
                        echo'<label></br>Nouveau nom de la categorie: </br><input type="text" name="new_categorie" id="id" /></label>
						<input type="submit" value="ok">
					</form>
				</div>';
					
		}
		
		else{
			$query = $bdd->prepare('UPDATE categorie SET libelle=:c WHERE libelle=:old');
			
			$query->bindParam(':c', $_POST['new_categorie']);
			$query->bindParam(':old', $_POST['categorie']);
			
			$query->execute() or die('Erreur update');
			
			
			echo'Changement effectue';
		
		}		
?>