<!DOCTYPE html>

<?php   // Initile car mail() est désactivée

	session_start();
        header( 'content-type: text/html; charset=utf-8' );
	include 'PDO.php';
        
        function random($car) 
        {
            $string = "";
            $chaine = "abcdefghijklmnpqrstuvwxyz";
            srand((double)microtime()*1000000);
            for($i=0; $i<$car; $i++) 
            {
                 $string .= $chaine[rand()%strlen($chaine)];
            }
            return $string;
        }
?>

<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="../css/style2.css">
        <title></title>
    </head>
    <body>
		<header>
			<h1>BANIERE</h1>
		</header>
		
		<?php
			if (isset($_SESSION['login'])) {
				include('./menuco.php');
			}
			else {
				include('./menu.php');
			}
		?>

		<section>
			<article>
        
        <?php
            if(!isset($_POST['mail']) || !isset($_POST['mail_verif']) || (!isset($_GET['new_mdp']) && !isset($_GET['cle'])))
            {
                echo '<div>'; 
                if(isset($_GET['mail_error']) && $_GET['mail_error']=="0")
                   echo 'Toi petit malin qui n\'utilise pas javascript, l\'adresse email saisie n\'existe pas ! </br>';
                echo '
                    <form method="POST" action="./forget.php" id="form" name="form">
                                <p>Un mail de redéfinition contenant votre login et votre nouveau mot de passe vous sera envoyé.</p></br>
                               <label > Mail: </br><input type="text" name="mail" id="mail" /> </label> </br></br>
                               <label > Retaper votre email: </br><input type="text" name="mail_verif" id="mail_verif" /> </label> </br></br>';
                 echo '</br></br>';
                 echo '<input type="submit" value="Envoyer" id="sub" class="tooltip"/> <div id="div_sub"></div>              
                      </form></div>';
            }
            
            if(isset($_POST['mail']) && isset($_POST['mail_verif']) && $_POST['mail'] == $_POST['mail_verif'])
            {

                    $req = $bdd->prepare('SELECT k, login FROM user WHERE mail_user=:mail');
                    $req->bindParam(':mail', $_POST['mail']);
                    $req->execute() or die('ERREUR SQL'); 
                    
                    if(($donnee = $req->fetch())==false)
                    {
                        header("Location: ./forget.php?error_mail=0");
                    }
                    $new_mdp = random(10);
                    
                    
                    $mail = $_POST['mail'];
                    $titre = "Redéfinition de votre compte EpicWearpon";
		    $message = "Bienvenue sur EpicWearpon,
                     
                     Votre pseudo : " .$donnee['name'] . ".
		     Voici votre nouveau mot de passe : " . $new_mdp . ".
                     Pour activer ce nouveau mot de passe veuillez cliquer sur le lien ci-dessous ou le copier/coller dans un navigateur.

		     https://etudiant.univ-mlv.fr/~sdelaher/pages/forget.php?cle=" . urlencode($donnee['cle']) . "&new_mdp=" . urldecode($new_mdp) . "

		     ---------------
		     Ceci est un mail automatique, Merci de ne pas y répondre.";
		     $expediteur = "subscribe@dutinfo.general-changelog-team.fr";

		     mail($mail, $titre, $message, $expediteur);
											
		     echo 'Un mail de redéfinition vous a été envoyé sur l\'adresse email saisie';
                }
                else if ($_POST['mail'] != $_POST['mail_verif']) {
                    echo 'Les mails ne correspondent pas';
                }
  
            if(isset($_GET['cle']) && isset($_GET['new_mdp']) && $_GET['new_mdp']!="")
            {
                $mdp2 = hash('sha512', 'ohhljgcfhg' . $_GET['new_mdp']);        // ou mettre directement $new_mdp pour plus de sécurité
                
                $up = $bdd->prepare('UPDATE user SET passwd = :mdp WHERE k = :clee');
                $up->bindParam(':clee', $_GET['cle']);
                $up->bindParam(':mdp', $mdp2);
                $up->execute() or die('Erreur up mdp');
                echo 'Votre mot de passe a été changé !';
            }
            
        ?>
                            
            </article>

		<aside>
                </aside>
		
            </section>

		<footer>
			<p></p>
		</footer>
        
        
    </body>
</html>
