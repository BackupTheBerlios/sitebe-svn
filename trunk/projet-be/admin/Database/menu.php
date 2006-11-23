<?php
/*
** Fichier : menu
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion du menu cote mise a jour
**	ajout, suppression, modification
*/





// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "database.php")))
{	
	// choix de l'action a realiser
	
	// premier cas : ajout
	if (isset($_POST['menuAdd']))
	{
		dbConnect() ;
			
		// enfin, on peut ajouter sans probleme
		$intitule = trim($_POST['menuIntitule']) ;
		$intutule = addslashes($intitule) ;

        

        $desc = addslashes(trim($_POST['menuDescription']));
        

        // On récupère le nombre d'élément du menu dans le but d'inséré en queue de liste

        $menuOld = dbQuery('SELECT `id-menu` FROM menu WHERE id_pmenu='.(int)$_POST['menuSub']);

        $nbsubmenu = (int)mysql_num_rows($menuOld) + 1;

        
		// on insere le nouveau menu
		dbQuery('INSERT INTO menu
			VALUES (NULL, ' . (int)$_POST['menuSub'] . ', "'.$intitule.'", "' . $desc . '", "' . $_POST['menuPath'] . '", '.$nbsubmenu.')') ;
					
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Menu ajout&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=menu\">\n") ;				
		
		dbClose() ;
	} // end of menuAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['menuMod']))
	{
		dbConnect() ;
			
		$intitule = trim($_POST['menuIntitule']) ;
		$intutule = addslashes($intitule) ;
				
		// on met a jour la vieille liste de menus
		$menuDetails = dbQuery('SELECT ordre
			FROM menu
			WHERE `id-menu` = '.$_POST['menuID']) ;
		$menuDetails = mysql_fetch_array($menuDetails) ;
		$oldPosition = $menuDetails['ordre'] ;
				
		// cas ou la position du menu a ete reduite			
		if ($_POST['menuPosition'] <= $oldPosition)
		{
			dbQuery('UPDATE menu
			SET ordre = ordre + 1
			WHERE ordre >= '.$_POST['menuPosition'].' AND ordre < '.$oldPosition) ;
		}
			
		// cas ou la position a ete augmentee
		else
		{
			dbQuery('UPDATE menu
			SET ordre = ordre - 1
			WHERE ordre > '.$oldPosition.' AND ordre <= '.$_POST['menuPosition']) ;
		}
				
		// on met a jour le  menu
		dbQuery('UPDATE menu
			SET intitule = "'.$intitule.'", ordre = '.$_POST['menuPosition'].', path = "'.$_POST['menuPath'].'", description = "' .$_POST['menuDescription']. '"
			WHERE `id-menu` = '.$_POST['menuID']) ;
					
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Menu modifi&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=menu\">\n") ;		
			
		dbClose() ;
	} // end of menuMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['menuDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucun menu selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=menu&a=del\">\n") ;
			return ;
		}
		
		// tout est correct
		dbConnect() ;	
			
			
		// enfin on supprime les menus
		foreach ($_POST['id'] as $idKey)
		{
			// menus
			$menuPos = dbQuery('SELECT id_pmenu, ordre
				FROM menu
				WHERE `id-menu` = '.$idKey) ;
			$menuPos = mysql_fetch_array($menuPos) ;
            
			dbQuery('UPDATE menu
				SET ordre = ordre - 1
				WHERE ordre > '.$menuPos['ordre'] . ' AND id_pmenu = '.$menuPos['id_pmenu']) ;
					
			dbQuery('DELETE
				FROM menu						
				WHERE `id-menu` = '.$idKey) ;

                

            dbQuery('DELETE
				FROM menu						
				WHERE `id_pmenu` = '.$idKey) ;
		}
		dbClose() ;
		centeredInfoMessage(3, 3, "Menu(s) supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=menu\">\n") ;		
	} // end of menuDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration du menu : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=menu\">\n") ;
	}

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
