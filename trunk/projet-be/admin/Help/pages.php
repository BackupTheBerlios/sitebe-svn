<?php
/*
** Fichier : pages
** Date de creation : 10/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier d'aide a l'administration des pages
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{	
	centeredInfoMessage(3, 3, "Aide : pages") ;
	print("\t\t\t<ul>") ;
	// a propos
	print("\t\t\t<li><b>A propos des pages</b><br>\n") ;
	print("\t\t\t Les pages sont des containeurs d'&eacute;l&eacute;mentes de type section.<br>") ;
	print("\t\t\t Une page d&eacute;pend forcement d'un menu (<a href=\"help.php?w=menu\">?</a>) de type <b>section</b>.<br><br>\n") ;
	print("\t\t\t Lors de l'ajout d'une page, celle-ci est inser&eacute;e automatiquement &agrave; la fin de son menu.<br>\n") ;
	print("\t\t\t Il est possible de changer cet ordre ult&eacute;rieurement dans la section <b>modifier</b>.<br>\n") ;
	print("\t\t\t Il est &eacute;galement possible de changer le menu associ&eacute; dans la section <b>reaffecter</b>.<br><br>\n") ;
	
	// ajout
	print("\t\t\t<br><li><b>Ajouter une page</b><br>\n") ;
	print("\t\t\t Pour ajouter une page il faut saisir dans le formulaire son titre ainsi que l'identifiant du menu auquel elle est associ&eacute;e\n") ;
	print("\t\t\t (il est possible de voir les identifiants en cliquant sur le lien <i>voir les menus section</i>) puis de valider en appuyant sur le bouton <b>Ajouter</b><br>\n") ;
	
	// modification
	print("\t\t\t<br><li><b>Modifier une page</b><br>\n") ;
	print("\t\t\t Il faut d'abord choisir la page &agrave; modifier\n") ;
	print("\t\t\t puis de donner des nouvelles valeurs aux champs <i>titre</i> et <i>position</i>\n") ;
	print("\t\t\t et enfin de valider en appuyant sur le bouton <b>Modifier</b>.<br>\n") ;
	
	// reaffectation
	print("\t\t\t<br><li><b>Reaffecter une page</b><br>\n") ;
	print("\t\t\t Il faut d'abord choisir la page &agrave; reaffecter\n") ;
	print("\t\t\t puis de donner une nouvelle valeur au champ <i>menu associ&eacute;</i>\n") ;
	print("\t\t\t et enfin de valider en appuyant sur le bouton <b>Modifier</b>.<br>\n") ;
	
	// suppression
	print("\t\t\t<br><li><b>Supprimer une(des) pages(s)</b><br>\n") ;
	print("\t\t\t Cocher les cases correspondantes aux pages &agrave; supprimer et valider en appuyant sur le bouton <b>Supprimer</b>.<br>\n") ;
	print("\t\t\t <b>Note :</b> les sections appartenant aux pages selectionn&eacute;s seront supprim&eacute;es.\n") ;
	
	print("\t\t\t</ul>\n") ;

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF pages
*/
?>
