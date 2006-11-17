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
	centeredInfoMessage(3, 3, "Aide : enseignants") ;
	print("\t\t\t<ul>") ;
	// a propos
	print("\t\t\t<li><b>A propos des �tudiants</b><br>\n") ;
	print("\t\t\t Les �tudiants pr�sents dans ce site, sont ceux de la formation de l'IUP ISI.<br><br>") ;
	print("\t\t\t Ils sont des diff�rentes promotions de l'IUP depuis 1995-1996.<br>\n") ;
	print("\t\t\t les op�rations que l'on peut effectuer au niveau des �tudiants sont :<br>\n") ;
	print("\t\t\t\t - L'ajout d'un nouvel �tudiant.<br>\n") ;
	print("\t\t\t\t - La modification d'une information concernant un �tudiant.<br>\n") ;
	print("\t\t\t\t - La suppresion d'un �tudiant(ou plusieurs).<br>\n") ;
	
	// ajout
	print("\t\t\t<br><li><b>Ajouter un �tudiant</b><br>\n") ;
	print("\t\t\t Pour ajouter un nouvel �tudiant il faut saisir dans le formulaire,le num�ro de carte �tudiant, le nom et le pr�nom, l'email, l'URL du cv, de l'enseignant.<br>\n") ;
	print("\t\t\t Il faut aussi choisir sa promotion et le diplome dans lequel il s'est inscrit.<br>\n") ;
	
	
	// modification
	print("\t\t\t<br><li><b>Modifier une information</b><br>\n") ;
	print("\t\t\t Il faut d'abord choisir l'�tudiant concern�\n") ;
	print("\t\t\t puis de donner de nouvelles valeurs aux champs\n") ;
	print("\t\t\t et enfin de valider en appuyant sur le bouton <b>Modifier</b>.<br>\n") ;
	
	// suppression
	print("\t\t\t<br><li><b>Supprimer un(des) �tudiant(s)</b><br>\n") ;
	print("\t\t\t Cocher les cases correspondantes aux enseignants,<br>supprimer et valider en appuyant sur le bouton <b>Supprimer</b>.<br>\n") ;
	
	
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
