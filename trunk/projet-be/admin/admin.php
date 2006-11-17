<?php
/*
** Fichier : admin
** Date de creation : 23/07/2005
** Auteurs : Avetisayan Gohar
** Version : 2.0
** Description : Fichier central responsable de l'administration du site
*/


/*
** IMPORTANT :	Pour acceder aux differentes parties de l'administration on
**		utilisera la variable $_GET['w'] (w pour what)
**		les parties a inclure se trouvent dans le repertoire Admin de la racine
*/

/*
** IMPORTANT :	Pour determiner le choix de l'action a effectuer une fois qu'on connait
**		la partie on utilisera la variable $_GET['a'] (a pour action)
**		les seules options possibles sont add, mod et del et parfois aff ; les fichiers
**		inclus a partir de what doivent tous traiter ce cas en interne
*/


session_start();


error_reporting(E_ALL && ~E_NOTICE);


// fichier pour les messages
require("Functions/messages.inc.php") ;

require_once("../includes/toolbar.php");

// mini header
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<title> IUP ISI [Administration - Menus] </title>
<meta name="author" content="Conde Mickael K., Badaoui Kassem, Canaye Kurvin, Guenatri Kamil">
<meta name="copyright" content="Copyright 2004 IUP ISI">

<style type="text/css">

    img {

        behavior: url("../styles/pngbehavior.htc");

    }

</style>
<link rel="stylesheet" type="text/css" href="Styles/style_admin.css">
<?php

// on inclut les fonctions javascript specifiques si elles existent
if (isset($_GET['w']))
{
	if (file_exists("Scripts/scripts_admin_".$_GET['w'].".js"))
	{
		print("<script language=\"JavaScript\" src=\"Scripts/scripts_admin_{$_GET['w']}.js\"></script>\n") ;
	}
}


?>
<script language="JavaScript" src="Scripts/scripts_admin.js"></script>

<script language="Javascript" language="javascript" type="text/javascript" charset="utf-8" src="../Includes/js.php?edit=1"></script>

</head>

<body>
<center>
<table id="mainContainer" cellpadding="0" cellspacing="10" width="800">
 <tr>
		<td width="800" height="80">
			<img src="../img/admin_toplogo.png" width="800" height="80">
		</td>
	</tr>
	<tr>
		<td align="left" id="centralZone" valign="top">
			<table cellspacing="3" cellpadding="0">
				<tr>
					<td align="left" width="100">Navigation</td>
					<td align="left" width="100">&lt; <a href="javascript:window.close()">Fermer</a> &gt;</td>
					<td align="center" width="100">&lt; <a href="javascript:window.history.back()">Pr&eacute;cedente</a> &gt;</td>
					<td align="center" width="100">&lt; <a href="javascript:window.history.forward()">Suivante</a> &gt;</td>
				</tr>
			</table>



<?php

// L'utilisateur n'est pas authentifie
if (! isset($_SESSION['rootConnecte']))
{
	// si l'utilisateur n'a pas essaye de se connecter
	if (!isset($_POST['rootAuth']))
	{
		// alors affichage du formulaire
		centeredInfoMessage(2, 2, "L'utilisation de cette page n&eacute;cessite une authentification") ;
		print("<form action=\"admin.php\" method=\"post\">\n") ;
		print("<center><table width=\"400\">\n<tr>\n") ;
		print("<td align=\"left\" width=\"200\"> Login </td>") ;
		print("<td align=\"right\" width=\"200\"><input name=\"rootLogin\" class=\"defaultInput\" maxlength=\"15\" size=\"15\"></td>\n</tr>\n") ;
		print("<tr>\n") ;
		print("<td align=\"left\" width=\"200\"> Pass </td>") ;
		print("<td align=\"right\" width=\"200\"><input name=\"rootPass\" class=\"defaultInput\" maxlength=\"15\" type=\"password\" size=\"15\"></td>\n</tr>\n") ;
		print("<tr>\n") ;
		print("<td colspan=\"2\" align=\"left\" width=\"400\"><input type=\"submit\" name=\"rootAuth\" class=\"defaultButton\" value=\"identfication\"></td>\n</tr>\n") ;
		print("</table>\n</center>\n") ;
	}
	
	// sinon l'utilisateur a essaye de se connecter on verifie les donnees
	else
	{
		require("Includes/settings.inc.php") ;
		require("Functions/database.inc.php") ;
		dbConnect() ;
		
		$login = trim($_POST['rootLogin']) ;
		$login = addslashes($login) ;
		// on trim pas le pass au cas ou il y aurait des espaces
		$pass = addslashes($_POST['rootPass']) ;
		
		$result = dbQuery('SELECT *
			FROM administrateur
			WHERE login = "'.$login.'" AND passwd = "'.$pass.'"') ;
			
		$result = mysql_num_rows($result) ;
		
		// succes => redirection vers la meme page mais on definit la variable de session
		if ($result == 1)
		{
			$_SESSION['rootConnecte'] = true ;
			print("<meta http-equiv=\"refresh\" content=\"0;url=admin.php\">\n") ;
		}
		
		// erreur on reaffiche le formulaire
		else
		{
			centeredErrorMessage(2, 2, "Login incorrect, réessayez") ;
			print("\t\t\t<form action=\"admin.php\" method=\"post\">\n") ;
			print("\t\t\t<center><table width=\"400\">\n\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td align=\"left\" width=\"200\"> Login </td>") ;
			print("<td align=\"right\" width=\"200\"><input name=\"rootLogin\" class=\"defaultInput\" maxlength=\"15\" size=\"15\"></td>\n\t\t\t\t</tr>\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td align=\"left\" width=\"200\"> Pass </td>") ;
			print("<td align=\"right\" width=\"200\"><input name=\"rootPass\" class=\"defaultInput\" maxlength=\"15\" type=\"password\" size=\"15\"></td>\n\t\t\t\t</tr>\n") ;
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td colspan=\"2\" align=\"left\" width=\"400\"><input type=\"submit\" name=\"rootAuth\" class=\"defaultButton\" value=\"identfication\"></td>\n\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n\t\t\t</center>\n") ;
		}
		
	}
	
} // if ! isset session

// l'utilisateur est authentifie avec succes
else
{	
	// petit menu de navigation en haut
	print("\t\t\t<table cellpadding=\"0\" cellspacing=\"3\">\n") ;
	print("\t\t\t\t<tr>\n") ;
	print("\t\t\t\t\t<td width=\"100\" align=\"left\">Session</td>\n") ;
	print("\t\t\t\t\t<td width=\"100\" align=\"left\" colspan=\"2\">&lt; <a href=\"admin.php?w=logout\">Deconnexion</a> &gt;</td>\n") ;
	print("\t\t\t\t</tr>\n") ;
	
	
	print("\t\t\t\t<tr>\n") ;
	print("\t\t\t\t<td width=\"100\" align=\"left\">Navigation</td>\n") ;
	
	if (isset($_SESSION['rootNavigation']) && ($_SESSION['rootNavigation'] == true))
	{
		print("\t\t\t\t\t<td width=\"100\" align=\"left\">&lt; <a href=\"admin.php?w=changenavigation\">Normale</a> &gt;</td>\n") ;
		print("\t\t\t\t\t<td align=\"left\">Vous utiliserez le site principal comme un utilisateur normal</td>\n") ;
	}
	
	else
	{
		print("\t\t\t\t\t<td width=\"100\" align=\"left\">&lt; <a href=\"admin.php?w=changenavigation\">Admin</a> &gt;</td>\n") ;
		print("\t\t\t\t\t<td align=\"left\">Vous pourrez administrer certaines parties &agrave; partir du site principal</td>\n") ;
	}
	
	print("\t\t\t\t</tr>\n") ;
	print("\t\t\t</table>\n") ;
	
	
	
	// rien n'est encore defini, on affiche le menu general
	if (! isset($_GET['w']))
	{
		centeredInfoMessage(3, 3, "Administration : menu principal") ;
		// on affiche les differents liens
		
		
		// tous les liens pr administrer		
		print("\t\t\t<table  cellspacing=\"5\" cellpadding=\"0\" align=\"center\">\n") ;
		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<th align=\"left\" width=\"200\"><u>Administration site</u></th>") ;
		print("<th align=\"center\" width=\"150\" >ajouter</th>") ;
		print("<th align=\"center\" width=\"150\">modifier</th>") ;	
		print("<th align=\"center\" width=\"150\">supprimer</th>\n") ;
		print("\t\t\t\t</tr>\n") ;
		
		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b>Gestion du menu</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=menu&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=menu&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;	
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=menu&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b>Gestion des pages</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=pages&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=pages&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;		
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=pages&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;

		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b>Gestion de l'information</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=information&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=information&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;
        print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=information&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		
                print("\t\t\t</table><br><br>\n") ;
		
		
		// Deuxieme table : enseignement
		print("\t\t\t<table  cellspacing=\"5\" cellpadding=\"0\" align=\"center\">\n") ;
		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<th align=\"left\" width=\"200\"><u>Administration enseignement</u></th>") ;
		print("<th align=\"center\" width=\"150\" >ajouter</th>") ;
		print("<th align=\"center\" width=\"150\">modifier</th>") ;
		print("<th align=\"center\" width=\"150\">supprimer</th>\n") ;
		print("\t\t\t\t</tr>\n") ;
		
		// etudiants
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b>Gestion des &eacute;tudiants</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=etudiants&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=etudiants&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;		
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=etudiants&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		// inscriptions
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"> &raquo; <b>Inscriptions</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=inscriptions&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=inscriptions&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;	
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=inscriptions&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		
		/*print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b>Gestion des promotions</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=promotions&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\">&nbsp;</a></td>") ;
		print("<td align=\"center\" width=\"150\">&nbsp;</td>") ;		
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=promotions&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;	*/	
		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b>Gestion des enseignants</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=enseignants&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=enseignants&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;		
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=enseignants&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b>Gestion des mati&egrave;res</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=matieres&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=matieres&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;	
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=matieres&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"> &raquo; <b>Evaluations</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=evaluation_matieres&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=evaluation_matieres&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;	
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=evaluation_matieres&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"> &raquo; <b>Natures</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=natures&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=natures&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=natures&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"> &raquo; <b>Contr&ocirc;les</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=controles&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=controles&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;		
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=controles&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"> &raquo; <b>Enseignements</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=enseignements&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=enseignements&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;	
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=enseignements&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b>Gestion des modules</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=modules&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=modules&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;	
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=modules&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"> &raquo; <b>Responsables de modules</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=responsable_module&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=responsable_module&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;		
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=responsable_module&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b>Gestion des dipl&ocirc;mes</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=diplomes&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=diplomes&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;	
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=diplomes&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;
		print("\t\t\t\t<tr>\n") ;
		print("\t\t\t\t\t<td align=\"left\" width=\"200\"> &raquo; <b>Responsables de dipl&ocirc;mes</b></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=responsable_diplome&a=add\"><img src=\"Gfx/admin_icon_add.gif\"></a></td>") ;
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=responsable_diplome&a=mod\"><img src=\"Gfx/admin_icon_modify.gif\"></a></td>") ;	
		print("<td align=\"center\" width=\"150\"><a href=\"admin.php?w=responsable_diplome&a=del\"><img src=\"Gfx/admin_icon_delete.gif\"></a></td>\n") ;
		print("\t\t\t\t</tr>\n") ;		
		print("\t\t\t</table><br><br>\n") ;	
		
	}
	
	// quelque chose est defini
	else
	{
		// on inclut si c est bon
		if (file_exists("Admin/".$_GET['w'].".php"))
		{		
			// quelques fichiers indispensables
			require("Includes/settings.inc.php") ;
			require("Functions/database.inc.php") ;	
			require("Admin/".$_GET['w'].".php") ;

            
			print("\t\t\t<br><br><center>") ;
			
			// lien vers l'aide si necessaire
			if (file_exists("Help/".$_GET['w'].".php"))
			{
				print("&lt; <a href=\"javascript:openHelp('{$_GET['w']}')\">voir l'aide</a> &gt; - ") ;
			}
			
			// liens pour le retour vers les menus precedents
			if (isset($_GET['a'])) // on affiche uniquement le lien vers le menu principal
			{
				print("[ <a href=\"admin.php?w={$_GET['w']}\">accueil {$_GET['w']}</a> ] - ") ;
			}
			
			print("[ <a href=\"admin.php\">menu principal</a> ]</center>\n") ;
		}
		// sinon message d'erreur		
		else
		{
			centeredErrorMessage(2, 2, "Page introuvable") ;
			print("\t\t\t<br><br><center>[ <a href=\"admin.php\">menu principal</a> ]</center>\n") ;
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
** EOF admin
*/
?>
