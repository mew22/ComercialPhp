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
            function imagette($nom_source, $nom_destination, $percent)
            {
                  
                
                // Calcul des nouvelles dimensions
                
                list($width, $height) = getimagesize($nom_source);
                $new_width = $width * $percent;
                $new_height = $height * $percent;

                
                $ressource_source = imagecreatefromjpeg ($nom_source);
                $ressource_dest= imagecreatetruecolor($new_width, $new_height);
                
                imagecopyresampled ( $ressource_dest , $ressource_source , 0 , 0 , 0 , 0 , $new_width , $new_height , $width , $height ) or die('ERROR : resampled');
                
                imagejpeg ( $ressource_dest, $nom_destination, 100) or die('ERROR : jpeg');
                
                
                imagedestroy($ressource_dest);
                imagedestroy($ressource_source);
            }

            function deleteChar($string)
            {
            	$i;
            	$tmp = '';
            	for($i=0;$i<strlen($string);$i++)
            	{
            		if($string[$i] != ' ' && $string[$i] != ':' && $string[$i] != '-')
            		{
            			$tmp = $tmp . $string[$i];
            		}
            	}

            	return $tmp;
            }
        ?>
        
        <?php
        
        if(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0)
        {
           if(isset($_POST['name']) && isset($_POST['prix'])  && isset($_POST['categorie']) && isset($_POST['promo'])) /* Si les variables name ou passwd sont vide, on applique le formulaire */
           {
                include 'PDO.php';

                $i = date("YmdHis");
            
                $upload_dir = '/mnt/traban/3inf2a/sdelaher/WWW/images';
                $upload_file = $upload_dir . '/' . $i . $_FILES['fichier']['name'];


                $upload = 'images/1-' . $i . $_FILES['fichier']['name'];
                $upload2 = 'images/' . $i . $_FILES['fichier']['name'];
                
                if(!file_exists("images"))
                {
                     mkdir("images");
                }

                /* if(is_file($upload))
                {
                	$i = '1';
                	$upload = 'images/1-' . $i . $_FILES['fichier']['name'];
                	$upload2 = 'images/' . $i . $_FILES['fichier']['name'];
                	$upload_file = $upload_dir . '/' . $i . $_FILES['fichier']['name'];
                }*/
                         

                
                move_uploaded_file($_FILES['fichier']['tmp_name'], $upload_file) or die('Erreur move');
                chmod($upload2, 0777);
                
                list($width, $height) = getimagesize($upload2);
                $coef=0;
                if($width<1000)
                    $coef=0.3;
                else
                    $coef=0.05;
                
                imagette($upload2, $upload, $coef);
                chmod($upload, 0777);
                
                $command= $bdd->prepare('INSERT INTO produit(nom_produit, prix, photo, num_categorie, promo) VALUES (:name, :prix, :photo, :num_categorie, :promo)');
                
                $command->bindParam(':name', $_POST['name']);
                $command->bindParam(':prix', $_POST['prix']);
                $command->bindParam(':photo', $upload);
                $command->bindParam(':num_categorie', $_POST['categorie']);
                $command->bindParam(':promo', $_POST['promo']);
                
                $command->execute() or die(print_r($command->errorInfo()));
                
                echo 'Added prod !';
                
                
           }
        }
        else
            echo 'erreur fichier';
        ?>
    </body>
</html>
