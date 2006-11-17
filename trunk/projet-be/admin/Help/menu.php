<?php
/*
** Fichier : menu
** Date de creation : 10/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier d'aide a l'administration du menu
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{	
	centeredInfoMessage(3, 3, "Aide : menu") ;
	print("\t\t\t<ul>") ;
	// a propos
	print("\t\t\t<li><b>A propos des menus</b><br>\n") ;
	print("\t\t\t Les &eacute;l&eacute;ments du menus repr&eacute;sentent des liens situ&eacute;s en haut du site, parametrables &agrave; volont&eacute; et necessaires &agrave; la navigation.<br><br>") ;
	print("\t\t\t Il existe diff&eacute;rents types de menu :\n") ;
	print("\t\t\t<ul>\n") ;
	print("\t\t\t<li><i> Le menu <u>sections</u> : </i> Il s'agit du seul type de menu pouvant contenir des pages (<a href=\"help.php?w=pages\">?</a>) qui elles-m&ecirc;mes contiennent\n") ;
	print("\t\t\t des sections (<a href=\"help.php?w=sections\">?</a>) pouvant afficher toute sorte d'information (texte, images, etc...)\n") ;
	print("\t\t\t<li><i> Les menus sp&eacute;cialis&eacute;s : </i> Il s'agit des menus ind&eacute;pendants qui n'ont pas besoin\n") ;
	print("\t\t\t que des pages leur soient ajout&eacute;es car ils font r&eacute;f&eacute;rence &agrave; leur propre\n") ;
	print("\t\t\t page pr&eacute;d&eacute;finie et de ce fait doivent &ecirc;tre uniques.<br><br>\n") ;
	print("\t\t\t <b>Il n'est pas possible d'avoir deux ou plusieurs fois un menu autre que <u>section</u></b>. Cela ne servirait a rien\n") ;
	print("\t\t\t puisqu'ils feraient ref&eacute;rence &agrave; la m&ecirc;me chose.<br>\n") ;
	print("\t\t\t Ces menus particuliers sont : <b> enseignants </b> (<a href=\"help.php?w=enseignants\">?</a>),\n") ;
	print("\t\t\t <b> &eacute;tudiants </b> (<a href=\"help.php?w=etudiants\">?</a>)\n") ;
	print("\t\t\t <b> enseignement </b> (<a href=\"help.php?w=enseignement\">?</a>)\n") ;
	//print("\t\t\t <b> enseignants </b> (<a href=\"help.php?w=enseignants\">?</a>)<br>\n") ;
	print("\t\t\t</ul>\n") ;
	
	// ajout
	print("\t\t\t<br><li><b>Ajouter un menu</b><br>\n") ;
	print("\t\t\t Pour ajouter un menu il faut saisir dans le formulaire, l'intitul&eacute;, la position\n") ;
	print("\t\t\t ainsi que le type du menu puis de valider en appuyant sur le bouton <b>Ajouter</b><br>\n") ;
	
	// modification
	print("\t\t\t<br><li><b>Modifier un menu</b><br>\n") ;
	print("\t\t\t Il faut d'abord choisir le menu &agrave; modifier\n") ;
	print("\t\t\t puis de donner de nouvelles valeurs aux champs\n") ;
	print("\t\t\t et enfin de valider en appuyant sur le bouton <b>Modifier</b>.<br>\n") ;
	
	// suppression
	print("\t\t\t<br><li><b>Supprimer un(des) menu(s)</b><br>\n") ;
	print("\t\t\t Cocher les cases correspondantes aux menus &agrave; supprimer et valider en appuyant sur le bouton <b>Supprimer</b>.<br>\n") ;
	print("\t\t\t Si la case <b>supprimer les pages associ&eacute;es</b> est coch&eacute;e les pages concern&eacute;es (pages li&eacute;es actuellement au menu)\n") ;
	print("\t\t\t ainsi que les sections (li&eacute;es &agrave; ces pages) seront supprim&eacute;s, sinon il sera possible de les reutiliser plus tard.<br>\n") ;
	
	print("\t\t\t</ul>\n") ;

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF menu
*/
?>
