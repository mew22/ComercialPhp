<?php

               try
                {
                    $bdd = new PDO('mysql:host=localhost; dbname=phpcommercial', 'root', '');
                }
                catch (Exception $e)
                {
                        die('Erreur : ' . $e->getMessage());
                }
