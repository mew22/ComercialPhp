<!DOCTYPE html>

<?php
	session_start();

	$balises = array('<br>','<br/>');
?>

<html>
	<head>
		<title>Projet Tuteur&eacute;</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="./style.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	</head>
	<body>
		
		<header>
			<h1>BANNIERE</h1>
		</header>

		<?php

			if (isset($_POST['login']) && isset($_POST['mdp']) && $_POST['login'] != null && $_POST['mdp'] != null) {
				$bdd = new PDO('mysql:dbname=projetiut_db;host=localhost', 'projetiut', 'zQ8EEBQw_Td_CmBCdA');
                                
                $mdp = hash('sha512', 'AzrHGlnfrf%%ù$fhfeoerHUmkg' . $_POST['mdp']);

				$resultat = $bdd->prepare('SELECT `id` ,`name` ,`passwd` ,`isActivated` FROM Player WHERE isActivated = 1');
				$resultat->execute() or die('Erreur bdd');
				$found = false;
				while ($donnee = $resultat->fetch(PDO::FETCH_ASSOC)) {
					if(($_POST['login'] == $donnee['name']) && ($mdp == $donnee['passwd'])){
						$_SESSION['login'] = $_POST['login'];
						$_SESSION['id'] = $donnee['id'];
						$found = true;
						break;
					}
				}
				if(!$found){
					header('Location: ./index.php?error=Login%20ou%20MDP%20incorrect.');
					exit();
				}
			}

			if (isset($_SESSION['login'])) {
				include('./menuco.php');
			}
			else{
				include('./menu.php');
			}
		?>

		<section>
			<article>
			</article>

			<aside>
			</aside>
		</section>

		<footer>
			<p></p>
		</footer>

		<script type="text/javascript">

			var foc = document.getElementById('focus1');
			alert("Coucou");
			foc.on("focus", function(){
				var style = {
					backgroundColor: "red";
				};
			});

		</script>

	</body>	
</html>