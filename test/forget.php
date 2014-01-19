<!DOCTYPE html>

<?php
	session_start();
        header( 'content-type: text/html; charset=utf-8' );
	$bdd = new PDO('mysql:dbname=projetiut_db;host=localhost', 'projetiut', 'zQ8EEBQw_Td_CmBCdA');
        
        function random($car) 
        {
            $string = "";
            $chaine = "abcdefghijklmnpqrstuvwxy";
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
        <link rel="stylesheet" type="text/css" href="./style.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
                               <label > Mail: </br><input type="text" name="mail" id="mail" class="tooltip"/> <div id="div_mail" class="infobulle"></div></label> </br></br>
                               <label > Retaper votre email: </br><input type="text" name="mail_verif" id="mail_verif" class="tooltip"/> <div id="div_mail_verif" class="infobulle"></div></label> </br></br>';


                                              require_once('recaptchalib.php'); 
                                               $publickey = "6LfSZOsSAAAAAMroyanFeXVrubCIj_gKtev-O8ri"; // Utiliser la clé que vous avez eu lors de l'inscription sur recaptcha.net
                                               echo recaptcha_get_html($publickey); // Affiche le captcha
                                               
                                               if(isset($_GET['cap']) && $_GET['cap']=="0")
                                                       echo 'Oups, le captcha antispam n\'est pas valide. Veuillez recommencer';
                                               
                                               echo '</br></br>';

                                               

                               echo '<input type="submit" value="Envoyer" id="sub" class="tooltip"/> <div id="div_sub"></div>              
                               </form></div>';
            }
            
            if(isset($_POST['mail']) && isset($_POST['mail_verif']) && $_POST['mail'] == $_POST['mail_verif'])
            {
                $privatekey = "6LfSZOsSAAAAAK32aVT-xc41kSz8WthHiinVQqPK"; // Utiliser la clé privée qui est donnée sur votre compte recaptcha.net
		$resp = recaptcha_check_answer ($privatekey,
						$_SERVER["REMOTE_ADDR"],
						$_POST["recaptcha_challenge_field"],
                                                $_POST["recaptcha_response_field"]);

		if ($resp->is_valid) 
		{ 
                    $req = $bdd->prepare('SELECT cle, name FROM Player WHERE mail=:mail');
                    $req->bindParam(':mail', $_POST['mail']);
                    $req->execute() or die('ERREUR SQL'); 
                    
                    if(($donnee = $req->fetch())==false)
                    {
                        header("Location: ./forget.php?error_mail=0");
                    }
                    $new_mdp = random(10);
                    
                    
                    $mail = $_POST['mail'];
                    $titre = "Redéfinition de votre compte HolyStones";
		    $message = "Bienvenue sur HolyStones,
                     
                     Votre pseudo : " .$donnee['name'] . ".
		     Voici votre nouveau mot de passe : " . $new_mdp . ".
                     Pour activer ce nouveau mot de passe veuillez cliquer sur le lien ci-dessous ou le copier/coller dans un navigateur.

		     http://dutinfo.general-changelog-team.fr/forget.php?cle=" . urlencode($donnee['cle']) . "&new_mdp=" . urldecode($new_mdp) . "

		     ---------------
		     Ceci est un mail automatique, Merci de ne pas y répondre.";
		     $expediteur = "subscribe@dutinfo.general-changelog-team.fr";

		     mail($mail, $titre, $message, $expediteur);
											
		     echo 'Un mail de redéfinition vous a été envoyé sur l\'adresse email saisie';
                }
                else 
		{					
                    header("Location: ./forget.php?cap=0");
		}
                
                
            }
           
            
            if(isset($_GET['cle']) && isset($_GET['new_mdp']) && $_GET['new_mdp']!="")
            {
                $mdp2 = hash('sha512', 'AzrHGlnfrf%%ù$fhfeoerHUmkg' . $_GET['new_mdp']);        // ou mettre directement $new_mdp pour plus de sécurité
                
                $up = $bdd->prepare('UPDATE Player SET passwd = :mdp WHERE cle = :clee');
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
                    $('#mail').css("color", "#979797");
                    cache('div_mail');        //document.getElementById('div_mail').style.visibility="hidden";
                        
               }
               else if(m != "true" && verif_mail_syntaxe == true)
               {
                    $('#mail').css("color", "red");
                    montre('Cette adresse email n\'existe pas !', 'div_mail');    // document.getElementById('div_mail').style.visibility="visible";
               }
               else if( m != "true" && verif_mail_syntaxe == false)
               {
                    $('#mail').css("color", "red");
                    montre('Cette adresse email n\'existe pas !</br> Utiliser une syntaxe du type stones@nomdomaine.com', 'div_mail');        //document.getElementById('div_mail').style.visibility="visible";
                        //document.getElementById('div_mail').innerHTML='Utiliser une syntaxe du type stones@nomdomaine.com';
               }
               else if(m == "true" && verif_mail_syntaxe == false)
               {
                   $('#mail').css("color", "red");
                   montre('Utiliser une syntaxe du type stones@nomdomaine.com', 'div_mail');         //document.getElementById('div_mail').style.visibility="visible";
                        //document.getElementById('div_mail').innerHTML='Cette adresse email existe déjà !</br>Utiliser une syntaxe du type stones@nomdomaine.com';
               }
           });
           
           
                   
            });
    
    });
    
               $("#mail_verif").blur(function()
            {
                verif_mail_syntaxe2 = false;
  
                if($("#mail").val() != $("#mail_verif").val())
                {

                   verif_mail_syntaxe2 = false;
                }
                else
                {

                   verif_mail_syntaxe2 = true;
                }
                
                
              
               if(verif_mail_syntaxe2  == true)
               {
                    $('#mail_verif').css("color", "#979797");
                    cache('div_mail_verif');//document.getElementById('div_mdpverif').style.visibility="hidden";
               }
               else if( verif_mail_syntaxe2  == false)
               {
                    $('#mail_verif').css("color", "red");
                    montre('Les adresses ne correspondent pas !','div_mail_verif');
                    //document.getElementById('div_mdpverif').style.visibility="visible";
                   //document.getElementById('div_mdpverif').innerHTML='Les mots de passes ne correspondent pas !';
               }
               
    
           });
           
           
                   


    $("#form").submit(function(form){
    
    
        if(m == "true" && verif_mail_syntaxe && verif_mail_syntaxe2)
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
