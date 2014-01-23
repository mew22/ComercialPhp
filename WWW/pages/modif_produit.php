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
        session_start();
        include 'PDO.php';
        
        //Choisir un produit dans la liste déroulante
        if(!isset($_POST['selection']))
        {
            echo 'Choisissez un produit à éditer parmis ceux la';
            echo'<form action="modif_produit.php" method="post">';
             echo '<p>Produit: 
                     <select name = "selection" >';
                     
                        $command= $bdd->prepare('SELECT * FROM produit');
                        $command->execute() or die(print_r($command->errorInfo()));
                        while($donnees = $command->fetch())
                        {
                            echo '<option>' . $donnees['nom_produit'] . '</option>';
                        }
             echo'</select></p></br>';
             echo '<input type="submit" value="choisir"/></form>';
        }
        
        //  Une fois le produit choisi, remplir les champs voulus
        else if (isset($_POST['selection']) && !isset($_POST['nom']) && !isset($_POST['prix']) && !isset($_POST['qte']) && !isset($_POST['promo']) && !isset($_POST['categorie'])) {
            echo 'Les valeurs dans la tables sont par défaut, veuillez renseigner la promo par 0 ou 1, ne pas rentrer une categorie inéxistante MrAdmin';
            echo'<form action="modif_produit.php" method="post">';
            echo'<table><tr><th>Nom</th><th>Prix</th><th>Quantite</th><th>Promo</th><th>Categorie</th></tr>';
                
                $cmd = $bdd->prepare('SELECT * FROM produit WHERE nom_produit=:n');
                $cmd->bindParam(':n', $_POST['selection']);
                $cmd->execute() or die('Erreur modif p1');
                $tmp = $cmd->fetch();
                echo'<tr>'
                        . '<td><input type="text" name="nom" value="'.$tmp['nom_produit'].'"/></td>' 
                        . '<td><input type="text" name="prix" value="' . $tmp['prix'] .'"/></td>'
                        . '<td><input type="text" name="qte" value="' . $tmp['quantite_produit'] . '"/></td>'
                        . '<td><input type="text" name="promo" value="' . $tmp['promo'] . '"/></td>'
                        . '<td><input type="text" name="categorie" value="' . $tmp['num_categorie'] . '"/></td>'
                        . '<input type="hidden" name="selection" value="' . $_POST['selection'].'"/>'
                        . '<td><input type="submit" value="valider"/></td>';
                echo'</tr>';
                
            
            echo'</table>';
            echo'</form>';
        }
        // Mettre dans la bdd les modification
        else if (isset($_POST['nom']) || isset($_POST['prix']) || isset($_POST['qte']) || isset($_POST['promo']) || isset($_POST['categorie'])) {
            
            $cmd2 = $bdd->prepare('UPDATE produit SET nom_produit=:n, prix=:p, quantite_produit=:q, promo=:pro, num_categorie=:c WHERE nom_produit =:np');
            $cmd2->bindParam(':n', $_POST['nom']);
            $cmd2->bindParam(':p', $_POST['prix']);
            $cmd2->bindParam(':q', $_POST['qte']);
            $cmd2->bindParam(':pro', $_POST['promo']);
            $cmd2->bindParam(':c', $_POST['categorie']);
            $cmd2->bindParam(':np', $_POST['selection']);
            
            $cmd2->execute() or die('erreur modif prod');
            echo'Les changements ont été pris en comptes';

        }    
?>
    </body>
</html>
