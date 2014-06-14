<?PHP 

	// Package de fonctions
	require_once ('fonctions/fonctions.php');
	connexion(); // Connexion � la bdd
	
	// Sessions
	@session_start();
	$id = session_id();	
	
	// Ancre hautdepage
	echo '<a name="hautdepage"></a>';
	
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
		echo '</div><!-- fin recherche -->';
		
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="feuille">';
			echo '<tr width="100%">';
				echo '<td class="colonne" align="center" valign="top"><div id="colonne">';
					horloge(200);	
					dateMaintenant();
					nbInscrits();
				echo '</div></td>';
				echo '<td class="postes"><div id="postes">';
// --- Page centrale --- /////////////////////////////////////////////////////////////////////////////////////////
									
				affMenu($id);
				affTitre('#900','administration');	
					
					// --- MENU --------------------------------------------------------------------------------------
					
					echo '<div style="border-bottom:5px solid #BBB;border-top:5px solid #BBB;padding:5px 20px;text-align:center;margin:20px 0px;background-color:#EAEAEA">';
						echo '<a href="#modif_mdp" target="_self"><input type="submit" name="ok" class="rech_submit" value="mot de passe"></a> ';
						echo '<a href="#bdd" target="_self"><input type="submit" name="ok" class="rech_submit" value="base de donn�es"></a> ';
						echo '<a href="#inscrits" target="_self"><input type="submit" name="ok" class="rech_submit" value="utilisateurs"></a> ';
						echo '<a href="#postes" target="_self"><input type="submit" name="ok" class="rech_submit" value="postes"></a> ';
						echo '<a href="#visites" target="_self"><input type="submit" name="ok" class="rech_submit" value="visites"></a> ';
						echo '<a href="#stats" target="_self"><input type="submit" name="ok" class="rech_submit" value="statistiques"></a> ';
					echo '</div>';
					
					
					// --- AFFICHAGES --------------------------------------------------------------------------------
					
					// --- Changement du mot de passe --------------------------
					echo '<a name="modif_mdp"></a><div style="border-left:8px solid #906;background:#EAEAEA;padding:10px;margin-bottom:10px;">';
						echo '<div id="admin_titre">Modifier le mot de passe</div>';
						afficheFormChangeMdp($id);
						echo '<a href="#hautdepage" target="_self"><div id="admin_hautdepage">&uarr; haut</div></a>';
					echo '</div>';
					// --- Connexion � la BDD -----------------------------------
					echo '<a name="bdd"></a><div style="border-left:8px solid #09F;background:#EAEAEA;padding:10px;margin-bottom:10px;">';
						echo '<div id="admin_titre">La base de donn�es</div>';
						echo '<a href="http://phpmyadmin.free.fr/phpMyAdmin/" target="_blank" title="se connecter � la base de donn�es">http://phpmyadmin.free.fr/phpMyAdmin/</a>';
						echo '<a href="#hautdepage" target="_self"><div id="admin_hautdepage">&uarr; haut</div></a>';
					echo '</div>';
					// --- Inscrits ---------------------------------------------
					echo '<a name="inscrits"></a><div style="border-left:8px solid #FC0;background:#EAEAEA;padding:10px;margin-bottom:10px;">';
						echo '<div id="admin_titre">Les utilisateurs</div>';
						echo '<a href="#hautdepage" target="_self"><div id="admin_hautdepage">&uarr; haut</div></a>';
					echo '</div>';
					// --- Postes ---------------------------------------------
					echo '<a name="postes"></a><div style="border-left:8px solid #F9C;background:#EAEAEA;padding:10px;margin-bottom:10px;">';
						echo '<div id="admin_titre">Les postes</div>';
						echo '<a href="#hautdepage" target="_self"><div id="admin_hautdepage">&uarr; haut</div></a>';
					echo '</div>';
					// --- Visites ---------------------------------------------
					echo '<a name="visites"></a><div style="border-left:8px solid #0C0;background:#EAEAEA;padding:10px;margin-bottom:10px;">';
						echo '<div id="admin_titre">Les visites</div>';
						echo '<a href="#hautdepage" target="_self"><div id="admin_hautdepage">&uarr; haut</div></a>';
					echo '</div>';
					// --- Statistiques ----------------------------------------
					echo '<a name="stats"></a><div style="border-left:8px solid #960;background:#EAEAEA;padding:10px;margin-bottom:10px;">';
						echo '<div id="admin_titre">Les statistiques</div>';
						echo '<a href="#hautdepage" target="_self"><div id="admin_hautdepage">&uarr; haut</div></a>';
					echo '</div>';
					
// --- Fin page centrale --- /////////////////////////////////////////////////////////////////////////////////////
				echo '</div></td><!-- fin postes -->';
			echo '</tr>';
		echo '</table>';
	echo '<div id="pied">&copy; Syst�me de Gestion de l\'Espace Multim�dia &middot; GUR 2009</div>';
	echo '</div><!-- fin conteneur -->';

} else { echo 'Cette page est en acc�s restreint !'; }

?>
</body>


</html>
<?PHP	deconnexion();	// d�connexion de la bdd		?>