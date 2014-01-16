<!DOCTYPE html>

<?php 
    include 'pages/PDO.php';
    ?>

<html>
    <head>
            <title>Projet PHP</title>
            <meta charset="utf-8" />
            <link rel="stylesheet" type = "text/css" href="css/style2.css" />
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    </head>

    <body  style="background-color: #BFBDBD;">

        <h1>Préparer vous pour le combat !!!</h1>

        <img class="banniere" src="images/banniere.jpg">
        </br>
        </br>


        <table class="table_menu">	
                <td class="td_menu">Mes commandes passées</td>
                <td class="td_menu">Nouveau ? Inscrivez vous !</td>
                <td class="td_panier"><a href="pages/ta_page" ><img src="images/panier.jpg" class="panier"/></a></td>
        </table>

        </br>

        <FORM id="form">
        <table class="table_recherche">
            
                <td>
                        
                            <p>Categorie: 
                             <select name = "categorie" id = "select">
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
                        <input id ="test" type="text" name="recherche" size="50" value="Rechercher un article">
                </td>
                <td>
                        <input type="submit" value="ok">
                </td>
               
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
                $("#produit").load("pages/affiche_produit.php", { // N'oubliez pas l'ouverture des accolades !
                    categorie: $(this).val()
                });
             });
        </script>
    </body>

</html>