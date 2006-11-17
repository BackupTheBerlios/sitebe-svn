<?php
/*
** Fichier : evaluation_matieres
** Date de creation : 30/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des evaluations (ou controles de connaissances) cote mise a jour
**	ajout, suppression, modification
*/




/*
** IMPORTANT :	La verification des donnees importantes fournies se fait cote serveur il
**		est donc important de controler toutes les donnees  concernees du formulaire
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "database.php")))
{	
	// choix de l'action a realiser
	
	// premier cas : ajout
	if (isset($_POST['evalMatAdd']))
	{
		dbConnect() ;
			
		$eltExists = dbQuery('SELECT COUNT(`id-matiere`) AS eltNumb
			FROM est_evalue
			WHERE `id-matiere` = '.$_POST['matiere'].' AND
				type = "'.$_POST['controle'].'" AND
				nature = "'.$_POST['nature'].'"') ;
					
		$eltExists = mysql_fetch_array($eltExists) ;
			
		// element existe deja
		if ($eltExists['eltNumb'] > 0)
		{
			centeredInfoMessage(3, 3, "Cette &eacute;valuation existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=evaluation_matieres\">\n") ;
			return ;
		}
			
		// ajout
		$matiere = trim($_POST['matiere']) ;
		$coeff1 = trim($_POST['coeff1']) ;
		$coeff2 = trim($_POST['coeff2']) ;
		
		dbQuery('INSERT INTO est_evalue
			VALUES ('.$matiere.', "'.$_POST['controle'].'", "'.$_POST['nature'].'", '.$coeff1.', '.$coeff2.')') ;
		centeredInfoMessage(3, 3, "Evaluation ajout&eacute;e avec succes, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=evaluation_matieres\">\n") ;
			
		dbClose() ;
		
	} // end of evalMatAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['evalMatMod']))
	{
		dbConnect() ;
			
		$eltExists = dbQuery('SELECT COUNT(`id-matiere`) AS eltNumb
			FROM est_evalue
			WHERE `id-matiere` = '.$_POST['matiere'].' AND
				type = "'.$_POST['controle'].'" AND
				nature = "'.$_POST['nature'].'"') ;
					
		$eltExists = mysql_fetch_array($eltExists) ;
			
		// element existe deja
		if ($eltExists['eltNumb'] > 0)
		{
			centeredInfoMessage(3, 3, "Cette &eacute;valuation existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=evaluation_matieres\">\n") ;
			return ;
		}
			
		$matiere = trim($_POST['matiere']) ;
		$coeff1 = trim($_POST['coeff1']) ;
		$coeff2 = trim($_POST['coeff2']) ;
		dbQuery('UPDATE est_evalue
			SET `id-matiere` = '.$matiere.', type = "'.$_POST['controle'].'", nature = "'.$_POST['nature'].'", coefficient1 = '.$coeff1.', coefficient2 = '.$coeff2.'
			WHERE `id-matiere` = '.$_POST['oldMat'].' AND type = "'.$_POST['oldType'].'" AND nature = "'.$_POST['oldNature'].'"') ;
		centeredInfoMessage(3, 3, "Evaluation modifi&eacute;e avec succes, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=evaluation_matieres\">\n") ;
			
		dbClose() ;
	} // end of menuMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['evalMatDel']))
	{	
		dbConnect() ;
		
		dbQuery('DELETE
			FROM est_evalue
			WHERE `id-matiere` = '.$_POST['oldMat'].' AND type = "'.$_POST['oldType'].'" AND nature = "'.$_POST['oldNature'].'"') ;
				
		dbClose() ;
		centeredInfoMessage(3, 3, "Evaluation supprim&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=evaluation_matieres\">\n") ;
	} // end of menuDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des &eacute;valuations des mati&egrave;res : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=evaluation_matieres\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF evaluation_matieres
*/
?>
