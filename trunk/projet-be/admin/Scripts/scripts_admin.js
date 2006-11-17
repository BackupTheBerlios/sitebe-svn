/*
** Fichier : scripts_admin
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascrpit
*/


/*
** fonction 	: openHelp
** entrees	: string : page a inclure
** sorties	: rien
** description	: ouvre la fenetre d'administration
*/
function openHelp(additionalPath)
{
	window.open("help.php?w="+additionalPath, "Aide", "width=850,height=600,toolbar=no,scrollbars=yes,directories=no,status=yes,resizable=no") ;
}



/*
** fonction	: checkItemsToDelete
** entrees	: nothing
** sorties	: boolean
** description	: verifie si au moins un champ du formulaire est choisi
** ATTENTION	: tous les formulaires de suppression doivent porter le nom deleteForm et
**		Les elements a supprimer l'identifiant chbox. Cela ne devrait poser aucun probleme
*/
function checkItemsToDelete(formLength)
{ 	
	var acceptSubmission = false ;
	var i = 0 ;
	while (!acceptSubmission && i < formLength)
	{
		acceptSubmission = window.document.getElementById("chbox"+i++).checked ;
	}

	if (!acceptSubmission)
	{
		alert("Pour supprimer, selectionnez au moins un element") ;
	}
	
	else
	{
		acceptSubmission = confirm("Supprimer definitivement ces elements ?") ;
	}
	
	return acceptSubmission ; ;
}



// fonctions d'edition appelees par la fonction PHP printEditingTools

/*
** fonction	: makeSimpleTag
** entrees	: form : nom du formulaire, area : zone de texte, tag : balise
** sorties	: boolean
** description	: insere un texte entre deux balises "simples"
*/
function makeSimpleTag(formName, fieldName, tagName)
{
	var targetField = window.document.forms[formName].elements[fieldName] ;
	var taggedText = prompt("Saisissez votre texte ici") ;
	if (taggedText)
	{
		targetField.value += "<"+tagName+">"+taggedText+"</"+tagName+">" ;
	}
}


/*
** fonction	: makeURL
** entrees	: form : nom du formulaire, area : zone de texte, mode : avance ou simple
** sorties	: boolean
** description	: fait une url
*/
function makeURL(formName, fieldName, mode)
{
	var targetField = window.document.forms[formName].elements[fieldName] ;
	var prefix = /^http:\/\// ;
	var url = prompt("Donnez une url") ;
	if (url)	
	{
		if (!prefix.test(url)){ url = "http://" + url ; }
		var urlI = url ; 
		if (mode == 2)
		{
			var temp = prompt("Donnez le texte a associer à l'url", "") ;
			if (temp) { urlI = temp ; }
		}
		targetField.value += "<a href=\""+url+"\" target=\"_blank\"> "+urlI+" </a>" ;
	}
}


/*
** fonction	: makeMail
** entrees	: form : nom du formulaire, area : zone de texte
** sorties	: boolean
** description	: fait un pointeur mail
*/
function makeMail(formName, fieldName)
{
	var targetField = window.document.forms[formName].elements[fieldName] ;
	var url = prompt("Saisissez une adresse éléctronique", "mail") ;
	if (url)	
	{		
		targetField.value += "<a href=\"mailto:"+url+"\"> "+url+" </a>" ;
	}
}


/*
** fonction	: makeImage
** entrees	: form : nom du formulaire, area : zone de texte
** sorties	: boolean
** description	: insere le code d'une image
*/
function makeImage(formName, fieldName)
{
	var targetField = window.document.forms[formName].elements[fieldName] ;
	var url = prompt("Donnez la source de l'image", "source") ;
	if (url)	
	{		
		targetField.value += "<img src=\""+url+"\" height=\"100%\" width=\"100%\">" ;
	}
}



/*
** EOF scripts_admin
*/
