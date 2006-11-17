<?php
/*
** Fichier : matieres
** Date de creation : 10/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier d'aide a l'administration des matieres
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{	
	centeredInfoMessage(3, 3, "Aide : mati&egrave;res") ;
	print("\t\t\t<ul>") ;
	// a propos
	print("\t\t\t<li><b>A propos des mati&egrave;res</b><br>\n") ;
	print("\t\t\t Une mati&egrave;re d&eacute;pend forcement d'un module (<a href=\"help.php?w=modules\">?</a>) et donc indirectement d'un dipl&ocirc;me (<a href=\"help.php?w=diplomes\">?</a>).<br><br>\n") ;
	
	
	// ajout
	print("\t\t\t<br><li><b>Ajouter une mati&egrave;re</b><br>\n") ;
	print("\t\t\t Saisir son intitul&eacute;, son coefficient, le nombre d'heures qui lui sont accord&eacute;es ainsi que le module associ&eacute; dans le formulaire d'ajout.\n") ;
	print("\t\t\t Vvalider en appuyant sur le bouton <b>Ajouter</b><br>\n") ;
	print("\t\t\t <b>Note :</b>Des valeurs fractionnaires (par exemple 3.5) peuvent &ecirc;tre attribu&eacute;es au champ coefficient.<br> \n") ;
	print("\t\t\t Il est important que le separateur entre les parties enti&egrave;res et fractionnaires soit un point et non une virgule<br>\n") ;;
	
	// modification
	print("\t\t\t<br><li><b>Modifier une mati&egrave;re</b><br>\n") ;
	print("\t\t\t Avant de pouvoir modifier une mati&egrave;re il faut chosir le dipl&ocirc;me puis le module auxquels la mati&egrave;re fait reference.<br>\n") ;
	print("\t\t\t Saisir les nouvelles valeurs dans les champs du formulaire et\n") ;
	print("\t\t\t valider en appuyant sur le bouton <b>Modifier</b>.<br>\n") ;
	
	
	// suppression
	print("\t\t\t<br><li><b>Supprimer une(des) mati&grave;re(s)</b><br>\n") ;
	print("\t\t\t Il faut d'abord choisir le dipl&ocirc;me puis le module auxquels appartiennent les mati&egrave;res &agrave; supprimer\n") ;
	print("\t\t\t Cocher ensuite les cases correspondantes &agrave; ces mati&egrave;res et valider en appuyant sur le bouton <b>Supprimer</b>.<br>\n") ;
	
	print("\t\t\t</ul>\n") ;

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF matieres
*/
?>
