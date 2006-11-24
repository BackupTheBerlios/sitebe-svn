<?php
	/*
	fichier qui permet d'acceder a l'espace reserve du site
	auteur : Julien SIEGA , Emilien PERICO
	*/
	session_start();
	error_reporting(E_ALL && ~E_NOTICE);
	include('includes/config.php');
	require_once('includes/lib-db.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="fr"	xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
	<title>IUP ISI - <?=$titre?></title>
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="revisit-after" content="15 days" />
	<meta name="robots" content="index,follow" />
	<link rel="shortcut icon" type="images/x-icon" href="favicon.ico" />
	<SCRIPT language="Javascript">
		function openAbout()
		{
			window.open("about.php", "About", "width=850,height=700,toolbar=no,scrollbars=yes,directories=no,status=yes,resizable=no") ;
		}
		function openAdmin(parameters)
		{
			window.open("admin/admin.php?"+parameters, "Administration", "width=850,height=700,toolbar=no,scrollbars=yes,directories=no,status=yes,resizable=no") ;
		}
	</SCRIPT>
	<meta name="DC.Publisher" content="IUP ISI" />
	<link rel="stylesheet" href="styles/isi.css" type="text/css" />
</head>
<body>
	<div id="page">
		<div id="bandeau">
			<!--======================= Debut logo ==================-->
			<a title="Retour en page d'accueil" href="index.php">
			<img style="BORDER-RIGHT: 0px solid; BORDER-TOP: 0px solid; BORDER-LEFT: 0px solid; BORDER-BOTTOM: 0px solid;" alt="IUP ISI" src="img/logo-trans.png" />
			</a>
		</div><!-- Debut Texte -->
		<table width="1000" valign="top" cellspacing="15">
			<tr>
			<td width="150" valign="top">
				<div id="theme">
<?php
	$sql = "SELECT * FROM menu WHERE ID_PMENU='0' ORDER BY ORDRE";
	$res = DB_query($sql);
	while($row = DB_fetchArray($res))
	{
		echo "<h2>" . $row['INTITULE'] . "</h2>\n";
		echo "<ul>";
		$sql1 = "SELECT * FROM menu WHERE ID_PMENU='" . $row['ID-MENU'] . "' ORDER BY ORDRE";
		$res1 = DB_query($sql1);
		while($row1 = DB_fetchArray($res1))
		{
			$essai = explode("?", $row1['PATH']);
			$finale = "index.php?".$essai[1];
			echo "<li><a href='" . $finale . "'>" . $row1['INTITULE'] . "</a></li>\n";
		}
		echo "</ul>";
	}
?>
				</div>
				<div id="direction">
					<!--debut direction-->
					<h1>Responsable</h1>Henri MASSIE&nbsp;<br />
					<a href="mailto:massie@irit.fr">massie@irit.fr</a>&nbsp;<br />
					T&eacute;l : 05 61 55 63 52<br />
					<h1>S&eacute;cr&eacute;tariat</h1>Christine
					AIROLA&nbsp;<br />
					<a href="mailto:airola@cict.fr">airola@cict.fr</a><br />
					B&acirc;timent U3<br />
					T&eacute;l : 05 61 55 75 46<br />
					Fax : 05 61 55 85 95
					<!--======================= fin direction ==================-->
				</div>
				<div id="logob">
					<!--==========debut pied de page======-->
					<a href="http://www.ups-tlse.fr/" title="UPS"><img src="img/logoups.gif" alt="UPS" /></a>
				</div>
			</td>
			<td valign="top">
				<div id="espacereserve">
				<!-------------------------------------------------->
				<!-- partie qui permet de gerer la partie reservé -->
				<!-------------------------------------------------->
				
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
						}
						else
						{
 							// $_FILES['nom_du_fichier']['error'] vaut 0 soit UPLOAD_ERR_OK
 							// ce qui signifie qu'il n'y a eu aucune erreur
						}
						$content_dir = 'Etudiants/'; // dossier où sera déplacé le fichier
						$content_dir=$content_dir.$id_etu;
						$matiere=$_POST['combo'];
						$res=mysql_query("select `id-matiere` from matiere where intitule='$matiere'");
						$row=DB_fetchArray($res);
						$content_dir=$content_dir."/";
						$content_dir=$content_dir.$row[0];
						$content_dir=$content_dir."/";

    					$tmp_file = $_FILES['fichier']['tmp_name'];

   						// on copie le fichier dans le dossier de destination
    					$name_file = $_FILES['fichier']['name'];

    					if( !move_uploaded_file($tmp_file, $content_dir . $name_file) )
    					{
    	    				exit("Impossible de copier le fichier dans $content_dir");
    					}
    					print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php?p=connexion&w=etudiants\">\n") ;
					}
					else
					{
						
							$res=mysql_query("select matiere.intitule from matiere,etudiant,module,inscrit,diplome
											 where inscrit.`id-etudiant`=etudiant.`id-etudiant`
											 and inscrit.`id-diplome`=diplome.`id-diplome`
									 	     and diplome.`id-diplome`=module.`id-diplome`
										 	 and matiere.`id-module`=module.`id-module`
											 and etudiant.`id-etudiant`='$id_etu'");
							print("<h2>Deposer des fichiers</h2>");			 	
							print("<font face=\"Arial\" color=#330033><i></i>Selectionner la matiere:</font><br><br>");
							print("<form method=\"post\" action=\"deposer_doc.php\" enctype=\"multipart/form-data\">");
							print("<select size=\"1\" name=\"combo\">");
							
							while($row = DB_fetchArray($res))
							{
								
								print("<option>$row[0]</option>");
							}
							print("</select>");
							
							print("<font face=\"Arial\" color=#330033><i></i><br><br><br>Selectionner le fichier a deposer:</font><br><br>");					
						
							print("<form method=\"post\" action=\"deposer_doc.php\" enctype=\"multipart/form-data\">");
          					print("<input type=hidden name=MAX_FILE_SIZE  VALUE=5000000>");
          					print("<input type=file name=\"fichier\"><br><br>");
          					print("<input type=submit value=\"Deposer\">");
           					print("</form>");
     				}
				?>



				<!------------------------------------------------------------>
				<!-- fin de la partie qui permet de gerer la partie reservé -->
				<!------------------------------------------------------------>
				</div>
			</td>
			</tr>
		</table>
		<div id="about">
			<a href="javascript:openAdmin('')">Administrer</a> - <a href="javascript:openAbout()">A propos</a> &nbsp;&nbsp;
		</div>
		<div id="iupisi">
			Universit&eacute; Paul Sabatier - IUP ISI -
			B&acirc;timent Pierre Paul Riquet (U3) - 118 route de
			Narbonne -&nbsp;31062 TOULOUSE Cedex 9
		</div><!--==========fin pied de page======-->
	</div>
</body>
</html>

<?php
	
	
        


