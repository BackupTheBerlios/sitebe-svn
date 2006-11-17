<?php
/*
** Fichier : logout
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu permettant la fermeture de session
*/


// rien de critique dans ce fichier
session_destroy() ;
// redirection
infoMessage(3, 3, "Fermeture de session...") ;
print("<meta http-equiv=\"refresh\" content=\"0;url=admin.php\">\n") ;


/*
** EOF logout
*/
?>
