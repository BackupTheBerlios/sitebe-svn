<?php
/*
** Fichier : promotions
** Date de creation : 27/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des promo cote mise a jour
**	ajout, suppression
*/




/*
** IMPORTANT :	La verification des donnees fournies se fait cote serveur il
**		est donc important de controler toutes les donnees du formulaire
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "database.php")))
{	
	// choix de l'action a realiser
	
	// premier cas : ajout
	if (isset($_POST['promoAdd']))
	{
		 	
			
			dbConnect() ;	 
							 
				// on insere la nouvelle promo
				dbQuery('INSERT INTO promotion
					VALUES ( "'.$_POST['promo'].'")') ;
					
				// felicitations et redirection
				centeredInfoMessage(3, 3, "promotion ajout&eacute; avec succ&egrave;s, redirection...") ;
				print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=promotions\">\n") ;				
			
			dbClose() ;
		
	} // end of menuAdd
	
	
	
	 
	
	
	// dernier cas : suppression
	elseif (isset($_POST['promoDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucune promotion selectionn&eacute;e, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=promotions&a=del\">\n") ;
		}
		
		// tout est correct
		else
		{
			dbConnect() ;	
			
			// si on doit supprimer les promotions
			 
			foreach ($_POST['id'] as $idKey)
			{
							
				dbQuery('DELETE
					FROM promotion
					WHERE `annee` = "'.$idKey.'"') ;
			}
						
			 		 
			
			dbClose() ;
			centeredInfoMessage(3, 3, "promotion(s) supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=promotions\">\n") ;
			
		}		
	} // end of menuDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des promotions : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=promotions\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF promotions
*/
?>
