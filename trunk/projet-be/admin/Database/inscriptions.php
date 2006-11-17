<?php
/*
** Fichier : inscriptions
** Date de creation : 04/01/2005
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion des inscriptions des etudiants cote mise a jour
**	ajout, suppression, modification
*/




/*
** IMPORTANT :	La verification des donnees critiques fournies se fait cote serveur il
**		est donc important de controler toutes les donnees du formulaire
*/


// !!! on s'assure toujours que l'utilisateur est bien loggue...
if (is_numeric(strpos($_SERVER['PHP_SELF'], "database.php")))
{	
	// choix de l'action a realiser
	
	// premier cas : ajout
	if (isset($_POST['inscriptionAdd']))
	{
		dbConnect() ;
		// traitement
		$annee = trim($_POST['annee']) ;
		$etudiant = trim($_POST['etudiant']) ;
		
		// on teste si cet etudiant existe bien
		$etuExists = dbQuery('SELECT COUNT(`id-etudiant`) AS etuNumb
			FROM etudiant
			WHERE `id-etudiant` = '.$etudiant) ;
			
		$etuExists = mysql_fetch_array($etuExists) ;
		
		// incorrect
		if ($etuExists['etuNumb'] != 1)
		{
			centeredErrorMessage(3, 3, "Cet &eacute;tudiant semble ne pas exister, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=inscriptions&a=add\">\n") ;
		}
		
		// tout est correct alors ajout
		else
		{
			
			// recuperation de l'identifiant du diplome
			$dipInfo = dbQuery('SELECT `id-diplome`
				FROM diplome
				WHERE intitule = "'.$_POST['diplome'].'"') ;
			$dipInfo = mysql_fetch_array($dipInfo) ;
			$dipID = $dipInfo['id-diplome'] ;
		
			// test si l'element est deja present
			$eltExists = dbQuery('SELECT COUNT(`id-etudiant`) AS eltNumb
				FROM inscrit
				WHERE `id-etudiant` = '.$etudiant.' AND
					`id-diplome` = '.$dipID.' AND
					annee = "'.$annee.'"') ;
					
			$eltExists = mysql_fetch_array($eltExists) ;
			
			// element existe deja
			if ($eltExists['eltNumb'] > 0)
			{
				centeredInfoMessage(3, 3, "Cette inscription existe d&eacute;j&agrave;, redirection...") ;
				print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=inscriptions\">\n") ;
			}
			
			// ajout
			else
			{			
				dbQuery('INSERT INTO inscrit
					VALUES ('.$etudiant.', '.$dipID.', "'.$annee.'")') ;
				centeredInfoMessage(3, 3, "Inscription ajout&eacute;e avec succes, redirection...") ;
				print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=inscriptions\">\n") ;
			}
		}	
		dbClose() ;
	} // end of inscriptionAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['inscriptionMod']))
	{
		dbConnect() ;
		// traitement
		$annee = trim($_POST['annee']) ;
		$etudiant = trim($_POST['etudiant']) ;
		
		// on teste si cet etudiant existe bien
		$etuExists = dbQuery('SELECT COUNT(`id-etudiant`) AS etuNumb
			FROM etudiant
			WHERE `id-etudiant` = '.$etudiant) ;
			
		$etuExists = mysql_fetch_array($etuExists) ;
		
		// incorrect
		if ($etuExists['etuNumb'] != 1)
		{
			centeredErrorMessage(3, 3, "Cet &eacute;tudiant semble ne pas exister, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=inscriptions&a=add\">\n") ;
		}
		
		// tout est correct alors ajout
		else
		{
			
			// recuperation de l'identifiant du diplome
			$dipInfo = dbQuery('SELECT `id-diplome`
				FROM diplome
				WHERE intitule = "'.$_POST['diplome'].'"') ;
			$dipInfo = mysql_fetch_array($dipInfo) ;
			$dipID = $dipInfo['id-diplome'] ;
		
			// test si l'element est deja present
			$eltExists = dbQuery('SELECT COUNT(`id-etudiant`) AS eltNumb
				FROM inscrit
				WHERE `id-etudiant` = '.$etudiant.' AND
					`id-diplome` = '.$dipID.' AND
					annee = "'.$annee.'"') ;
					
			$eltExists = mysql_fetch_array($eltExists) ;
			
			// element existe deja
			if ($eltExists['eltNumb'] > 0)
			{
				centeredInfoMessage(3, 3, "Cette inscription existe d&eacute;j&agrave;, redirection...") ;
				print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=inscriptions\">\n") ;
			}
			
			// ajout
			else
			{			
				dbQuery('UPDATE inscrit
					SET `id-etudiant` = '.$etudiant.', `id-diplome` = '.$dipID.', annee = "'.$annee.'"
					WHERE `id-etudiant` = '.$_POST['oldEtuID'].' AND
						`id-diplome` = '.$_POST['oldDiplome'].' AND
						 annee = "'.$_POST['oldAnnee'].'"') ;
				centeredInfoMessage(3, 3, "Inscription modifi&eacute;e avec succes, redirection...") ;
				print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=inscriptions\">\n") ;
			}
		}	
		dbClose() ;
	} // end of inscriptionMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['inscriptionDel']))
	{	
		dbConnect() ;
		
		dbQuery('DELETE
			FROM inscrit
			WHERE `id-etudiant` = '.$_POST['oldEtuID'].' AND
			`id-diplome` = '.$_POST['oldDiplome'].' AND 
			annee = "'.$_POST['oldAnnee'].'"') ;
				
		dbClose() ;
		centeredInfoMessage(3, 3, "Inscription supprim&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=inscriptions\">\n") ;
	} // end of menuDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des inscriptions : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=inscriptions\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF inscriptions
*/
?>
