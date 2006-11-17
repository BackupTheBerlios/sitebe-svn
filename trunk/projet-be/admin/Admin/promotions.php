<?php
/*
** Fichier : promotions
** Date de creation : 27/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des promotions cote administration
**	ajout, suppression
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))
{	
	// aucune action precisee : menu principal
	if (!isset($_GET['a']))
	{
		centeredInfoMessage(3, 3, "Administration des promotions : accueil") ;
		print("\t\t\t<center>[ <a href=\"admin.php?w=promotions&a=add\">ajouter promotion</a> ] - ") ;
		print("[ <a href=\"admin.php?w=promotions&a=del\">supprimer promotion</a> ]<br>\n") ;
	} // fin de if (!isset($_GET['a']))
	
	
	
	
	// une action est precisee
	else
	{
	
		
	   		
	
		// ajout d'un element
		if ($_GET['a'] == "add")
		{
			
			// liste de toutes les années de 1980 jusqu a l annee prochaine le format d une année est : 'yyyy - yyyy' 
			$firstYear= "1980";
			$currentYear = date("Y");
			$nextYear = 1+$currentYear;

			$i=0+$firstYear;
			$j=1+$firstYear;
            		$k=0;
			while ($j <= $nextYear)
			{
				$tabYear[$k]="$i - $j";
				$i=$j;
				$j++;
				$k++;
			}		 		
			

			// connexion a la bd pour determiner les promos déja présentes dans la base de données
			dbConnect() ;
			$dbYear = dbQuery('SELECT annee as year
				FROM promotion') ;
				
			$k=0;
			while( $res = mysql_fetch_array($dbYear))
			{
				$Year[$k]=$res[0];
				$k++;
			}	
			
			
			
			centeredInfoMessage(3, 3, "Administration des promotions : ajout") ;
			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=promotions\" method=\"post\">\n") ;
			print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
			 
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"400\" align=\"left\"><b> Choix de l'ann&eacute;e </b></td><td width=\"100\" align=\"left\"><select class=\"defaultInput\" name=\"promo\">") ;
			foreach ($tabYear as $element)
			{
                                // on rajout une année dans la liste deroullante si elle est pas déja presente dans la bd
				if ( !in_array($element,$Year)) 
					{
					print("<option> $element </option>") ;
                         		}	

			}

			print("</select></td>") ;
			print("\t\t\t\t</tr>\n") ;
 
			print("\t\t\t\t<tr>\n") ;
			print("\t\t\t\t\t<td width=\"700\" align=\"left\" colspan=\"3\"><br><input class=\"defaultButton\" type=\"submit\" name=\"promoAdd\" value=\"Ajouter\"></td>\n") ;
			print("\t\t\t\t</tr>\n") ;
			print("\t\t\t</table>\n") ;
			print("\t\t\t</form></center>\n") ;
			
			dbClose() ;
		} // end of if add
		
		
		
		
		 
		
		
		
		
		// suppression d'un element
		elseif ($_GET['a'] == "del")
		{
			centeredInfoMessage(3, 3, "Administration des promotions : suppression") ;
			
			// connexion a la base de donnees et recuperation des infos
			dbConnect() ;
			$promoList = dbQuery('SELECT annee
				FROM promotion') ;
					
			$promoCount = mysql_num_rows($promoList) ;
				
			// aucun menu pour le moment
			if ($promoCount == 0)
			{
				centeredInfoMessage(2, 2, "Element vide") ;
			}
			
			else
			{
				print("\t\t\t<center><form name=\"deleteForm\" action=\"database.php?w=promotions\" method=\"post\" onSubmit=\"return checkItemsToDelete()\">\n") ;
				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;
				
				for ($i = 0 ; $i < $promoCount ; $i++)
				{
					$fpromoList = mysql_fetch_array($promoList) ;
					print("\t\t\t\t<tr>\n") ;
					print("\t\t\t\t\t<td width=\"200\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" value=\"{$fpromoList['annee']}\"> {$fpromoList['annee']}</td>\n") ;
					print("\t\t\t\t</tr>\n") ;
				}
				 
				print("\t\t\t\t<tr>\n") ;
				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"promoDel\" value=\"Supprimer\"></td>\n") ;
				print("\t\t\t\t</tr>\n") ;
				print("\t\t\t</table>\n") ;
				print("\t\t\t</form></center>\n") ;
			}
			dbClose() ;
		} // end of if del
		
		// cas critique : action inconnue : message erreur et redirection
		else
		{
			centeredErrorMessage(3, 3, "Administration des promotions : choix incorrect, redirection") ;
			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=promotions\">\n") ;
		}
		
	} // fin de else(!isset($_GET['a']))

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF menu
*/
?>
