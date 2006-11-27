<?php
	/*
	fichier qui permet d'acceder a l'espace reserve du site
	auteur : Julien SIEGA , Emilien PERICO
	*/
	session_start();
	error_reporting(E_ALL && ~E_NOTICE);
	include('includes/config.php');
	include('attente.php');
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
					$i=$_GET['i'];
					if ($i==1)
					{
						if(isset($_POST['password']))      $password=$_POST['password'];
						else      $password="";
					
						if(isset($_POST['password2']))      $password2=$_POST['password2'];
						else      $password2="";
						
						if(empty($password2) OR $password!=$password2)
						{
  							if (isset($_POST['password']) OR isset($_POST['password2']))
  							{
  								print("Attention, aucun champ ne doit rester vide !<br>");
							}
  							if($password!=$password2)
  							{
  								print("<br>Attention, les deux mots de passe sont diff&eacute;rents !<br><br>");
  							}
							print("Modification de votre mot de passe<br><br>");
							print("<h2>Veuillez saisir deux fois votre nouveau mot de passe<br><br>");
							print("<form method=post action=modifier.php?i=1>");
							print("<table width=400 border=0>");
							print("<tr><td align=left>Password : </td><td align=right><input type=password name=password size=16></td></tr>");
							print("<tr><td align=left>Password : </td><td align=right><input type=password name=password2 size=16></td></tr>");
							print("<tr><td>&nbsp;</td><td align=right><input type=submit value=Valider></td></tr>");
						}
						else
						{
							$password=md5($password);
							$etu=$_SESSION['id-etu'];
							mysql_query("UPDATE etudiant set mdp='$password' where `id-etudiant`='$etu'")
							or die('Erreur SQL ! <br>'.mysql_error());
							print("Votre mot de passe a ete modifie avec succes.");
							print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php?p=connexion&w=etudiants\">\n") ;
						}					
					}
					else
					{
						if(isset($_POST['login']))      $login=$_POST['login'];
						else      $login="";
						if(empty($login))
						{
  							if (isset($_POST['login']))
  							{
  								print("Attention, aucun champ de doit reste vide !<br>");
							}
  							print("Modification de votre login<br><br>");
							print("<h2>Veuillez saisir votre nouveau login<br><br>");
							print("<form method=post action=modifier.php?i=0>");
							print("<center><table width=400 border=0>");
							print("<tr><td align=left>Login : </td><td align=right><input type=\"login\" name=\"login\" size=\"16\"></td></tr>");
							print("<tr><td>&nbsp;</td><td align=right><input type=\"submit\" value=\"Valider\"></td></tr>");
						}
						else
						{
							$res=mysql_query("SELECT count(*) from etudiant where `id-etudiant`=(SELECT `id-etudiant` FROM etudiant WHERE login='$login')");
							$nb=mysql_fetch_array($res);
  							if($nb[0]==1)
  							{
  								print("<br>Attention, login deja utilisé choisissez en un autre !<br><br>");
  								print("Modification de votre login<br><br>");
								print("<h2>Veuillez saisir votre nouveau login<br><br>");
								print("<form method=post action=modifier.php?i=0>");
								print("<center><table width=400 border=0>");
								print("<tr><td align=left>Login : </td><td align=right><input type=\"login\" name=\"login\" size=\"16\"></td></tr>");
								print("<tr><td>&nbsp;</td><td align=right><input type=\"submit\" value=\"Valider\"></td></tr>");
  							}
  							else
  							{
  								$etu=$_SESSION['id-etu'];
  								mysql_query("UPDATE etudiant set login='$login' where `id-etudiant`='$etu'")
								or die('Erreur SQL ! <br>'.mysql_error());
								print("Votre login a ete modifie avec succes.");
								print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php?p=connexion&w=etudiants\">\n") ;  							
  							}
  						}
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
