<?PHP	

	
	// --- DATE ----------------------------------------------------------------------------------------------------

// creation / modif = DATETIME : YYYY-MM-JJ 00:00:00
// @setlocale("LC_TIME", "fr_FR");
// $datetime = strftime("%Y-%m-%d");

// Affichage de la date du jour et de l'heure du moment
	function dateMaintenant () {
		@setlocale("LC_TIME", "fr_FR");
		echo '<div style="text-align:center;">';
		echo '<table width="80%" cellpadding="0" align="center" cellspacing="0" border="0" style="background-color:#FFF;margin:30px 0px;border:1px solid #333;"><tr>';
		echo '<td style="border-right:1px dotted #333;font-size:50px;font-weight:bold;">'.strftime("%d").'</td>';
		echo '<td>'.strftime("%A").'<br>'.strftime("%B").' '.strftime("%Y").'</td>';
		echo '</tr></table>';
		echo '</div>';
	}

// Nombre d'inscrits
	function nbInscrits () {
		$req	= 'SELECT id FROM stave_inscrit';
		$res	= @mysql_query($req);
		echo '<div style="border:1px solid #FC0;background-color:#FFC;color:#FC0;font-size:50px;padding:5px 3px;margin:0px 80px 20px 80px;font-weight:bold;">'.@mysql_num_rows($res).'<br><font style="font-weight:normal;font-size:20px;">inscrits</font></div>';
	}

// Récupération de l'adresse IP
	function get_ip() {
	    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
	   		elseif(isset($_SERVER['HTTP_CLIENT_IP'])) { $ip  = $_SERVER['HTTP_CLIENT_IP']; }
	    else { $ip = $_SERVER['REMOTE_ADDR']; }
	    return $ip; 
	}

// Affichage du formulaire d'identification
	function afficheFormIdent () {
		// Affichage
		echo	'<div id="identification">';
			echo	'<FORM method="post" name="identification" action="index.php?rub=access" class="formulaireIdent">';
				echo	'<div id="ident"><b>Identifiant</b></div><div id="ident"><input type="text" size="18px" title="identifiant" name="login" class="text" onFocus="this.className=\'textHover\'" onBlur="this.className=\'text\'"></div>';
				echo	'<div id="ident"><b>Mot de passe</b></div><div id="ident"><input type="password" size="15px" title="mot de passe" name="pass" class="text" onFocus="this.className=\'textHover\'" onBlur="this.className=\'text\'"></div>';
				echo	'<div id="ident"><input type="submit" name="ok" class="submit" value="se connecter"></div>';
			echo	'</FORM>';
		echo	'</div>';
	}

// Affichage du formulaire de changement de mot de passe
	function afficheFormChangeMdp ($idsession) {
		// Affichage
		echo	'<div id="identification">';
			echo	'<FORM method="post" name="change_mdp" action="admin.php?session='.$id.'&rub=change_mdp" class="formulaireIdent">';
				echo	'<div id="ident"><b>Identifiant</b></div><div id="ident"><input type="text" size="18px" title="identifiant" name="login" class="text" onFocus="this.className=\'textHover\'" onBlur="this.className=\'text\'"></div>';
				echo	'<div id="ident"><b>Ancien mot de passe</b></div><div id="ident"><input type="password" size="15px" title="mot de passe" name="pass_old" class="text" onFocus="this.className=\'textHover\'" onBlur="this.className=\'text\'"></div>';
				echo	'<div id="ident"><b>Nouveau mot de passe</b></div><div id="ident"><input type="password" size="15px" title="mot de passe" name="pass_new1" class="text" onFocus="this.className=\'textHover\'" onBlur="this.className=\'text\'"></div>';
				echo	'<div id="ident"><b>Nouveau mot de passe (bis)</b></div><div id="ident"><input type="password" size="15px" title="mot de passe" name="pass_new2" class="text" onFocus="this.className=\'textHover\'" onBlur="this.className=\'text\'"></div>';
				echo	'<div id="ident"><input type="submit" name="ok" class="submit" value="changer de mot de passe"></div>';
			echo	'</FORM>';
		echo	'</div>';
	}

// Affichage du formulaire de recherche
	function afficheFormRecherche ($id) {
		// Affichage
			echo	'<FORM method="post" name="recherche" action="recherche.php?session='.$id.'" class="formulaireRecherche">';
				echo	'<select name="recherche_choix" class="rech_select" onFocus="this.className=\'rech_selectHover\'" onBlur="this.className=\'rech_select\'">';
					echo	'<option value="nom_prenom" class="rech_option">&raquo; Nom et prénom</option>';
					echo	'<option value="nom" class="rech_option" selected>&raquo; Nom seul</option>';
					echo	'<option value="num_multimedia" class="rech_option">&raquo; N° d\'adhésion multimédia</option>';
					echo	'<option value="num_bibliotheque" class="rech_option">&raquo; N° de lecteur bibliothèque</option>';
				echo	'</select> ';
				echo	'<input type="text" size="20px" title="champ à rechercher" name="recherche_texte" class="rech_text" onFocus="this.className=\'rech_textHover\'" onBlur="this.className=\'rech_text\'"> ';
				echo	'<input type="submit" name="ok" class="rech_submit" value="rechercher">';
			echo	'</FORM>';
	}

// Affichage du formulaire d'inscription
	function afficheFormInscription ($session) {
		echo 'formulaire d\'inscription...';
	}

	// --- COLONNE DE GAUCHE ---------------------------------------------------------------------------------------------------------
	
	// Affichage de l'horloge
	function horloge ($taille) {
		echo	'<div id="horloge">';
		echo	'<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" '
				.'WIDTH="'.$taille.'px" HEIGHT="'.$taille.'px" id="relog" ALIGN="center" ALT="" TITLE="">'
			.'	<PARAM NAME="movie" VALUE="themes/Ace-Orange/images/relog.swf">'
			.'	<PARAM NAME="quality" VALUE="high">'
			.'	<PARAM NAME="BGCOLOR" VALUE="#FFF">'
			.'	<param NAME="wmode" value="TRansparent">'
			.'	<param NAME="menu" value="false">'
			.'	<EMBED SRC="relog.swf" quality="high" BGCOLOR="#FFF" WIDTH="'.$taille.'px" HEIGHT="'.$taille.'px" wmode="TRansparent" ALIGN="center" TYPE="application/x-shockwave-flash"'
				.' PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" menu="false">'
			.'	</EMBED>'
			.'</OBJECT>';
		echo	"</div>\r\n";
	}


	// --- TABLEAU DE BORD ---------------------------------------------------------------------------------------------------------------
	function affMenu ($id) {
		echo '<div id="menu"><a href="accueil.php?session='.$id.'" target="_self">tableau de bord</a> <a href="fiche.php?session='.$id.'&rub=ajout_fiche" target="_self">ajout d\'un utilisateur</a> <a href="admin.php?session='.$id.'" target="_self">administration</a> <a href="index.php" target="_self">déconnexion</a></div>';
	}
	function affTitre ($couleur,$titre) { // $couleur = '#xxx'
		echo '<div style="font-size:30px;border-left:16px solid '.$couleur.';padding-left:10px;color:#333;padding-bottom:5px;border-bottom:1px dotted #DDD;margin-bottom:20px;">'.$titre.'</div>';
	}



?>