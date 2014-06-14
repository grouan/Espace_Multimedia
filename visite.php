<?PHP 


	// Package de fonctions
	require_once ('fonctions/fonctions.php');
	connexion(); // Connexion � la bdd
	
	// Sessions
	@session_start();
	$id = session_id();	
	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="Robots" content="noindex, nofollow">
	<link rel="shortcut icon" href="favicon.ico">

<title>Syst�me de Gestion de l'Espace Multim�dia</title>

<?PHP
	// Redirection de la page
	if ( (isset($_GET['session'])) && ($_GET['session']==$id) ) { }
	else {
		/* Redirection */
		echo '<meta http-equiv="refresh" content="0;URL=index.php">'; /* 0 seconde */
	}
	if ($_GET['rub']=='creer_visite') {
		$poste = $_POST['poste_num'];
		if (!empty($_GET['idinscrit'])) {
			$creer = creerVisite ($_GET['idinscrit'],$poste,$_POST['activite']);
			if ($creer!=-1) { echo '<meta http-equiv="refresh" content="0;URL=fiche.php?session='.$id.'&idinscrit='.$_GET['idinscrit'].'">'; }
		}
	}
	if ($_GET['rub']=='terminer_session') {
		$terme = terminerVisite ($_GET['idsession'],$_GET['idinscrit']);
		if ($terme==1) { echo '<meta http-equiv="refresh" content="0;URL=fiche.php?session='.$id.'&idinscrit='.$_GET['idinscrit'].'">'; }
	}
	
?>

<link type="text/css" href="css/multimedia.css" rel="stylesheet">

</head>

<body>

<?PHP
// V�rification d'acc�s � la page -------------------------------------------------------------
if ( (isset($_GET['session'])) && ($_GET['session']==$id) ) {

	echo '<div id="conteneur">';
	
		echo '<a href="accueil.php?session='.$id.'" target="_self" title="tableau de bord"><div id="logo"><div id="logo_gauche"><b>syst�me de gestion</b> d\'espace multim�dia</div></div></a>';
		echo '<div id="recherche">';
			afficheFormRecherche($id);
			//echo	' <a href="accueil.php?session='.$id.'" target="_self" title="Rafra�chir la page"><input type="submit" name="ok" class="bouton_rafraichir" value="rafra�chir"></a>';
			//echo	' <a href="index.php" target="_self" title="Se d�connecter"><input type="submit" name="ok" class="bouton_deconnexion" value="se d�connecter"></a>';
		echo '</div><!-- fin recherche -->';
		
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="feuille">';
			echo '<tr width="100%">';
				echo '<td class="colonne" align="center" valign="top"><div id="colonne">';
					horloge(200);	
					dateMaintenant();
					nbInscrits();
				echo '</div></td>';
				echo '<td class="postes">';
					
					affMenu($id);
					affTitre('#603','visite : session d\'un utilisateur');
					
					// --- MODIFICATION DE LA FICHE ------------------------------------------------------------------------------------------------------------------------
					if (isset($_GET['rub'])) {
						if ($_GET['rub']=='voir') {
							
						}
						if ($_GET['rub']=='creer_visite') {
							if (!empty($_GET['idinscrit'])) {
								if ($creer!=-1) {
									echo '<font color="green">L\'utilisateur n� '.$idinscrit.' a maintenant une session de travail au poste n� '.$poste.'.</font>';
								}
							}
						}
						if ($_GET['rub']=='modifier_visite') {
							
						}
						if ($_GET['rub']=='terminer_session') {
							
						}
						/*if ($_GET['rub']=='supprimer_visite') {
							
						}*/
					}
					//
					else {
						
					}
					
				echo '</td><!-- fin postes -->';
			echo '</tr>';
		echo '</table>';
	echo '<div id="pied">&copy; Syst�me de Gestion de l\'Espace Multim�dia &middot; GUR 2009</div>';
	echo '</div><!-- fin conteneur -->';

} else { echo 'Cette page est en acc�s restreint !'; }

?>
</body>


</html>
<?PHP	deconnexion();	// d�connexion de la bdd		?>