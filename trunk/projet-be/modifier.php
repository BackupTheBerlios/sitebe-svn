<?php
	/*
	fichier qui permet d'acceder a l'espace reserve du site
	mise à jour 2006-2007 par : VAR Sovanramy et DANG Laurent
	*/
	session_start();
	error_reporting(E_ALL && ~E_NOTICE);
	include('includes/config.php');
	require_once('includes/lib-db.php');
	$titre = "Modifier";
	if ($_GET['b']=='pass')
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
			print("<table width=400 border='0' align='center'>");
			print("<tr><td colspan=2><h2>Modification de votre mot de passe</h2><br><br>");
			print("Veuillez saisir deux fois votre nouveau mot de passe<br><br></td></tr>");
			print("<form method=post action=espacereserve.php?p=connexion&w=".$_GET['w']."&a=modif&b=pass>");
			print("<tr><td align=left>Password : </td><td align=right><input type=password name=password size=16></td></tr>");
			print("<tr><td align=left>Password : </td><td align=right><input type=password name=password2 size=16></td></tr>");
			print("<tr><td>&nbsp;</td><td align=right><input type=submit value=Valider></td></tr>");
		}
		else
		{
			$password=md5($password);
			if ($_GET['w']=='etudiants')
			{
				$etu=$_SESSION['id-etu'];
				mysql_query("UPDATE etudiant SET mdp='$password' where `id-etudiant`='$etu'")
				or die('Erreur SQL ! <br>'.mysql_error());
			}
			elseif ($_GET['w']=='enseignants')
			{
				$ens=$_SESSION['id-enseignant'];
				mysql_query("UPDATE enseignant SET mdp='$password' where `id-enseignant`='$ens'")
				or die('Erreur SQL ! <br>'.mysql_error());
			}
			print("Votre mot de passe a ete modifie avec succes. Redirection...");
			print("<meta http-equiv=\"refresh\" content=\"3;url=espacereserve.php?p=connexion&w=etudiants\">\n") ;
		}
	}
	elseif ($_GET['b']=='login')
	{
		if(isset($_POST['login']))	$login=$_POST['login'];
		else	$login="";
		if(empty($login))
		{
			if (isset($_POST['login']))
			{
				print("Attention, aucun champ de doit rester vide !<br>");
			}
			print("<table width=400 border='0' align='center'>");
			print("<tr><td colspan='2'><h2>Modification de votre login</h2><br><br>");
			print("Veuillez saisir votre nouveau login<br><br><tr><td>");
			print("<form method=post action=espacereserve.php?p=connexion&w=".$_GET['w']."&a=modif&b=login>");
			print("<tr><td align=left>Login : </td><td align=right><input type=\"login\" name=\"login\" size=\"16\"></td></tr>");
			print("<tr><td>&nbsp;</td><td align=right><input type=\"submit\" value=\"Valider\"></td></tr>");
		}
		else
		{

			//$res=mysql_query("SELECT count(*) FROM etudiant WHERE `id-etudiant`=(SELECT `id-etudiant` FROM etudiant WHERE login='$login')");
			/*$res=mysql_query("SELECT count(*) FROM etudiant WHERE login='$login'");
			$nb=mysql_fetch_array($res);*/
			$res = mysql_query("SELECT * FROM etudiant WHERE login='$login'");
			$nb = mysql_num_rows($res);
			echo $nb;
			$res=mysql_query("SELECT * FROM enseignant WHERE login='$login'");
			$nb+=mysql_num_rows($res);
			echo $nb;
			if($nb!=0)
			{
				print("<br>Attention, login deja utilisé choisissez en un autre !<br><br>");
				print("<tr><td colspan='2'><h2>Modification de votre login</h2><br><br>");
				print("Veuillez saisir votre nouveau login<br><br><tr><td>");
				print("<form method=post action=espacereserve.php?p=connexion&w=".$_GET['w']."&a=modif&b=login>");
				print("<center><table width=400 border=0>");
				print("<tr><td align=left>Login : </td><td align=right><input type=\"login\" name=\"login\" size=\"16\"></td></tr>");
				print("<tr><td>&nbsp;</td><td align=right><input type=\"submit\" value=\"Valider\"></td></tr></table>");
			}
			else
			{
				if ($_GET['w']=='etudiants')
				{
					$etu=$_SESSION['id-etu'];
					mysql_query("UPDATE `etudiant` set login='$login' where `id-etudiant`='$etu'")
					or die('Erreur SQL ! <br>'.mysql_error());
				}
				elseif ($_GET['w']=='enseignants')
				{
					$ens=$_SESSION['id-enseignant'];
					mysql_query("UPDATE `enseignant` set login='$login' where `id-enseignant`='$ens'")
					or die('Erreur SQL ! <br>'.mysql_error());
				}
				print("Votre login a ete modifi&eacute; avec succes. Redirection...");
				print("<meta http-equiv=\"refresh\" content=\"3;url=espacereserve.php?p=connexion&w=etudiants\">\n") ;
			}
		}
	}
	print("</table>");
?>
