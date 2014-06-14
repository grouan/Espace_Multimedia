<?PHP 


	// Package de fonctions
	require_once ('fonctions/fonctions.php');
	connexion(); // Connexion � la bdd
	
	// Sessions
	@session_start();
	$id = session_id();	
	
	// Date et heure
	@setlocale("LC_TIME", "fr_FR");
	$jour_date = strftime("%Y-%m-%d");
	$heure_maintenant = date('H:i:s');
	
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
	
		echo '<div id="logo"><div id="logo_gauche"><b>syst�me de gestion</b> d\'espace multim�dia</div></div>';
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
				echo '<td class="postes"><div id="postes">';
				
				affMenu($id);
				affTitre('#F90','tableau de bord');	
				
					$nbPostes = nombre_de_postes();
					/* nb de lignes */ $nbLignes = ceil($nbPostes/2);
					$compteurPoste = 1;
					echo '<table width="100%" height="100%" cellpadding="0" cellspacing="10px" border="0" class="bord">';
						for ($ligne=0;$ligne<$nbLignes;$ligne++) {
							echo '<tr>';
								$liberte = posteLibre($compteurPoste);
								// param�trage ---
								if ($liberte==-1) { // ce poste est libre
									$img='ecran_disponible.png'; $couleur='#EAEAEA'; $leg='ce poste est disponible';
									$compteurPoste++;	$couleurNumPoste = '#EAEAEA';
								}
								else { // ce poste est occup�
									$img='ecran_utilise.png'; $leg='ce poste est actuellement occup�';
									$tabInfosPoste = infosPoste ($compteurPoste); $compteurPoste++;
									$couleur = $tabInfosPoste[5];	$couleurNumPoste = '#333';
									$tabInfosInscrit = infosInscrit($tabInfosPoste[2]);
								}
								// affichage ---
								echo '<td width="50%" class="bord_gauche" style="background: '.$couleur.' url(img/ico/'.$img.') top left no-repeat; height:128px;">'; // colonne de gauche
									echo '<div style="position:relative;float:left;font-size:80px;color:'.$couleurNumPoste.';font-weight:bold;margin:-20px 50px 5px 40px;">'.($compteurPoste-1).'</div>';
									//echo '<img src="img/ico/'.$img.'" border="0" title="'.$leg.'" style="position:relative;float:left;margin:0px 20px 20px 0px;">';
									if ($liberte==-1) { // ce poste est libre > affichage d'un message de disponibilit�
										echo '<font color="#999">Le poste n� <b>'.($compteurPoste-1).'</b> est disponible.</font>';
									}
									else { // ce poste est occup� > affichage des informations de la session
										echo '<a href="fiche.php?session='.$id.'&idinscrit='.$tabInfosPoste[2].'" target="_self" title="voir la fiche de cet utilisateur">';
										echo '<div style="margin:-10px 0px 0px 150px;"><img src="img/ico/'.$tabInfosInscrit[7].'_20x20.png" border="0"> <span style=";font-weight:bold;font-size:20px">'.$tabInfosInscrit[4].'</span> <span style="text-transform:uppercase;font-weight:bold;font-size:20px">'.$tabInfosInscrit[3].'</span>';
										$duree = transformeHeure(difheure($tabInfosPoste[0],$heure_maintenant));
										echo '<div style="margin-left:24px;font-size:14px;">&rarr; Arriv�e � <b>'.transformeHeure($tabInfosPoste[0]).'</b>, soit depuis <b>'.$duree.'</b></div>';
										echo '<div style="margin-left:24px;font-size:14px;">&rarr; La session est r�serv�e jusqu\'� <b>'.transformeheure($tabInfosPoste[1]).'</b></div>';
										echo '</a>';
										echo '<a href="visite.php?session='.$id.'&rub=terminer_session&idsession='.$tabInfosPoste[6].'&idinscrit='.$tabInfosPoste[2].'" target="_self"><input type="submit" name="ok" class="bouton_fin_session" value="terminer la session"></a>';
										echo '</div>';
									}
								echo '</td>';
								if ($compteurPoste<=$nbPostes) {
									/**/
									$liberte = posteLibre($compteurPoste);
									// param�trage ---
									if ($liberte==-1) { // ce poste est libre
										$img='ecran_disponible.png'; $couleur='#EAEAEA'; $leg='ce poste est disponible';
										$compteurPoste++;	$couleurNumPoste = '#EAEAEA';
									}
									else { // ce poste est occup�
										$img='ecran_utilise.png'; $leg='ce poste est actuellement occup�';
										$tabInfosPoste = infosPoste ($compteurPoste); $compteurPoste++;
										// [0]=heure_arrivee [1]=heure_limite [2]=inscrit_id [3]=activite [4]=note [5]=couleur
										$couleur = $tabInfosPoste[5];	$couleurNumPoste = '#333';
										$tabInfosInscrit = infosInscrit($tabInfosPoste[2]);
									}
									/**/
									// affichage ---
								echo '<td width="50%" class="bord_droit" style="background: '.$couleur.' url(img/ico/'.$img.') top left no-repeat; height:128px;">'; // colonne de gauche
									echo '<div style="position:relative;float:left;font-size:80px;color:'.$couleurNumPoste.';font-weight:bold;margin:-20px 50px 5px 40px;">'.($compteurPoste-1).'</div>';
									//echo '<img src="img/ico/'.$img.'" border="0" title="'.$leg.'" style="position:relative;float:left;margin:0px 20px 20px 0px;">';
									if ($liberte==-1) { // ce poste est libre > affichage d'un message de disponibilit�
										echo '<font color="#999">Le poste n� <b>'.($compteurPoste-1).'</b> est disponible.</font>';
									}
									else { // ce poste est occup� > affichage des informations de la session
										echo '<a href="fiche.php?session='.$id.'&idinscrit='.$tabInfosPoste[2].'" target="_self" title="voir la fiche de cet utilisateur">';
										echo '<div style="margin:-10px 0px 0px 150px;"><img src="img/ico/'.$tabInfosInscrit[7].'_20x20.png" border="0"> <span style=";font-weight:bold;font-size:20px">'.$tabInfosInscrit[4].'</span> <span style="text-transform:uppercase;font-weight:bold;font-size:20px">'.$tabInfosInscrit[3].'</span>';
										$duree = transformeHeure(difheure($tabInfosPoste[0],$heure_maintenant));
										echo '<div style="margin-left:24px;font-size:14px;">&rarr; Arriv�e � <b>'.transformeHeure($tabInfosPoste[0]).'</b>, soit depuis <b>'.$duree.'</b></div>';
										echo '<div style="margin-left:24px;font-size:14px;">&rarr; La session est r�serv�e jusqu\'� <b>'.transformeheure($tabInfosPoste[1]).'</b></div>';
										echo '</a>';
										echo '<a href="visite.php?session='.$id.'&rub=terminer_session&idsession='.$tabInfosPoste[6].'&idinscrit='.$tabInfosPoste[2].'" target="_self"><input type="submit" name="ok" class="bouton_fin_session" value="terminer la session"></a>';
										echo '</div>';
									}
								}
								else { echo '<td width="50%" class="bord_droit_vide">&nbsp;'; }
								echo '</td>';
							echo '</tr>';
						}
					echo '</table>';
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