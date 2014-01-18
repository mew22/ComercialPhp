<?php

include 'PDO.php';

/**
 * BaseDeDonnee = la liste des produits dans la bdd
 *
 * @author seb
 */
class BaseDeDonnee {
   private $_base;
   
   public function __BaseDeDonnee($bdd)
   {
       $query = $bdd->prepare('SELECT * FROM produit');
       $query->execute() or die('Erreur sql');
       $this->_base = $query;
   }
   public function getBase()
   {
       return $this->_base;
   }
}
