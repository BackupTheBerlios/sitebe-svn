/*
** Fichier : scripts_admin_sections
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascrpit pour le controle des formulaires des sections
*/


/*
** fonction 	: checkModule
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout/modification des modules est correct et soumet dans ce cas
*/
function checkModule(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrectText = /^\s*$/ ;
	
	var intitule = window.document.forms[formName].moduleIntitule.value ;
	
		
	// test de l'intitule
	if (incorrectText.test(intitule))
	{
		formIsCorrect = false ;
		message += "\n- intitule du module vide" ;
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
** EOF scripts_admin_modules
*/
