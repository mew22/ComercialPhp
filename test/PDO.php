<?php

               try
                {
                    $bdd = new PDO('mysql:host=sqletud.univ-mlv.fr; dbname=sdelaher_db', 'sdelaher', 'Y1uudaex');
                }
                catch (Exception $e)
                {
                        die('Erreur : ' . $e->getMessage());
                }
