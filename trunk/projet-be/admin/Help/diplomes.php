<?php
/*
** Fichier : diplomes
** Date de creation : 10/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier d'aide a l'administration des pages
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{	
	centeredInfoMessage(3, 3, "Aide : dipl&ocirc;mes") ;
	print("\t\t\t<ul>") ;
	// a propos
	print("\t\t\t<li><b>A propos des dipl&ocirc;mes</b><br>\n") ;
	print("\t\t\t Les &eacute;l&eacute;ments \"dipl&ocirc;me\" correspondent aux dipl&ocirc;mes r&eacute;els de l'enseignement de l'IUP.<br>") ;
	print("\t\t\t Il sont &eacute;galement l'equivalent des ann&eacute;es d'&eacute;tudes.<br><br>\n") ;
	
	// ajout
	print("\t\t\t<br><li><b>Ajouter un dipl&ocirc;me</b><br>\n") ;
	print("\t\t\t Pour ajouter un dipl&ocirc;me il faut saisir son intitul&eacute; dans le formulaire \n") ;
	print("\t\t\t puis valider en appuyant sur le bouton <b>Ajouter</b><br>\n") ;
	print("\t\t\t Un exemple d'intitul&eacute; peut &ecirc;tre <b>Licence 3</b><br> \n") ;
	
	// modification
	print("\t\t\t<br><li><b>Modifier un dipl&ocirc;me</b><br>\n") ;
	print("\t\t\t Il faut d'abord choisir le dipl&ocirc;me &agrave; modifier\n") ;
	print("\t\t\t puis de donner une nouvelle valeur au champ <i>intitul&eacute;</i>\n") ;
	print("\t\t\t et enfin de valider en appuyant sur le bouton <b>Modifier</b>.<br>\n") ;
	
	
	// suppression
	print("\t\t\t<br><li><b>Supprimer un(des) dipl&ocirc;me(s)</b><br>\n") ;
	print("\t\t\t Cocher les cases correspondantes aux dipl&ocirc;mes &agrave; supprimer et valider en appuyant sur le bouton <b>Supprimer</b>.<br>\n") ;
	print("\t\t\t <b>Note :</b> les modules et mati&egrave;res appartenant aux dipl&ocirc;mes selectionn&eacute;s seront supprim&eacute;s.\n") ;;
	
	print("\t\t\t</ul>\n") ;

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF diplomes
*/
?>
