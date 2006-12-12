/*
** Fichier : scripts_admin_inscriptions
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascrpit pour la partie inscription
*/


/*
** fonction 	: checkInscription
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout de menu est correct et soumet dans ce cas
*/
function checkInscription(formName)
{ 
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :\n" ;
	var correct = /^\s*\d+\s*$/ ;		// un nombre entier, on ne teste pas la longueur
	
	// debut de verification
	var etudiant = window.document.forms[formName].etudiant.value ;
	
	if (!correct.test(etudiant)) // numero incorrect
	{
		formIsCorrect = false ;
		message += "- numéro etudiant incorrect" ;
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
** EOF scripts_admin_inscription
*/
