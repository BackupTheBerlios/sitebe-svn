<?php
/*
** Fichier : matieres
** Date de creation : 20/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des matieres cote mise a jour
**	ajout, suppression, modification
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
	if (isset($_POST['matiereAdd']))
	{
		dbConnect() ;
			
		$intitule = trim($_POST['matiereIntitule']) ;
		$intitule = addslashes($intitule) ;
			
		$coeff = trim($_POST['matiereCoeff']) ;
		$coeff = (float)$coeff ;
			
		$heures = trim($_POST['matiereHeures']) ;
			
		$moduleID = trim($_POST['matiereModule']) ;
							
				
		// on verifie si la matiere existe deja
		$matiereInfo = dbQuery('SELECT COUNT(`id-matiere`) AS matNumb
			FROM matiere
			WHERE intitule = "'.$intitule.'" AND `id-module` = '.$moduleID) ;
				
		$matiereInfo = mysql_fetch_array($matiereInfo) ; 
			
		// si quelque chose existe deja
		if ($matiereInfo['matNumb'] > 0)
		{
			centeredInfoMessage(3, 3, "Impossible d'ajouter cette mati&egrave;re car elle existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres&a=add\">\n") ;
			return ;
		}
		
		dbQuery('INSERT INTO matiere
			VALUES (NULL, '.$moduleID.', 0, '.$coeff.', "'.$intitule.'",'.$heures.', 0,0,0,0,0,0)') ;
					
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Mati&egrave;re ajout&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres\">\n") ;
		
		dbClose() ;
	} // end of matiereAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['matiereMod']))
	{
		dbConnect() ;
			
		$intitule = trim($_POST['matiereIntitule']) ;
		$intitule = addslashes($intitule) ;
			
		$coeff = trim($_POST['matiereCoeff']) ;
		$coeff = (float)$coeff ;
			
		$heures = trim($_POST['matiereHeures']) ;
			
		$moduleID = trim($_POST['matiereModule']) ;
							
					
		// on verifie si la matiere existe deja
		$matiereInfo = dbQuery('SELECT COUNT(`id-matiere`) AS matNumb
			FROM matiere
			WHERE intitule = "'.$intitule.'" AND 
			`id-module` = '.$moduleID.' AND
			`id-matiere` != '.$_POST['matiereID']) ;
				
		$matiereInfo = mysql_fetch_array($matiereInfo) ; 
			
		// si quelque chose existe deja
		if ($matiereInfo['matNumb'] > 0)
		{
			centeredInfoMessage(3, 3, "Impossible d'ajouter cette mati&egrave;re car elle existe d&eacute;j&agrave;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres&a=mod&id={$_POST['matiereID']}\">\n") ;
			return ;
		}
		
		dbQuery('UPDATE matiere
			SET `id-module` = '.$moduleID.', intitule = "'.$intitule.'", coefficient = '.$coeff.', `nbre-heures` = '.$heures.'
			WHERE `id-matiere` = '.$_POST['matiereID']) ;
					
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Mati&egrave;re modifi&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres\">\n") ;
		
		dbClose() ;
	} // end of matiereMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['matiereDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucune mati&egrave;re selectionn&eacute;e, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;
			
			
		foreach ($_POST['id'] as $idKey)
		{					
			dbQuery('DELETE
				FROM matiere						
				WHERE `id-matiere` = '.$idKey) ;
				
			dbQuery('DELETE
				FROM est_evalue						
				WHERE `id-matiere` = '.$idKey) ;
					
			dbQuery('DELETE
				FROM enseignement						
				WHERE `id-matiere` = '.$idKey) ;
		}
			
			
		dbClose() ;
		centeredInfoMessage(3, 3, "Mati&egrave;re(s) et enseignements supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=matieres\">\n") ;
			
	} // end of matiereDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des mati&egrave;res : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=matieres\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF matieres
*/
?>
