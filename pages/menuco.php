
	<menu>
			
                                <?php
                                        if(isset($_GET['error'])){
                                                echo "<p style='color:red;'>" . $_GET['error'] . "</p>";
                                        }
                                ?>

                        <table class="table_menu">	
                            <td class="td_menu"><a href="/~sdelaher/pages/historique_commande.php" onclick="window.open(this.href, '', 
                                        'toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350'); return false;">Mes commandes passées</a></td>
                            <td class="td_menu"><a href="/~sdelaher/pages/deco.php">Déconnexion</a></td>

                            <td class="td_panier"><a href="/~sdelaher/pages/panier.php?action=affiche" onclick="window.open(this.href, '', 
                                'toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350'); return false;"><img src="/~sdelaher/images/panier.jpg" alt="Cliquer pour ajouter au panier" class="panier"/></a></td>

                         </table>
			    <?php $cmd = $bdd->prepare('SELECT * FROM user WHERE id_user=:i');
                            $cmd->bindParam(':i', $_SESSION['id']);
                            $cmd->execute() or die('ERREUR menuco');
                            $tab = $cmd->fetch();
                            
                            // Si l'utilisateur est un administrateur
                            if ($tab['admin'] == 1) {
                echo '<table>';
                echo '<td ><a href="/~sdelaher/pages/modif_categorie.php"> Modifier une categorie ?</a></td>';
                echo '<td ><a href="/~sdelaher/pages/ajout_categorie.php"> Ajouter une categorie ?</a></td>';
                echo '<td ><a href="/~sdelaher/pages/supprimer_categorie.php"> Supprimer une categorie ?</a></td>';
                echo '<td ><a href="/~sdelaher/pages/addview.php"> Ajouter un produit ?</a></td>';
                echo '<td ><a href="/~sdelaher/pages/supprimer_produit.php"> Supprimer un produit ?</a></td>';
                echo '<td ><a href="/~sdelaher/pages/modif_produit.php"> Modifier un produit ?</a></td>';
                echo '<td ><a href="/~sdelaher/pages/commandeview.php"> Voir l\'emsemble des commandes ?</a></td>';
                echo '</table>';
            }
            ?>
            
	</menu>