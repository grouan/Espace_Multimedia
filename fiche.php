<?PHP 


	// Package de fonctions
	require_once ('fonctions/fonctions.php');
	connexion(); // Connexion à la bdd
	
	// Sessions
	@session_start();
	$id = session_id();	
	
	// Récupération des informations de l'inscrite
	$idinscrit = $_GET['idinscrit'];
	$tabInfosInscrit = infosInscrit($idinscrit);
	
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
	// Modification de la fiche
	if ($_GET['rub']=='modif_confirm_fiche') {
		$modification = modifInscrit ($_GET['idinscrit'],$_POST['adherent_num'],$_POST['personnel'],$_POST['nom'],$_POST['prenom'],$_POST['naissance_date_jour'],$_POST['naissance_date_mois'],$_POST['naissance_date_annee'],$_POST['majeur'],$_POST['sexe'],$_POST['adresse'],$_POST['cp'],$_POST['ville'],$_POST['pays'],$_POST['tel_fixe'],$_POST['tel_mobile'],$_POST['email'],$_POST['identite_type'],$_POST['identite_num'],$_POST['identite_date_jour'],$_POST['identite_date_mois'],$_POST['identite_date_annee'],$_POST['identite_lieu'],$_POST['socio_pro'],$_POST['inscription_date'],$_POST['note']);
		if ($modification==1) {
			$message = 'La fiche de l\'utilisateur(trice) n° '.$_GET['idinscrit'].' a bien été modifiée.';
			echo '<meta http-equiv="refresh" content="0;URL=fiche.php?session='.$id.'&idinscrit='.$_GET['idinscrit'].'">'; /* 0 seconde */
		}
		else {
			$message = 'Une erreur est survenue lors de la modification de l\'utilisateur(trice) n° '.$_GET['idinscrit'].'.';
		}
	}
	// Ajout de la fiche
	if ($_GET['rub']=='ajout_confirm_fiche') {
		$ajout = ajoutInscrit ($_POST['adherent_num'],$_POST['personnel'],$_POST['nom'],$_POST['prenom'],$_POST['naissance_date_jour'],$_POST['naissance_date_mois'],$_POST['naissance_date_annee'],$_POST['majeur'],$_POST['sexe'],$_POST['adresse'],$_POST['cp'],$_POST['ville'],$_POST['pays'],$_POST['tel_fixe'],$_POST['tel_mobile'],$_POST['email'],$_POST['identite_type'],$_POST['identite_num'],$_POST['identite_date_jour'],$_POST['identite_date_mois'],$_POST['identite_date_annee'],$_POST['identite_lieu'],$_POST['socio_pro'],$_POST['inscription_date'],$_POST['note']);
		if ($ajout!=-1) {
			$message = 'L\'utilisateur(trice) n° '.$ajout.' a bien été ajouté(e).';
			echo '<meta http-equiv="refresh" content="0;URL=fiche.php?session='.$id.'&idinscrit='.$ajout.'">'; /* 0 seconde */
		}
		else {
			$message = 'Une erreur est survenue lors de l\'ajout de cet(te) utilisateur(trice).';
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
					affTitre('#960','fiche utilisateur');					
					
					// --- MODIFICATION DE LA FICHE ------------------------------------------------------------------------------------------------------------------------
					if (isset($_GET['rub'])) {
						if ($_GET['rub']=='modif_fiche') {
							echo '<div id="fiche_identite">';
							echo '<FORM method="post" name="fiche_modif" action="fiche.php?session='.$id.'&idinscrit='.$_GET['idinscrit'].'&rub=modif_confirm_fiche" class="formulaireModifFiche">';
							echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
							echo '	<tr><td class="ligne_modif">numéro espace multimédia</td><td><input type="texte" name="" value="'.$_GET['idinscrit'].'" size="4" disabled> <input type="texte" name="" value="'.$_POST['inscription_date'].'"  size="10" disabled><input type="hidden" name="inscription_date" value="'.$_POST['inscription_date'].'"  size="10"></td></tr>';
							echo '	<tr><td class="ligne_modif">numéro d\'adhérent bibliothèque</td><td><input type="text" name="adherent_num" value="'.$_POST['adherent_num'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">membre du personnel ?</td><td>';
								echo '<select name="personnel">';
								if ($_POST['personnel']=='oui') { echo '<option value="oui" selected>oui</option><option value="non">non</option></select>'; }
								else { echo '<option value="oui">oui</option><option value="non" selected>non</option></select>'; }
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">nom</td><td><input type="text" name="nom" value="'.$_POST['nom'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">prénom</td><td><input type="text" name="prenom" value="'.$_POST['prenom'].'"></td></tr>';
							echo '<tr><td class="ligne_modif">date de naissance</td><td>';
								echo	'<input type="text" name="naissance_date_jour" value="'.$_POST['naissance_date_jour'].'" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="naissance_date_mois" value="'.$_POST['naissance_date_mois'].'" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="naissance_date_annee" value="'.$_POST['naissance_date_annee'].'" size="4" maxlength="4">';
							echo '	</td></tr>';
							echo '	<tr><td class="ligne_modif">majeur ?</td><td>';
								echo '<select name="majeur">';
								if ($_POST['majeur']=='oui') { echo '<option value="oui" selected>oui</option><option value="non">non</option></select>'; }
								else { echo '<option value="oui">oui</option><option value="non" selected>non</option></select>'; }
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">sexe</td><td>';
								echo '<select name="sexe">';
								if ($_POST['sexe']=='homme') { echo '<option value="homme" selected>homme</option><option value="femme">femme</option></select>'; }
								else { echo '<option value="homme">homme</option><option value="femme" selected>femme</option></select>'; }
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">adresse</td><td><input type="text" name="adresse" value="'.$_POST['adresse'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">cp</td><td><input type="text" name="cp" value="'.$_POST['cp'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">ville</td><td><input type="text" name="ville" value="'.$_POST['ville'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">pays</td><td><input type="text" name="pays" value="'.$_POST['pays'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">téléphone fixe</td><td><input type="text" name="tel_fixe" value="'.$_POST['tel_fixe'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">téléphone portable</td><td><input type="text" name="tel_mobile" value="'.$_POST['tel_mobile'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">email</td><td><input type="text" name="email" value="'.$_POST['email'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">pièce d\'identité : type</td><td>';
							echo '<select name="identite_type">';
								if ($_POST['identite_type']=='cni') { echo '<option value="cni" selected>carte nationale d\'identité</option>'; }
								else { echo '<option value="cni">carte nationale d\'identité</option>'; }
								if ($_POST['identite_type']=='passeport') { echo '<option value="passeport" selected>passeport</option>'; }
								else { echo '<option value="passeport">passeport</option>'; }
								if ($_POST['identite_type']=='permis') { echo '<option value="permis" selected>permis de conduire</option>'; }
								else { echo '<option value="permis">permis de conduire</option>'; }
								if ($_POST['identite_type']=='militaire') { echo '<option value="militaire" selected>carte militaire</option>'; }
								else { echo '<option value="militaire">carte militaire</option>'; }
								echo '</select>';
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">pièce d\'identité : n°</td><td><input type="text" name="identite_num" value="'.$_POST['identite_num'].'"></td></tr>';
							echo '<tr><td class="ligne_modif">pièce d\'identité : date</td><td>';
								echo	'<input type="text" name="identite_date_jour" value="'.$_POST['identite_date_jour'].'" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="identite_date_mois" value="'.$_POST['identite_date_mois'].'" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="identite_date_annee" value="'.$_POST['identite_date_annee'].'" size="4" maxlength="4">';
							echo '	</td></tr>';
							echo '	<tr><td class="ligne_modif">pièce d\'identité : lieu</td><td><input type="text" name="identite_lieu" value="'.$_POST['identite_lieu'].'"></td></tr>';
							echo '	<tr><td class="ligne_modif">catégorie socio-pro</td><td>';
								echo '<select name="socio_pro">';
								if ($_POST['socio_pro']=='etudiant') { echo '<option value="etudiant" selected>étudiant(e)</option>'; }
								else { echo '<option value="etudiant">étudiant(e)</option>'; }
								if ($_POST['socio_pro']=='retraite') { echo '<option value="retraite" selected>retraité(e)</option>'; }
								else { echo '<option value="retraite">retraité(e)</option>'; }
								if ($_POST['socio_pro']=='demandeur_emploi') { echo '<option value="demandeur_emploi" selected>demandeur d\'emploi</option>'; }
								else { echo '<option value="demandeur_emploi">demandeur d\'emploi</option>'; }
								if ($_POST['socio_pro']=='sans_emploi') { echo '<option value="sans_emploi" selected>sans emploi</option>'; }
								else { echo '<option value="sans_emploi">sans emploi</option>'; }
								if ($_POST['socio_pro']=='salarie_prive') { echo '<option value="salarie_prive" selected>salarié(e) du secteur privé</option>'; }
								else { echo '<option value="salarie_prive">salarié(e) du secteur privé</option>'; }
								if ($_POST['socio_pro']=='salarie_public') { echo '<option value="salarie_public" selected>salarié(e) du secteur public</option>'; }
								else { echo '<option value="salarie_public">salarié(e) du secteur public</option>'; }
								if ($_POST['socio_pro']=='autre') { echo '<option value="autre" selected>autre</option>'; }
								else { echo '<option value="autre">autre</option>'; }
								echo '</select>';
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">note</td><td><textarea name="note">'.$_POST['note'].'</textarea></td></tr>';
							echo	'<tr><td>&nbsp;</td><td><a href="fiche.php?session='.$id.'&idinscrit='.$_GET['idinscrit'].'" target="_self"><input type="text" class="fiche_modif_submit" value="&laquo; annuler"></a> ';
							echo	'<input type="submit" name="ok" class="fiche_modif_submit" value="enregistrer les modifications"></td></tr>';
							echo '</FORM>';
							echo '</div><!-- fin fiche_identite -->';
						}
						if ($_GET['rub']=='modif_confirm_fiche') {
							echo '<div>'.$message.'</div>';
						}
						if ($_GET['rub']=='ajout_fiche') {
							echo '<div id="fiche_identite">';
							echo '<FORM method="post" name="fiche_ajout" action="fiche.php?session='.$id.'&idinscrit='.$_GET['idinscrit'].'&rub=ajout_confirm_fiche" class="formulaireAjoutFiche">';
							echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
							echo '	<tr><td class="ligne_modif">numéro d\'adhérent bibliothèque</td><td><input type="text" name="adherent_num"></td></tr>';
							echo '	<tr><td class="ligne_modif">membre du personnel ?</td><td>';
								echo '<select name="personnel">';
								echo '<option value="oui">oui</option><option value="non" selected>non</option></select>';
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">nom</td><td><input type="text" name="nom"></td></tr>';
							echo '	<tr><td class="ligne_modif">prénom</td><td><input type="text" name="prenom"></td></tr>';
							echo '<tr><td class="ligne_modif">date de naissance</td><td>';
								echo	'<input type="text" name="naissance_date_jour" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="naissance_date_mois" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="naissance_date_annee" size="4" maxlength="4">';
							echo '	</td></tr>';
							echo '	<tr><td class="ligne_modif">majeur ?</td><td>';
								echo '<select name="majeur">';
								echo '<option value="oui" selected>oui</option><option value="non">non</option></select>';
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">sexe</td><td>';
								echo '<select name="sexe">';
								echo '<option value="homme" selected>homme</option><option value="femme">femme</option></select>';
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">adresse</td><td><input type="text" name="adresse"></td></tr>';
							echo '	<tr><td class="ligne_modif">cp</td><td><input type="text" name="cp"></td></tr>';
							echo '	<tr><td class="ligne_modif">ville</td><td><input type="text" name="ville"></td></tr>';
							echo '	<tr><td class="ligne_modif">pays</td><td><input type="text" name="pays" value="France"></td></tr>';
							echo '	<tr><td class="ligne_modif">téléphone fixe</td><td><input type="text" name="tel_fixe"></td></tr>';
							echo '	<tr><td class="ligne_modif">téléphone portable</td><td><input type="text" name="tel_mobile"></td></tr>';
							echo '	<tr><td class="ligne_modif">email</td><td><input type="text" name="email"></td></tr>';
							echo '	<tr><td class="ligne_modif">pièce d\'identité : type</td><td>';
							echo '<select name="identite_type">';
								echo '<option value="cni" selected>carte nationale d\'identité</option>';
								echo '<option value="passeport">passeport</option>';
								echo '<option value="permis">permis de conduire</option>';
								echo '<option value="militaire">carte militaire</option>';
								echo '</select>';
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">pièce d\'identité : n°</td><td><input type="text" name="identite_num"></td></tr>';
							echo '<tr><td class="ligne_modif">pièce d\'identité : date</td><td>';
								echo	'<input type="text" name="identite_date_jour" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="identite_date_mois" size="2" maxlength="2"> / ';
								echo	'<input type="text" name="identite_date_annee" size="4" maxlength="4">';
							echo '	</td></tr>';
							echo '	<tr><td class="ligne_modif">pièce d\'identité : lieu</td><td><input type="text" name="identite_lieu"></td></tr>';
							echo '	<tr><td class="ligne_modif">catégorie socio-pro</td><td>';
								echo '<select name="socio_pro">';
									echo '<option value="etudiant">étudiant(e)</option>';
									echo '<option value="retraite">retraité(e)</option>';
									echo '<option value="demandeur_emploi">demandeur d\'emploi</option>';
									echo '<option value="sans_emploi">sans emploi</option>';
									echo '<option value="salarie_prive" selected>salarié(e) du secteur privé</option>';
									echo '<option value="salarie_public">salarié(e) du secteur public</option>';
									echo '<option value="autre">autre</option>';
								echo '</select>';
							echo '</td></tr>';
							echo '	<tr><td class="ligne_modif">note</td><td><textarea name="note"></textarea></td></tr>';
							echo	'<tr><td><input type="submit" name="ok" class="fiche_ajout_submit" value="enregistrer cet(te) utilisateur(trice)"></td></tr>';
							echo '</FORM>';
							echo '</div><!-- fin fiche_identite -->';
						}
						if ($_GET['rub']=='ajout_confirm_fiche') {
							echo '<div>'.$message.'</div>';
						}
					}
					// --- AFFICHAGE DE LA FICHE ---------------------------------------------------------------------------------------------------------------------------
					else {
					if ($tabInfosInscrit[7]=='femme') { $feminin='e'; } else { $feminin=''; }
					echo '<div id="fiche_identite">';
						echo '<div id="identite_nomprenom"><img src="img/ico/'.$tabInfosInscrit[7].'_64x64.png" border="0" title="'.$tabInfosInscrit[0].'"> <font color="#999">N°'.$tabInfosInscrit[0].'</font> '.$tabInfosInscrit[4].' <span style="text-transform:uppercase;font-weight:bold;">'.$tabInfosInscrit[3].'</span></div>';
						$age = $aujourdhui-$tabInfosInscrit[5];
						echo '<div id="identite_age"><img src="img/ico/age.png" border="0" title="âge"> <b>'.$age.' ans</b> (né'.$feminin.' le '.transformeTime($tabInfosInscrit[5]).')</div>';
						echo '<div id="identite_adresse"><img src="img/ico/adresse.png" border="0" title="adresse"> '.$tabInfosInscrit[8].' &middot; '.$tabInfosInscrit[9].' <b>'.$tabInfosInscrit[10].'</b>';
						if ($tabInfosInscrit[11]!='France') { echo ' &middot; '.$tabInfosInscrit[11]; }
						echo '</div>';
						if (!empty($tabInfosInscrit[12])) { echo '<div id="identite_telfixe"><img src="img/ico/fixe.png" border="0" title="téléphone fixe"> '.$tabInfosInscrit[12].'</div>'; }
						if (!empty($tabInfosInscrit[13])) { echo '<div id="identite_telmobile"><img src="img/ico/mobile.png" border="0" title="téléphone portable"> '.$tabInfosInscrit[13].'</div>'; }
						if (!empty($tabInfosInscrit[14])) { echo '<div id="identite_email"><img src="img/ico/email.png" border="0" title="envoyer un message"> <a href="mailto:'.$tabInfosInscrit[14].'">'.$tabInfosInscrit[14].'</a></div>'; }
						if ($tabInfosInscrit[1]!=0) { echo '<div id="identite_ligne"><img src="img/ico/mediatheque.png" border="0" title="adhérent'.$feminin.'"> Adhérent'.$feminin.' à la médiathèque sous le n° '.$tabInfosInscrit[1].'</div>'; }
						if ($tabInfosInscrit[2]=='oui') { echo '<div id="identite_ligne"><img src="img/ico/personnel.png" border="0" title="membre du personnel"> Membre du personnel de la médiathèque.</div>'; }
						if (!empty($tabInfosInscrit[16])) { echo '<div id="identite_piece"><img src="img/ico/identite.png" border="0" title="pièce d\'identité"> '.$tabInfosInscrit[15].' n° '.$tabInfosInscrit[16].', '.$tabInfosInscrit[18].', '.transformeTime($tabInfosInscrit[17]).'</div>'; }
						echo '<div id="identite_sociopro"><img src="img/ico/malette.png" border="0" title="catégorie socio-professionnelle"> '.$tabInfosInscrit[19].'</div>';
						echo '<div id="identite_inscription"><img src="img/ico/inscription.png" border="0" title="inscription à l\'espace multimédia"> '.transformeTime($tabInfosInscrit[20]).'</div>';
						if (!empty($tabInfosInscrit[21])) { echo '<div id="identite_note" style="padding-right:140px;"><img src="img/ico/commentaire.png" border="0" title="commentaires sur cet utilisateur"> <font color="#999">'.$tabInfosInscrit[21].'</font></div>'; }
					echo '</div><!-- fin fiche_identite -->';
					// Modifier la fiche ---------------------------------------------
					echo '<div style="text-align:right; margin: -60px 0px 40px 0px;">';
					echo	'<FORM method="post" name="fiche_modif" action="fiche.php?session='.$id.'&idinscrit='.$_GET['idinscrit'].'&rub=modif_fiche" class="formulaireModifFiche">';
						echo	'<input type="hidden" name="adherent_num" value="'.$tabInfosInscrit[1].'">';
						echo	'<input type="hidden" name="personnel" value="'.$tabInfosInscrit[2].'">';
						echo	'<input type="hidden" name="nom" value="'.$tabInfosInscrit[3].'">';
						echo	'<input type="hidden" name="prenom" value="'.$tabInfosInscrit[4].'">';
						$tempNaissance = explode('-',$tabInfosInscrit[5]);
						echo	'<input type="hidden" name="naissance_date_jour" value="'.$tempNaissance[2].'">';
						echo	'<input type="hidden" name="naissance_date_mois" value="'.$tempNaissance[1].'">';
						echo	'<input type="hidden" name="naissance_date_annee" value="'.$tempNaissance[0].'">';
						//echo	'<input type="hidden" name="naissance_date" value="'.$tabInfosInscrit[5].'">';
						echo	'<input type="hidden" name="majeur" value="'.$tabInfosInscrit[6].'">';
						echo	'<input type="hidden" name="sexe" value="'.$tabInfosInscrit[7].'">';
						echo	'<input type="hidden" name="adresse" value="'.$tabInfosInscrit[8].'">';
						echo	'<input type="hidden" name="cp" value="'.$tabInfosInscrit[9].'">';
						echo	'<input type="hidden" name="ville" value="'.$tabInfosInscrit[10].'">';
						echo	'<input type="hidden" name="pays" value="'.$tabInfosInscrit[11].'">';
						echo	'<input type="hidden" name="tel_fixe" value="'.$tabInfosInscrit[12].'">';
						echo	'<input type="hidden" name="tel_mobile" value="'.$tabInfosInscrit[13].'">';
						echo	'<input type="hidden" name="email" value="'.$tabInfosInscrit[14].'">';
						echo	'<input type="hidden" name="identite_type" value="'.$tabInfosInscrit[15].'">';
						echo	'<input type="hidden" name="identite_num" value="'.$tabInfosInscrit[16].'">';
						$tempIdentite = explode('-',$tabInfosInscrit[17]);
						echo	'<input type="hidden" name="identite_date_jour" value="'.$tempIdentite[2].'">';
						echo	'<input type="hidden" name="identite_date_mois" value="'.$tempIdentite[1].'">';
						echo	'<input type="hidden" name="identite_date_annee" value="'.$tempIdentite[0].'">';
						//echo	'<input type="hidden" name="identite_date" value="'.$tabInfosInscrit[17].'">';
						echo	'<input type="hidden" name="identite_lieu" value="'.$tabInfosInscrit[18].'">';
						echo	'<input type="hidden" name="socio_pro" value="'.$tabInfosInscrit[19].'">';
						echo	'<input type="hidden" name="inscription_date" value="'.$tabInfosInscrit[20].'">';
						echo	'<input type="hidden" name="note" value="'.$tabInfosInscrit[21].'">';
						echo	'<input type="submit" name="ok" class="fiche_modif_submit" value="modifier cette fiche">';
						echo	'</FORM>';
					echo '</div>';
// Autorisation parentale --------------------------------------------------------------------------------------------------------------------------------------------------------------------
					if ($tabInfosInscrit[6]=='non') {
						echo '<div id="fiche_session">';
							// Recherche des autorisations parentales de l'idinscrit
							$tabAutorisations = autorisations ($_GET['idinscrit']);
								// DERNIERE AUTORISATION : Est-elle valide ET signée ?
								if ($tabAutorisations==0) { // aucune autorisation pour le moment
									echo '<div style="height:48px;background:transparent url(img/ico/autorisation_non.png) center left no-repeat;padding-left:70px;font-size:12px;color:red;">Aucune autorisation parentale n\'a été signée pour le moment.</div>';
									// Affichage du formulaire de demande d'autorisation
									echo '<div style="text-align:right;margin-top:-40px;"><a href="autorisation_parentale.php?session='.$id.'&rub=ajout_fiche&idinscrit='.$_GET['idinscrit'].'" target="_self"><input type="submit" name="ok" class="rech_submit" value="ajouter une autorisation parentale"></a></div>';
								}
								else { // il existe au moins une autorisation
									// Paramétrages des logo et couleur : fin_date - 1 mois / signature
									$nbJoursAutorisation = diffDate($aujourdhui,$tabAutorisations[0][3]); // nombres positifs = valeur absolue = abs()
									if ($nbJoursAutorisation>=0) { $signe='+'; } else { $signe='-'; } // - = il reste encore du temps // + = la date limite est dépassée
									$nbMoisAutorisation = abs(ceil($nbJoursAutorisation/30));
									if ($signe=='+') { $color='red'; $logo='autorisation_non.png'; $texteAut='L\'autorisation est dépassée de '; $lienAut = '<a href="autorisation_parentale.php?session='.$id.'&rub=ajout_fiche&idinscrit='.$_GET['idinscrit'].'" target="_self"><input type="submit" name="ok" class="rech_submit" value="ajouter une autorisation parentale"></a>'; }
									else {
										$texteAut='Il reste ';
										if ($tabAutorisations[0][17]=='oui') 	{ $color='#333'; $logo='autorisation_oui.png'; $lienAut='<a href="autorisation_parentale.php?session='.$id.'&idautorisation='.$tabAutorisations[0][0].'" target="_self"><input type="submit" name="ok" class="rech_submit" value="voir l\'autorisation"></a>'; } // autorisation en cours
										if ($tabAutorisations[0][17]=='non') 	{ $color='red'; $logo='autorisation_non.png'; $lienAut = '<a href="autorisation_parentale.php?session='.$id.'&rub=ajouter&idinscrit='.$_GET['idinscrit'].'" target="_self"><input type="submit" name="ok" class="rech_submit" value="ajouter une autorisation parentale"></a>'; } // autorisation dépassée
										if ((-60<=$nbJoursAutorisation)&&($nbJoursAutorisation<=0)) 			{ $color='#F90'; $logo='autorisation_oui.png'; $lienAut = '<a href="autorisation_parentale.php?session='.$id.'&rub=ajout_fiche&idinscrit='.$_GET['idinscrit'].'" target="_self"><input type="submit" name="ok" class="rech_submit" value="ajouter une autorisation parentale"></a> <a href="autorisation_parentale.php?session='.$id.'&idautorisation='.$tabAutorisations[0][0].'" target="_self"><input type="submit" name="ok" class="rech_submit" value="voir l\'autorisation"></a>'; } // autorisation bientôt terminée
									}
									echo '<div style="height:48px;background:transparent url(img/ico/'.$logo.') center left no-repeat;padding-left:70px;font-size:12px;color:'.$color.';">';
									echo '<div>Autorisation parentale valable jusqu\'au <b>'.transformeTime($tabAutorisations[0][3]).'</b><br>'.$texteAut.' <b>'.abs($nbJoursAutorisation).' jours</b> (<b>'.$nbMoisAutorisation.' mois</b>)</div>';
									echo '<div>Signataire : <b>'.$tabAutorisations[0][5].' <span style="text-transform:uppercase;">'.$tabAutorisations[0][4].'</span></b></div>';
									// Affichage du formulaire de demande d'autorisation
									echo '<div style="text-align:right;margin-top:-40px;">'.$lienAut.'</div>';
									echo '</div>';
								}
						echo '</div>';
					}
// Session : en cours : arrêter la session / proposer un poste -------------------------------------------------------------------------------------------------------------------------------
					echo '<div id="fiche_session">';
						$sessionCourante = visiteEnCours ($idinscrit);
						// 0= pas encore de session / sinon = tableau
						if ($sessionCourante==0) {
							// 1- Des places sont disponibles : proposer un poste
							echo '<div>';
							$nbPostes = nombre_de_postes(); $cptLibres=0; for ($m=0;$m<$nbPostes;$m++) { if (posteLibre($m+1)==-1) { $cptLibres++; } }
							if ($cptLibres==0) { echo '<div style="font-size:14px;color:#333;border:1px dashed #999;background-color:#EAEAEA;padding:10px 20px;margin-bottom:20px;">Aucun poste n\'est disponible pour le moment &raquo; <a href="accueil.php?session='.$id.'" target="_self">consultez le tableau de bord</a>.</div>'; }
							else {	
								echo	'<FORM method="post" name="session_nouvelle" action="visite.php?session='.$id.'&rub=creer_visite&idinscrit='.$idinscrit.'" class="formulaireRecherche">';
									
									echo	'<select name="poste_num" class="rech_select" onFocus="this.className=\'rech_selectHover\'" onBlur="this.className=\'rech_select\'">';
									for ($m=0;$m<$nbPostes;$m++) { if (posteLibre($m+1)==-1) { echo	'<option value="'.($m+1).'" class="rech_option">poste '.($m+1).'</option>'; } }
									echo	'</select> ';
									echo	'<select name="activite" class="rech_select" onFocus="this.className=\'rech_selectHover\'" onBlur="this.className=\'rech_select\'">';
										echo '<option value="cdrom_jeux">cdrom de jeux</option>';
										echo '<option value="jeux_en_ligne">jeux en ligne</option>';
										echo '<option value="jeux_en_reseau" selected>jeux en réseau</option>';
										echo '<option value="messagerie">messagerie</option>';
										echo '<option value="blog-forum">blog-forum</option>';
										echo '<option value="recherches">recherches</option>';
										echo '<option value="bureautique">bureautique</option>';
										echo '<option value="cdrom_documentaires">cdrom documentaires</option>';
									echo '</select> ';
									echo	'<input type="submit" name="ok" class="rech_submit" value="proposer un poste">';
								echo	'</FORM>';
								echo '</div>';
							}	
						}
						else { // $sessionCourante [0]=id [1]= poste_num [2]=jour_date [3]=heure_arrivee [4]=heure_limite [5]=heure_depart [6]=duree_minutes [7]=activite [8]=note
							//echo '<img src="img/ico/ecran_utilise.png" border="0" title="cet utilisateur a une session en cours" style="position:relative;float:left;margin:0px 40px 20px 0px;">';
							echo '<div style="background: transparent url(img/ico/ecran_utilise.png) center left no-repeat; padding-left: 150px;padding-bottom:20px; border-bottom: 1px dotted #DDD; margin-bottom: 20px;">';
							$duree = transformeHeure(difheure($sessionCourante[3],$heure_maintenant)); // Depuis combien de temps est-il sur ce poste ?
							$dureejour = dureeJour($aujourdhui,$idinscrit); // Durée du jour ? + nb de postes (cumul)
							if ($dureejour==0) { /* Aucune session pour cette date */ } else { /*[0]=cumul [1]=nbSessions*/ $dureeTotale=$dureejour[0]; $nbSessionsJour=$dureejour[1]; }
							echo 'Poste n° <b>'.$sessionCourante[1].'</b> depuis <b>'.transformeHeure($sessionCourante[3]).'</b>, soit <b>'.$duree.'</b><br>';
							if ($heure_maintenant < $sessionCourante[4]) { $couleur='green'; }
							else { $couleur='red'; }
							echo 'Fin de session à <span style="color:'.$couleur.';font-size:20px;font-weight:bold;">'.transformeheure($sessionCourante[4]).'</span><br>';
							echo '<font color="#999">Durée totale pour aujourd\'hui : <b>'.transformeHeure($dureeTotale).'</b>';
							if ($nbSessionsJour!=1) { echo ' ('.$nbSessionsJour.' sessions)'; }
							echo '<br>Activité principale : '.$sessionCourante[7].'</font><br>';
							echo '<a href="visite.php?session='.$id.'&rub=terminer_session&idsession='.$sessionCourante[0].'&idinscrit='.$idinscrit.'" target="_self"><input type="submit" name="ok" class="bouton_fin_session" value="terminer la session"></a>';
							echo '</div>';
						}
						// Dernière visite ?
						$limiteVisites = limiteVisites();
						$tabVisitesInscrit = visitesInscrit($idinscrit,$limiteVisites); // [0]=id [1]=poste_num [2]=jour_date [3]=heure_arrivee [4]=heure_limite [5]=heure_depart [6]=duree_minutes [7]=activite [8]=note
						//echo '<img src="img/ico/visites.png" border="0" title="cet utilisateur a une session en cours" style="position:relative;float:left;margin:0px 40px 20px 0px;">';
						//echo '<div style="background: transparent url(img/ico/visite.png) center left no-repeat; padding-left: 150px">';
						echo '<div>';
						if (count($tabVisitesInscrit)>=1) {
						echo '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #06C;margin-bottom:20px;">';
							echo '<tr bgcolor="#06C">';
								echo '<td class="listeVisites_titre">poste</td>';
								echo '<td class="listeVisites_titre">jour</td>';
								echo '<td class="listeVisites_titre">arrivée</td>';
								echo '<td class="listeVisites_titre">limite</td>';
								echo '<td class="listeVisites_titre">départ</td>';
								echo '<td class="listeVisites_titre">durée</td>';
								echo '<td class="listeVisites_titre">activité</td>';
								echo '<td class="listeVisites_titre">note</td>';
								echo '<td class="listeVisites_titre">&nbsp;</td>';
							echo '</tr>';
						for ($k=0;$k<count($tabVisitesInscrit);$k++) {
							if (pair($k)) { $bg=''; } else { $bg=' bgcolor="#FFC" '; }
							echo '<tr '.$bg.'>'; // affichage des interlignes colorées
								echo '<td class="listeVisites_texte">'.$tabVisitesInscrit[$k][1].'</td>';
								echo '<td class="listeVisites_texte">'.transformeTime($tabVisitesInscrit[$k][2]).'</td>';
								echo '<td class="listeVisites_texte">'.transformeHeure($tabVisitesInscrit[$k][3]).'</td>';
								echo '<td class="listeVisites_texte">'.transformeHeure($tabVisitesInscrit[$k][4]).'</td>';
								echo '<td class="listeVisites_texte">'.transformeHeure($tabVisitesInscrit[$k][5]).'</td>';
								echo '<td class="listeVisites_texte">'.transformeHeure($tabVisitesInscrit[$k][6]).'</td>';
								echo '<td class="listeVisites_texte">'.$tabVisitesInscrit[$k][7].'</td>';
								if ($tabVisitesInscrit[$k][8]=='') { $noteTexte='&nbsp;'; } else { $noteTexte='&bull;'; }
								echo '<td class="listeVisites_texte">'.$noteTexte.'</td>';
								echo '<td class="listeVisites_texte"><a href="visite.php?session='.$id.'&rub=voir&idvisite='.$tabVisitesInscrit[$k][0].'" target="_self" title="voir la fiche de cette session"><img src="img/ico/voir.png" border="0"></a></td>';
							echo '</tr>';
						}
						echo '</table>';
						}
						echo '</div>';
						$nbVI = count($tabVisitesInscrit);
						if ($nbVI==0) { $nbTotalVisites = 'Cet utilisateur n\'a encore jamais utilisé de poste.'; }
						if ($nbVI==1) { $nbTotalVisites = 'Cet utilisateur est venu <b>'.count($tabVisitesInscrit).' seule</b> fois depuis son inscription.'; }
						if ($nbVI>0) { $nbTotalVisites = 'Cet utilisateur est venu <b>'.count($tabVisitesInscrit).'</b> fois depuis son inscription.'; }
						echo '<div style="font-size:14px;color:#333;border:1px dashed #999;background-color:#EAEAEA;padding:10px 20px;margin-bottom:20px;">'.$nbTotalVisites.'</div>';
					// Nb de visites ?
					echo '</div><!-- fin fiche_menu -->';
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