<?PHP

/////// Paramètres de connexions //////////////////////////////////////////////////////////////////////////////////////////
function connexion () {
	// Constantes
	
	define ('NOM', "xxxxx");					// login utilisateur
	define	('PASSE', "xxxxx");					// mot de passe
	define	('SERVEUR', "xxxxx");				// sql.free.fr ...
	define	('BASE', "xxxxx");					// base de données
	
	// Requête de connexion
	
  	$connexion = mysql_pconnect (SERVEUR, NOM, PASSE);

  	if (!$connexion)	{	echo "Désolé, la connexion au serveur <b>$pServeur</b> est impossible\n";	exit; }

  	// Connexion à la base
  	if (!mysql_select_db (BASE, $connexion)) {
		echo "Désolé, accès à la base <b>$pBase</b> est impossible\n";
		echo "<b>Message de MySQL :</b> " . mysql_error($connexion);
		exit;
	}
}

function deconnexion () {
	mysql_close();
}

?>