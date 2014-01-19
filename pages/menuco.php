
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
			
	</menu>