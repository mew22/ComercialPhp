<!DOCTYPE html>

<?php 
    session_start();
    include 'pages/PDO.php';
    ?>

<html>
    <head>
            <title>Projet PHP</title>
            <meta charset="utf-8" />
            <link rel="stylesheet" type = "text/css" href="css/style2.css" />
            <script type="text/javascript" src="script/jquery-1.10.2.js"></script> <!-- On utilise jquery -->
    </head>

    <body >

        <h1>Préparez vous pour le combat !!!</h1>

        <img class="banniere" src="images/banniere.jpg">
        </br>
        </br>
        <?php
        
                //  *** Si l'utilisateur souhaite se connecter ***
        
            if (isset($_POST['login']) && isset($_POST['mdp']) && $_POST['login'] != null && $_POST['mdp'] != null) {

            echo $trans;
            $mdp = hash('sha512', 'ohhljgcfhg' . $_POST['mdp']);

            $resultat = $bdd->prepare('SELECT id_user, login , passwd FROM user'); // WHERE isActivated = 1, mais la fonction mail() est désactivée
            $resultat->execute() or die('Erreur bdd');
            
            $found = false;
            while ($donnee = $resultat->fetch()) {              //Si on trouve une correspondance on le met dans la variable session
                    if(($_POST['login'] == $donnee['login']) && ($mdp == $donnee['passwd'])){
                            $_SESSION['login'] = $_POST['login'];
                            $_SESSION['id'] = $donnee['id_user'];
                            $found = true;
                            break;
                    }
            }

            if(!$found){    //SI on trouve pas de correspondance on envoi un message d'erreur qui sera traiter dans mennu.php
                    header('Location: ./index.php?error=Login%20ou%20MDP%20incorrect.');
                    exit();
            }
    }

    if (isset($_SESSION['login'])) {
            include('pages/menuco.php');
    }
    else{
            include('pages/menu.php');
    }
    ?>



        </br>

        <FORM id="form1">
        <table class="table_recherche">
            
                <td>
                        <!-- Un menu déroulant pour la categorie, et un input de recherche (independant) -->
                            <p>Categorie: 
                                <select id="selection"  name = "categorie" >
                             <?php 
                                $command= $bdd->prepare('SELECT * FROM categorie');
                                $command->execute() or die(print_r($command->errorInfo()));
                                while($donnees = $command->fetch())
                                {
                                    echo '<option id="' . $donnees['num_categorie'] . '">' . $donnees['libelle'] . '</option>';
                                }
                                ?>
                             </select>
                            </p>
                        
                </td>
                <td>
                        <input id ="recherche" type="text" name="recherche" size="50" value="Rechercher un article">
                </td>
                <td>
                        <input type="submit" value="ok">
                </td>
               
        </table>
        </FORM>

        </br>
        </br>
        
        <!-- L'endroit ou seront afficher tout les produit grace à AJAX -->
        <table class="table_affiche" id="produit"> 
                
        </table>

        <script>
            // Le script qui affiche dynamiquement les produits des categories (charge affiche_promotion.php/affiche_produit.php)
            $("select").click(function()
            {

                tmp = $(this).val();
                
                if($(this).val() == "promotion")
                {
                    $("#produit").load("pages/affiche_promotion.php", { 
                    promo: $(this).val()
                }); 
                }
                else{
                    $("#produit").load("pages/affiche_produit.php", { 
                    categorie: $(this).val()
                });
                }

             });
             
             // Le script qui affiche le resultat de la recherche de produit par mot cle (charge recherche_produit.php)
             $("#form1").submit(function(form)
             {
                 
                 form.preventDefault();
                 if($("#recherche").val() != "" && $("#recherche").val() != "Rechercher un article")
                 {
                     $("#produit").load("pages/recherche_produit.php", {
                         categorie: $("select").val(),
                         recherche: $("#recherche").val()
                     });
                 }
             });
        </script>
    </body>

</html>