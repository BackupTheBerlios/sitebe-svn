/*
** Fichier : scripts_admin_controles
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascrpit
*/


/*
** fonction 	: checkControle
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout des types de controles est correct et soumet dans ce cas
*/
function checkControle(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :\n" ;
	var incorrect = /^\s*$/ ;		// la seule possibilite incorrecte aucune lettre
	
	// debut de verification
	var typeControle = window.document.forms[formName].typeControle.value ;
	
	
	if (incorrect.test(typeControle)) // champ vide
	{
		formIsCorrect = false ;
		message += "- type de contrôle vide" ;
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
** EOF scripts_admin_enseignant
*/
