/*
** Fichier : scripts_admin_matieres
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascript pour verifier les matieres
*/


/*
** fonction 	: checkMatiere
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout des matières est correct et soumet dans ce cas
*/
function checkMatiere(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrect = /^\s*$/ ;
	var floatCorrect = /^\d+([\.]{1}[\d]+)?$/ ;
	var intCorrect = /^\d+$/ ;	
	
	// debut de verification
	var intitule = window.document.forms[formName].matiereIntitule.value ;
	var coeff = window.document.forms[formName].matiereCoeff.value ;
	var heures = window.document.forms[formName].matiereHeures.value ;
	var module = window.document.forms[formName].matiereModule.value ;
	
		
	if (incorrect.test(intitule)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- intitulé de la matière vide" ;
	}
	
	if (!floatCorrect.test(coeff))
	{
		formIsCorrect = false ;
		message += "\n- coefficient de la matière incorrect" ;
	}
	
	if (!intCorrect.test(heures))
	{
		formIsCorrect = false ;
		message += "\n- nombre d'heures de la matière incorrect" ;
	}
	
	if (!intCorrect.test(module))
	{
		formIsCorrect = false ;
		message += "\n- module de la matière incorrect" ;
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
** EOF scripts_admin_matieres
*/
