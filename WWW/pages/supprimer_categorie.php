<?php

	session_start();
    include 'PDO.php';
	
	if(!isset($_POST['categorie']))
		{

			$query = $bdd->prepare('SELECT libelle FROM categorie');
			$query->execute() or die('Erreur SQL (search)');
			
			echo '<div><form method="POST" action="./supprimer_categorie.php" id="form" name="form">
								categorie a supprimer: <SELECT name="categorie" size="1">';
								while($donnees = $query->fetch()){
									echo'<OPTION id="' . $donnees['libelle'] .'" >' .$donnees['libelle'].'</OPTION>';
									}
								echo'</SELECT>								
								<input type="submit" value="ok"></form>
				</div>';							
		}
				
	else{
	
		$query1 = $bdd->prepare('SELECT num_categorie FROM categorie WHERE libelle=:c');
		$query1->bindParam(':c', $_POST['categorie']);
		$query1->execute() or die('Erreur SQL (num categorie)');
		$categorie= $query1->fetch();
		
		//suppression des photos des produits de la catégorie
		$query2 = $bdd->prepare('SELECT photo FROM produit WHERE num_categorie=:n');
		$query2->bindParam(':n', $categorie['num_categorie']);
		$query2->execute() or die('Erreur SQL (supp categorie)');
		
		while($photo = $query2->fetch())
		{		
			unlink($photo['photo']);		
			unlink( deletePattern("1-",$photo['photo']));		
		}
		
		//suppression des produits de la catégorie
		$query3 = $bdd->prepare('DELETE FROM produit WHERE num_categorie=:n2');
		$query3->bindParam(':n2', $categorie['num_categorie']);
		$query3->execute() or die('Erreur SQL (supp categorie)');
		
		//suppression de la categorie
		$query4 = $bdd->prepare('DELETE FROM categorie WHERE libelle=:l');
		$query4->bindParam(':l', $_POST['categorie']);
		$query4->execute() or die('Erreur SQL (supp categorie)');
		
		
		
		echo'Categorie supprimee';
	
	
	
	}
	
	function deletePattern($pattern, $string)
            {
                $str='';
                
                $verif;
                $verif[0]=false;
                $verif[1]=false;
                
                for($i=0;$i<strlen($string);$i++)
                {

                     if($string[$i]!=$pattern[0] && $string[$i]!=$pattern[1])
                           $str=$str . $string[$i];
                       
                     if($string[$i]==$pattern[0])
                           $verif[0]=true;
                       
                     if($string[$i]==$pattern[1])
                           $verif[1]=true;
                       
                       if($verif[0]==true && $verif[1]==true)
                           break;
                     
                }
                
                for($i++;$i<strlen($string);$i++)
                {
                    $str = $str . $string[$i];
                }
                return $str;
            }
?>