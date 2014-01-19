<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        include 'PDO.php';
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
                  for ($i=0 ;$i < $nbArticles; $i++)
                    {
                   
                       $text = $text .htmlspecialchars($_SESSION['panier']['libelleProduit'][$i]);
                       $text = $text . ' ' .htmlspecialchars($_SESSION['panier']['qteProduit'][$i]);
                       $text = $text . 'x'.htmlspecialchars($_SESSION['panier']['prixProduit'][$i]);
                       $text = $text . '; '; 
                    }
                    $date = date("YmdH");
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

     
                echo 'Un mail de confirmation vous a été envoyer pour la confirmation et le récapitulatif de la commande';
                echo 'Merci d\'avoir commander chez EpicWearpon';
}
        ?>
    </body>
</html>
