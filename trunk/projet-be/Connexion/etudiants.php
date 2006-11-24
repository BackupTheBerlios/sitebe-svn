<?php



/*

** Fichier : etudiant

** Date de creation : 15/08/2004

** Auteurs : Avetisyan Gohar

** Auteurs deuxieme version : Julien SIEGA, Emilien PERICO

** Version : 2.0

** Description : Consultation  des fichiers déposés

*/





	if (!isset($_GET['a']))

	{

		print("\t\t\t<center>[ <a href=\"espacereserve.php?p=connexion&w=etudiants&a=load\"><u>Consulter les fichiers &agrave; t&eacute;l&eacute;charger</u></a> ] - ") ;

	} // fin de if (!isset($_GET['a']))



	// une action est precisee

	else

	{

                 // telecharger des fichier

		if ($_GET['a'] == "load")

		{



        $requettediplome = "select * from diplome where `intitule` = '".$_SESSION['diplome']."'";

        $res= DB_Query($requettediplome);

        $liste = mysql_fetch_array($res);

                         $fileList = DB_Query('SELECT *

                                   FROM fichier

                                   WHERE `id-diplome` = "'.$liste['id-diplome'].'"

                                   ORDER BY `id-fichier`');



                         $fileCount = mysql_num_rows($fileList);

                         

                         print("\t\t\t<table  cellspacing=\"1\" cellpadding=\"0\>\n") ;



                         if ($fileCount == 0)

                         {

				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;

                                print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td width=\"600\" align=\"center\"> ");

                                print "Aucun fichier pr&eacute;sent pour ce diplome !" ;

                                print("\t\t\t\t</td></tr>\n") ;

			}



                         if ($fileCount > 0)

                         {

                                   for ($i=0; $i<$fileCount; $i++)

                                   {

                                       $fFileList = mysql_fetch_array($fileList);



                                       $ensDetails = DB_Query('SELECT nom, prenom

                                                           FROM enseignant

                                                           WHERE `id-enseignant` = "'.$fFileList['id-enseignant'].'"');

                                       $ensDetails = mysql_fetch_array($ensDetails);



                                       print("\t\t\t\t<tr>\n") ;

	                               print("<td align=\"left\" width=\"400\">");

                                       //print("<div class=\"blueZone\">");

                                       print("<h1><u>".$ensDetails['nom']." ".$ensDetails['prenom']."</h1></u><br>") ;

                                       print("<u>".$fFileList['titre']."</u><br><br>") ;

                                       $fFileList['commentaire'] = nl2br($fFileList['commentaire']);

                                       print($fFileList['commentaire']."<br>") ;

                                       $chaine = explode(" ",$liste['intitule']);

	                                     $finalchaine = $chaine[0]."".$chaine[1];

	                                     print("<a href=Data/Telechargement/".$finalchaine."/".$fFileList['URL'].">Telechargement</a>");

  

                                       //print("</div>");

                                       print("\t\t\t\t</tr>\n") ;





                                   }

                         }

                         //infoMessage(3,3,"Page en cours de construction......");

                         print("\t\t\t</table>\n");





                } //end of if($_GET['a'] == "load")

                // deconnexion

    if($_GET['a']== "tele")

    {

      if(isset($_POST['depot']))

      {

             

             $valeur=stripslashes($_POST['depot']);

             $valeur2=stripslashes($_POST['file']);

             $valeur3=stripslashes($valeur2);



			// teste de toutes les facons

			 copy($_POST['file'], $valeur."".$_POST['fic']);

	//		copy('http://localhost/iupisi3/Data/Telechargement/Licence 2/05.jpg','C:\05.jpg');

		//	move_uploaded_file('http://localhost/iupisi3/Data/Telechargement/Licence 2/05.jpg','C:\05.jpg');

			//if ($success) { $finalCV = $fileId."_fich.".$finalExtension ; }

		

      }

      else

      {

        print("\t\t\t<center><form name=\"Form\" action=\"espacereserve.php?p=connexion&w=etudiants&a=tele\" method=\"post\" enctype=\"multipart/form-data\">\n") ;

                 

        print("\t\t\t<table  cellspacing=\"1\" cellpadding=\"0\>\n") ;



        print("\t\t\t\t<tr>\n") ;

        print("\t\t\t\t\t<td align=\"left\"><input type=\"hidden\" name=\"fic\" value=\"".$_GET['fic']."\"><input type=\"hidden\" name=\"file\" value=\"".$_GET['chemin']."\"><b> repertoire de telechargement </b></td><td width=\"700\" align=\"left\" colspan=\"2\"><input class=\"defaultInput\" name=\"depot\" size=\"40\"></td>\n") ;

        print("\t\t\t\t</tr>\n") ;

        print "<tr><td><input class=\"defaultButton\" type=\"submit\" name=\"addButton\" value=\"D&eacute;poser\" ></td></tr>  ";      

        print("\t\t\t\t</table");

      }

    }

		if ($_GET['a'] == "logout")

		{

                         // rien de critique

                         session_destroy() ;

                         //$SESSION['ensConnecte'] = false;

                         //$SESSION['etuConnecte'] = false;

                         // redirection

                         print "Fermeture de session..." ;

                         print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php?p=connexion\">\n") ;

                } //end of if($_GET['a'] == "logout")





        }











/*

** EOF enseignants

*/



?>

