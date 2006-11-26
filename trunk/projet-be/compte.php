<?php
	/*
	fichier qui permet d'acceder a l'espace reserve du site
	auteur : CLOUET Gael
	*/
	session_start();
	error_reporting(E_ALL && ~E_NOTICE);
	include('includes/config.php');
	require_once('includes/lib-db.php');
	$titre="Compte";
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
	<style type="text/css">
	img {
		behavior: url("styles/pngbehavior.htc");
	}
	</style>
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
			<th valign="top">
				<div id="espacereserve">
				<!-------------------------------------------------->
				<!-- partie qui permet de gerer la partie reservé -->
				<!-------------------------------------------------->
				
				<?
				if(isset($_POST['nom']))      $nom=$_POST['nom'];
					else      $nom="";
					
				if(isset($_POST['prenom']))      $prenom=$_POST['prenom'];
					else      $prenom="";
					
				if(isset($_POST['mail']))      $mail=$_POST['mail'];
					else      $mail="";
					
				if(isset($_POST['num_etu']))      $num_etu=$_POST['num_etu'];
					else      $num_etu="";
					
				if(isset($_POST['login']))      $login=$_POST['login'];
					else      $login="";
					
				if(isset($_POST['password']))      $password=$_POST['password'];
					else      $password="";
					
				if(isset($_POST['password2']))      $password2=$_POST['password2'];
					else      $password2="";
					
				if(empty($nom) OR empty($prenom) OR empty($num_etu) OR empty($mail) OR empty($password) OR empty($login) OR empty($password2) OR $password!=$password2)
				{
  					if (isset($_POST['nom']) OR isset($_POST['prenom']) OR isset($_POST['num_etu']) OR isset($_POST['mail']) OR isset($_POST['password']) OR isset($_POST['login']))
  					{
  						print("Attention, aucun champ de doit reste vide !");
					}
  					if($password!=$password2)
  					{
  						print("\nAttention, les deux mots de passe sont différents !");
  					}
  					?>
					<h2>
					Inscription
					<form method="post" action="compte.php">
					<center><table width=400 border=0>
					<tr><td align="left">N&deg;Etudiant : </td><td align="right"><input type="text" name="num_etu" value="<?= $_POST['num_etu'] ?>"size="15"></td></tr><br>
					<tr><td align="left">Pr&eacute;nom : </td><td align="right"><input type="text" name="prenom" value="<?= $_POST['prenom'] ?>" size="15"></td></tr><br>
					<tr><td align="left">Nom : </td><td align="right"><input type="text" name="nom" value="<?= $_POST['nom']?>" size="15"></td></tr><br><br>
					<tr><td align="left">Mail : </td><td align="right"><input type="text" name="mail" value="<?= $_POST['mail'] ?>" size="15"></td></tr>
					<tr><td align="left">Login : </td><td align="right"><input type="text" name="login" value="<?= $_POST['login'] ?>" size="15"></td></tr>
										<tr><td>&nbsp;</td></tr>
					<tr><td align="left">Mot de passe :</td><td align="right"><input type="password" name="password" size="16"></td></tr>
					<tr><td align="left">Confirmer le mot de passe :</td><td align="right"><input type="password" name="password2" size="16"></td></tr>
										<tr><td>&nbsp;</td></tr>
					<?
					
					$res=mysql_query("select intitule from diplome");
  					print("<tr><td align=left>Selectionner votre annee:</td><td align=right><select size=\"1\" name=\"combo\">");
							
					while($row = DB_fetchArray($res))
					{		
						print("<option>$row[0]</option>");
					}
					print("</select></td></tr>");
					print("<tr><td>&nbsp;</td><td align=right><input type=\"submit\" value=\"Valider\"></td></tr>");
					print("</form>");
					print("</table>");

					print("</h2>");
					
				}
				// Aucun champ n'est vide, on peut enregistrer dans la table
				else
				{
					$res=mysql_query("SELECT * FROM etu_inscrit where num_etu='$num_etu'");
					$nb = mysql_num_rows($res);
					if ($nb==0)
					{
						$password=md5($password);
						mysql_query("INSERT INTO etudiant VALUES ('$num_etu','$nom','$prenom','$mail','$login','$password','')")
						or die('Erreur SQL ! <br>'.mysql_error());
						
						$combo=$_POST['combo'];
						$anne="2006 - 2007";
						$res=mysql_query("select `id-diplome` from diplome where intitule='$combo'");
						$id_diplome = DB_fetchArray($res);
						mysql_query("INSERT INTO inscrit VALUES ('$num_etu','$id_diplome[0]','$anne')")
						or die('Erreur SQL ! <br>'.mysql_error());
						
						
						
						$res=mysql_query("select `id-matiere` from matiere,etudiant,module,inscrit,diplome
											 where inscrit.`id-etudiant`=etudiant.`id-etudiant`
											 and inscrit.`id-diplome`=diplome.`id-diplome`
									 		 and diplome.`id-diplome`=module.`id-diplome`
										 	 and matiere.`id-module`=module.`id-module`
											 and etudiant.`id-etudiant`='$num_etu';");
						$chemin='etudiants/'.$num_etu.'/';
						mkdir ($chemin, 0770);
						while($row = DB_fetchArray($res))
						{
							
							$var=$chemin.$row[0].'/';
							mkdir ($var, 0770);
						}
						print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php\">") ;						
						echo 'Vous avez ete inscrit.';						
					}
					else
					{
						print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php\">") ;
						echo 'Inscription impossible.';
					}
				}
				?>

				<!------------------------------------------------------------>
				<!-- fin de la partie qui permet de gerer la partie reservé -->
				<!------------------------------------------------------------>
				</div>
			</th>
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
