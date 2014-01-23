<?php
session_start();
include_once("gestionPanier.php");
include_once 'PDO.php';

$erreur = false;

$action = (isset($_POST['action'])? $_POST['action']:  (isset($_GET['action'])? $_GET['action']:null )) ;
if($action !== null)
{
    if (!in_array($action, array('ajout', 'suppression', 'refresh', 'affiche'))) {
        $erreur = true;
    }

    //récuperation des variables en POST ou GET
   $l = (isset($_POST['l'])? $_POST['l']:  (isset($_GET['l'])? $_GET['l']:null )) ;
   $p = (isset($_POST['p'])? $_POST['p']:  (isset($_GET['p'])? $_GET['p']:null )) ;
   $n = (isset($_POST['n'])? $_POST['n']:  (isset($_GET['n'])? $_GET['n']:null )) ;
   $q = (isset($_POST['q'])? $_POST['q']:  (isset($_GET['q'])? $_GET['q']:null )) ;    

   //Suppression des espaces verticaux
   $l = preg_replace('#\v#', '',$l);
   //On verifie que $p soit un float
   $p = floatval($p);

   //On traite $q qui peut etre un entier simple ou un tableau d'entier
    
   if (is_array($q)){
      $QteArticle = array();
      $i=0;
      foreach ($q as $contenu){
         $QteArticle[$i++] = intval($contenu);
      }
   }
   else
   $q = intval($q);
    
}

if (!$erreur){
   switch($action){
      Case "ajout":
         ajouterArticle($l,$p,$bdd);
         break;

      Case "suppression":
         supprimerArticle($l);
         break;
      Case "suppressionPanier":
          supprimePanier();
          break;

      Case "refresh" :
         for ($i = 0 ; $i < count($QteArticle) ; $i++)
         {
            modifierQTeArticle($_SESSION['panier']['libelleProduit'][$i],round($QteArticle[$i]),$bdd);
         }
         $total = MontantGlobal();
         break;
      Case "affiche" :
          $total = MontantGlobal();
          break;
      Default:
         break;
   }
}


echo '<?xml version="1.0" encoding="utf-8"?>';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
<title>Votre panier</title>
</head>
<body>

<table style="width: 400px">
<form method="post" action="panier.php">



	<?php

	if (creationPanier())
	{
	   $nbArticles=  count($_SESSION['panier']['libelleProduit']);
	   if ($nbArticles <= 0)
	   echo "<p>Votre panier est vide </p>";
	   else
	   {
               echo '<p> Votre panier : ' . $nbArticles .'</p>';
               echo '<table>	<tr>
                            <th><img src="/~sdelaher/images/shotgun-test.fw.png"/></th>
                            <th>Libelle</th>
                            <th>Quantite</th>
                            <th>Prix Unitaire</th>
                            <th>Action</th>
                            </tr>';
                        
	      for ($i=0 ;$i < $nbArticles; $i++)
	      {
	         echo "<tr>";
                 echo "<td><img src=\"/~sdelaher/images/puce-test2.fw.png\"/></td>";
	         echo "<td>".htmlspecialchars($_SESSION['panier']['libelleProduit'][$i])."</ td>";
	         echo "<td><input type=\"text\" size=\"4\" name=\"q[]\" value=\"".htmlspecialchars($_SESSION['panier']['qteProduit'][$i])."\"/></td>";
	         echo "<td>".htmlspecialchars($_SESSION['panier']['prixProduit'][$i])."</td>";
	         echo "<td><a href=\"".htmlspecialchars("panier.php?action=suppression&l=".rawurlencode($_SESSION['panier']['libelleProduit'][$i]))."\">XX</a></td>";
	         echo "</tr>";
	      }
              echo '</table>';

	      echo "<tr><td colspan=\"2\"> </td>";
	      echo "<td colspan=\"2\">";
	      echo "Total : ".$total;
	      echo "</td></tr>";

	      echo "<tr><td colspan=\"4\">";
	      echo "<input type=\"submit\" value=\"Rafraichir\"/>";
	      echo "<input type=\"hidden\" name=\"action\" value=\"refresh\"/></td></form>";
              echo "<td><form action=\"commande.php\" method=\"post\">";
              echo "<input type=\"submit\" value=\"Passer commande\"/></td></form>";
	      echo "</tr></table>";
	   }
	}
	?>

</body>
</html>
