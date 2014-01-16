<!DOCTYPE html>
<html>
<head>
	<title>Projet PHP</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="style2.css" />
</head>

<body>

<h1>Préparer vous pour le combat !!!</h1>

<img class="banniere" src="banniere.jpg">
</br>
</br>


<table class="table_menu">	
	<td class="td_menu">Mes commandes passées</td>
	<td class="td_menu">Nouveau ? Inscrivez vous !</td>
	<td class="td_panier"><a href="ta_page" ><img src="panier.jpg" class="panier"/></a></td>
</table>

</br>

<table class="table_recherche">
	<td>
		<FORM>
			<SELECT name="nom" size="1">
				<OPTION>Tous produits
				<OPTION>Fusil
				<OPTION>Mitraillette
			</SELECT>
		</FORM>
	</td>
	<td>
		<input type="text" name="recherche" size="50" value="Rechercher un article">
	</td>
	<td>
		<input type="submit" value="ok">
	</td>
</table>

</br>
</br>

<table class="table_affiche">
	<td class="td_affiche">Les produits à afficher</td>
</table>

</body>

</html>