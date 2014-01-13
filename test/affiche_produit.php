<?php

        include 'gestionPanier.php';
?>
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
        <div>
            <a href="index.php">Cliquer ici pour retourner au formulaire</a>
            </br>
            </br>
        </div>
        <?php

            function deletePattern($pattern, $string)
            {
                $str='';
                
                $verif;
                $verif[0]=false;
                $verif[1]=false;
                
                for($i=0;$i<strlen($string);$i++)
                {

                     if($string[$i]!=$pattern[0] && $string[$i]!=$pattern[1])
                           $str=$str . $string[$i];
                       
                     if($string[$i]==$pattern[0])
                           $verif[0]=true;
                       
                     if($string[$i]==$pattern[1])
                           $verif[1]=true;
                       
                       if($verif[0]==true && $verif[1]==true)
                           break;
                     
                }
                
                for($i++;$i<strlen($string);$i++)
                {
                    $str = $str . $string[$i];
                }
                return $str;
            }
        ?>
      
        
        <?php
        
                include 'PDO.php';
                
                
                $panier = "images/panier2.png";
                            
                            if(!file_exists("images"))
                            {
                                mkdir("images");
                            }
                            if(!file_exists("images/panier2.png"))
                            {
                                echo "No image for basket";
                            }
                echo '<p>Votre panier : <a href="panier.php?action=affiche;" onclick="window.open(this.href, \'\', 
                                                \'toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350\'); return false;"><img src="' . $panier . '"; alt="Cliquer pour ajouter au panier"; style=" display:block; margin:0 auto;"/></a></p>';
        
                if(isset($_GET['affiche']))
                {
                    affichePanier();
                }
         $command= $bdd->prepare('SELECT * FROM produit');
                        $command->execute() or die(print_r($command->errorInfo()));
                        while($donnees = $command->fetch())
                        {
                            $tmp = $donnees['photo'];
                            $img = deletePattern('1-', $tmp);
                            $promo = $donnees['promo'];

                            
                            
                           echo'
                                    <table border="1px;">
                               <tr>
                                   <th>Numero</th>
                                   <td>' . $donnees['num_produit'] . '</td>
                               </tr>
                               <tr>
                                   <th>Nom</th>
                                   <td>' . $donnees['nom_produit'] . '</td>
                               </tr>
                               <tr>
                                   <th>Prix</th>
                                   <td>' . $donnees['prix'] .'</td>
                               </tr>
                               <tr>
                                   <th>Image</th>';
                                   if($promo)
                                   {
                                       list($width, $height) = getimagesize($tmp);
                                        
                                       
                                       $ressource = imagecreatefromjpeg ($tmp);
                                       imagecopyresampled ( $ressource , $ressource , 0 , 0 , 0 , 0 , $width , $height , $width , $height ) or die('ERROR : resampled');
                                        imagestring ( $ressource , 4 , 0 , 0 , 'PROMO !!' , imagecolorallocate ( $ressource , 0 , 0 , 0 ) );
                                        imagejpeg ( $ressource, $tmp, 100) or die('ERROR : jpeg');
                                        
                                        
                                       echo '
                                                <td style="width:200px; height:100px;"><a href="' . $img . '"><img src="' . $tmp . '"; alt="Cliquer pour agrandir"; style=" display:block; margin:0 auto";/></a></td>';
                                   }
                                       else echo '<td style="width:200px; height:100px;"><a href="' . $img . '"><img src="' . $tmp . '"; alt="Cliquer pour agrandir"; style=" display:block; margin:0 auto";/></a></td>';   
                                    
                                    echo '
                                    </tr>   
                               <tr>
                                   <th>Promotion</th>';
                                   if($promo)
                                       echo '<td>Oui</td>';
                                   else echo '<td>Non</td>';
                                   
                                   echo '
                               </tr>
                               <tr>
                                   <th>Catégorie</th>';
                                   $command2=$bdd->prepare('SELECT libelle FROM categorie WHERE num_categorie=:categorie');
                                   $command2->bindParam(':categorie', $donnees['num_categorie']);
                                   $command2->execute() or die(print_r($command2->errorInfo()));
                                   
                                   $categorie = $command2->fetch();
                                   echo'
                                   <td>' . $categorie['libelle'] .'</td>
                               </tr>
                               <tr>
                                    <th>Panier</th>';
                                   if(!isset($_GET["id"]) || $_GET["id"] != $donnees["num_produit"]) {
                                        echo '<td style="width:200px; height:100px;"><a href="panier.php?action=ajout&amp;l=' . $donnees['nom_produit'] . '&amp;p=' . $donnees['prix'] . '" onclick="window.open(this.href, \'\', 
                                                \'toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350\'); return false;">Ajouter au panier</a>
                                              </td>';
                                    } else if ($_GET["id"] == $donnees["num_produit"]) {
                                        echo '<td style="width:200px; height:100px;"> Cet article a été ajouté au panier, <a href="panier.php?action=ajout&amp;l=' . $donnees['nom_produit'] . '&amp;p=' . $donnees['prix'] . '" onclick="window.open(this.href, \'\', 
                                                \'toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350\'); return false;">Cette article à été ajouter, ajouter ?</a></td>';
                                    }
                                    echo '</tr>
                            </table></br></br>';
                                        
                        }
                        
                        ?>

    </body>
</html>
