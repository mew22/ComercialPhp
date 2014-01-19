			<menu>
			<ul>
				<li>
					<?php
						if(isset($_GET['error'])){
							echo "<p style='color:red;'>" . $_GET['error'] . "</p>";
						}
					?>
				</li>
				<li id="link"><a href="./" style="color: #101010; text-decoration: none;">Accueil</a></li>
				<li id="link">Forum</li>
				<li id="link"><a href="./mp.php" style="color: #101010; text-decoration: none;">Messagerie</a></li>
				<li id="link">Contact</li>
				<li id="link"><a href="./deco.php" style="color: #101010; text-decoration: none;">D&eacute;connexion</a></li>
			</ul>
		</menu>