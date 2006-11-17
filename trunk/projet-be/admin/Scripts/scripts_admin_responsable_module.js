/*
** Fichier : scripts_admin_responsable_module
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascript pour la partie gestion des responsables de modules
*/


/*
** fonction 	: checkRMAdd
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout d'enseignement est correct et soumet dans ce cas
*/
function checkRMAdd(formName)
{ 
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var correct = /^\s*\d+\s*$/ ;		// un nombre entier, on ne teste pas la longueur
	
	// debut de verification
	var enseignant = window.document.forms[formName].enseignant.value ;
	var module = window.document.forms[formName].module.value ;
	
	if (!correct.test(enseignant)) // numero incorrect
	{
		formIsCorrect = false ;
		message += "\n- identifiant enseignant incorrect" ;
	}
	if (!correct.test(module)) // numero incorrect
	{
		formIsCorrect = false ;
		message += "\n- identifiant module incorrect" ;
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
** fonction 	: checkRMMod
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire de modification de l'enseignement est correct et soumet dans ce cas
*/
function checkRMMod(formName)
{ 
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var correct = /^\s*\d+\s*$/ ;		// un nombre entier, on ne teste pas la longueur
	
	// debut de verification
	var module = window.document.forms[formName].module.value ;	
	
	if (!correct.test(module)) // numero incorrect
	{
		formIsCorrect = false ;
		message += "\n- identifiant module incorrect" ;
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
** EOF scripts_admin_enseignements
*/
