<?php
/*
** Fichier : header.inc
** Date de creation : 28/08/2005
** Auteurs : Avetisyan Gohar
** Version : 2.0
** Description : Fichier contenant le haut du site
*/


/*
Structure generale : une seule table 
 - [X] premiere ligne : haut proprement dit
 - [ ] deuxieme ligne : menu dans la base de donnees + liens divers
 - [ ] ligne suivante : corps du site
 - [ ] derniere ligne : bas du site
*/


if (isset($_GET['chemin'])&& isset($_GET['file']))
{
   header("Content-type: application/force-download");
   header("Content-Disposition: attachment; filename=".$file);
   readfile($_GET['chemin'].$_GET['file']);
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<title> &raquo; Bienvenue sur le site de l'IUP ISI ! </title>
<meta name="author" content="Conde Mickael K., Badaoui Kassem, Canaye Kurvin, Guenatri Kamil">
<meta name="copyright" content="Copyright 2004 Conde Mickael K.">
<meta name="keywords" content="IUP, ISI, Paul Sabatier">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="Styles/style.css">
<?php

if(file_exists("Styles/style_".$corePage.".css"))
{
	print("<link rel=\"stylesheet\" type=\"text/css\" href=\"Styles/style_".$corePage.".css\">\n") ;
}

?>
<script language="JavaScript" src="Scripts/scripts.js"></script>
<?php

if(file_exists("Scripts/scripts_".$corePage.".js"))
{
	print("<script language=\"JavaScript\" src=\"Scripts/scripts_".$corePage.".js\"></script>\n") ;
}

?>

</head>

<body>
<a name="top"></a>
<center>
<table id="siteContainer" cellpadding="0" cellspacing="0" width="800">
	<tr>
		<td id="header">
		</td>
	</tr>
	<tr>
		<td class="cellSeparator"></td>
	</tr>

<?php
/*
** EOF header.inc
*/
?>
