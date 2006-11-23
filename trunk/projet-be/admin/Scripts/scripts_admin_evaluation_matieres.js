/*
** Fichier : scripts_admin_matieres
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascript pour verifier les matieres
*/


/*
** fonction 	: checkEM
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout / modif des evaluations de matières est correct et soumet dans ce cas
*/
function checkEM(formName)
{ 
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrect = /^\s*$/ ;
	var floatCorrect = /^\d+([\.]{1}[\d]+)?$/ ;
	var intCorrect = /^\d+$/ ;	
	
	// debut de verification
	var matiere = window.document.forms[formName].matiere.value ;
	var coeff1 = window.document.forms[formName].coeff1.value ;
	var coeff2 = window.document.forms[formName].coeff2.value ;
	
		
	if (!intCorrect.test(matiere)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- identifiant de la mati&egrave;re incorrect" ;
	}
	
	if (!floatCorrect.test(coeff1))
	{
		formIsCorrect = false ;
		message += "\n- coefficient 1 de la mati&egrave;re incorrect" ;
	}
	
	if (!floatCorrect.test(coeff2))
	{
		formIsCorrect = false ;
		message += "\n- coefficient 2 de la mati&egrave;re incorrect" ;
	}
	
		
	// si on peut ajouter ou modifier
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
** EOF scripts_admin_evaluation_matieres
*/
