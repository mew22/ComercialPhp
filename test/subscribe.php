<!DOCTYPE html>
<?php
	session_start();
        header( 'content-type: text/html; charset=utf-8' );
	$bdd = new PDO('mysql:dbname=projetiut_db;host=localhost', 'projetiut', 'zQ8EEBQw_Td_CmBCdA');
?>


<html>
	<head>
		<title>Projet Tuteur&eacute;</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="./style.css">
                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
capcha:
				if(!isset($_GET['login']) && !isset($_GET['cle']) || !isset($_POST['mail'])) {
                    echo '<div><form method="POST" action="./subscribe.php" id="form" name="form">
                        <label > Pseudo: </br><input type="text" name="pseudo" id="pseudo" class="tooltip"/><div id="div_pseudo" class="infobulle"></div></label>  </br></br>
                        <label > Mail: </br><input type="text" name="mail" id="mail" class="tooltip"/> <div id="div_mail" class="infobulle"></div></label> </br></br>
                        <label> Date de naissance: </br><input type="text" name="jour" id="jour" value="dd" style="height:30px; width:30px;"/> - <input type="text" name="mois" id="mois" value="mm" style="height:30px; width:30px;"/> - <input type="text" name="annee" id="annee" value="YYYY" style="height:30px; width:50px;" class="tooltip"/> <div id="div_date" class="infobulle"></div></label> </br></br>
                        <label > Mot de passe: </br><input type="password" name="mdp" id="mdp" class="tooltip"/> <div id="div_mdp" class="infobulle"></div></label> </br></br> 
                        <label >Retapez votre mot de passe: </br><input type="password" name="verif" id="verif" class="tooltip"/> <div id="div_mdpverif" class="infobulle"></div></label> </br> </br>';
					
					require_once('recaptchalib.php'); 
					$publickey = "6LfSZOsSAAAAAMroyanFeXVrubCIj_gKtev-O8ri"; // Utiliser la clé que vous avez eu lors de l'inscription sur recaptcha.net
					echo recaptcha_get_html($publickey); // Affiche le captcha
					
						if(isset($_GET['cap']) && $_GET['cap']=="0")
						echo 'Oups, le captcha antispam n\'est pas valide. Veuillez recommencer';
					
					echo '</br></br>
			<input type="submit" value="Envoyer" id="sub" class="tooltip"/> <div id="div_sub"></div>';

						
					echo '</form></div>';
				}

	            if(!isset($_GET['actif']) || $_GET['actif'] != 1) {

					if (isset($_POST['pseudo']) && isset($_POST['mdp']) && $_POST['pseudo'] != "" && $_POST['mdp'] != "" && ($_POST['mdp'] == $_POST['verif']) && isset($_POST['jour']) && isset($_POST['mois']) && isset($_POST['annee']) && isset($_POST["recaptcha_challenge_field"]) && isset($_POST["recaptcha_response_field"])) 
					{
						$privatekey = "6LfSZOsSAAAAAK32aVT-xc41kSz8WthHiinVQqPK"; // Utiliser la clé privée qui est donnée sur votre compte recaptcha.net
						$resp = recaptcha_check_answer ($privatekey,
														$_SERVER["REMOTE_ADDR"],
														$_POST["recaptcha_challenge_field"],
														$_POST["recaptcha_response_field"]);

						if ($resp->is_valid) 
						{ 
						
							$cle = md5(microtime(TRUE)*100000);
												
							$mdp = hash('sha512', 'AzrHGlnfrf%%ù$fhfeoerHUmkg' . $_POST['mdp']);
								$concat = $_POST['annee'] . '-' . $_POST['mois'] . '-' . $_POST['jour'];
								$anniv =date('Y-m-d', strtotime($concat));
								
							$statement = $bdd->prepare('INSERT INTO Player (name, 
								passwd, 
								mail,
															dateAnniv,
								isActivated, 
								cle, 
								lv, 
								experience, 
								totalExperience, 
								gold, 
								lifePoints, 
								totalLifePoints,
								actionPoints, 
								strenght, 
								vitality, 
								dexterity) 
								VALUES (:pseudo,
									:mdp,
									:mail,
																	:anniv,
									0,
									:cle,
									0,
									0,
									0,
									0,
									0,
									0,
									0,
									0,
									0,
									0)');
							$statement->bindParam(':pseudo' , $_POST['pseudo']);
							$statement->bindParam(':mdp' , $mdp);
							$statement->bindParam(':mail' , $_POST['mail']);
													$statement->bindParam(':anniv', $anniv);
							$statement->bindParam(':cle' , $cle);
							$statement->execute() or die('Erreur SQL !');

							$mail = $_POST['mail'];
							$titre = "Activation de votre compte HolyStones";
							$message = $message = "Bienvenue sur HolyStones,

								Pour activer votre compte, veuillez cliquer sur le lien ci dessous
								ou copier/coller dans votre navigateur internet.

								http://dutinfo.general-changelog-team.fr/subscribe.php?login=".urlencode($_POST['pseudo'])."&cle=".urlencode($cle)."

								---------------
								Ceci est un mail automatique, Merci de ne pas y r�pondre.";
							$expediteur = "subscribe@dutinfo.general-changelog-team.fr";

							mail($mail, $titre, $message, $expediteur);
											
							echo 'Un mail de confirmation vous a été envoyé sur l\'adresse email saisie';

						}
						else 
						{
							header("Location: ./subscribe.php?cap=0");
						}
					}
					else if ($_POST['mdp'] != $_POST['verif'])
					{
						echo ' Les mots de passes ne correspondent pas.';
					}
						
	        	}

				if (isset($_GET['login']) && isset($_GET['cle']) && $_GET['login'] != "" && $_GET['cle'] != "") {
					
	                $up = $bdd->prepare('UPDATE Player SET isActivated = 1 WHERE cle = :clee');
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

    <script script type="text/javascript">

                
   $(document).ready(function(){


    $("#mail").blur(function()
            {
                var regex = /^[a-zA-Z0-9._-]{1,40}@[a-z0-9._-]{2,20}\.[a-z]{2,4}$/;
                verif_mail_syntaxe = false;
                if(!regex.test($("#mail").val()))
                {

                   verif_mail_syntaxe = false;
        
                }
                else
                {

                   verif_mail_syntaxe = true;
                }
                
                var req = $.ajax({
                url:'ressource.php',
                type : 'POST',
                data : {type: "mail", email: $("#mail").val()},
                dataType : 'xml'
            });
            
            req.done(function(rep){
                
           $(rep).find("racine").each(function(){
               //alert($(this).text());
               m = ($(this).text());
               if(m == "true" && verif_mail_syntaxe == true)
               {
                    $('#mail').css("color", "red");
                    montre('Cette adresse email existe déjà !', 'div_mail');    // document.getElementById('div_mail').style.visibility="visible";
                        //document.getElementById('div_mail').innerHTML='Cette adresse email existe déjà !';
               }
               else if(m != "true" && verif_mail_syntaxe == true)
               {
                    $('#mail').css("color", "#979797");
                    cache('div_mail');        //document.getElementById('div_mail').style.visibility="hidden";
               }
               else if( m != "true" && verif_mail_syntaxe == false)
               {
                    $('#mail').css("color", "red");
                    montre('Utiliser une syntaxe du type stones@nomdomaine.com', 'div_mail');        //document.getElementById('div_mail').style.visibility="visible";
                        //document.getElementById('div_mail').innerHTML='Utiliser une syntaxe du type stones@nomdomaine.com';
               }
               else if(m == "true" && verif_mail_syntaxe == false)
               {
                   $('#mail').css("color", "red");
                   montre('Cette adresse email existe déjà !</br>Utiliser une syntaxe du type stones@nomdomaine.com', 'div_mail');         //document.getElementById('div_mail').style.visibility="visible";
                        //document.getElementById('div_mail').innerHTML='Cette adresse email existe déjà !</br>Utiliser une syntaxe du type stones@nomdomaine.com';
               }
           });
           
           
                   
            });
    
    });
    
    
    $("#pseudo").blur(function()
            {
                verif_pseudo_syntaxe = false;
                var regex = /^[a-zA-Z0-9]{4,}$/;
                if($("#pseudo").val().length < 4 || $("#pseudo").val().length> 16 || !regex.test($("#pseudo").val()))
                {

                   verif_pseudo_syntaxe = false;
                }
                else
                {

                   verif_pseudo_syntaxe = true;
                }
                
                var req = $.ajax({
                url:'ressource.php',
                type : 'POST',
                data : {type: "pseudo", pseudo: $("#pseudo").val()},
                dataType : 'xml'
            });
            
            req.done(function(rep){
                
           $(rep).find("racine").each(function(){
               //alert($(this).text());
               p = $(this).text();
               
               //document.getElementById('div_pseudo').insertAdjacentHTML('afterEnd', 'Le pseudo existe deja');
               if(p == "true" && verif_pseudo_syntaxe== true)
               {
                    $('#pseudo').css("color", "red");
                    montre('Ce pseudo existe déjà !', 'div_pseudo');        //document.getElementById('div_pseudo').style.visibility="visible";
                        //document.getElementById('div_pseudo').innerHTML='Ce pseudo existe déjà !';
               }
               else if(p != "true" && verif_pseudo_syntaxe  == true)
               {
                    $('#pseudo').css("color", "#979797");
                    cache('div_pseudo');        //document.getElementById('div_pseudo').style.visibility="hidden";
               }
               else if( p != "true" && verif_pseudo_syntaxe  == false)
               {

               		$('#pseudo').css("color", "red");
                    montre('Votre pseudo doit faire entre 4 et 16 caractères et ne doit contenir que des chiffres et des lettres', 'div_pseudo');        //document.getElementById('div_pseudo').style.visibility="visible";
                        //document.getElementById('div_pseudo').innerHTML='Votre pseudo doit faire entre 4 et 16 caractères et ne doit contenir que des chiffres et des lettres';
               		//$('#pseudo').addClass('boxShadow');
                    
               }
               else if(p == "true" && verif_pseudo_syntaxe  == false)
               {
                   $('#pseudo').css("color", "red");
                   montre('Ce pseudo existe déjà !</br>Votre pseudo doit faire entre 4 et 16 caractères et ne doit contenir que des chiffres et des lettres', 'div_pseudo');     //document.getElementById('div_pseudo').style.visibility="visible";
                        //document.getElementById('div_pseudo').innerHTML='Ce pseudo existe déjà !</br>Votre pseudo doit faire entre 4 et 16 caractères et ne doit contenir que des chiffres et des lettres';
               }
           });
           
           
                   
            });
    
    });
    
    
    $("#jour").blur(function()
            {
                verif_day = false;
  
                if(isNaN($("#jour").val()) || $("#jour").val() < 1 || $("#jour").val() > 31)
                {

                   verif_day= false;
                }
                else
                {

                   verif_day = true;
                }
                
 
               if(verif_day == true && verif_month == true && verif_year == true)
               {
                  
                    cache('div_date'); document.getElementById('div_date').style.visibility="hidden";
               }
               if(verif_day == true)
               {
                   $('#jour').css("color", "#979797");
               }
               else if( verif_day == false)
               {
                    $('#jour').css("color", "red");
                     montre('Mettre un jour entre 1 et 31 ! ', 'div_date');       //document.getElementById('div_date').style.visibility="visible";
                            //document.getElementById('div_date').innerHTML='Mettre un jour entre 1 et 31 ! ';
               }
    
           });
           
            $("#mois").blur(function()
            {
                verif_month = false;
  
                if(isNaN($("#mois").val()) ||$("#mois").val() < 1 || $("#mois").val() > 12)
                {

                   verif_month = false;
                }
                else
                {

                   verif_month = true;
                }
                
 
               if(verif_day == true && verif_month == true && verif_year == true)
               {
                    
                    cache('div_date');  //document.getElementById('div_date').style.visibility="hidden";
               }
               if(verif_month == true)
               {
                   $('#mois').css("color", "#979797");
               }
               else if( verif_month == false)
               {
                    $('#mois').css("color", "red");
                   montre('Mettre un mois entre 1 et 12 ! ', 'div_date');         //document.getElementById('div_date').style.visibility="visible";
                        //document.getElementById('div_date').innerHTML='Mettre un mois entre 1 et 12 ! ';
               }
    
           });
           
               $("#annee").blur(function()
            {
                verif_year = false;
                d = new Date();
                y = d.getFullYear();
  
                if(isNaN($("#annee").val()) ||$("#annee").val() < 1900 || $("#annee").val()> y)
                {

                   verif_year= false;
                }
                else
                {

                   verif_year = true;
                }
                
 
               if(verif_day == true && verif_month == true && verif_year == true)
               {
 
                   cache('div_date');      //document.getElementById('div_date').style.visibility="hidden";
               }
               if(verif_year== true)
               {
                   $('#annee').css("color", "#979797");
               }
               else if( verif_year== false)
               {
                    $('#annee').css("color", "red");
                   montre('Mettre une année entre 1900 et ' + y + ' ! ', 'div_date');         //document.getElementById('div_date').style.visibility="visible";
                            //document.getElementById('div_date').innerHTML='Mettre une année entre 1900 et ' + y + ' ! ';
               }
    
           });
    
    $("#mdp").blur(function()
            {
                verif_mdp_syntaxe = false;
  
                if($("#mdp").val().length < 4 || $("#mdp").val().length> 16)
                {

                   verif_mdp_syntaxe = false;
                }
                else
                {

                   verif_mdp_syntaxe = true;
                }
                
                
              
               if(verif_mdp_syntaxe  == true)
               {
                    $('#mdp').css("color", "#979797");
                    cache('div_mdp');   //document.getElementById('div_mdp').style.visibility="hidden";
               }
               else if( verif_mdp_syntaxe  == false)
               {
                    $('#mdp').css("color", "red");
                    montre('Votre mot de passe doit contenir entre 4 et 16 caractères !','div_mdp');    //document.getElementById('div_mdp').style.visibility="visible";
                   //document.getElementById('div_mdp').innerHTML='Votre mot de passe doit contenir entre 4 et 16 caractères !';
               }

    
           });
           
           $("#verif").blur(function()
            {
                verif_mdp_syntaxe2 = false;
  
                if($("#mdp").val() != $("#verif").val())
                {

                   verif_mdp_syntaxe2 = false;
                }
                else
                {

                   verif_mdp_syntaxe2 = true;
                }
                
                
              
               if(verif_mdp_syntaxe2  == true)
               {
                    $('#verif').css("color", "#979797");
                    cache('div_mdpverif');//document.getElementById('div_mdpverif').style.visibility="hidden";
               }
               else if( verif_mdp_syntaxe2  == false)
               {
                    $('#verif').css("color", "red");
                    montre('Les mots de passes ne correspondent pas !','div_mdpverif');
                    //document.getElementById('div_mdpverif').style.visibility="visible";
                   //document.getElementById('div_mdpverif').innerHTML='Les mots de passes ne correspondent pas !';
               }
               
    
           });
           
           
                   


    $("#form").submit(function(form){
    
    
        if(m == "false" && p == "false" && verif_mail_syntaxe && verif_pseudo_syntaxe && verif_mdp_syntaxe && verif_mdp_syntaxe2 && verif_day && verif_month && verif_year)
        {
                cache('div_sub');       //document.getElementById('div_sub').style.visibility="hidden";
                document.forms["#form"].submit();

        }
        else
        {
            form.preventDefault();
            montre('Veuiller remplir tous les champs','div_sub');//document.getElementById('div_sub').innerHTML='Veuiller remplir tous les champs';
            //alert("Veuiller remplir tous les champs");
        }
        


    });

});
   
            
              
              function montre(text, id) 
              {
                        document.getElementById(id).style.visibility="visible";
                        document.getElementById(id).innerHTML = text;
                    
              }
              function cache(id) 
              {
                    
                        document.getElementById(id).style.visibility="hidden"; // Si la bulle est visible on la cache
                     
              }
              </script>
			  
			  <script type="text/javascript">
				var RecaptchaOptions={
				lang: 'fr',
				theme: 'custom'
				};
			  </script>
        
            
	</body>	
</html>