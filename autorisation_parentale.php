<?PHP 


	// Package de fonctions
	require_once ('fonctions/fonctions.php');
	connexion(); // Connexion à la bdd
	
	// Sessions
	@session_start();
	$id = session_id();	
	
	// Récupération des informations de l'inscrit
	if (isset($_GET['idinscrit'])) { $idinscrit = $_GET['idinscrit']; $tabInfosInscrit = infosInscrit($idinscrit); }
	
	// Récupération des informations de l'autorisation
	if (isset($_GET['idautorisation'])) {
		$idautorisation = $_GET['idautorisation']; $tabInfosAutorisation = infosAutorisation($idautorisation);
		$idinscrit = $tabInfosAutorisation[1]; $tabInfosInscrit = infosInscrit($idinscrit);
	}
	
	// Date et heure
	@setlocale("LC_TIME", "fr_FR");
	$aujourdhui = strftime("%Y-%m-%d");
	$heure_maintenant = date('H:i:s');
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="Robots" content="noindex, nofollow">
	<link rel="shortcut icon" href="favicon.ico">

<title>Système de Gestion de l'Espace Multimédia</title>

<?PHP
	// Redirection de la page
	if ( (isset($_GET['session'])) && ($_GET['session']==$id) ) { }
	else {
		/* Redirection */
		echo '<meta http-equiv="refresh" content="0;URL=index.php">'; /* 0 seconde */
	}
	// Ajout de la fiche
	if ($_GET['rub']=='ajout_confirm_fiche') {
		$ajout = ajoutAutorisation ($idinscrit,$_POST['resp_nom'],$_POST['resp_prenom'],$_POST['resp_adresse'],$_POST['resp_cp'],$_POST['resp_ville'],$_POST['resp_pays'],$_POST['resp_tel_fixe'],$_POST['resp_tel_mobile'],$_POST['resp_email'],$_POST['resp_identite_type'],$_POST['resp_identite_num'],$_POST['resp_identite_date_jour'],$_POST['resp_identite_date_mois'],$_POST['resp_identite_date_annee'],$_POST['resp_identite_lieu'],$_POST['resp_signature'],$_POST['note']);
		if ($ajout!=-1) {
			$message = 'L\'autorisation n° '.$ajout.' a bien été ajoutée.';
			echo '<meta http-equiv="refresh" content="0;URL=fiche.php?session='.$id.'&idinscrit='.$idinscrit.'">'; /* 0 seconde */
		}
		else {
			$message = 'Une erreur est survenue lors de l\'ajout de cette autorisation.';
		}
	}
?>

<link type="text/css" href="css/multimedia.css" rel="stylesheet">

</head>

<body>

<?PHP
// Vérification d'accès à la page -------------------------------------------------------------
if ( (isset($_GET['session'])) && ($_GET['session']==$id) ) {

	echo '<div id="conteneur">';
	
		echo '<a href="accueil.php?session='.$id.'" target="_self" title="tableau de bord"><div id="logo"><div id="logo_gauche"><b>système de gestion</b> d\'espace multimédia</div></div></a>';
		echo '<div id="recherche">';
			afficheFormRecherche($id);
			//echo	' <a href="accueil.php?session='.$id.'" target="_self" title="Rafraîchir la page"><input type="submit" name="ok" class="bouton_rafraichir" value="rafraîchir"></a>';
			//echo	' <a href="index.php" target="_self" title="Se déconnecter"><input type="submit" name="ok" class="bouton_deconnexion" value="se déconnecter"></a>';
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
					affTitre('#CC9','autorisation parentale');					
					
					// --- AJOUT DE L'AUTORISATION ------------------------------------------------------------------------------------------------------
					if (isset($_GET['rub'])) {
						if ($_GET['rub']=='ajout_fiche') {
							echo '<div id="fiche_identite">';
							echo '<FORM method="post" name="fiche_ajout" action="autorisation_parentale.php?session='.$id.'&idinscrit='.$idinscrit.'&rub=ajout_confirm_fiche" class="formulaireAjoutFiche">';
							echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
							echo '	<tr><td class="ligne_modif" colspan="2"><span style="text-transform:uppercase;font-size:40px;font-weight:bold;color:#CC9;text-align:center;">'.$tabInfosInscrit[3].' '.$tabInfosInscrit[4].' [#'.$idinscrit.']</span><input type="hidden" name="inscrit_id" value="'.$idinscrit.'"></td></tr>';
							echo '	<tr><td class="ligne_modif" colspan="2"><span style="font-size:20px;color:#CCC;margin:10px 0px; text-transform:uppercase;font-weight:bold;text-align:center;">Responsable de l\'enfant &darr;</span></td></tr>';
							echo '	<tr><td class="ligne_modif">nom</td><td><input type="text" name="resp_nom" value="'.$tabInfosInscrit[3].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">prénom</td><td><input type="text" name="resp_prenom"></td></tr>';
							echo '	<tr><td class="ligne_modif">adresse</td><td><input type="text" name="resp_adresse" value="'.$tabInfosInscrit[8].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">cp</td><td><input type="text" name="resp_cp" value="'.$tabInfosInscrit[9].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">ville</td><td><input type="text" name="resp_ville" value="'.$tabInfosInscrit[10].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">pays</td><td><input type="text" name="resp_pays" value="'.$tabInfosInscrit[11].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">téléphone fixe</td><td><input type="text" name="resp_tel_fixe" value="'.$tabInfosInscrit[12].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">téléphone portable</td><td><input type="text" name="resp_tel_mobile"></td></tr>';
							echo '	<tr><td class="ligne_modif">email</td><td><input type="text" name="resp_email"></td></tr>';
							echo '	<tr><td class="ligne_modif">pièce d\'identité : type</td><td>';
							echo '<select name="resp_identite_type">';
								echo '<option value="cni" selected>carte nationale d\'identité</option>';
								echo '<option value="passeport">passeport</option>';
								echo '<option value="permis">permis de conduire</option>';
								echo '<option value="militaire">carte militaire</option>';
								echo '</select>';
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">pièce d\'identité : n°</td><td><input type="text" name="resp_identite_num"></td></tr>';
							echo '<tr><td class="ligne_modif">pièce d\'identité : date</td><td>';
								echo	'<input type="text" name="resp_identite_date_jour" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="resp_identite_date_mois" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="resp_identite_date_annee" size="4" maxlength="4">';
							echo '	</td></tr>';
							echo '	<tr><td class="ligne_modif">pièce d\'identité : lieu</td><td><input type="text" name="resp_identite_lieu"></td></tr>';
							echo '	<tr><td class="ligne_modif">signature</td><td>';
								echo '<select name="resp_signature">';
									echo '<option value="oui" selected>oui</option>';
									echo '<option value="non">non</option>';
								echo '</select>';
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">note</td><td><textarea name="note"></textarea></td></tr>';
							echo	'<tr><td><input type="submit" name="ok" class="fiche_ajout_submit" value="enregistrer cette autorisation"></td></tr>';
							echo '</FORM>';
							echo '</div><!-- fin fiche_identite -->';
						}
						if ($_GET['rub']=='ajout_confirm_fiche') {
							echo '<div>'.$message.'</div>';
						}
					}
					// --- AFFICHAGE DE L'AUTORISATION --------------------------------------------------------
					else {
					echo '<div id="fiche_identite">';
						echo '<div id="identite_nomprenom"><font color="#999">N°'.$tabInfosInscrit[0].'</font> '.$tabInfosInscrit[4].' <span style="text-transform:uppercase;font-weight:bold;">'.$tabInfosInscrit[3].'</span></div>';
						echo '<div id="identite_age"><img src="img/ico/mediatheque.png" border="0" title="responsable légal"> <b>'.$tabInfosAutorisation[5].' <span style="text-transform:uppercase;font-weight:bold;">'.$tabInfosAutorisation[4].'</span></b></div>';
						echo '<div id="identite_age"><img src="img/ico/age.png" border="0" title="validité"> Du '.transformeTime($tabInfosAutorisation[2]).' au '.transformeTime($tabInfosAutorisation[3]).'</div>';
						echo '<div id="identite_adresse"><img src="img/ico/adresse.png" border="0" title="adresse"> '.$tabInfosAutorisation[6].' &middot; '.$tabInfosAutorisation[7].' <b>'.$tabInfosAutorisation[8].'</b>';
						if ($tabInfosAutorisation[9]!='France') { echo ' &middot; '.$tabInfosAutorisation[9]; }
						echo '</div>';
						if (!empty($tabInfosAutorisation[10])) { echo '<div id="identite_telfixe"><img src="img/ico/fixe.png" border="0" title="téléphone fixe"> '.$tabInfosAutorisation[10].'</div>'; }
						if (!empty($tabInfosAutorisation[11])) { echo '<div id="identite_telmobile"><img src="img/ico/mobile.png" border="0" title="téléphone portable"> '.$tabInfosAutorisation[11].'</div>'; }
						if (!empty($tabInfosAutorisation[12])) { echo '<div id="identite_email"><img src="img/ico/email.png" border="0" title="envoyer un message"> <a href="mailto:'.$tabInfosAutorisation[12].'">'.$tabInfosAutorisation[12].'</a></div>'; }
						if (!empty($tabInfosAutorisation[14])) {echo '<div id="identite_piece"><img src="img/ico/identite.png" border="0" title="pièce d\'identité"> '.$tabInfosAutorisation[13].' n° '.$tabInfosAutorisation[14].', '.$tabInfosAutorisation[16].', '.transformeTime($tabInfosAutorisation[15]).'</div>'; }
						if ($tabInfosAutorisation[17]=='oui') { $signature='<font color="green">L\'autorisation est signée</font>'; }
						else { $signature='<font color="red">L\'autorisation n\'est pas signée.</font>'; }
						echo '<div id="identite_piece"><img src="img/ico/malette.png" border="0" title="signature de l\'autorisation"> '.$signature.'</div>';
						if (!empty($tabInfosAutorisation[18])) { echo '<div id="identite_note" style="padding-right:140px;"><img src="img/ico/commentaire.png" border="0" title="commentaires sur cet utilisateur"> <font color="#999">'.$tabInfosAutorisation[18].'</font></div>'; }
					echo '<div style="margin-top:20px;"><a href="fiche.php?session='.$id.'&idinscrit='.$tabInfosInscrit[0].'" target="_self"><input type="submit" name="ok" class="rech_submit" value="retour à la fiche de '.$tabInfosInscrit[4].' '.$tabInfosInscrit[3].'"></a></div>';
					echo '</div><!-- fin fiche_identite -->';
				} // fin de l'affichage
				echo '</td><!-- fin postes -->';
			echo '</tr>';
		echo '</table>';
	echo '<div id="pied">&copy; Système de Gestion de l\'Espace Multimédia &middot; GUR 2009</div>';
	echo '</div><!-- fin conteneur -->';

} else { echo 'Cette page est en accès restreint !'; }

?>
</body>


</html>
<?PHP	deconnexion();	// déconnexion de la bdd		?>