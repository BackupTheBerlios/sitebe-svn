/*
** Fichier : scripts_admin_menu
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascript
*/


/*
** fonction 	: checkDiplome
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout de menu est correct et soumet dans ce cas
*/
function checkDiplome(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrect = /^\s*$/ ;		// la seule possibilite incorrecte aucune lettre
	
	// debut de verification
	var dipIntitule = window.document.forms[formName].dipIntitule.value ;
	
	if (incorrect.test(dipIntitule)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- intitule du diplôme vide" ;
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
