<?php
include_once 'PDO.php';
/**
 * Verifie si le panier existe, le créé sinon
 * @return booleen
 */
function creationPanier(){
   if (!isset($_SESSION['panier'])){
      $_SESSION['panier']=array();
      $_SESSION['panier']['libelleProduit'] = array();
      $_SESSION['panier']['qteProduit'] = array();
      $_SESSION['panier']['prixProduit'] = array();
      $_SESSION['panier']['verrou'] = false;
   }
   return true;
}


/**
 * Ajoute un article dans le panier
 * @param string $libelleProduit
 * @param float $prixProduit
 * @return void
 */
function ajouterArticle($libelleProduit,$prixProduit,$bdd){
    
   //Si le panier existe
   if (creationPanier() && !isVerrouille())
   {
      //Si le produit existe déjà on ajoute seulement la quantité
      
      $positionProduit = array_search($libelleProduit,  $_SESSION['panier']['libelleProduit']);

      if ($positionProduit !== false)
      {
          //Verification que l'utilisateur ne dépasse pas la quantité max
        $query = $bdd->prepare('SELECT quantite_produit FROM produit WHERE nom_produit=:nom');
        $query->bindParam(':nom',$libelleProduit);
        $query->execute() or die('erreur verif quantite');
        $quantite_max = $query->fetch();
        $quantite = $quantite_max['quantite_produit'] - ($_SESSION['panier']['qteProduit'][$positionProduit]);
        if($quantite>0)
            $_SESSION['panier']['qteProduit'][$positionProduit] += 1 ;
      }
      else
      {
         //Sinon on ajoute le produit
         array_push( $_SESSION['panier']['libelleProduit'],$libelleProduit);
         array_push( $_SESSION['panier']['qteProduit'],1);
         array_push( $_SESSION['panier']['prixProduit'],$prixProduit);
      }
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}



/**
 * Modifie la quantité d'un article
 * @param $libelleProduit
 * @param $qteProduit
 * @return void
 */
function modifierQTeArticle($libelleProduit,$qteProduit,$bdd){
   
   if (creationPanier() && !isVerrouille())
   {
      //Si la quantité est positive on modifie sinon on supprime l'article
      //Verification que l'utilisateur ne dépasse pas la quantité max
      $query = $bdd->prepare('SELECT quantite_produit FROM produit WHERE nom_produit=:nom');
      $query->bindParam(':nom',$libelleProduit);
      $query->execute() or die('erreur verif quantite');
      $quantite_max = $query->fetch();
      
      if ($qteProduit > 0 && ($quantite_max['quantite_produit']>=$qteProduit))
      {
         //Recharche du produit dans le panier
         $positionProduit = array_search($libelleProduit,  $_SESSION['panier']['libelleProduit']);
         
         
         if ($positionProduit !== false)
         {
            $_SESSION['panier']['qteProduit'][$positionProduit] = $qteProduit ;
         }
      }
      else if($qteProduit > 0 && ($quantite_max['quantite_produit']<$qteProduit))
      {
          //Recharche du produit dans le panier
         $positionProduit = array_search($libelleProduit,  $_SESSION['panier']['libelleProduit']);
         
         
         if ($positionProduit !== false)
         {
            $_SESSION['panier']['qteProduit'][$positionProduit] = $quantite_max['quantite_produit'] ;
         }
      }
      else
      supprimerArticle($libelleProduit);
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
   
}

/**
 * Supprime un article du panier
 * @param $libelleProduit
 * @return unknown_type
 */
function supprimerArticle($libelleProduit){
   //Si le panier existe
   if (creationPanier() && !isVerrouille())
   {
      //Nous allons passer par un panier temporaire
      $tmp=array();
      $tmp['libelleProduit'] = array();
      $tmp['qteProduit'] = array();
      $tmp['prixProduit'] = array();
      $tmp['verrou'] = $_SESSION['panier']['verrou'];

      for($i = 0; $i < count($_SESSION['panier']['libelleProduit']); $i++)
      {
         if ($_SESSION['panier']['libelleProduit'][$i] !== $libelleProduit)
         {
            array_push( $tmp['libelleProduit'],$_SESSION['panier']['libelleProduit'][$i]);
            array_push( $tmp['qteProduit'],$_SESSION['panier']['qteProduit'][$i]);
            array_push( $tmp['prixProduit'],$_SESSION['panier']['prixProduit'][$i]);
         }

      }
      //On remplace le panier en session par notre panier temporaire à jour
      $_SESSION['panier'] =  $tmp;
      //On efface notre panier temporaire
      unset($tmp);
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}


/**
 * Montant total du panier
 * @return int
 */
function MontantGlobal(){
   $total=0;
   for($i = 0; $i < count($_SESSION['panier']['libelleProduit']); $i++)
   {
      $total += $_SESSION['panier']['qteProduit'][$i] * $_SESSION['panier']['prixProduit'][$i];
   }
   return $total;
}


/**
 * Fonction de suppression du panier
 * @return void
 */
function supprimePanier(){
   unset($_SESSION['panier']);
}

/**
 * Permet de savoir si le panier est verrouillé
 * @return booleen
 */
function isVerrouille(){
   if (isset($_SESSION['panier']) && $_SESSION['panier']['verrou'])
   return true;
   else
   return false;
}

/**
 * Compte le nombre d'articles différents dans le panier
 * @return int
 */
function compterArticles()
{
   if (isset($_SESSION['panier']))
   return count($_SESSION['panier']['libelleProduit']);
   else
   return 0;

}

?>