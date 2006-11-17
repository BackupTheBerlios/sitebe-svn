/*
** Fichier : scripts_help
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascript pour l'aide 
*/


/*
** fonction 	: setLink
** entrees	: entier : identifiant, string : formulaire, string : champ
** sorties	: rien
** description	: insere un identifiant dans un champ de formulaire
*/
function setLink(identifier, targetForm, targetField)
{ 
	if (window.opener && !window.opener.closed)
	{
		var IDTarget = window.opener.document.forms[targetForm].elements[targetField] ;
		IDTarget.value = identifier ;
		window.close() ;
	}
}


/*
** EOF scripts_help
*/
