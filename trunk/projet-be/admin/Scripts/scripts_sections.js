/*
** Fichier : scripts_sections
** Date de creation : 03/01/2005
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascript pour la page principale
*/


/*
** fonction 	: gotoPage
** entrees	: menu : identifiant du menu, position : position de la page
** sorties	: rien
** description	: redirige vers une autre page
*/
function gotoPage(menu, position)
{ 
	window.location.href = "index.php?p=sections&id="+menu+"&pos="+position ;
}


/*
** EOF scripts_sections
*/
