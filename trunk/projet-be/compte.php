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
<html xml:lang='fr'	xmlns="http://www.w3.org/1999/xhtml" lang="fr">
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
	<!-- DEBUT BLOCAGE 1ERE LETTRE EN MAJUSCULE-->
	<script type="text/javascript" language="JavaScript" title="G1SCRIPT">
		//Script Généré sur le Site http://www.G1SCRIPT.COM
		// © genered by tanguy@crollen.com
		<!-- Begin
		function changeCase(frmObj)
		{
			var index;
			var tmpStr;
			var tmpChar;
			var preString;
			var postString;
			var strlen;
			tmpStr = frmObj.value.toLowerCase();
			strLen = tmpStr.length;
			if (strLen > 0)
			{
				for (index = 0; index < strLen; index++)
				{
					if (index == 0)
					{
						tmpChar = tmpStr.substring(0,1).toUpperCase();
						postString = tmpStr.substring(1,strLen);
						tmpStr = tmpChar + postString;
					}
					else
					{
						tmpChar = tmpStr.substring(index, index+1);
						if (tmpChar == " " && index < (strLen-1))
						{
							tmpChar = tmpStr.substring(index+1, index+2).toUpperCase();
							preString = tmpStr.substring(0, index+1);
							postString = tmpStr.substring(index+2,strLen);
							tmpStr = preString + tmpChar + postString;
						}
					}
				}
			}
			frmObj.value = tmpStr;
		}
		//  End -->
	</script>
	<!-- FIN BLOCAGE 1ERE LETTRE EN MAJUSCULE-->

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
			<th valign="top">
				<div id="espacereserve">
				<!------------------------------------------------------>
				<!-- debut de la partie qui permet de creer un compte -->
				<!------------------------------------------------------>
<?php
	if(isset($_POST['nom']))	$nom=$_POST['nom'];
		else	$nom="";
		
	if(isset($_POST['prenom']))	$prenom=$_POST['prenom'];
		else	$prenom="";
		
	if(isset($_POST['mail']))	$mail=$_POST['mail'];
		else	$mail="";
		
	if(isset($_POST['num_etu']))	$num_etu=$_POST['num_etu'];
		else	$num_etu="";
		
	if(isset($_POST['login']))	$login=$_POST['login'];
		else	$login="";
		
	if(isset($_POST['password']))	$password=$_POST['password'];
		else	$password="";
		
	if(isset($_POST['password2']))	$password2=$_POST['password2'];
		else	$password2="";
	
	// Etudiant existe ?
	$res = mysql_query("SELECT * FROM `etudiant` WHERE `id-etudiant`=$num_etu AND `nom`='$nom' AND `prenom`='$prenom'");
	$nbcours = mysql_num_rows($res);
	// Si le login dispo ?
	$res = mysql_query("SELECT * FROM `etudiant` WHERE login='$login' AND login !=''");
	$nblogin = mysql_num_rows($res);
	$res = mysql_query("SELECT * FROM `enseignant` WHERE login='$login' AND login !=''");
	$nblogin += mysql_num_rows($res);
	$res = mysql_query("SELECT * FROM `secretaire` WHERE login='$login' AND login !=''");
	$nblogin += mysql_num_rows($res);
	
	// S'il y a un champ vide OU le login existe deja OU l'etudiant n'existe pas
	if((empty($nom) OR empty($prenom) OR empty($num_etu) OR empty($mail) OR empty($password) OR empty($login) OR empty($password2) OR $password!=$password2) OR $nbcours==0 OR $nblogin!=0)
	{
		// S'affiche seulement si reload de la page
		if (isset($_POST['num_etu']) OR isset($_POST['nom']) OR isset($_POST['prenom']) OR isset($_POST['mail']) OR isset($_POST['login']) OR isset($_POST['password']))
		{
			print("<table><tr><td align='left'>Attention :<br>");
			if ($_POST['num_etu']=='' OR $_POST['nom']=='' OR $_POST['prenom']=='' OR $_POST['mail']=='' OR $_POST['login']=='' OR $_POST['password']=='' OR $_POST['password2']=='')
			{
			print("- aucun champ ne doit rester vide !<br>");
			}
			elseif ($nbcours == 0)
			{
				print("- les param&egrave;tres que vous avez saisis ne pas valides, v&eacute;rifiez-les ou contactez l'administrateur !<br>");
			}
			if ($nblogin != 0)
			{
				print("- ce login est d&eacute;ja pris !<br>");
			}
			if($password!=$password2)
			{
				print("- les deux mots de passe sont diff&eacute;rents !<br>");
			}
			print("</td></tr></table>");
		}
		$numEtu = $_POST['num_etu'];
		$nomEtu = $_POST['nom'];
		$prenomEtu = $_POST['prenom'];
		$mailEtu = $_POST['mail'];
		$loginEtu = $_POST['login'];
		  	/*		
		echo "<center><table width='400' valign='top'>";
		//echo "<table valign='top'>";
		echo "<tr><td colspan=2><h2>Inscription</h2></td></tr>";
		echo "<form method='post' action='compte.php'>";
		*/
		echo "<h2>Cr&eacute;ation d'un compte</h2>";
		echo "<form action=\"compte.php\" method=\"post\">";
		echo "<center><table width=\"400\">\n<tr>\n";
		echo "<tr><td align='left'>N&deg;Etudiant : </td><td align='right'><input type='text' name='num_etu' value='$numEtu' size='8' maxlength='8'></td></tr><br>";
		echo "<tr><td align='left'>Nom : </td><td align='right'><input type='text' name='nom' value='$nomEtu' size='15' onChange='javascript:this.value=this.value.toUpperCase();'></td></tr><br><br>";
		echo "<tr><td align='left'>Pr&eacute;nom : </td><td align='right'><input type='text' name='prenom' value='$prenomEtu' size='15' onChange='javascript:changeCase(this.form.prenom);'></td></tr><br>";
		echo "<tr><td align='left'>Mail : </td><td align='right'><input type='text' name='mail' value='$mailEtu' size='15'></td></tr>";
		echo "<tr><td align='left'>Login : </td><td align='right'><input type='text' name='login' value='$loginEtu' size='15'></td></tr><tr><td>&nbsp;</td></tr>";
		echo "<tr><td align='left'>Mot de passe :</td><td align='right'><input type='password' name='password' size='16'></td></tr>";
		echo "<tr><td align='left'>Confirmer le mot de passe :</td><td align='right'><input type='password' name='password2' size='16'></td></tr><tr><td>&nbsp;</td></tr>";
		echo "<tr><td>&nbsp;</td><td align=right><input type='submit' value='Valider'></td></tr>";
		echo "</table>";
		echo "</form>";
		
	}
	// Aucun champ n'est vide, on peut enregistrer dans la table
	else
	{
		$password=md5($password);
		mysql_query("UPDATE etudiant SET email='$mail', login='$login', mdp='$password' WHERE `id-etudiant`='$num_etu'")
		or die('Erreur SQL ! <br>'.mysql_error());
		
		$combo=$_POST['combo'];
		//$anne="2006 - 2007";
		$res=mysql_query("select `id-diplome` from diplome where intitule='$combo'");
		$id_diplome = DB_fetchArray($res);
	
		echo "<table><tr><td>Votre inscription a bien &eacute;t&eacute; valid&eacute;e, vous pouvez maintenant vous connecter. Redirection...</td></tr></table>";
		print("<meta http-equiv=\"refresh\" content=\"3;url=espacereserve.php\">") ;

	}
	print("<center><table><tr><td><br><br><a href='espacereserve.php?'>retour</a></td></tr><tr><td>&nbsp;</td></tr></table></center>");
?>
				<!---------------------------------------------------->
				<!-- fin de la partie qui permet de creer un compte -->
				<!---------------------------------------------------->
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
