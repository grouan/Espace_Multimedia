<?PHP

/////// Param�tres de connexions //////////////////////////////////////////////////////////////////////////////////////////
function connexion () {
	// Constantes
	
	define ('NOM', "xxxxx");					// login utilisateur
	define	('PASSE', "xxxxx");					// mot de passe
	define	('SERVEUR', "xxxxx");				// sql.free.fr ...
	define	('BASE', "xxxxx");					// base de donn�es
	
	// Requ�te de connexion
	
  	$connexion = mysql_pconnect (SERVEUR, NOM, PASSE);

  	if (!$connexion)	{	echo "D�sol�, la connexion au serveur <b>$pServeur</b> est impossible\n";	exit; }

  	// Connexion � la base
  	if (!mysql_select_db (BASE, $connexion)) {
		echo "D�sol�, acc�s � la base <b>$pBase</b> est impossible\n";
		echo "<b>Message de MySQL :</b> " . mysql_error($connexion);
		exit;
	}
}

function deconnexion () {
	mysql_close();
}

?>