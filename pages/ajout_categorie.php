<?php
        session_start();
        include 'PDO.php';
		
		if(!isset($_POST['new_categorie']))
		{       
            echo'<div><form method="POST" action="./ajout_categorie.php" id="form" name="form">
			<label></br>Nouvelle categorie</br><input type="text" name="new_categorie" id="id" /></label>
			<input type="submit" value="ok">
			</form></div>';
					
					
		}
		
		
		else{
		
		$query2 = $bdd->prepare('SELECT libelle FROM categorie');
		$query2->execute() or die('Erreur SQL (select libelle)');
		
		while($donnees=$query2->fetch())
		{
			if($_POST['new_categorie'] == $donnees['libelle'])
				die('Categorie deja existante');
		}
		
		
		$query = $bdd->prepare('INSERT INTO categorie(libelle) VALUE (:n)');
		
		$query->bindParam(':n', $_POST['new_categorie']);
		$query->execute() or die('Erreur SQL (insert into)');	
		
		echo'Categorie ajoutee';	

		}		
?>