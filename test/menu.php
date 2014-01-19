		<menu id="menu">
			<ul>
				<form onSubmit="./index.php" action="./index.php" method="POST">
					<?php
						if(isset($_GET['error'])){
							echo "<span style='color:red;'>" . $_GET['error'] . "</span>";
						}
					?>
					<label for="focus1">login :</label><input id="focus1" type="text" name="login"> <br/>
					<label for="focus2">mdp :</label><input id="focus2" type="password" name="mdp"> <br/>
					<input type="submit" value="Se connecter"> <br/>
                                        <a href="./subscribe.php" style="font-size: 16px;">Nouveau ? Inscrivez-vous.</a></br>
                                        <a href="./forget.php" style="font-size: 16px;">Mot de passe oublier ?</a> | <a href="./forgetLogin.php" style="font-size: 16px;">Login ? </a>
				</form>
				<a href="./" style="color: #101010; text-decoration: none;"><li id="link">Accueil</li></a>
				<a href="#" style="color: #101010; text-decoration: none;"><li id="link">Forum</li></a>
				<a href="#" style="color: #101010; text-decoration: none;"><li id="link">Contact</li></a>
			</ul>
		</menu>