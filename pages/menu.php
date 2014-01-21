<menu id="menu">

    <form  action="/~sdelaher/index.php" method="POST" style="text-align: center;">
        <?php
        if (isset($_GET['error'])) {
            echo "<span style='color:red;'>" . $_GET['error'] . "</span>";
        }
        ?>	
            <label for="focus1">Login :</label><input id="focus1" type="text" name="login"/> 
            <label for="focus2">Password :</label><input id="focus2" type="password" name="mdp"/> 
            <input type="submit" value="Se connecter"/>
            <a href="/~sdelaher/pages/panier.php?action=affiche" onclick="window.open(this.href, '',
                        'toolbar=no, location=no, directories=no, status=yes, scrollbars=yes, resizable=yes, copyhistory=no, width=600, height=350');
                return false;"><img src="/~sdelaher/images/panier.jpg" alt="Cliquer pour ajouter au panier" class="panier"/></a></br>
    </form>
    <div > <a href="/~sdelaher/pages/subscribe.php" style="font-size: 16px;">Nouveau ? Inscrivez-vous.</a></br>
               <a href="/~sdelaher/pages/forget.php" style="font-size: 16px;">Mot de passe/login oublié ?</a></div>

</menu>