<?php
/*
** Fichier : database
** Date de creation : 30/08/2005
** Auteurs : Avetisyan Gohar
** Version : 2.0
** Description : Fichier central responsable de la mise a jour de la base de données
*/

/*
** IMPORTANT :	Pour acceder aux differentes parties qui se chargent de la mise a jour
**		on utilisera la variable $_GET['w'] (w pour what)
**		les parties a inclure se trouvent dans le repertoire Database de la racine
*/
session_start();
// fichier pour les messages
require("Functions/messages.inc.php") ;
// mini header
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title> IUP ISI [Administration - Mise &agrave; jour] </title>
	<meta name="author" content="Conde Mickael K., Badaoui Kassem, Canaye Kurvin, Guenatri Kamil">
	<meta name="copyright" content="Copyright 2004 IUP ISI">
	<link rel="stylesheet" type="text/css" href="Styles/style_admin.css">
</head>
<body>
	<center>
<table id="mainContainer" cellpadding="0" cellspacing="10" width="800">
	<tr>
		<td width="800" height="80">
			<img src="Gfx/admin_toplogo.gif" width="800" height="80">
		</td>
	</tr>
	<tr>
		<td id="centralZone">
<?php
//aucuin atilisateur n'est identifie
if (! isset($_SESSION['rootConnecte'])&& ! isset($_SESSION['ensConnecte']) && ! isset($_SESSION['etuConnecte']))
{
	// L'administrateur n'est pas authentifie pas de raison de lui proposer quoi que ce soit
	if (! isset($_SESSION['rootConnecte']))
	{
		centeredErrorMessage(3, 3, "L'utilisation de cette page n&eacute;cessite une authentification") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php\">\n") ;
	} // if ! isset session
	
	// L'utilisateur n'est pas authentifie pas de raison de lui proposer quoi que ce soit
	elseif (! isset($_SESSION['ensConnecte']) && ! isset($_SESSION['etuConnecte']))
	{
		centeredErrorMessage(3, 3, "L'utilisation de cette page n&eacute;cessite une authentification") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=index.php?p=connexion\">\n") ;	} // if ! isset session

}
// l'administrateur est authentifie avec succes
elseif (isset($_SESSION['rootConnecte']))
{
	// rien n'est defini : pas normal
	if (! isset($_GET['w']))
	{
		centeredErrorMessage(2, 2, "Aucune cible d&eacute;finie, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php\">\n") ;
	}
	// quelque chose est defini
	else
	{
		// on inclut si c est bon
		if (file_exists("Database/".$_GET['w'].".php"))
		{
			// quelques fichiers indispensables
			require("Includes/settings.inc.php") ;
			require("Functions/database.inc.php") ;			
			require("Database/".$_GET['w'].".php") ;
		}
		// sinon message d'erreur
		else
		{
			centeredErrorMessage(2, 2, "Page introuvable, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php\">\n") ;
		}
	}
}
// l'utilisateur est authentifie avec succes
elseif (isset($_SESSION['ensConnecte']) || isset($_SESSION['etuConnecte']))
{
	// rien n'est defini : pas normal
	if (! isset($_GET['w']))
	{
		centeredErrorMessage(2, 2, "Aucune cible d&eacute;finie, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=index.php?p=connexion\">\n") ;
	}
	// quelque chose est defini
	else
	{
		// on inclut si c est bon
		if (file_exists("Database/".$_GET['w'].".php"))
		{
			// quelques fichiers indispensables
			require("Includes/settings.inc.php") ;
			require("Functions/database.inc.php") ;			
			require("Database/".$_GET['w'].".php") ;
		}
		// sinon message d'erreur
		else
		{
			centeredErrorMessage(2, 2, "Page introuvable, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=index.php?p=connexion&w={$_GET['w']}\">\n") ;
		}
	}
}
?>
		</td>
	</tr>
	<tr>
		<td align=\"center\">
			&copy; 2004 - 2005 IUP ISI
		</td>
	</tr>
</table>
</body>
</html>

<?php
/*
** EOF database.php
*/
?>
