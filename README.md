Espace Multimédia
=================

Espace Multimédia est un gestionnaire... d'espace multimédia ! Il permet de :
- gérer les usagers de l'espace,
- gérer les autorisations parentales pour les mineurs,
- allouer, uns à uns, les ordinateurs de l'espace (délais d'attente, temps de mise à disposition...),
- gérer les visites des usagers.

Les mots de passe d'accès sont cryptés et le site n'est pas référencé par les moteurs de recherche.

Version
=======
v 1 = 2009

Installation
============
1. Bifurquez (fork) ce dépôt
2. Utilisez le fichier `espace_multimedia.sql` pour créer les tables de la base de données
3. Modifiez le fichier `/include/fonctions/connexion.func.php` avec vos identifiants de connexion à vos serveur + base de données
4. Modifiez le fichier `/include/fonctions/parametrages.inc.php` pour indiquer le nombre de postes informatiques dont vous disposez ainsi que le nombre de visites que vous souhaitez voir affiché
5. Clonez l'ensemble des fichiers à la racine de votre serveur web
6. Créez votre identifiant ainsi que votre mot de passe crypté : http://www.md5.fr/
7. Lancez votre navigateur
8. Profitez d'un bon thé fraîchement infusé !

Liens utiles
============
- PhpMyAdmin : http://www.phpmyadmin.net
- MAMP (Mac) : http://www.mamp.info
- LAMP (Linux) : http://doc.ubuntu-fr.org/lamp
- MySQL : http://www.mysql.fr/

Licence
=======
Espace Multimédia est sous licence Attribution 4.0 International (CC BY 4.0) http://creativecommons.org/licenses/by/4.0/
