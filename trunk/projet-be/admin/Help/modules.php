<?php
/*
** Fichier : modules
** Date de creation : 10/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier d'aide a l'administration des modules
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{	
	centeredInfoMessage(3, 3, "Aide : modules") ;
	print("\t\t\t<ul>") ;
	// a propos
	print("\t\t\t<li><b>A propos des modules</b><br>\n") ;
	print("\t\t\t Les &eacute;l&eacute;ments \"module\" correspondent aux modules d'enseignement de l'IUP.<br>") ;
	print("\t\t\t Un module d&eacute;pend forcement d'un dipl&ocirc;me (<a href=\"help.php?w=diplomes\">?</a>).<br><br>\n") ;
	
	// ajout
	print("\t\t\t<br><li><b>Ajouter un module</b><br>\n") ;
	print("\t\t\t Pour ajouter un module il faut saisir son intitul&eacute;, sa description et choisir le dipl&ocirc;me dans le formulaire \n") ;
	print("\t\t\t puis valider en appuyant sur le bouton <b>Ajouter</b><br>\n") ;
	print("\t\t\t Le champ description permet de d&eacute;crire en d&eacute;tail le r&ocirc;le du module et eventuellement une description des mati&egrave;res<br> \n") ;
	
	// modification
	print("\t\t\t<br><li><b>Modifier un module</b><br>\n") ;
	print("\t\t\t Il faut d'abord choisir le dipl&ocirc;me auquel appartient le module &agrave; modifier\n") ;
	print("\t\t\t puis de choisir le module &agrave; modifier. Donner des nouvelles valeur aux champs\n") ;
	print("\t\t\t et valider en appuyant sur le bouton <b>Modifier</b>.<br>\n") ;
	
	
	// suppression
	print("\t\t\t<br><li><b>Supprimer un(des) module(s)</b><br>\n") ;
	print("\t\t\t Il faut d'abord choisir le dipl&ocirc;me auquel appartiennent les modules &agrave; supprimer.\n") ;
	print("\t\t\t Cocher ensuite les cases correspondantes &agrave; ces modules et valider en appuyant sur le bouton <b>Supprimer</b>.<br>\n") ;
	print("\t\t\t <b>Note :</b> les mati&egrave;res appartenant aux modules selectionn&eacute;s seront supprim&eacute;es.\n") ;
	
	print("\t\t\t</ul>\n") ;

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF modules
*/
?>
