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
        $query = $bdd->prepare('SELECT * FROM commande WHERE id_user = :id');
        $query->bindParam(':id', $_SESSION['id']);
        $query->execute() or die('Erreur historique commandes');
        
        echo '<ul>';
        while($donnees = $query->fetch())
        {
            echo '<li> le ' . $donnees['date_commande'] . ' :' . $donnees['libelle_commande'] . '</li>';
        }
        echo '</ul>';
        ?>
    </body>
</html>
