/*
** Fichier : scripts_admin_menu
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascript
*/


/*
** fonction 	: checkMenu
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout de menu est correct et soumet dans ce cas
*/
function checkMenu(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrect = /^\s*$/ ;		// la seule possibilite incorrecte aucune lettre
	
	// debut de verification
	var menuIntitule = window.document.forms[formName].menuIntitule.value ;
	
	if (incorrect.test(menuIntitule)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- intitule du menu vide" ;
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
