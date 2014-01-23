<?php

	session_start();
    include 'PDO.php';
	
	if(!isset($_POST['produit']))
		{
		
		$query = $bdd->prepare('SELECT * FROM produit');
		$query->execute() or die('Erreur SQL (search)');
		
		echo '<div><form method="POST" action="./supprimer_produit.php" id="form" name="form">
								Produit a supprimer: <SELECT name="produit" size="1">';
								while($donnees = $query->fetch()){
									echo'<OPTION id="' . $donnees['nom_produit'] .'" >' .$donnees['nom_produit'].'</OPTION>';
									}
								echo'</SELECT>								
								<input type="submit" value="ok"></form>
				</div>';							
		}
		
	else{
	
		$query1 = $bdd->prepare('SELECT photo FROM produit WHERE nom_produit=:n1');
		$query1->bindParam(':n1', $_POST['produit']);
		$query1->execute() or die('Erreur SQL (search photo)');
		$donnee1 = $query1->fetch();
		
		//Suppression des photos du produits
		unlink($donnee1['photo']);		
		unlink( deletePattern("1-",$donnee1['photo'])); 	
		
		
		$query2 = $bdd->prepare('DELETE FROM produit WHERE nom_produit=:n');
		$query2->bindParam(':n', $_POST['produit']);		
		$query2->execute() or die('Erreur SQL (supp produit)');
		
		echo'Le produit est supprimee';
	
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