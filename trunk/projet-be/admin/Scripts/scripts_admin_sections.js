/*
** Fichier : scripts_admin_sections
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascrpit pour le controle des formulaires des sections
*/


/*
** fonction 	: checkSectionAdd
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout des section est correct et soumet dans ce cas
*/
function checkSectionAdd(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrectText = /^\s*$/ ;
	var correctNumber = /^\d+$/ ;
	
	var contenu = window.document.forms[formName].sectionContenu.value ;
	var page = window.document.forms[formName].sectionPage.value ;
	
	// titre peut etre vide
	
	// test du contenu
	if (incorrectText.test(contenu))
	{
		formIsCorrect = false ;
		message += "\n- contenu de la section vide" ;
	}
	
	// test du contenu
	if (!correctNumber.test(page))
	{
		formIsCorrect = false ;
		message += "\n- identifiant de page incorrect" ;
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
** fonction 	: checkSectionMod
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire de modif des section est correct et soumet dans ce cas
*/
function checkSectionMod(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var incorrectText = /^\s*$/ ;
	
	var contenu = window.document.forms[formName].sectionContenu.value ;
	
	// titre peut etre vide
	
	// test du contenu
	if (incorrectText.test(contenu))
	{
		formIsCorrect = false ;
		message += "\n- contenu de la section vide" ;
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
** fonction 	: checkSectionAff
** entrees	: formName : nom du formulaire a tester
** sorties	: rien
** description	: verifie si le formulaire d'ajout des section est correct et soumet dans ce cas
*/
function checkSectionAff(formName)
{
	var formIsCorrect = true ;
	var message = "Impossible de continuer pour les raisons suivantes :" ;
	var correctNumber = /^\d+$/ ;	
	
	var page = window.document.forms[formName].sectionPage.value ;
	
	// titre peut etre vide
	
	// test du contenu
	if (!correctNumber.test(page))
	{
		formIsCorrect = false ;
		message += "\n- identifiant de page incorrect" ;
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
** EOF scripts_admin_sections
*/
