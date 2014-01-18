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

    <body>

        <h1>Préparer vous pour le combat !!!</h1>

        <img class="banniere" src="images/banniere.jpg">
        </br>
        </br>


        <table class="table_menu">	
                <td class="td_menu">Mes commandes passées</td>
                <td class="td_menu">Nouveau ? Inscrivez vous !</td>
                <td class="td_panier"><a href="pages/panier.php?action=affiche" onclick="window.open(this.href, \'\', 
                    \'toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350\'); return false;"><img src="images/panier.jpg" alt="Cliquer pour ajouter au panier" class="panier"/></a></td>
        </table>

        </br>

        <FORM id="form">
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
                $("#produit").load("pages/affiche_produit.php", { 
                    categorie: $(this).val()
                });
             });
             
             $("form").submit(function(e)
             {
                 alert("submit");
                 if($("#recherche").val() != "")
                 {
                     $("#produit").load("pages/recherche_produit.php", {
                         categorie: $("select").val(),
                         recherche: $("#recherche").val()
                     });
                 }
                 else
                 {
                     e.preventDefault();
                 }
             });
        </script>
    </body>

</html>