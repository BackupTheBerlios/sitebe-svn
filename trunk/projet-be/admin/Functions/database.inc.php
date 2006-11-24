<?php
/*** Fichier : database.inc** Date de creation : 11/10/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Contient la fonction de connexion à la base et la fonction de requete
*/
/*
** fonction 	: dbConnect
** entrees		: rien
** sorties		: rien
** description	: gere la connexion a la base de donnees et les cas d'erreur
*/
function dbConnect()
{
	global $db;
	$connectId = mysql_pconnect($db['host'],$db['user'],$db['pass']);
	$base = mysql_select_db($db['name'],$connectId);
	if (!$connectId || !$base)
	{
		die("probl&egrave;me d'acces &agrave; la base de donn&eacute;es dbConnect") ;
	}
	// peut etre revue par la suite pour diriger une page dediee a traiter les erreurs de connexion
	// a placer dans le repertoire Exceptions
}

/*
** fonction 	: dbQuery
** entrees	: requete : string
** sorties	: resultat : donnees sql
** description	: execute la requete et gere les cas d'erreur
*/
function dbQuery($requete)
{
	global $db;
	$res = mysql_db_query($db['name'],$requete);
	if (!$res)
	{
		print($requete);
		die("probl&egrave;me d'acces &agrave; la base de donn&eacute;es dbQuery ".$requete." \n".mysql_error()) ;
	}
	return $res;
}

/*
** fonction 	: dbClose
** entrees	: requete : string
** sorties	: rien
** description	: ferme la connexion a la base de donnees
*/
function dbClose()
{
	mysql_close() ;
}
/*
** EOF database.inc
*/
?>
