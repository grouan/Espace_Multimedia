<?PHP 


	// Package de fonctions
	require_once ('fonctions/fonctions.php');
	connexion(); // Connexion � la bdd
	
	// Sessions
	@session_start();
	$id = session_id();	
	
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

<title>Syst�me de Gestion de l'Espace Multim�dia</title>

<?PHP
	// Redirection de la page
	if ( (isset($_GET['session'])) && ($_GET['session']==$id) ) { }
	else {
		/* Redirection */
		echo '<meta http-equiv="refresh" content="0;URL=index.php">'; /* 0 seconde */
	}
	// Calcul du r�sultat de la recherche
	// r�sultat = 0 > redirection vers formulaire d'inscription
	// r�sultat = 1 > redirection vers la fiche de l'utilisateur
	// r�sultat > 1 > retour tableau des id_utilisateurs
	$resultat = cherche($_POST['recherche_choix'],$_POST['recherche_texte'],$id);
	$nbResultats = count($resultat);
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
				affTitre('#6C0','recherche');	
				
					if ($nbResultats==0) { // Aucun r�sultat
						//echo '<div id="resultat"><font color="red">&raquo; Aucune personne inscrite ne correspond � votre recherche.</font></div>';
					}
					else { // Tableau des r�sultats
						// [$i][0=id][1=adherent_num][2=nom][3=prenom][4=naissance_age][5=sexe][6=inscription_date][7=majeur]
						//$resultat = cherche($_POST['recherche_choix'],$_POST['recherche_texte'],$id);
						//if ($nbResultats==0) { $message_resultat = 'n\'a retourn� <b>aucun</b> r�sultat.'; }
						//if ($nbResultats==1) { $message_resultat = 'a retourn� <b>un</b> seul r�sultat.'; }
						if ($nbResultats>1) { $message_resultat = 'a retourn� <b>'.$nbResultats.'</b> r�sultats.'; }
						echo '<div style="font-size:14px;color:#333;border:1px dashed #999;background-color:#EAEAEA;padding:10px 20px;margin-bottom:20px;">Votre recherche : &laquo; '.$_POST['recherche_choix'].' &raquo; et &laquo; '.$_POST['recherche_texte'].' &raquo; '.$message_resultat.'</div>';
						echo '<div id="resultat">';
						echo '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #AAA">';
							echo '<tr bgcolor="#CCC">';
								echo '<td class="listeVisites_titre">#</td>';
								echo '<td class="listeVisites_titre">F/H</td>';
								echo '<td class="listeVisites_titre">Nom</td>';
								echo '<td class="listeVisites_titre">Pr�nom</td>';
								echo '<td class="listeVisites_titre">�ge</td>';
								echo '<td class="listeVisites_titre">Inscription</td>';
								echo '<td class="listeVisites_titre">Autorisation parentale</td>';
							echo '</tr>';
						for ($j=0;$j<$nbResultats;$j++) {
							@setlocale("LC_TIME", "fr_FR");
							$aujourdhui = strftime("%Y-%m-%d");
							$age = $aujourdhui-$resultat[$j][4];
							if (pair($j)) { $bg=''; } else { $bg=' bgcolor="#FFC" '; }
							// affichage des interlignes color�es : if () {  } else { }
							echo '<tr '.$bg.'>';
								echo '<td class="listeVisites_texte"><a href="fiche.php?session='.$id.'&idinscrit='.$resultat[$j][0].'" target="_self" title="voir la fiche">'.$resultat[$j][0].'</a></td>';
								echo '<td class="listeVisites_texte"><img src="img/ico/'.$resultat[$j][5].'_20x20.png" border="0"></td>';
								echo '<td class="listeVisites_texte"><a href="fiche.php?session='.$id.'&idinscrit='.$resultat[$j][0].'" target="_self" title="voir la fiche">'.$resultat[$j][2].'</a></td>';
								echo '<td class="listeVisites_texte">'.$resultat[$j][3].'</td>';
								echo '<td class="listeVisites_texte">'.$age.' ans</td>';
								echo '<td class="listeVisites_texte">'.transformeTime($resultat[$j][6]).'</td>';
								if ($resultat[$j][7]=='oui') { echo '<td class="listeVisites_texte">&nbsp;</td>'; } // majeur
								else { // mineur > autorisation n�cessaire
									// Recherche des autorisations parentales de l'idinscrit
									$tabAutorisations = autorisations ($resultat[$j][0]);
									if ($tabAutorisations==0) { echo '<td class="listeVisites_texte" style="color:red">&empty;</td>'; }
									else {
										// Param�trages des logo et couleur : fin_date - 1 mois / signature
										$nbJoursAutorisation = diffDate($aujourdhui,$tabAutorisations[0][3]); // nombres positifs = valeur absolue = abs()
										if ($nbJoursAutorisation>=0) { $signe='+'; } else { $signe='-'; } // - = il reste encore du temps // + = la date limite est d�pass�e
										if ($signe=='+') { $color='red'; }
										else {
											if ($tabAutorisations[0][17]=='oui') 						{ $color='#333'; } // autorisation en cours
											if ($tabAutorisations[0][17]=='non') 						{ $color='red'; } // autorisation d�pass�e
											if ((-60<=$nbJoursAutorisation)&&($nbJoursAutorisation<=0)) { $color='#F90'; } // autorisation bient�t termin�e < 2 mois
										}
										echo '<td class="listeVisites_texte" style="color:'.$color.'">'.transformeTime($tabAutorisations[0][3]).'</td>';
									}
								}
							/*echo '<div id="resultatliste"><img src="img/ico/'.$resultat[$j][5].'_20x20.png" border="0"> <b>'.$resultat[$j][2].'</b> '.$resultat[$j][3].'<img src="img/ico/age.png" border="0" title="�ge"> '.$age.' ans <img src="img/ico/inscription.png" border="0" title="date d\'inscription � l\'espace multim�dia"> '.transformeTime($resultat[$j][6]).' [N�'.$resultat[$j][0].']</div></a>';*/
						}
						echo '</div>';
						//echo '<div style="font-size:11px;color:#999;"><a href="fiche.php?session='.$id.'&rub=ajout_fiche" target="_self" style="text-decoration:none;color:#900;">&raquo; Ajouter un(e) utilisateur(trice)</a></div>';
						echo	'<div><a href="fiche.php?session='.$id.'&rub=ajout_fiche" target="_self"><input type="submit" name="ok" class="rech_submit" value="ajouter un utilisateur"></a></div>';
					}
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