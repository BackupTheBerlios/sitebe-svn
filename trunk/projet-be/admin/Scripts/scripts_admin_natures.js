/*
** Fichier : scripts_admin_natures
** Date de creation : 6/01/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascrpit
*/


/*
** fonction 	: checkMenu
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout de nature des examens est correct et soumet dans ce cas
*/
function checkMenu(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :\n" ;
	var incorrect = /^\s*$/ ;		// la seule possibilite incorrecte aucune lettre
	
	// debut de verification
	var natureExamen = window.document.forms[formName].natureExamen.value ;
	
	if (incorrect.test(natureExamen)) // champ vide
	{
		formIsCorrect = false ;
		message += "- intitulé de la nature vide" ;
	}
	
	// si on peut ajouter
	if (formIsCorrect)
	{
		window.document.forms[formName].submit() ;
	}
	
	// affichage du message d'erreur
	else
	{
		alert(message) ;
	}
	
}




/*
** EOF scripts_admin_menu
*/
