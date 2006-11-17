<?php
/*
** Fichier : messages.inc
** Date de creation : 28/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Contient les fonctions predefinies pour afficher les messages 
*/



/*
** fonction 	: defaultInfoMessage
** entrees		: message : string
** sorties		: rien
** description	: affiche un message dans un format qui le met en evidence
*/
function defaultInfoMessage($message)
{
	print("<span class=\"info\">$message</span>") ;
}



/*
** fonction 	: infoMessage
** entrees		: brAvant : entier, brApres :entier, message : string
** sorties		: rien
** description	: affiche un message dans un format qui le met en evidence et effectue des retours
**		  a la ligne correspondant aux deux autres parametres
*/
function infoMessage($brBefore, $brAfter, $message)
{
	for ($i = 0 ; $i < $brBefore ; $i++) { print("<br>") ; }
	print("<span class=\"info\">$message</span>") ;
	for ($i = 0 ; $i < $brAfter ; $i++) { print("<br>") ; }
	print("\n") ;
}


/*
** fonction 	: centeredInfoMessage
** entrees		: brAvant : entier, brApres :entier, message : string
** sorties		: rien
** description	: affiche un message dans un format qui le met en evidence au centre et effectue des retours
**		  a la ligne correspondant aux deux autres parametres
*/
function centeredInfoMessage($brBefore, $brAfter, $message)
{
	infoMessage($brBefore, $brAfter, "<center>".$message."</center>") ;
}


/*
** fonction 	: defaultErrorMessage
** entrees		: message : string
** sorties		: rien
** description	: affiche un message d'erreur dans un format qui le met en evidence
*/
function defaultErrorMessage($message)
{
	print("<span class=\"error\">$message</span>") ;
}


/*
** fonction 	: errorMessage
** entrees		: brAvant : entier, brApres :entier, message : string
** sorties		: rien
** description	: affiche un message dans un format qui le met en evidence et effectue des retours
**		  a la ligne correspondant aux deux autres parametres
*/
function errorMessage($brBefore, $brAfter, $message)
{
	for ($i = 0 ; $i < $brBefore ; $i++) { print("<br>") ; }
	print("<span class=\"error\">$message</span>") ;
	for ($i = 0 ; $i < $brAfter ; $i++) { print("<br>") ; }
	print("\n") ;
}


/*
** fonction 	: centeredErrorMessage
** entrees		: brAvant : entier, brApres :entier, message : string
** sorties		: rien
** description	: affiche un message dans un format qui le met en evidence au centre et effectue des retours
**		  a la ligne correspondant aux deux autres parametres
*/
function centeredErrorMessage($brBefore, $brAfter, $message)
{
	errorMessage($brBefore, $brAfter, "<center>".$message."</center>") ;
}




/*
** EOF messages.inc
*/
?>
