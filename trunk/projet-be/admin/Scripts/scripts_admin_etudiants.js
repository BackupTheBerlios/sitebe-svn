/*
** Fichier : scripts_admin_etudiants
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascrpit
*/


/*
** fonction 	: checkEtudiantAdd
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout des etudiants est correct et soumet dans ce cas
*/
function checkEtudiantAdd(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrect = /^\s*$/ ;		// la seule possibilite incorrecte aucune lettre
	var intCorrect = /^\d+$/
	
	
	// debut de verification
	var numEtudiant = window.document.forms[formName].numetu.value ;
	var nomEtudiant = window.document.forms[formName].nometu.value ;
	var prenomEtudiant = window.document.forms[formName].prenometu.value ;
		
	if (!intCorrect.test(numEtudiant)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- numéro de carte étudiant incorrect" ;
	}
	
	if (incorrect.test(nomEtudiant)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- nom de l'étudiant vide" ;
	}
	
	if (incorrect.test(prenomEtudiant)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- prenom de l'étudiant vide" ;
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
** fonction 	: checkEtudiantMod
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire de modification des etudiants est correct et soumet dans ce cas
*/
function checkEtudiantMod(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrect = /^\s*$/ ;		// la seule possibilite incorrecte aucune lettre
	
	
	// debut de verification
	var nomEtudiant = window.document.forms[formName].nometu.value ;
	var prenomEtudiant = window.document.forms[formName].prenometu.value ;
	
	
	if (incorrect.test(nomEtudiant)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- nom de l'étudiant vide" ;
	}
	
	if (incorrect.test(prenomEtudiant)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- prenom de l'étudiant vide" ;
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
** EOF scripts_admin_etudiants
*/
