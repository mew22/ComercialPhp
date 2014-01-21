<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        session_start();
        include 'PDO.php';
        include_once 'gestionPanier.php';
            if(!isset($_SESSION['id']))
            {
                echo 'Il faut etre connecté pour passer une commande';
            }
            else {
        //TODO : Mettre la commande du panier dans la bdd avec l'id de l'utilisateur
        //TODO : recuperer l'email de l'utilisateur
        //TODO : Envoyer le mail
                 $nbArticles=  count($_SESSION['panier']['libelleProduit']);
                 $text;
                 
                $query = $bdd->prepare('SELECT quantite_produit, nom_produit FROM produit ');
                $query->bindParam(':nom',$libelleProduit);
                $query->execute() or die('erreur verif quantite');
                
                
                  for ($i=0 ;$i < $nbArticles; $i++)
                    {
                   
                       $text = $text .htmlspecialchars($_SESSION['panier']['libelleProduit'][$i]);
                       /* while($quantite_max = $query->fetch())
                        {
                            if($_SESSION['panier']['libelleProduit'][$i] == $quantite_max['nom_produit'])
                                 if($quantite_max['quantite_produit']>=htmlspecialchars($_SESSION['panier']['qteProduit'][$i]))
                                    $text = $text . ' ' .htmlspecialchars($_SESSION['panier']['qteProduit'][$i]);
                                 else $text = $text . ' ' . $quantite_max['quantite_produit'];

                        }*/
                       $text = $text . ' ' .htmlspecialchars($_SESSION['panier']['qteProduit'][$i]);
                       $text = $text . 'x'.htmlspecialchars($_SESSION['panier']['prixProduit'][$i]);
                       $text = $text . '; '; 
                    }
           
                    $query = $bdd->prepare('INSERT INTO commande (id_user, libelle_commande)'
                            . 'VALUES (:id, :libelle)');
                    $query->bindParam(':id', $_SESSION['id']);
                    $query->bindParam(':libelle',$text);
                    $query->execute() or die('Erreur insert command');
                    
                    $query2 = $bdd->prepare('SELECT mail_user FROM user WHERE id_user=:i');
                    $query2->bindParam(':i', $_SESSION['id']);
                    $query2->execute() or die('Erreur recup mail');
                    
                    $query3 = $bdd->prepare('SELECT id_commande FROM commande WHERE id_user=:i AND libelle_commande=:libelle');
                    $query3->bindParam(':i', $_SESSION['id']);
                    $query3->bindParam(':libelle', $text);
                    $query3->execute() or die('Erreur recup id_commande');
                    
                    $mail = $query2->fetch();
                    $mail = $mail['mail_user'];
                    $tmp = $query3->fetch();
                    $tmp = $tmp['id_commande'];
                    $titre = "Votre commande n°" . $tmp;
                    $message = "Merci d'avoir choisis EpicWearpon,

                            Voici le récapitulatif de votre commande :  
                             " . $text . "

                            ---------------
                            Ceci est un mail automatique, Merci de ne pas y répondre.";
                    $expediteur = "commande@etudiant.univ-mlv.fr";

                    mail($mail, $titre, $message, $expediteur);

     
                echo 'Un mail de confirmation vous a été envoyer pour la confirmation et le récapitulatif de la commande</br>';
                echo 'Merci d\'avoir commander chez EpicWearpon';
                
                for ($i=0 ;$i < $nbArticles; $i++)
                {
                    $query4 = $bdd->prepare('SELECT quantite_produit FROM produit WHERE nom_produit=:nom');
                    $query4->bindParam(':nom', $_SESSION['panier']['libelleProduit'][$i]);
                    $query4->execute() or die('Erreur4');
                    $q = $query4->fetch();
                    $final = $q['quantite_produit'] - $_SESSION['panier']['qteProduit'][$i];
                    
                    $query5 = $bdd->prepare('UPDATE produit SET quantite_produit =:qte WHERE nom_produit =:nom');
                    $query5->bindParam(':nom', $_SESSION['panier']['libelleProduit'][$i]);
                    $query5->bindParam(':qte', $final);
                    $query5->execute() or die('Erreur 5');
                    
                }
                supprimePanier();
    }
        ?>
    </body>     
</html>
