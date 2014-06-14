<?PHP 
/*	--------------------------------------------------------------------------------------------------------------
		Auteur 	: Guillaume ROUAN						Titre	: index.php
		Contact	: @grouan							Date 	: 06/09
		Pour		: Espace multimédia						Version: 1
	--------------------------------------------------------------------------------------------------------------
		Licence : Attribution 4.0 International (CC BY 4.0) http://creativecommons.org/licenses/by/4.0/
	--------------------------------------------------------------------------------------------------------------	*/

// Package de fonctions
	require_once ('fonctions/fonctions.php');
	connexion(); // Connexion à la bdd
	
	//unset($_SESSION['Lognom']);
	@session_destroy();	
	
	// Pour la vérification
	if ( (isset($_GET['rub'])) && ($_GET['rub']=='access') ) {
		// Vérification de l'accès
		$verifAccess = verifAccess($_POST['login'],md5($_POST['pass']));
		if ($verifAccess==1) { /* Session */ @session_start (); $id = session_id (); }
	}
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="Robots" content="noindex, nofollow">
	<link rel="shortcut icon" href="favicon.ico">

<title>&middot;&middot;&middot;&middot;&middot; S &middot; G &middot; E &middot; M</title>

<?PHP
	// Redirection de la page
	if ( (isset($_GET['rub'])) && ($_GET['rub']=='access') ) {
		/* Redirection */
		if ($verifAccess==1) { $url = 'accueil.php?session='.$id; } else { $url = 'index.php'; }
		echo '<meta http-equiv="refresh" content="0;URL='.$url.'">';
	}
?>

<link type="text/css" href="css/multimedia.css" rel="stylesheet">

</head>

<body>

	<div id="conteneur">
		<div id="logo">&nbsp;</div>	
		<div id="recherche">&nbsp;</div>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr width="100%">
				<td class="colonne"><div id="colonne">
			<?PHP	horloge(250);	
					dateMaintenant();
			?>
				</div></td>
				<td class="postes"><div id="postes">
					<?PHP
					if (!$_GET['rub']) { /* Formulaire d'identification */ afficheFormIdent (); }
					else {
						if ($_GET['rub'] == 'access') { /* Messages */
							if ($verifAccess){ }
							else { echo '<meta http-equiv="refresh" content="5;URL=index.php\>'; }
						}
						if ($_GET['rub'] == 'mdp') { /* Mot de passe perdu/oublié */ }
					}
												
					?>
									
				</div></td><!-- fin postes -->
			</tr>
		</table>
	<div id="pied">&copy; SGEM &middot; GUR 2009</div>
	</div><!-- fin conteneur -->

</body>


</html>
<?PHP	deconnexion();	// déconnexion de la bdd		?>