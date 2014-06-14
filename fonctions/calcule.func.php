<?PHP	

	// --- DIVERS ------------------------------------------------------------------------------------------------
	function pair ($nombre) {
    	if ($nombre%2 == 0) { return 1; } return 0;
	}

	// --- DATES HEURES ------------------------------------------------------------------------------------------
	function diffDate($datedebut,$datefin){ 
		$tempdebut=explode('-',$datedebut); $tempfin=explode('-',$datefin);
		$today = date("d,m,Y");
		//echo $today; 
		$date = mktime(0, 0, 0, $tempdebut[1], $tempdebut[2], $tempdebut[0]);  
		$date2 = mktime(0, 0, 0, $tempfin[1], $tempfin[2], $tempfin[0]);  		 
		$diff = floor(($date - $date2) / (3600 * 24));  
		return $diff;
	}


	function difheure($heuredeb,$heurefin) { // retour : 00:00:00:
	   $hd=explode(":",$heuredeb);
	   $hf=explode(":",$heurefin);
	   $hd[0]=(int)($hd[0]);$hd[1]=(int)($hd[1]);$hd[2]=(int)($hd[2]);
	   $hf[0]=(int)($hf[0]);$hf[1]=(int)($hf[1]);$hf[2]=(int)($hf[2]);
	   if($hf[2]<$hd[2]){$hf[1]=$hf[1]-1;$hf[2]=$hf[2]+60;}
	   if($hf[1]<$hd[1]){$hf[0]=$hf[0]-1;$hf[1]=$hf[1]+60;}
	   if($hf[0]<$hd[0]){$hf[0]=$hf[0]+24;}
	   // taille des éléments
	   $heure = ($hf[0]-$hd[0]); 
	   if (strlen($heure)==1) { $heure = '0'.$heure; }
	   $minute = ($hf[1]-$hd[1]);
	   if (strlen($minute)==1) { $minute = '0'.$minute; }
	   $seconde = ($hf[2]-$hd[2]);
	   if (strlen($seconde)==1) { $seconde = '0'.$seconde; }
	   // retour
	   return ($heure.":".$minute.":".$seconde);
	}
	function sommeHeures ($tabHeures) { // 00:00:00
		$heure=0; $minute=0; $seconde=0;
		for ($i=0;$i<count($tabHeures);$i++) {
			$temp = explode(':',$tabHeures[$i]); // temp[0]=hh / temp[1]=mm / temp[2]=ss
			$temp[0]=(int)($temp[0]);$temp[1]=(int)($temp[1]);$temp[2]=(int)($temp[2]); // transtypage
			$heure = $heure + $temp[0];
			$minute = $minute + $temp[1];
			$seconde = $seconde + $temp[2];
		}
		// secondes
		//$cs = $seconde / 60; $rs = $seconde % 60; $rs = (int)($rs); // %=reste (modulo) 0.5=(0.5*60)=30s /= division entière
		// minutes
		//$minute = $minute + $rs;
		if ($minute>60) { $cm = $minute / 60; $rm = $minute % 60; $rm = (int)($rm); } else { $cm = $minute; $rm = 0; }
		// heures
		$heure = $heure + ceil($rm);
		return $heure.':'.ceil($cm).':00';//.$cs;
	}
	function dureeJour($date,$idinscrit) { // Durée du jour ? + nb de postes (cumul)
		@setlocale("LC_TIME", "fr_FR");
		$heure_maintenant = date('H:i:s');
		$aujourdhui = strftime("%Y-%m-%d");
		// Liste des sessions de cet inscrit à cette date
		$res = @mysql_query ('SELECT id,poste_num,heure_arrivee,heure_depart,duree_minutes,activite,note FROM stave_visite WHERE inscrit_id="'.$idinscrit.'" AND jour_date="'.$date.'" ORDER BY heure_arrivee ASC');
		$nbResult = @mysql_num_rows($res);
		if ($nbResult==0) { return 0; }
		if ($nbResult>=1) {
			for ($i=0; $blabla = @mysql_fetch_object($res); $i++) {
				if (($date==$aujourdhui) && ($blabla->heure_depart=='00:00:00')) { $tabCumul[$i] = difheure($blabla->heure_arrivee,$heure_maintenant); }
				else { $tabCumul[$i] = difheure($blabla->heure_arrivee,$blabla->heure_depart); }
			}
			$cumul = sommeHeures($tabCumul);
			// Retour : durée_totale + nb de sessions
			$tab[0] = $cumul; $tab[1] = $nbResult;
			return $tab; // [0]=cumul [1]=nbSessions
		}
	}

	// --- IDENTIFICATION ----------------------------------------------------------------------------------------
	function verifAccess($login,$md5pass) {
		$req = 'SELECT id FROM stave_victorhugo WHERE pseudo="'.$login.'" AND mdp="'.$md5pass.'" LIMIT 1';
		$res = @mysql_query ($req);
		return @mysql_num_rows($res); // 1 ou 0
	}


	// --- RECHERCHE ---------------------------------------------------------------------------------------------
	function cherche ($choix,$texte,$idsession) { // nom_prenom / nom / num_multimedia / num_bibliotheque
		// Paramétrage de la cible
		if ($choix=='nom_prenom') {
			$temp = explode (' ',$texte);
			$where = 'WHERE (nom LIKE "%%'.$temp[0].'%%" AND prenom LIKE "%%'.$temp[1].'%%") '
			.'OR (nom LIKE "%%'.$temp[1].'%%" AND prenom LIKE "%%'.$temp[0].'%%")'; }
		if ($choix=='nom') { $where = 'WHERE nom LIKE "%%'.$texte.'%%"'; }
		if ($choix=='num_multimedia') { $where = 'WHERE id LIKE "%%'.$texte.'%%"'; }
		if ($choix=='num_bibliotheque') { $where = 'WHERE adherent_num LIKE "%%'.$texte.'%%"'; }
		$req = 'SELECT id,adherent_num,nom,prenom,naissance_date,sexe,inscription_date,majeur FROM stave_inscrit '.$where.' ORDER BY nom ASC';
		$res = @mysql_query ($req);
		$nbResult = @mysql_num_rows($res);
		if ($nbResult==0) { echo '<meta http-equiv="refresh" content="0;URL=fiche.php?session='.$idsession.'&rub=ajout_fiche">'; }
		if ($nbResult==1) { while ($blabla = @mysql_fetch_object($res)) { echo '<meta http-equiv="refresh" content="0;URL=fiche.php?session='.$idsession.'&idinscrit='.$blabla->id.'">'; } }
		if ($nbResult>1) {
			for ($i=0; $blabla = @mysql_fetch_object($res); $i++) {
				$tab[$i][0] = $blabla->id;
				$tab[$i][1] = $blabla->adherent_num;
				$tab[$i][2] = $blabla->nom;
				$tab[$i][3] = $blabla->prenom;
				$tab[$i][4] = $blabla->naissance_date;
				$tab[$i][5] = $blabla->sexe;
				$tab[$i][6] = $blabla->inscription_date;
				$tab[$i][7] = $blabla->majeur;
			}
			return $tab;
		}
	}

// Informations sur l'inscrit
	function infosInscrit ($idinscrit) {
		$req	= 'SELECT id,adherent_num,personnel,nom,prenom,naissance_date,majeur,sexe,adresse,cp,ville,pays,tel_fixe,tel_mobile,email,identite_type,identite_num,identite_date,identite_lieu,socio_pro,inscription_date,note FROM stave_inscrit WHERE id="'.$idinscrit.'" LIMIT 1';
		$res	= @mysql_query($req);
		for ($i=0; $blabla = @mysql_fetch_object($res); $i++) {
			$tab[0] = $blabla->id;
			$tab[1] = $blabla->adherent_num;
			$tab[2] = $blabla->personnel;
			$tab[3] = $blabla->nom;
			$tab[4] = $blabla->prenom;
			$tab[5] = $blabla->naissance_date;
			$tab[6] = $blabla->majeur;
			$tab[7] = $blabla->sexe;
			$tab[8] = $blabla->adresse;
			$tab[9] = $blabla->cp;
			$tab[10] = $blabla->ville;
			$tab[11] = $blabla->pays;
			$tab[12] = $blabla->tel_fixe;
			$tab[13] = $blabla->tel_mobile;
			$tab[14] = $blabla->email;
			$tab[15] = $blabla->identite_type;
			$tab[16] = $blabla->identite_num;
			$tab[17] = $blabla->identite_date;
			$tab[18] = $blabla->identite_lieu;
			$tab[19] = $blabla->socio_pro;
			$tab[20] = $blabla->inscription_date;
			$tab[21] = $blabla->note;
		}
		return $tab;
	}
// Modification des informations d'un inscrit
	function modifInscrit ($idinscrit,$adherent_num,$personnel,$nom,$prenom,$naissance_date_jour,$naissance_date_mois,$naissance_date_annee,$majeur,$sexe,$adresse,$cp,$ville,$pays,$tel_fixe,$tel_mobile,$email,$identite_type,$identite_num,$identite_date_jour,$identite_date_mois,$identite_date_annee,$identite_lieu,$socio_pro,$inscription_date,$note) {
		$naissance_date = $naissance_date_annee.'-'.$naissance_date_mois.'-'.$naissance_date_jour;
		$identite_date = $identite_date_annee.'-'.$identite_date_mois.'-'.$identite_date_jour;
		$req = 'UPDATE stave_inscrit SET adherent_num="'.$adherent_num.'",personnel="'.$personnel.'",nom="'.$nom.'",prenom="'.$prenom.'",naissance_date="'.$naissance_date.'",majeur="'.$majeur.'",sexe="'.$sexe.'",adresse="'.$adresse.'",cp="'.$cp.'",ville="'.$ville.'",pays="'.$pays.'",tel_fixe="'.$tel_fixe.'",tel_mobile="'.$tel_mobile.'",email="'.$email.'",identite_type="'.$identite_type.'",identite_num="'.$identite_num.'",identite_date="'.$identite_date.'",identite_lieu="'.$identite_lieu.'",socio_pro="'.$socio_pro.'",inscription_date="'.$inscription_date.'",note="'.$note.'"  WHERE id="'.$idinscrit.'" LIMIT 1';
		$res = @mysql_query ($req);
		if ($res) { return 1; } else { return 0; }
	}
// Ajout d'un utilisateur (inscrit)
	function ajoutInscrit ($adherent_num,$personnel,$nom,$prenom,$naissance_date_jour,$naissance_date_mois,$naissance_date_annee,$majeur,$sexe,$adresse,$cp,$ville,$pays,$tel_fixe,$tel_mobile,$email,$identite_type,$identite_num,$identite_date_jour,$identite_date_mois,$identite_date_annee,$identite_lieu,$socio_pro,$inscription_date,$note) {
		$inscription_date = strftime("%Y-%m-%d");
		$naissance_date = $naissance_date_annee.'-'.$naissance_date_mois.'-'.$naissance_date_jour;
		$identite_date = $identite_date_annee.'-'.$identite_date_mois.'-'.$identite_date_jour;
		$req = 'INSERT INTO stave_inscrit (adherent_num,personnel,nom,prenom,naissance_date,majeur,sexe,adresse,cp,ville,pays,tel_fixe,tel_mobile,email,identite_type,identite_num,identite_date,identite_lieu,socio_pro,inscription_date,note) VALUES ("'.$adherent_num.'","'.$personnel.'","'.$nom.'","'.$prenom.'","'.$naissance_date.'","'.$majeur.'","'.$sexe.'","'.$adresse.'","'.$cp.'","'.$ville.'","'.$pays.'","'.$tel_fixe.'","'.$tel_mobile.'","'.$email.'","'.$identite_type.'","'.$identite_num.'","'.$identite_date.'","'.$identite_lieu.'","'.$socio_pro.'","'.$inscription_date.'","'.$note.'")';
		$res = @mysql_query ($req);
		if ($res) { return @mysql_insert_id($res); } else { return -1; }
	}
// Terminer une session
	function terminerVisite ($idsession,$idinscrit) {
		$tabVisite = visiteEnCours ($idinscrit);
		// Indication de l'heure de départ
		@setlocale("LC_TIME", "fr_FR");
		$heure_depart = date('H:i:s');
		// Calcul de la durée		
		$duree_minutes = difheure($tabVisite[3],$heure_depart);
		$req = 'UPDATE stave_visite SET heure_depart="'.$heure_depart.'",duree_minutes="'.$duree_minutes.'" WHERE id="'.$idsession.'" LIMIT 1';
		$res = @mysql_query ($req);
		if ($res) { return 1; } else { return 0; }
	}
// Modification d'une visite
	function modifVisite ($poste_num,$jour_date,$heure_arrivee,$heure_limite,$activite,$note) {
		$req = 'UPDATE stave_visite SET poste_num="'.$poste_num.'",$jour_date="'.$jour_date.'",heure_arrivee="'.$heure_arrivee.'",heure_limite="'.$heure_limite.'",activite="'.$activite.'",note="'.$note.'" LIMIT 1';
		$res = @mysql_query ($req);
		if ($res) { return 1; } else { return 0; }
	}
// Ajout d'une visite
	function creerVisite ($idinscrit,$poste,$activite) {
		// Date et heure
		@setlocale("LC_TIME", "fr_FR");
		$aujourdhui = strftime("%Y-%m-%d");
		$heure_maintenant = date('H:i:s');
		$h = (int)(date('H')); $m = (int)(date('i')); $s = (int)(date('s'));
		if ($m<30) { $heure_limite = $h.':'.($m+30).':'.$s; }
		else {
			$minutes = 30+(60-$m);
			$heures = ($h+1);
			$heure_limite = $heures.':'.$minutes.':'.$s;
		}
		$req = 'INSERT INTO stave_visite (poste_num,jour_date,heure_arrivee,heure_limite,inscrit_id,activite) VALUES ("'.$poste.'","'.$aujourdhui.'","'.$heure_maintenant.'","'.$heure_limite.'","'.$idinscrit.'","'.$activite.'")';
		$res = @mysql_query($req);
		if ($res) { return @mysql_insert_id($res); } else { return -1; }
	}


// Mise en forme de la date (FICHE)
	function transformeDatetime ($datetime) { // YYYY-MM-JJ ////////////00:00:00
		//$t1 = explode (' ',$datetime); 	// [0]=YYYY-MM-JJ / [1]=00:00:00
		$t2 = explode ('-',$datetime); //('-',$t1[0]);		// [0]=YYYY / [1]=MM / [2]=JJ
		$t3 = explode (':',$t1[1]);		// [0]=heures / [1]=minutes / [2]=secondes
		$nouvelledate = 	$t2[2]."/".$t2[1]."/".$t2[0];
	//	$nouvelledate .=	" (".$t3[0]."h".$t3[1]."min".$t3[3]."sec.)";
		return $nouvelledate;
	}
	function transformeTime ($time) { // YYYY-MM-JJ
		$t1 = explode ('-',$time); 	// [0]=YYYY / [1]=MM / [2]=JJ
		$nouvelledate = $t1[2]."/".$t1[1]."/".$t1[0];
		return $nouvelledate;
	}
	function transformeHeure ($heure) { // hh:mm:ss
		$t = explode (':',$heure); // [0]=hh [1]=mm [2]=ss
		$h = (int)($t[0]); $m = (int)($t[1]); $s = (int)($t[2]);
		if (($h==0)||($h==00)) { $heure=''; } else { $heure=$h.'h'; }
		$nouvelleheure = $heure.$m.'min';
		return $nouvelleheure;
	}

	// --- SESSIONS -----------------------------------------------------------------------------------------------
	// L'inscrit a-t-il une session en cours ? (ie : commencée et non encore cloturée)
	function visiteEnCours ($idinscrit) { // 0= pas encore de session / sinon = tableau
		@setlocale("LC_TIME", "fr_FR");
		$jour_date = strftime("%Y-%m-%d");
		$heure_maintenant = date('H:i:s'); // hh:mm:ss
		$res	=	@mysql_query('SELECT id,poste_num,jour_date,heure_arrivee,heure_limite,heure_depart,duree_minutes,activite,note FROM stave_visite WHERE inscrit_id="'.$idinscrit.'" AND jour_date="'.$jour_date.'" AND heure_depart="00:00:00" LIMIT 1');
		if (@mysql_num_rows($res)==1) {
			while ($blabla = @mysql_fetch_object($res)) {
				$tab[0] = $blabla->id;
				$tab[1] = $blabla->poste_num;
				$tab[2] = $blabla->jour_date;
				$tab[3] = $blabla->heure_arrivee;
				$tab[4] = $blabla->heure_limite;
				$tab[5] = $blabla->heure_depart;
				$tab[6] = $blabla->duree_minutes;
				$tab[7] = $blabla->activite;
				$tab[8] = $blabla->note;
				return $tab; // [0]=id [1]= poste_num [2]=jour_date [3]=heure_arrivee [4]=heure_limite [5]=heure_depart [6]=duree_minutes [7]=activite [8]=note
			}
		} return 0;
	}
	// Y at-til un poste de libre ?
	function postesLibres () { // 0=tous les postes sont occupés / sinon tableau
		$nbPostes = nombre_de_postes();
		$cpt=0;
		for ($i=1;$i<=$nbPostes;$i++) {
			if (posteLibre($i)!=-1) { $tab[$cpt]=$i; $cpt++; }
		}
		if ($cpt==0) { return 0; } else { return $tab; } // 0 ou tab[num_poste]
	}
	// Ce poste est-il libre ? 0=occupé / 1=libre
	function posteLibre ($num_poste) {
		// Paramétrage date / heure du jour
		@setlocale("LC_TIME", "fr_FR");
		$jour_date = strftime("%Y-%m-%d");
		$heure_maintenant = date('H:i:s');
		// 1- Dernière visite sur ce poste pour aujourd'hui
		$req = 'SELECT id,poste_num,jour_date,heure_arrivee,heure_limite,heure_depart,duree_minutes,activite,note FROM stave_visite WHERE poste_num="'.$num_poste.'" AND jour_date="'.$jour_date.'" AND heure_depart="00:00:00" LIMIT 1'; //ORDER BY heure_arrivee DESC
		$res = @mysql_query($req);
			if (@mysql_num_rows($res)==1) { while ($blabla = @mysql_fetch_object($res)) { return $blabla->id; } } else { return -1; }
	}
	// Quel est le prochain poste libre et à quelle heure ?
	function prochainPoste () { // Dans le cas où tous les postes sont déjà occupés...
		@setlocale("LC_TIME", "fr_FR");
		$jour_date = strftime("%Y-%m-%d");
		$heure_maintenant = date('H:i:s');
		// Heure limite > heure_maintenant ET la plus proche
		$req = 'SELECT id,poste_num,jour_date,heure_arrivee,heure_limite,heure_depart,duree_minutes,activite,note FROM stave_visite WHERE jour_date="'.$jour_date.'" AND heure_limite>"'.$heure_maintenant.'" ORDER BY heure_limite ASC LIMIT 1';
		$res = @mysql_query($req);
		while ($blabla = @mysql_fetch_object($res)) {
			$tab[0] = $blabla->poste_num;
			$tab[1] = $blabla->heure_limite;
		}
		return $tab; // [0]=poste_num [1]=heure_limite
	}

	// --- INFORMATIONS ---------------------------------------------------------------------------------------
	// Informations sur un poste : Qui l'occupe ? Depuis quand ? Jusque quand ?
	function infosPoste ($numPoste) { // Dans le cas où ce poste est déjà occupé...
		@setlocale("LC_TIME", "fr_FR");
		$jour_date = strftime("%Y-%m-%d");
		$heure_maintenant = date('H:i:s');
		$req = 'SELECT id,poste_num,jour_date,heure_arrivee,heure_limite,heure_depart,duree_minutes,inscrit_id,activite,note FROM stave_visite WHERE jour_date="'.$jour_date.'" AND poste_num="'.$numPoste.'" ORDER BY heure_arrivee DESC LIMIT 1';
		$res = @mysql_query($req);
		while ($blabla = @mysql_fetch_object($res)) {
			$tab[0] = $blabla->heure_arrivee;
			$tab[1] = $blabla->heure_limite;
			$tab[2] = $blabla->inscrit_id;
			$tab[3] = $blabla->activite;
			$tab[4] = $blabla->note;
			$tab[6] = $blabla->id;
		}
		if ($tab[1]>$heure_maintenant) { $tab[5]='#9F0';/*#9F0:vert*/ } else { $tab[5]='#F33';/*#C66:rouge*/ }
		return $tab; // [0]=heure_arrivee [1]=heure_limite [2]=inscrit_id [3]=activite [4]=note [5]=couleur
	}


	// --- VISITES ---------------------------------------------------------------------------------
	// Liste des visites d'un inscrit
	function visitesInscrit ($idinscrit,$limitVisites) {
		if ($limitVisites==0) { $limit = ''; }
		else { $limit = 'LIMIT '.$limitVisites; }
		$req = 'SELECT id,poste_num,jour_date,heure_arrivee,heure_limite,heure_depart,duree_minutes,inscrit_id,activite,note FROM stave_visite WHERE inscrit_id="'.$idinscrit.'" AND heure_depart NOT IN ("00:00:00") ORDER BY jour_date DESC '.$limit;
		$res = @mysql_query($req);
		for ($i=0; $blabla = @mysql_fetch_object($res); $i++) {
			$tab[$i][0] = $blabla->id;
			$tab[$i][1] = $blabla->poste_num;
			$tab[$i][2] = $blabla->jour_date;
			$tab[$i][3] = $blabla->heure_arrivee;
			$tab[$i][4] = $blabla->heure_limite;
			$tab[$i][5] = $blabla->heure_depart;
			$tab[$i][6] = $blabla->duree_minutes;
			$tab[$i][7] = $blabla->activite;
			$tab[$i][8] = $blabla->note;
		}
		return $tab; // [0]=id [1]=poste_num [2]=jour_date [3]=heure_arrivee [4]=heure_limite [5]=heure_depart [6]=duree_minutes [7]=activite [8]=note
	}

// --- AUTORISATIONS PARENTALES ----------------------------------------------------------------------------------
	// Liste des autorisations d'un inscrit
	function autorisations ($idinscrit) {
		$req = 'SELECT id,inscrit_id,debut_date,fin_date,resp_nom,resp_prenom,resp_adresse,resp_cp,resp_ville,resp_pays,resp_tel_fixe,resp_tel_mobile,resp_email,resp_identite_type,resp_identite_num,resp_identite_date,resp_identite_lieu,resp_signature,note FROM stave_autorisation_parentale WHERE inscrit_id="'.$idinscrit.'" ORDER BY debut_date DESC';
		$res = @mysql_query($req);
		$nbRes = mysql_num_rows($res);
		if ($nbRes==0) { return 0; }
		else {
			for ($i=0; $blabla = @mysql_fetch_object($res); $i++) {
				$tab[$i][0] = $blabla->id;
				$tab[$i][1] = $blabla->inscrit_id;
				$tab[$i][2] = $blabla->debut_date;
				$tab[$i][3] = $blabla->fin_date;
				$tab[$i][4] = $blabla->resp_nom;
				$tab[$i][5] = $blabla->resp_prenom;
				$tab[$i][6] = $blabla->resp_adresse;
				$tab[$i][7] = $blabla->resp_cp;
				$tab[$i][8] = $blabla->resp_ville;
				$tab[$i][9] = $blabla->resp_pays;
				$tab[$i][10] = $blabla->resp_tel_fixe;
				$tab[$i][11] = $blabla->resp_tel_mobile;
				$tab[$i][12] = $blabla->resp_email;
				$tab[$i][13] = $blabla->resp_identite_type;
				$tab[$i][14] = $blabla->resp_identite_num;
				$tab[$i][15] = $blabla->resp_identite_date;
				$tab[$i][16] = $blabla->resp_identite_lieu;
				$tab[$i][17] = $blabla->resp_signature;
				$tab[$i][18] = $blabla->note;
			}
		return $tab;
		}	
	}
	// Liste des autorisations d'un inscrit
	function infosAutorisation ($idautorisation) {
		$req = 'SELECT id,inscrit_id,debut_date,fin_date,resp_nom,resp_prenom,resp_adresse,resp_cp,resp_ville,resp_pays,resp_tel_fixe,resp_tel_mobile,resp_email,resp_identite_type,resp_identite_num,resp_identite_date,resp_identite_lieu,resp_signature,note FROM stave_autorisation_parentale WHERE id="'.$idautorisation.'" LIMIT 1';
		$res = @mysql_query($req);
		for ($i=0; $blabla = @mysql_fetch_object($res); $i++) {
			$tab[0] = $blabla->id;
			$tab[1] = $blabla->inscrit_id;
			$tab[2] = $blabla->debut_date;
			$tab[3] = $blabla->fin_date;
			$tab[4] = $blabla->resp_nom;
			$tab[5] = $blabla->resp_prenom;
			$tab[6] = $blabla->resp_adresse;
			$tab[7] = $blabla->resp_cp;
			$tab[8] = $blabla->resp_ville;
			$tab[9] = $blabla->resp_pays;
			$tab[10] = $blabla->resp_tel_fixe;
			$tab[11] = $blabla->resp_tel_mobile;
			$tab[12] = $blabla->resp_email;
			$tab[13] = $blabla->resp_identite_type;
			$tab[14] = $blabla->resp_identite_num;
			$tab[15] = $blabla->resp_identite_date;
			$tab[16] = $blabla->resp_identite_lieu;
			$tab[17] = $blabla->resp_signature;
			$tab[18] = $blabla->note;
		}
		return $tab;
	}
	// Ajout d'une autorisation à un utilisateur (idinscrit)
	function ajoutAutorisation ($idinscrit,$resp_nom,$resp_prenom,$resp_adresse,$resp_cp,$resp_ville,$resp_pays,$resp_tel_fixe,$resp_tel_mobile,$resp_email,$resp_identite_type,$resp_identite_num,$resp_identite_date_jour,$resp_identite_date_mois,$resp_identite_date_annee,$resp_identite_lieu,$resp_signature,$note) {
		$a=strftime("%Y");$m=strftime("%m");$j=strftime("%d");
		$debut_date = $a.'-'.$m.'-'.$j; $fin_date = ($a+1).'-'.$m.'-'.$j;
		$resp_identite_date = $resp_identite_date_annee.'-'.$resp_identite_date_mois.'-'.$resp_identite_date_jour;
		$req = 'INSERT INTO stave_autorisation_parentale (inscrit_id,debut_date,fin_date,resp_nom,resp_prenom,resp_adresse,resp_cp,resp_ville,resp_pays,resp_tel_fixe,resp_tel_mobile,resp_email,resp_identite_type,resp_identite_num,resp_identite_date,resp_identite_lieu,resp_signature,note) VALUES ("'.$idinscrit.'","'.$debut_date.'","'.$fin_date.'","'.$resp_nom.'","'.$resp_prenom.'","'.$resp_adresse.'","'.$resp_cp.'","'.$resp_ville.'","'.$resp_pays.'","'.$resp_tel_fixe.'","'.$resp_tel_mobile.'","'.$resp_email.'","'.$resp_identite_type.'","'.$resp_identite_num.'","'.$resp_identite_date.'","'.$resp_identite_lieu.'","'.$resp_signature.'","'.$note.'")';
		$res = @mysql_query ($req);
		if ($res) { return @mysql_insert_id($res); } else { return -1; }
	}

