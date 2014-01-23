<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
        <?php
   
         include 'PDO.php';
 
     
?>
        <div>
            <form   action="ajout_produit.php" method="post" enctype="multipart/form-data">
		<p>Nom:
			<input type="text" name="name" /></p>
		<p>Prix:
                    <input type="text" name="prix" /></p>
                <p>Quantité:
			<input type="text" name="quantite" /></p>
                <p>Promo:
                    <input type="radio" name="promo" value="1" id="oui" checked="checked" /> <label for="oui">Oui</label>
                    <input type="radio" name="promo" value="0" id="non" /> <label for="non">Non</label></p></br>
                <input type="hidden" name="MAX_FILE_SIZE" value="6000000"/>
                <p>Fichier:
                    <input type="file" name="fichier"/></p></br>
            
                 <p>Categorie: 
                     <select name = "categorie">
                     <?php 
                        $command= $bdd->prepare('SELECT * FROM categorie');
                        $command->execute() or die(print_r($command->errorInfo()));
                        while($donnees = $command->fetch())
                        {
                            echo '<option value="' . $donnees['num_categorie'] . '">' . $donnees['libelle'] . '</option>';
                        }
                        ?>
                     </select></p></br>
                 
                     
                     <input type="submit" value="Envoyer"/>


                </form>

        </div>
       
  
    </body>
</html>
