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
            <script type="text/javascript" src="script/jquery-1.10.2.js"></script>
    </head>

    <body >

        <h1>Préparez vous pour le combat !!!</h1>

        <img class="banniere" src="images/banniere.jpg">
        </br>
        </br>
        <?php
        
            if (isset($_POST['login']) && isset($_POST['mdp']) && $_POST['login'] != null && $_POST['mdp'] != null) {

            echo $trans;
            $mdp = hash('sha512', 'ohhljgcfhg' . $_POST['mdp']);

            $resultat = $bdd->prepare('SELECT id_user, login , passwd FROM user'); // WHERE isActivated = 1
            $resultat->execute() or die('Erreur bdd');
            
            $found = false;
            while ($donnee = $resultat->fetch()) {
                    if(($_POST['login'] == $donnee['login']) && ($mdp == $donnee['passwd'])){
                            $_SESSION['login'] = $_POST['login'];
                            $_SESSION['id'] = $donnee['id_user'];
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
                <td><p><a href="pages/addview.php">Ajouter prod.</a></p></td>
               
        </table>
        </FORM>

        </br>
        </br>

        <table class="table_affiche" id="produit">
                
        </table>

        <script>
            $("select").click(function()
            {

                tmp = $(this).val();
                alert(tmp);
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
             
             $("#form1").submit(function(form)
             {
                 alert("submit");
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