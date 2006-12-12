/*
** Fichier : scripts_admin_enseignant
** Date de creation : 26/07/2004
** Auteurs :  Avetisyan Gohar
** Version : 2.0
** Description : Fichier contenant les fonctions javascrpit
*/


/*
** fonction 	: checkEnseignant
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout des enseignants est correct et soumet dans ce cas
*/
function checkEnseignant(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrect = /^\s*$/ ;		// la seule possibilite incorrecte aucune lettre
	
	// debut de verification
	var nomEnseignant = window.document.forms[formName].nomEnseignant.value ;
	var prenomEnseignant = window.document.forms[formName].prenomEnseignant.value ;
	
	if (incorrect.test(nomEnseignant)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- nom de l'enseignant vide" ;
	}
	
	if (incorrect.test(prenomEnseignant)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- prénom de l'enseignant vide" ;
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


function checkFileDep(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrect = /^\s*$/ ;		// la seule possibilite incorrecte aucune lettre
	var intCorrect = /^\d+$/
	
	
	// debut de verification
	var fileTitre = window.document.forms[formName].titreDepot.value ;
	var fileCommentaire = window.document.forms[formName].fichierCommentaire.value ;
		
	if (incorrect.test(fileTitre)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- saisie de titre incorrecte" ;
	}
	
	if (incorrect.test(fileCommentaire)) // champ vide
	{
		formIsCorrect = false ;
		message += "\n- Commentaire vide" ;
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
