<?php
/*
** Fichier : etudiants
** Date de creation : 20/08/2005
** Auteurs : Avetisyan Gohar
** Version : 2.0
** Description : Fichier inclu charge de la gestion des etudiants cote mise a jour
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
	if (isset($_POST['etuAdd']))
	{
		dbConnect() ;
		
		//on format les données récupérées en ajoutant des anti-slashes
		$numetu = trim($_POST['numetu']) ;
		$numetu = addslashes($numetu) ;
		$nom = trim($_POST['nometu']) ;
		$nom = addslashes($nom) ;
		$prenom = trim($_POST['prenometu']) ;
		$prenom = addslashes($prenom) ;
		$email = trim($_POST['mailetu']) ;
		$email = addslashes($email) ; 
		$login = trim($_POST['loginetu']) ;
		$login = addslashes($login) ;
        $mdp = md5($_POST['mdpetu']) ;
        $mdp = addslashes($mdp) ;

		$cv = $_FILES['cvetu']['name'] ;
		$cvT = $_FILES['cvetu']['tmp_name'] ;
		
		// extension du cv
		$extension = explode(".", $cv) ;
		$parts = count($extension) ;
		$finalExtension = $extension[$parts - 1] ;
		
		//$cv = addslashes($cv) ;
		// on verifie si le numero etudiant est unique
		$idEtu = dbQuery('SELECT COUNT(`id-etudiant`) AS counter
			FROM etudiant
			WHERE `id-etudiant` = '.$numetu) ;
			
		$idEtu = mysql_fetch_array($idEtu) ;	
		
		// existant
		if ($idEtu['counter'] != 0)
		{
			centeredErrorMessage(3, 3, "Ce numero semble d&eacute;j&egrave; exister, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=etudiants&a=add\">\n") ;				
			return ;
		}
		
		$finalCV = "" ;
		// on verifie si le cv est uploade avec succes
		if (is_uploaded_file($cvT))
		{
			// teste de toutes les facons
			@ $success = move_uploaded_file($cvT, "../Data/CV/{$numetu}_cv.".$finalExtension) ;
			if ($success) { $finalCV = $numetu."_cv.".$finalExtension ; }
		}
		
			
		// on insere le nouvel etudiant
		dbQuery ('INSERT INTO etudiant				
			VALUES ('.$numetu.', "'.$nom.'", "'.$prenom.'","'.$email.'", "'.$login.'", "'.$mdp.'","'.$finalCV.'")') ;
		
			
			
		//on recupere l'identification de son diplome
		if (isset($_POST['inscrire']))
		{
			$dipInfo = dbQuery('SELECT `id-diplome`
				FROM diplome
				WHERE intitule = "'.$_POST['diplome'].'"') ;
			$dipInfo = mysql_fetch_array($dipInfo) ;
			$dipID = $dipInfo['id-diplome'] ;
			
			if (!empty($dipID))
			{
				//on insere les données relatifs a son inscription
				dbQuery ('INSERT INTO inscrit
					VALUES ('.$numetu.', '.$dipID.', "'.$_POST['annee'].'")') ;
			}
				
		}
			
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Etudiant ajout&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=etudiants\">\n") ;				
			
		dbClose() ;
	} // end of etuAdd
	
	
	
	// second cas : modification
	elseif (isset($_POST['etuMod']))
	{
		dbConnect() ;
		
		$nom = trim($_POST['nometu']) ;
		$nom = addslashes($nom) ;
		$prenom = trim($_POST['prenometu']) ;
		$prenom = addslashes($prenom) ;
		$email = trim($_POST['mailetu']) ;
		$email = addslashes($email) ; 
        $login = trim($_POST['loginetu']) ;
        $login = addslashes($login) ;
        $mdp = md5($_POST['mdpetu']) ;
        $mdp = addslashes($mdp) ;
		
		if (isset($_POST['rmCV']))
		{		
			$cv = $_FILES['cvetu']['name'] ;
			$cvT = $_FILES['cvetu']['tmp_name'] ;
		
			// extension du cv
			$extension = explode(".", $cv) ;
			$parts = count($extension) ;
			$finalExtension = $extension[$parts - 1] ;
		
		
		
			$finalCV = "" ;
			// on verifie si le cv est uploade avec succes
			if (is_uploaded_file($cvT))
			{
				$success = move_uploaded_file($cvT, "../Data/CV/{$_POST['etuID']}_cv.".$finalExtension) ;
				if ($success) { $finalCV = $_POST['etuID']."_cv.".$finalExtension ; }
			}
			
			// on met a jour les donnees de l'etudiant
			dbQuery('UPDATE etudiant
				SET nom = "'.$nom.'", prenom = "'.$prenom.'", email = "'.$email.'", login = "'.$login.'", mdp = "'.$mdp.'", CV ="'.$finalCV.'"
				WHERE `id-etudiant` = '.$_POST['etuID']) ;
		}
		
		// on met pas a jour le cv
		else
		{ 
			// on met a jour les donnees de l'etudiant
			dbQuery('UPDATE etudiant
				SET nom = "'.$nom.'", prenom = "'.$prenom.'", email = "'.$email.'", login = "'.$login.'", mdp = "'.$mdp.'"
				WHERE `id-etudiant` = '.$_POST['etuID']) ;
		}
		
								
		// felicitations et redirection
		centeredInfoMessage(3, 3, "Etudiant modifi&eacute; avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=etudiants\">\n") ;		
		
		dbClose() ;
		
	} // end of etuMod
	
	
	
	// troisieme cas : suppression
	elseif (isset($_POST['etuDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucun etudiant selectionn&eacute;, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=etudiants&a=del\">\n") ;
			return ;
		}
		
		// tout est correct
		dbConnect() ;	
				
		// on supprime les etudiants
		foreach ($_POST['id'] as $idKey)
		{
			dbQuery('DELETE
				FROM etudiant						
				WHERE `id-etudiant` = '.$idKey) ;
					
			dbQuery('DELETE
				FROM inscrit						
				WHERE `id-etudiant` = '.$idKey) ;
			
			// suppression de tous les fichiers qui ont l'id en tete
			$cvDir = opendir("../Data/CV/") ;
			while ($fileName = readdir($cvDir))
			{
				
				if (is_numeric(strpos($fileName, $idKey."_")))
				{
					unlink("../Data/CV/".$fileName) ;
				}
			}
		}
			
		dbClose() ;
		centeredInfoMessage(3, 3, "Etudiant(s) supprim&eacute;(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=etudiants\">\n") ;
				
	} // end of etuDel
	
	// dernier cas : suppression de tous les étudiants sans exception
	elseif (isset($_POST['etuDelAll']))
	{
		dbConnect() ;	
				
		// on supprime tous les etudiants
		dbQuery('DELETE
			FROM etudiant') ;
				
		dbQuery('DELETE
			FROM inscrit') ;
		
		// suppression de tous les fichiers du dossier CV
		$cvDir = opendir("../Data/CV/") ;
		while ($fileName = readdir($cvDir))
		{
			if (strcmp($fileName, ".") != 0 && strcmp($fileName, "..") != 0)
			{
				unlink("../Data/CV/".$fileName) ;
			}
		}
			
		dbClose() ;
		centeredInfoMessage(3, 3, "Etudiants supprim&eacute;s avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=etudiants\">\n") ;
	} // end of etuDelAll

	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration des etudiants : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=etudiants\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF etudiants
*/
?>
