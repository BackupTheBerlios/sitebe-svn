<?php
/*
** Fichier : information
** Date de creation : 20/12/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier inclu charge de la gestion de l'information du jour cote mise a jour
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
	elseif (isset($_POST['fileDep']))
	{
		// donnees correctes : traitement et ajout
		dbConnect() ;			
		
                //on ne trim pas le titre ni la matiere car il peut y avoir des espaces
                $fMatiere = adslashes($_POST['matiere']);
                $fileTitre = addslashes($_POST['titreDepot']) ;
                $fileCommentaire = addslashes($_POST['commentaireDepot']) ;

                $fileURL = $_FILES['fichierDepot']['name'];
                $fileURLT = $_FILES['fichierDepot']['tmp_name']

                // extension du fichier
		$extension = explode(".", $fileURL) ;
		$parts = count($extension) ;
		$finalExtension = $extension[$parts - 1] ;
		
		$finalFile = "" ;
		// on verifie si le cv est uploade avec succes
		if (is_uploaded_file($fileURLT))
		{
			// teste de toutes les facons
			@ $success = move_uploaded_file($fileURLT, "Data/Telechargement/{$_SESSION['diplome']}/{$fileTitre}_fich.".$finalExtension) ;
			if ($success) { $finalCV = $numetu."_cv.".$finalExtension ; }
		}
		
		$fMatiereId = dbQuery('SELECT `id-matiere`
                            FROM matiere
                            WHERE intitule = "'.$fMatiere.'"');
                $fMatiereId = mysql_fetch_array($fMatiereId);
                $fMatiereId = $fMatiereId['id-matiere'];

	        dbQuery('INSERT INTO fichier
		VALUES (NULL, "'.$fileTitre.'", "'.$_SESSION['id-dip'].'", "'.$_SESSION['id-enseignant'].'", "'.$finalFile.'", "'.$fileCommentaire.'")') ;

                // felicitations et redirection
                centeredInfoMessage(3, 3, "Fichier d&eacute;pos&eacute; avec succ&egrave;s, redirection...") ;
                print("<meta http-equiv=\"refresh\" content=\"2;url=index.php?w=enseignant\">\n") ;

                dbClose() ;
	} // end of fileDep
	
	
	
	// second cas : modification
	elseif (isset($_POST['infoMod']))
	{
		dbConnect() ;			
			
		$infoContenu = trim($_POST['informationContenu']) ;
		$infoContenu = addslashes($infoContenu) ;
			
		$infoTitre = addslashes($_POST['informationTitre']) ;
		$infoTitre = trim($infoTitre) ;
		
		$infoImg = trim($_POST['informationImage']);
		$infoImg = addslashes($infoImg);
			

		// mise a jour
		dbQuery('UPDATE information
			SET contenu = "'.$infoContenu.'", titre = "'.$infoTitre.'", URL = "'.$infoImg.'"
                        WHERE `id-information` = '.$_POST['informationID']) ;

		centeredInfoMessage(3, 3, "Information modifi&eacute;e avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=information\">\n") ;
			
		dbClose() ;
	} // end of infoMod
	
	
	
	// dernier cas : suppression
	elseif (isset($_POST['infoDel']))
	{		
		// aucun choix defini
		if (!isset($_POST['id']))
		{
			centeredErrorMessage(3, 3, "Aucune information selectionn&eacute;e, redirection...") ;
			print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=information&a=del\">\n") ;
			return ;
		}
		
		dbConnect() ;			
			
		foreach ($_POST['id'] as $info)
		{
			dbQuery('DELETE FROM information
				WHERE `id-information` = '.$info) ;
		}
			
			
		centeredInfoMessage(3, 3, "Information supprim&eacute;e(s) avec succ&egrave;s, redirection...") ;
		print("<meta http-equiv=\"refresh\" content=\"2;url=admin.php?w=information\">\n") ;				
			
			
		dbClose() ;	
	} // end of sectionDel
		
	// cas critique : action inconnue ou erronee : message erreur et redirection
	else
	{
		centeredErrorMessage(3, 3, "Administration de l'informaton : choix incorrect, redirection") ;
		print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=information\">\n") ;
	}

} // fin de if (isset($_SESSION['connecte']))


// l'utilisateur n'est pas authentifie
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}


/*
** EOF information
*/
?>
