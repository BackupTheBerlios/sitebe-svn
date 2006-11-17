<?php
/*
** Fichier : sections
** Date de creation : 10/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier d'aide a l'administration des sections
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{	
	centeredInfoMessage(3, 3, "Aide : sections") ;
	
	// si on veut voir l'aide
	if (! isset($_GET['id']))
	{
		print("\t\t\t<ul>") ;
		// a propos
		print("\t\t\t<li><b>A propos des sections</b><br>\n") ;
		print("\t\t\t Les sections sont des &eacute;l&eacute;ments de base contenant toute sorte d'information (texte, images, liens...).<br>") ;
		print("\t\t\t Une section d&eacute;pend forcement d'une page (<a href=\"help.php?w=pages\">?</a>).<br><br>\n") ;
		print("\t\t\t Lors de l'ajout d'une section, il est possible de l'ins&eacute;rer &agrave; la fin de la page ou au tout debut.<br>\n") ;
		print("\t\t\t Il est possible de changer cet ordre ult&eacute;rieurement dans la section <b>modifier</b>.<br>\n") ;
		print("\t\t\t Il est &eacute;galement possible de changer la page associ&eacute;e dans la section <b>reaffecter</b>.<br><br>\n") ;
	
		// ajout
		print("\t\t\t<br><li><b>Ajouter une section</b><br>\n") ;
		print("\t\t\t Pour ajouter une page il faut saisir dans le formulaire son contenu ainsi que l'identifiant de la page &agrave; laquelle elle est associ&eacute;e\n") ;
		print("\t\t\t (il est possible de voir les identifiants en cliquant sur le lien <i>voir les toutes les pages</i>) puis de valider en appuyant sur le bouton <b>Ajouter</b><br>\n") ;
	
		// modification
		print("\t\t\t<br><li><b>Modifier une section</b><br>\n") ;
		print("\t\t\t La premi&egrave;re &eacute;tape consiste &agrave; choisir la page &agrave; laquelle appartient la section, ceci afin d'&eacute;viter d'avoir trop de sections en m&ecirc;me temps.\n") ;
		print("\t\t\t Une fois la page choisie, il est possible de choisir la section &agrave; modifier\n") ;
		print("\t\t\t puis de donner des nouvelles valeurs aux champs <i>contenu</i> et <i>position</i>.<br>\n") ;
		print("\t\t\t Pour prendre en compte les changements appuyer sur le bouton <b>Modifier</b>.<br>\n") ;
	
		// reaffectation
		print("\t\t\t<br><li><b>Reaffecter une section</b><br>\n") ;
		print("\t\t\t La premi&egrave;re &eacute;tape consiste &agrave; choisir la page &agrave; laquelle appartient la section, ceci afin d'&eacute;viter d'avoir trop de sections en m&ecirc;me temps.\n") ;
		print("\t\t\t Une fois la page choisie, il est possible de choisir la section &agrave; reaffecter\n") ;
		print("\t\t\t puis de donner une nouvelle valeur au champ <i>page de lien</i>.<br>\n") ;
		print("\t\t\t Pour prendre en compte les changements appuyer sur le bouton <b>Modifier</b>.<br>\n") ;
	
		// suppression
		print("\t\t\t<br><li><b>Supprimer une(des) section(s)</b><br>\n") ;
		print("\t\t\t Il faut d'abord choisir la page li&eacute;e &agrave; la section.<br>\n") ;
		print("\t\t\t Une fois la page choisie, cocher les cases correspondantes aux sections &agrave; supprimer et valider en appuyant sur le bouton <b>Supprimer</b>.<br>\n") ;
		
		print("\t\t\t</ul>\n") ;
	}
	
	// sinon on veut voir une section en particulier
	else
	{
		dbConnect() ;
		$section = dbQuery('SELECT contenu, titre
			FROM section
			WHERE `id-section` = '.$_GET['id']) ;
			
		$sectionDetails = mysql_fetch_array($section) ; 
		$contenu = nl2br($sectionDetails['contenu']) ;
		
		print("\t\t\t<b>{$sectionDetails['titre']}</b>\n") ;
		print("\t\t\t$contenu n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF sections
*/
?>
