/*
** Fichier : scripts
** Date de creation : 03/01/2005
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant les fonctions javascript propres au site
*/


/*
** fonction 	: openAdmin
** entrees	: chemin : parametres optionnels le premier commencant par la page a charger(w)
** sorties	: rien
** description	: ouvre la fenetre d'administration
*/
function openAdmin(parameters)
{
	window.open("admin.php?"+parameters, "Administration", "width=850,height=700,toolbar=no,scrollbars=yes,directories=no,status=yes,resizable=no") ;
}

/*
** fonction 	: openAbout
** entrees	: rien
** sorties	: rien
** description	: ouvre la fenetre d'administration
*/
function openAbout()
{
	window.open("about.php", "About", "width=850,height=700,toolbar=no,scrollbars=yes,directories=no,status=yes,resizable=no") ;
}
/*
** EOF scripts
*/
