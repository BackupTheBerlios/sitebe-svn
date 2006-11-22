<?php
/*
** Fichier : help
** Date de creation : 22/11/2006
** Auteurs : PELOUAS Guillaume
** Version : 1.0
** Description : Fichiers d'aide
*/



// fichier pour les messages
require("Functions/messages.inc.php") ;

require_once("../includes/toolbar.php");

// mini header
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<title> IUP ISI [Administration - Aide] </title>
<meta name="author" content="Conde Mickael K., Badaoui Kassem, Canaye Kurvin, Guenatri Kamil">
<meta name="copyright" content="Copyright 2004 IUP ISI">
<link rel="stylesheet" type="text/css" href="Styles/style_help.css">
<script language="javascript" src="Scripts/scripts_help.js"></script>
</head>

<body>
<center>
<table id="mainContainer" cellpadding="0" cellspacing="10" width="800">
	<tr>
		<td width="800" height="80">
			<img src="../img/aide_toplogo.png" width="800" height="80">
		</td>
	</tr>
	<tr>
		<td align="left" id="centralZone">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" width="100">Navigation</td>
					<td align="center" width="100">&lt; <a href="javascript:window.close()">Fermer</a> &gt;</td>
				</tr>
			</table>
			<?php
				require("Help/".$_GET['w'].".php");
			?>
		</td>
	</tr>
	<tr>
		<td align=\"center\">
			&copy; 2004 - 2006 IUP ISI General design by C. Mickael K.
		</td>
	</tr>
</table>
</body>
</html>

<?php
/*
** EOF help
*/
?>
