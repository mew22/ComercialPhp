<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
         include 'PDO.php';
        
        echo 'Voici l\'historique de vos commande : ';
        $query = $bdd->prepare('SELECT * FROM commande');
        $query->execute() or die('Erreur historique commandes view');
        
        echo '<ul>';
        while($donnees = $query->fetch())
        {
            echo '<li> N°Commande :'.$donnees['id_commande'] . ' N°Client :'.$donnees['id_user'] .' le ' . $donnees['date_commande'] . ' :' . $donnees['libelle_commande'] . '</li>';
        }
        echo '</ul>';
        ?>
    </body>
</html>
