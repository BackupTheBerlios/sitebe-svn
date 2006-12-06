<?
	$id_etu=$_SESSION['id-etu'];
	if (isset($_FILES['fichier']))
	{
		if ($_FILES['fichier']['error'])
		{
			switch ($_FILES['fichier']['error'])
  			{
        				case 1: // UPLOAD_ERR_INI_SIZE
         				echo"Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !";
         				break;
         				case 2: // UPLOAD_ERR_FORM_SIZE
         				echo "Le fichier dépasse la limite autorisée dans le formulaire HTML !";
         				break;
         				case 3: // UPLOAD_ERR_PARTIAL
         				echo "L'envoi du fichier a été interrompu pendant le transfert !";
         				break;
         				case 4: // UPLOAD_ERR_NO_FILE
         				echo "Le fichier que vous avez envoyé a une taille nulle !";
         				break;
			}
			echo '</table>';
		}
		else
		{
			// $_FILES['nom_du_fichier']['error'] vaut 0 soit UPLOAD_ERR_OK
			// ce qui signifie qu'il n'y a eu aucune erreur
			$content_dir = 'Data/'; // Racine du dossier où sera déplacé le fichier
			$id_matiere=$_POST['combo'];
			
			$res=mysql_query("select matiere.apogee, module.apogee from matiere,module where matiere.`id-module`=module.`id-module` and matiere.`id-matiere`='$id_matiere'");
			$row=DB_fetchArray($res);
						
			$content_dir=$content_dir.$row[1]."/".$row[0]."/";
			
			$tmp_file = $_FILES['fichier']['tmp_name'];

			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['fichier']['name'];
			
			if ($_GET['w']=='etudiants')
			{
				$name_file="_".$name_file;
				$prop=$_SESSION['id-etu'];
			}
			elseif ($_GET['w']=='enseignants')
			{
				$prop=$_SESSION['id-enseignant'];
				$id_ens=$_SESSION['id-enseignant'];
				$req=mysql_query("select login from enseignant where `id-enseignant`='$id_ens'");
				$login=DB_fetchArray($req);
				$content_dir=$content_dir.$login[0]."/";
			}

			if( !move_uploaded_file($tmp_file, $content_dir.$id_etu.$name_file))
			{
				print ("Impossible de copier le fichier dans $content_dir");
			}
			else
			{
				echo "<tr><td>Fichier envoy&eacute; avec succ&egrave;s. Redirection...</td></tr>";
				$nomDiplome=$_SESSION['diplome'];
				
				$res=mysql_query("select `id-diplome` from diplome where intitule='$nomDiplome'");
				$row=DB_fetchArray($res);
				
				$diplome=$row[0];
				
				echo '</table>';
				DB_Query('INSERT INTO fichier	VALUES (NULL, "'.$_POST['titreDepot'].'", "'.$diplome.'", "'.$prop.'", "'.$content_dir.$id_etu.$name_file.'", "'.$_POST['commentaireDepot'].'")') ;
			}
			print("<meta http-equiv=\"refresh\" content=\"3;url=espacereserve.php?p=connexion&w=".$_GET['w']."\">\n") ;
		}
	}
	else
	{
		print("<table cellspacing=\"3\"><tr><td colspan=\"2\"><h2>D&eacute;poser des fichiers</h2></td></tr>");
		print("<form method=\"post\" action=\"espacereserve.php?p=connexion&w=".$_GET['w']."&a=dep\" enctype=\"multipart/form-data\">");
		
		if ($_GET['w']=='etudiants')
		{
			$res=mysql_query("select matiere.`id-matiere`,matiere.intitule  from matiere,etudiant,module,inscrit,diplome
						where inscrit.`id-etudiant`=etudiant.`id-etudiant`
						and inscrit.`id-diplome`=diplome.`id-diplome`
						and diplome.`id-diplome`=module.`id-diplome`
						and matiere.`id-module`=module.`id-module`
						and etudiant.`id-etudiant`='$id_etu'");
			print("<tr><td colspan=\"2\"><dd>S&eacute;lectionner la mati&egrave;re:</td></tr>");
			print("<tr><td colspan=\"2\"><dd><select size=\"1\" name=\"combo\">");
			
			while($row = DB_fetchArray($res))
			{
				
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
			print("</select></td></tr>");
			print("<tr><td>&nbsp;</td></tr>");
		}
		else
		{
			$id_matiere=$_GET['mat'];
			print("<input type=\"hidden\" name=\"combo\" value=\"$id_matiere\">");
		}
		print("<tr><td align=\"left\" width=\"20\"><b> Titre </b></td><td align=\"left\"><input class=\"defaultInput\" name=\"titreDepot\" size=\"40\"></td></tr>\n") ;
		print("<tr><td align=\"left\" width=\"20\"><font face=\"Arial\" color=#330033><br>Fichier </font></td>");
		print("<input type=hidden name=MAX_FILE_SIZE  VALUE=5000000>");
		print("<td><input type=file name=\"fichier\" size=\"40\"></td></tr>");
		print("<tr>\n") ;
		print("<td align=\"left\" colspan=\"3\"><b> Commentaire </b></td>\n") ;
		print("</tr>\n") ;
		print("<tr>\n") ;
		print("<td width=\"800\" colspan=\"3\" align=\"left\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"50\" name=\"commentaireDepot\"></textarea><br><br></td>\n") ;
		print("</tr>\n") ;
		print("<tr><td><input type=submit value=\"D&eacute;poser\"></td></tr>");
		print("</form></table>");           					
	}
?>

