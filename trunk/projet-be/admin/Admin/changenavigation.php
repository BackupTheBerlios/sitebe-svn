<?php
/*
** Fichier : changenavigation
** Date de creation : 27/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu permettant la modification du mode de navigation sur le site principal
*/



// !!! on s'assure que cette page est appelee a partir de admin.php
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{
	// si on doit basculer en mode normal
	if (isset($_SESSION['rootNavigation']))
	{
		unset($_SESSION['rootNavigation']) ;
	} // end of if (isset($_SESSION['rootNavigation']))
	
	// si on doit basculer en mode super
	else
	{
		$_SESSION['rootNavigation'] = true ;
	}
	
	infoMessage(3, 3, "Mode de navigation chang&eacute;...") ;
	print("<meta http-equiv=\"refresh\" content=\"0;url=admin.php\">\n") ;

}

else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF changenavigation
*/
?>
