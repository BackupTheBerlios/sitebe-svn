<?php
/*
** Fichier : enseignants
** Date de creation : 10/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier d'aide a l'administration des etudiants
*/

// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (strpos($_SERVER['PHP_SELF'], "help.php"))
{	
	centeredInfoMessage(3, 3, "Aide : &eacute;tudiants") ;
	print("\t\t\t<ul>") ;
	// a propos
	print("\t\t\t<li><b>A propos des &eacute;tudiants</b><br>\n") ;
	print("\t\t\t Les &eacute;tudiants pr&eacute;sents dans ce site, sont ceux de la formation de l'IUP ISI.<br><br>") ;
	print("\t\t\t Ils sont des diff&eacute;rentes promotions de l'IUP depuis 1995-1996.<br>\n") ;
	print("\t\t\t les op&eacute;rations que l'on peut effectuer au niveau des &eacute;tudiants sont :<br>\n") ;
	print("\t\t\t\t - L'ajout d'un nouvel &eacute;tudiant.<br>\n") ;
	print("\t\t\t\t - La modification d'une information concernant un &eacute;tudiant.<br>\n") ;
	print("\t\t\t\t - La suppresion d'un &eacute;tudiant(ou plusieurs, ou encore de tous).<br>\n") ;
	
	// ajout
	print("\t\t\t<br><li><b>Ajouter un &eacute;tudiant</b><br>\n") ;
	print("\t\t\t Pour ajouter un nouvel &eacute;tudiant il faut saisir dans le formulaire,le num&eacute;ro de carte &eacute;tudiant, le nom et le pr&eacute;nom, l'email, l'URL du cv, de l'enseignant.<br>\n") ;
	print("\t\t\t Il faut aussi choisir sa promotion et le diplome dans lequel il s'est inscrit.<br>\n") ;
	
	
	// modification
	print("\t\t\t<br><li><b>Modifier une information</b><br>\n") ;
	print("\t\t\t Il faut d'abord choisir l'&eacute;tudiant concern&eacute;\n") ;
	print("\t\t\t puis de donner de nouvelles valeurs aux champs\n") ;
	print("\t\t\t et enfin de valider en appuyant sur le bouton <b>Modifier</b>.<br>\n") ;
	
	// suppression
	print("\t\t\t<br><li><b>Supprimer un(des) &eacute;tudiant(s)</b><br>\n") ;
	print("\t\t\t Cocher les cases correspondantes aux &eacute;tudiants,<br>supprimer et valider en appuyant sur le bouton <b>Supprimer</b>.\n");
	print("\t\t\t Ou bien, pour supprimer tous les &eacute;tudiants, cliquer directement sur le bouton <b>Supprimer tous</b>.<br>\n") ;
	
	
	print("\t\t\t</ul>\n") ;

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF etudiants
*/
?>
