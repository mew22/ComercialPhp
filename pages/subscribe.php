<!DOCTYPE html>
<?php
	session_start();
        header( 'content-type: text/html; charset=utf-8' );
	include 'PDO.php';
?>


<html>
	<head>
		<title>Subscribe</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <link rel="stylesheet" type="text/css" href="../css/style2.css">
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
				if(!isset($_GET['login']) && !isset($_GET['cle']) || !isset($_POST['mail'])) {
                    echo '<div><form method="POST" action="./subscribe.php" id="form" name="form">
                        <label > Pseudo: </br><input type="text" name="pseudo" id="pseudo" /></label > </br></br>
                        <label > Mail: </br><input type="text" name="mail" id="mail"/></label > </br></br>
                        <label> Adresse de facturation: </br><input type="text" name="adresse" id="adresse" /></label ></br></br>
                        <label> Nom: </br><input type="text" name="nom" id="nom" /></label ></br></br>
                        <label> Prénom: </br><input type="text" name="prenom" id="prenom" /></label ></br></br>
                        <label > Mot de passe: </br><input type="password" name="mdp" id="mdp" /></label ></br></br> 
                        <label >Retapez votre mot de passe: </br><input type="password" name="verif" id="verif" /> </label ></br> </br>';
					
					
                    echo '</br></br>
			<input type="submit" value="Envoyer" id="sub" /> ';

						
					echo '</form></div>';
				}

	            if(!isset($_GET['actif']) || $_GET['actif'] != 1) {

					if (isset($_POST['pseudo']) && isset($_POST['mdp']) && $_POST['pseudo'] != "" && $_POST['mdp'] != "" && ($_POST['mdp'] == $_POST['verif'])) 
					{
                                            if(isset($_POST['adresse']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']))
                			    {	
                                                $cle = md5(microtime(TRUE)*100000);
												
							$mdp = hash('sha512', 'ohhljgcfhg' . $_POST['mdp']);

								
							$statement = $bdd->prepare('INSERT INTO user (login, 
								passwd, 
								nom_user,
                                                                prenom_user,
                                                                adresse_user,
                                                                mail_user,
                                                                keys) 
								VALUES (:pseudo, :mdp, :nom, :prenom, :adresse, :mail, :keys)');
							$statement->bindParam(':pseudo' , $_POST['pseudo']);
							$statement->bindParam(':mdp' , $mdp);
                                                        $statement->bindParam(':nom', $_POST['nom']);
                                                        $statement->bindParam(':prenom', $_POST['prenom']);
                                                        $statement->bindParam(':adresse', $_POST['adresse']);
							$statement->bindParam(':mail' , $_POST['mail']);
							$statement->bindParam(':keys' , $cle);
							$statement->execute() or die(print_r($statement->errorInfo()));

							$mail = $_POST['mail'];
							$titre = "Activation de votre compte EpicWearpon";
							$message = $message = "Bienvenue sur EpicWearpon,

								Pour activer votre compte, veuillez cliquer sur le lien ci dessous
								ou copier/coller dans votre navigateur internet.

								https://etudiant.univ-mlv.fr/~sdelaher/pages/subscribe.php?login=".urlencode($_POST['pseudo'])."&cle=".urlencode($cle)."

								---------------
								Ceci est un mail automatique, Merci de ne pas y répondre.";
							$expediteur = "subscribe@etudiant.univ-mlv.fr";

							mail($mail, $titre, $message, $expediteur);
											
							echo 'Un mail de confirmation vous a été envoyé sur l\'adresse email saisie';

						}
			
					}
					else if ($_POST['mdp'] != $_POST['verif'])
					{
						echo ' Les mots de passes ne correspondent pas.';
					}
						
	        	}

				if (isset($_GET['login']) && isset($_GET['cle']) && $_GET['login'] != "" && $_GET['cle'] != "") {
					
	                $up = $bdd->prepare('UPDATE user SET isActivated = 1 WHERE keys = :clee');
	                $up->bindParam(':clee', $_GET['cle']);
	                $up->execute() or die('Erreur isActivated');
                    echo 'Félicitation votre compte à été activé !';
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