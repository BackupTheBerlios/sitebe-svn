/*
** Fichier : scripts_admin_information
** Date de creation : 26/07/2004
** Auteurs : Avetisyan Gohar
** Version : 1.0
** Description : Fichier contenant les fonctions javascrpit pour le controle des formulaires des sections
*/


/*
** fonction 	: checkInformationAdd
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout des section est correct et soumet dans ce cas
*/
function checkInformationAdd(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrectText = /^\s*$/ ;
	var correctNumber = /^\d+$/ ;

	var titre = window.document.forms[formName].informationTitre.value ;
        var contenu = window.document.forms[formName].informationContenu.value ;
	
	// test du contenu
	if (incorrectText.test(titre))
	{
		formIsCorrect = false ;
		message += "\n- titre de l'information vide" ;
	}
	
	if (incorrectText.test(contenu))
	{
		formIsCorrect = false ;
		message += "\n- contenu de l'information vide" ;
	}


	// test du contenu
	/*if (!correctNumber.test(page))
	{
		formIsCorrect = false ;
		message += "\n- identifiant de page incorrect" ;
	}
	*/
	
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
** fonction 	: checkInformationMod
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire de modif des section est correct et soumet dans ce cas
*/
function checkInformationMod(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrectText = /^\s*$/ ;
	
	var contenu = window.document.forms[formName].informationContenu.value ;
    var titre = window.document.forms[formName].informationTitre.value ;

	// test du titre
	if (incorrectText.test(titre))
	{
		formIsCorrect = false ;
		message += "\n- titre incorrect de l'information" ;
	}

        // test du contenu
	if (incorrectText.test(contenu))
	{
		formIsCorrect = false ;
		message += "\n- contenu incorrect de l'information " ;
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
** EOF scripts_admin_information
*/
