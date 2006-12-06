<?php

/*

** Fichier : connexion

** Date de creation : 15/07/2005

** Auteurs : Avetisyan Gohar

** Version : 1.0

** Description : Fichier central responsable de la navigation des utilisateurs du site

*/





/*

** IMPORTANT :	Pour acceder aux differentes parties de la navigation on

**		utilisera la variable $_GET['w'] (w pour what)

**		les parties a inclure se trouvent dans le repertoire Connexion de la racine

*/





// fichier pour les messages

//require("Functions/messages.inc.php") ;



// mini header

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>



<head>

<title> IUP ISI [Administration - Menus] </title>

<meta name="author" content="Conde Mickael K., Badaoui Kassem, Canaye Kurvin, Guenatri Kamil">

<meta name="copyright" content="Copyright 2004 IUP ISI">

<link rel="stylesheet" type="text/css" href="Styles/style_connect.css">

<?php



// on inclut les fonctions javascript specifiques si elles existent

if (isset($_GET['w']))

{

	if (file_exists("Scripts/scripts_admin_".$_GET['w'].".js"))

	{

		print("<script language=\"JavaScript\" src=\"Scripts/scripts_admin_{$_GET['w']}.js\"></script>\n") ;

	}



	$user = $_GET['w'];

}



//champs de connexion

print("<script language=\"JavaScript\" src=\"Scripts/scripts_admin.js\"></script>");

print("</head>");









// on verifie toujours que cette page a ete appelee a partir de index

if (is_numeric(strpos($_SERVER['PHP_SELF'], "index.php")))

{



	// on est connecte a la base de donnees

	// mini haut

	print("\t\t\t<table cellpadding=\"0\" cellspacing=\"0\" width=\"800\">\n") ;

	print("\t\t\t\t<tr>\n") ;

	print("\t\t\t\t\t<td id=\"centerTitle\">Navigation &raquo;&nbsp; Espace R&eacute;serv&eacute; ");

        // si l'utilisateur est connecte on affiche l'environnement dans lequel il est

        if (isset($user))

        {

           print("&raquo;&nbsp; <b>Espace ".$user." - ".$_SESSION['diplome']);

        }

        print("</b></td>\n") ;

	print("\t\t\t\t</tr>\n") ;

	print("\t\t\t\t<tr>\n") ;

	print("\t\t\t\t\t<td align=\"left\">\n") ;

	

	//pour modifier le mot de passe, on accede a la BD

/*	if (isset($_SESSION['usrConnecte'])&&($_SESSION['ensNavigation']))

	        {

	 	        print("<br>[ <a class=\"admin\" href=\"javascript:openAdmin('w=enseignants&a=mod')\">modifier mot de passe</a> ]<br>\n") ;

	        }

                elseif(isset($_SESSION['usrConnecte'])&& isset($_SESSION['etuNavigation']))

	        {

                        print("<br>[ <a class=\"admin\" href=\"javascript:openAdmin('w=etudiants&a=mod')\">modifier mot de passe</a> ]<br>\n") ;

                }



 */





?>



<body>

<center>



<?php



// L'utilisateur n'est pas authentifie

if (! isset($_SESSION['etuConnecte']) && !isset($_SESSION['ensConnecte']))

{

	// si l'utilisateur n'a pas essaye de se connecter

	if (!isset($_POST['usrAuth']))

	{

		$dipsList = dbQuery('SELECT *

				FROM diplome

				ORDER BY intitule') ;

				

		$countDips = mysql_num_rows($dipsList) ;



                // alors affichage du formulaire

		centeredInfoMessage(2, 2, "L'utilisation de cette page n&eacute;cessite une authentification") ;

		print("\t\t\t<form action=\"index.php?p=connexion\" method=\"post\">\n") ;

		print("\t\t\t<center><table width=\"400\">\n\t\t\t\t<tr>\n") ;

		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b> Login </b></td>") ;

		print("<td align=\"right\" width=\"200\"><input name=\"usrLogin\" class=\"defaultInput\" maxlength=\"15\" size=\"15\"></td>\n\t\t\t\t</tr>\n") ;

		print("\t\t\t\t<tr>\n") ;

		print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b> Mot de Passe </b></td>") ;

		print("<td align=\"right\" width=\"200\"><input name=\"usrPass\" class=\"defaultInput\" maxlength=\"15\" type=\"password\" size=\"15\"></td>\n\t\t\t\t</tr>\n") ;

		print("\t\t\t\t<tr>\n") ;

		

		if ($countDips > 0)

			{

				print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b> Dipl&ocirc;me *</b></td><td width=\"200\" align=\"right\"><select class=\"defaultInput\" name=\"diplome\">") ;

				for($i = 0 ; $i < $countDips ; $i++)

				{

					$dipsDetails = mysql_fetch_array($dipsList) ;

					print("<option> {$dipsDetails['intitule']} </option>") ;

				}

				print("</select></td>\n\t\t\t\t</tr>\n") ;

                        }

                print("\t\t\t\t<tr>\n") ;

		print("\t\t\t\t\t<td colspan=\"2\" align=\"left\" width=\"400\"><input type=\"submit\" class=\"defaultButton\" name=\"usrAuth\" class=\"defaultButton\" value=\"Connexion\"></td>\n\t\t\t\t</tr>\n") ;

		print("\t\t\t</table>\n\t\t\t</center>\n") ;

	}

	

	// sinon l'utilisateur a essaye de se connecter on verifie les donnees

	else

	{

		require("Includes/settings.inc.php") ;

		dbConnect() ;

		

		$usrlogin = trim($_POST['usrLogin']) ;

		$usrlogin = addslashes($usrlogin) ;

		// on trim pas le pass au cas ou il y aurait des espaces

		$usrpass = addslashes($_POST['usrPass']) ;

		//$diplome = trim($_POST['diplome']) ;

		//on ne trim pas le diplome car il ya des espaces

                $diplome = addslashes($_POST['diplome']);





		$connect_result = dbQuery('SELECT `id-enseignant`, nom, prenom

                         FROM enseignant

                         WHERE login ="'.$usrlogin.'" AND mdp = "'.$usrpass.'"');



                $ensDetails = mysql_fetch_array($connect_result);

                $connect_result = mysql_num_rows($connect_result) ;





		//un ou pusieurs resultats trouves pour le cas d'enseignant

		if ($connect_result != 0)

		{

                         //plusieurs resultats pour les meme pârametres de connexion => erreur

                         if ($connect_result != 1)

                         {

                                centeredErrorMessage(2, 2, "Erreurs de base de donn&eacute;es! Plusieurs utilisateurs possibles!") ;

                         }

                   

                         //un resultat retrouve pour le cas d'enseignant

                         else

                         {

                                $_SESSION['ensConnecte'] = true ;

                                $_SESSION['nom'] = $ensDetails['nom'];

                                $_SESSION['prenom'] = $ensDetails['prenom'];

                                $_SESSION['id-enseignant'] = $ensDetails['id-enseignant'];

                                $_SESSION['diplome'] = $diplome;

                                print("<meta http-equiv=\"refresh\" content=\"0;url=index.php?p=connexion&w=enseignants\">\n") ;

                         }

                 }



                 //aucun resultat pour le cas de l'enseignant

                 //on essaie dans les etudiants

                 else

                 {



                         //on retrouve l'etudiant

                         //$connect_result = dbQuery('SELECT `id-etudiant`

                         $etuDetails = dbQuery('SELECT `id-etudiant`, nom, prenom

                         FROM etudiant

                         WHERE login = "'.$usrlogin.'" AND mdp = "'.$usrpass.'"');

                         

                         $etuDetails = mysql_fetch_array($etuDetails) ;



                         $dipDetails = dbQuery('SELECT `id-diplome`

                         FROM diplome

                         WHERE intitule ="'.$diplome.'"');



                         $dipDetails = mysql_fetch_array($dipDetails);





                         //il faut que l'etudiant corresponde au dipolome choisi

                         $connect_result = dbQuery('SELECT *

                                         FROM inscrit

                                         WHERE `id-etudiant` ="'.$etuDetails['id-etudiant'].'" AND `id-diplome` ="'.$dipDetails['id-diplome'].'"');



                         $connect_result = mysql_num_rows($connect_result) ;



                         //un ou plusieurs resultats trouves pour le cas d'etudiant

		         if ($connect_result != 0)

		         {

                                //plusieurs resultats trouves => erreur

                                if ($connect_result != 1)

                                {

                                centeredErrorMessage(2, 2, "Erreurs de base de donn&eacute;es! Plusieurs utilisateurs possibles!") ;

                                }



                                //un resultat retrouve pour le cas d'etudiant

                                else

                                {

                                        //succes => redirection vers la meme page mais on definit la variable de session

                                        if ($connect_result == 1)

		                        {

                                               $_SESSION['etuConnecte'] = true ;

                                               $_SESSION['nom'] = $etuDetails['nom'];

                                               $_SESSION['prenom'] = $etuDetails['prenom'];

                                               $_SESSION['diplome'] = $diplome;

			                       print("<meta http-equiv=\"refresh\" content=\"0;url=index.php?p=connexion&w=etudiants\">\n") ;

                                        }

                                }

                         }

                         //aucun resultat ni pour le cas d'etudiant ni pour le cas d'enseignant

		         else

		         {

		                       	centeredErrorMessage(2, 2, "Param&egrave;tres de connexion incorrects, r&eacute;essayez") ;

		                       	print("\t\t\t<form action=\"index.php?p=connexion\" method=\"post\">\n") ;

		                       	print("\t\t\t<center><table width=\"400\">\n\t\t\t\t<tr>\n") ;

			                print("\t\t\t\t\t<td align=\"left\" width=\"200\"> Login </td>") ;

			                print("<td align=\"right\" width=\"200\"><input name=\"usrLogin\" class=\"defaultInput\" maxlength=\"15\" size=\"15\"></td>\n\t\t\t\t</tr>\n") ;

			                print("\t\t\t\t<tr>\n") ;

			                print("\t\t\t\t\t<td align=\"left\" width=\"200\"> Mot de passe </td>") ;

			                print("<td align=\"right\" width=\"200\"><input name=\"usrPass\" class=\"defaultInput\" maxlength=\"15\" type=\"password\" size=\"15\"></td>\n\t\t\t\t</tr>\n") ;

			                print("\t\t\t\t<tr>\n") ;

			                

			                $dipsList = dbQuery('SELECT *

				                  FROM diplome

				                  ORDER BY intitule') ;

				

                                        $countDips = mysql_num_rows($dipsList) ;



			                if ($countDips > 0)

			                {

				                       print("\t\t\t\t<tr>\n") ;

				                       print("\t\t\t\t\t<td align=\"left\" width=\"200\"><b> Dipl&ocirc;me *</b></td><td width=\"200\" align=\"right\"><select class=\"defaultInput\" name=\"diplome\">") ;

				                       for($i = 0 ; $i < $countDips ; $i++)

				                       {

					                      $dipsDetails = mysql_fetch_array($dipsList) ;

					                      print("<option> {$dipsDetails['intitule']} </option>") ;

				                       }

				                       print("</select></td>\n\t\t\t\t</tr>\n") ;

                                        }

                                        print("\t\t\t\t<tr>\n") ;

		                        print("\t\t\t\t\t<td colspan=\"2\" align=\"left\" width=\"400\"><input type=\"submit\" class=\"defaultInput\" name=\"usrAuth\" class=\"defaultButton\" value=\"Connexion\"></td>\n\t\t\t\t</tr>\n") ;

		                        print("\t\t\t</table>\n\t\t\t</center>\n") ;

                          }







             }



	}

	

} // if ! isset session



// l'utilisateur est authentifie avec succes

else

{



    print("\t\t\t<table width=\"800\" cellpadding=\"0\" cellspacing=\"3\">\n") ;

    print("\t\t\t\t<tr>\n") ;

    print("\t\t\t\t\t<td width=\"400\" align=\"left\"><br><br><div id=\"centerTitle\">Bienvenue ".$_SESSION['nom']." ".$_SESSION['prenom']." !!!!</div><br><br></td>");

    print("\t\t\t\t\t<td width=\"400\" align=\"right\"><br><br>&lt; <a href=\"index.php?p=connexion&w=".$_GET['w']."&a=logout\">D&eacute;connexion</a> &gt;</td>\n");

    print("\t\t\t\t</tr>\n") ;



    if (!isset($_GET['a']))

    {



            //un enseugnant est connecte

            if(isset($_SESSION['ensConnecte']) && $_SESSION['ensConnecte'])

            {



                    print("\t\t\t<table  cellspacing=\"1\" cellpadding=\"0\">\n") ;



	            print("\t\t\t\t<tr>\n") ;

	            print("<td align=\"center\" width=\"400\"><a href=\"index.php?p=connexion&w=enseignants&a=dep\"><u>D&eacute;poser des fichiers</u></a></td>") ;

                    print("\t\t\t\t</tr>\n") ;

        

                    print("\t\t\t\t<tr>\n") ;

	            print("<td align=\"center\" width=\"400\"><a href=\"index.php?p=connexion&w=enseignants&a=undep\"><u>Supprimer des fichiers</u></a></td>") ;

                    print("\t\t\t\t</tr>\n") ;



                    print("\t\t\t</table>\n");

            }

    

    

            //un etudiant est connecte

            elseif(isset($_SESSION['etuConnecte']) && $_SESSION['etuConnecte'])

            {

                    print("\t\t\t<table  cellspacing=\"1\" cellpadding=\"0\">\n") ;



	            print("\t\t\t\t<tr>\n") ;

	            print("<td align=\"left\" width=\"400\"><a href=\"index.php?p=connexion&w=etudiants&a=load\"><u>Consulter les fichiers &agrave; t&eacute;l&eacute;charger</u></a></td>") ;

                    print("\t\t\t\t</tr>\n") ;



                    print("\t\t\t</table>\n");

            }





    }

    else

    {

             // on inclut si c est bon

		if (file_exists("Connexion/".$_GET['w'].".php"))

		{		

			// quelques fichiers indispensables

			require("Includes/settings.inc.php") ;

			//require("Functions/database.inc.php") ;

			require("Connexion/".$_GET['w'].".php") ;

			print("\t\t\t<br><br><center>") ;

		}

		// sinon message d'erreur		

		else

		{

			centeredErrorMessage(2, 2, "Page introuvable ".$_GET['w']) ;

			//print("\t\t\t<br><br><center>[ <a href=\"admin.php\">menu principal</a> ]</center>\n") ;

		}

		





    }





/*	// petit menu de navigation en haut

	print("\t\t\t<table cellpadding=\"0\" cellspacing=\"3\">\n") ;

	print("\t\t\t\t<tr>\n") ;

	print("\t\t\t\t\t<td width=\"100\" align=\"left\">Session</td>\n") ;

	print("\t\t\t\t\t<td width=\"100\" align=\"left\" colspan=\"2\">&lt; <a href=\"index.php?p=connexion&w=logout\">D&eacute;connexion</a> &gt;</td>\n") ;

	print("\t\t\t\t</tr>\n") ;

	

	

	print("\t\t\t\t<tr>\n") ;

	print("\t\t\t\t<td width=\"100\" align=\"left\">Navigation</td>\n") ;

*/

	/*if (isset($_SESSION['usrNavigation']) && ($_SESSION['usrNavigation'] == true))

	{

		print("\t\t\t\t\t<td width=\"100\" align=\"left\">&lt; <a href=\"index.php?p=connexion&id=6&w=changenavigation\">Normale</a> &gt;</td>\n") ;

		print("\t\t\t\t\t<td align=\"left\">Vous utiliserez le site principal comme un utilisateur normal</td>\n") ;

	}

	

	else

	{

		print("\t\t\t\t\t<td width=\"100\" align=\"left\">&lt; <a href=\"admin.php?w=changenavigation\">Admin</a> &gt;</td>\n") ;

		print("\t\t\t\t\t<td align=\"left\">Vous pourrez administrer certaines parties &agrave; partir du site principal</td>\n") ;

	}

	

	print("\t\t\t\t</tr>\n") ;

	print("\t\t\t</table>\n") ; */

}





	// mini bas

	print("\t\t\t\t\t</td>\n") ;

	print("\t\t\t\t</tr>\n") ;

	print("\t\t\t</table>\n") ;

	

}









else

{

	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;

}







/*

** EOF enseignants

*/

?>

