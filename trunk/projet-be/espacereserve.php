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

<html xml:lang="fr"
      xmlns="http://www.w3.org/1999/xhtml"
      lang="fr">
<head>
    <meta http-equiv="Content-Type"
          content="text/html; charset=us-ascii" />

    <title>IUP ISI - <?=$titre?></title>
    <meta http-equiv="Content-Script-Type"
          content="text/javascript" />
    <meta http-equiv="Content-Style-Type"
          content="text/css" />
    <meta name="revisit-after"
          content="15 days" />
    <meta name="robots"
          content="index,follow" />
    <link rel="shortcut icon"
          type="images/x-icon"
          href="favicon.ico" />
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
   
    <meta name="DC.Publisher"
          content="IUP ISI" />
    <link rel="stylesheet"
          href="styles/isi.css"
          type="text/css" />
</head>

<body>   
    <div id="page">
        <div id="bandeau">
                <!--======================= Début logo ==================-->
                <a title="Retour en page d'accueil"
               href="index.php"><img style="BORDER-RIGHT: 0px solid; BORDER-TOP: 0px solid; BORDER-LEFT: 0px solid; BORDER-BOTTOM: 0px solid;"
                 alt="IUP ISI"
                 src="img/logo-trans.png" />
                 </a>
        </div><!-- Début Texte -->
        
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
                    <!--==========début pied de page======--><a href="http://www.ups-tlse.fr/"
                         title="UPS"><img 
                         src="img/logoups.gif"
                         alt="UPS" /></a>
                </div>
            </td>
            <td valign="top">
                <div id="espacereserve">
                <!-------------------------------------------------->
                <!-- partie qui permet de gerer la partie reserv� -->
                <!-------------------------------------------------->
                
                <?php
                // L'utilisateur n'est pas authentifie

if (! isset($_SESSION['etuConnecte']) && !isset($_SESSION['ensConnecte']))
{
	// si l'utilisateur n'a pas essaye de se connecter
	if (!isset($_POST['usrAuth']))
	{
		$dipsList = DB_query('SELECT *
				FROM diplome
				ORDER BY intitule') ;
				
		$countDips = mysql_num_rows($dipsList) ;

                // alors affichage du formulaire
		print "<H2>L'utilisation de cette page n&eacute;cessite une authentification</h2>" ;
		print("\t\t\t<form action=\"espacereserve.php\" method=\"post\">\n") ;
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
                print("\t\t\t\t<tr>\n\n\n") ;
                
		print("\t\t\t\t\t<td colspan=\"2\" align=\"center\" width=\"400\"><input type=\"submit\" class=\"defaultButton\" name=\"usrAuth\" class=\"defaultButton\" value=\"Connexion\"></td>\n\t\t\t\t</tr>\n") ;
		print("\t\t\t</table>\n\t\t\t</center>\n") ;
	}
	
	// sinon l'utilisateur a essaye de se connecter on verifie les donnees
	else
	{
		
		$usrlogin = trim($_POST['usrLogin']) ;
		$usrlogin = addslashes($usrlogin) ;
		// on trim pas le pass au cas ou il y aurait des espaces
		$usrpass = addslashes($_POST['usrPass']) ;
		//$diplome = trim($_POST['diplome']) ;
		//on ne trim pas le diplome car il ya des espaces
    $diplome = addslashes($_POST['diplome']);


		$connect_result = DB_query('SELECT `id-enseignant`, nom, prenom
                         FROM enseignant
                         WHERE login ="'.$usrlogin.'" AND mdp = "'.$usrpass.'"');

                $ensDetails = mysql_fetch_array($connect_result);
                $connect_result = mysql_num_rows($connect_result) ;


		//un ou pusieurs resultats trouves pour le cas d'enseignant
		if ($connect_result != 0)
		{
                         //plusieurs resultats pour les meme p�rametres de connexion => erreur
                         if ($connect_result != 1)
                         {
                               print "Erreurs de base de donn�es! Plusieurs utilisateurs possibles!" ;
                         }
                   
                         //un resultat retrouve pour le cas d'enseignant
                         else
                         {
                                
                                $_SESSION['ensConnecte'] = true ;
                                $_SESSION['nom'] = $ensDetails['nom'];
                                $_SESSION['prenom'] = $ensDetails['prenom'];
                                $_SESSION['id-enseignant'] = $ensDetails['id-enseignant'];
                                $_SESSION['diplome'] = $diplome;
                                print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php?p=connexion&w=enseignants\">\n") ;
                         }
                 }

                 //aucun resultat pour le cas de l'enseignant
                 //on essaie dans les etudiants
                 else
                 {

                         //on retrouve l'etudiant
                         //$connect_result = dbQuery('SELECT `id-etudiant`
                         $etuDetails = DB_query('SELECT `id-etudiant`, nom, prenom
                         FROM etudiant
                         WHERE login = "'.$usrlogin.'" AND mdp = "'.$usrpass.'"');
                         
                         $etuDetails = mysql_fetch_array($etuDetails) ;

                         $dipDetails = DB_query('SELECT `id-diplome`
                         FROM diplome
                         WHERE intitule ="'.$diplome.'"');

                         $dipDetails = mysql_fetch_array($dipDetails);


                         //il faut que l'etudiant corresponde au dipolome choisi
                         $connect_result = DB_query('SELECT *
                                         FROM inscrit
                                         WHERE `id-etudiant` ="'.$etuDetails['id-etudiant'].'" AND `id-diplome` ="'.$dipDetails['id-diplome'].'"');

                         $connect_result = mysql_num_rows($connect_result) ;

                         //un ou plusieurs resultats trouves pour le cas d'etudiant
		         if ($connect_result != 0)
		         {
                                //plusieurs resultats trouves => erreur
                                if ($connect_result != 1)
                                {
                                print "Erreurs de base de donn�es! Plusieurs utilisateurs possibles!" ;
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
			                       print("<meta http-equiv=\"refresh\" content=\"0;url=espacereserve.php?p=connexion&w=etudiants\">\n") ;
                                        }
                                }
                         }
                         //aucun resultat ni pour le cas d'etudiant ni pour le cas d'enseignant
		         else
             {
                     	print "<H2>Param�tres de connexion incorrects, r�essayez</H2>" ;
                     	print("\t\t\t<form action=\"espacereserve.php?p=connexion\" method=\"post\">\n") ;
                     	print("\t\t\t<center><table width=\"400\">\n\t\t\t\t<tr>\n") ;
			                print("\t\t\t\t\t<td align=\"left\" width=\"200\"> Login </td>") ;
			                print("<td align=\"right\" width=\"200\"><input name=\"usrLogin\" class=\"defaultInput\" maxlength=\"15\" size=\"15\"></td>\n\t\t\t\t</tr>\n") ;
			                print("\t\t\t\t<tr>\n") ;
			                print("\t\t\t\t\t<td align=\"left\" width=\"200\"> Mot de passe </td>") ;
			                print("<td align=\"right\" width=\"200\"><input name=\"usrPass\" class=\"defaultInput\" maxlength=\"15\" type=\"password\" size=\"15\"></td>\n\t\t\t\t</tr>\n") ;
			                print("\t\t\t\t<tr>\n") ;
			                
			                $dipsList = DB_Query('SELECT *
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
		                        print("\t\t\t\t\t<td colspan=\"2\" align=\"center\" width=\"400\"><input type=\"submit\" class=\"defaultInput\" name=\"usrAuth\" class=\"defaultButton\" value=\"Connexion\"></td>\n\t\t\t\t</tr>\n") ;
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
    print("\t\t\t\t\t<td width=\"400\" align=\"right\"><br><br>&lt; <a href=\"espacereserve.php?p=connexion&w=".$_GET['w']."&a=logout\">Deconnexion</a> &gt;</td>\n");
    print("\t\t\t\t</tr>\n") ;

    if (!isset($_GET['a']))
    {

            //un enseugnant est connecte
            if(isset($_SESSION['ensConnecte']) && $_SESSION['ensConnecte'])
            {

                    print("\t\t\t<table  cellspacing=\"1\" cellpadding=\"0\">\n") ;

	            print("\t\t\t\t<tr>\n") ;
	            print("<td align=\"center\" width=\"400\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=dep\"><u>D�poser des fichiers</u></a></td>") ;
                    print("\t\t\t\t</tr>\n") ;
                       print("\t\t\t\t<tr>\n") ;
	            print("<td align=\"center\" width=\"400\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=undep\"><u>Supprimer des fichiers</u></a></td>") ;
              print("\t\t\t\t</tr>\n") ;
              print("\t\t\t\t<tr>\n") ;
	            print("<td align=\"center\" width=\"800\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=excel\"><u>excel</u></a></td>") ;
              print("\t\t\t\t</tr>\n") ;
              print("\t\t\t\t<tr>\n") ;
	            print("<td align=\"center\" width=\"800\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=visualisation\"><u>visualisation</u></a></td>") ;
              print("\t\t\t\t</tr>\n") ;
              
              print("\t\t\t\t<tr>\n") ;
	            print("<td align=\"center\" width=\"800\"><a href=\"espacereserve.php?p=connexion&w=enseignants&a=note\"><u>saisie des notes</u></a></td>") ;
              print("\t\t\t\t</tr>\n") ;

              print("\t\t\t</table>\n");
            }
    
    
            //un etudiant est connecte
            elseif(isset($_SESSION['etuConnecte']) && $_SESSION['etuConnecte'])
            {
                    print("\t\t\t<table  cellspacing=\"1\" cellpadding=\"0\">\n") ;

	            print("\t\t\t\t<tr>\n") ;
	            print("<td align=\"left\" width=\"400\"><a href=\"espacereserve.php?p=connexion&w=etudiants&a=load\"><u>Consulter les fichiers � t�l�charger</u></a></td>") ;
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
			        // require("Includes/settings.inc.php") ;
		        	//require("Functions/database.inc.php") ;
	         		require("Connexion/".$_GET['w'].".php") ;
	         		print("\t\t\t<br><br><center>") ;
		}
		// sinon message d'erreur		
		else
		{
			print "Page introuvable ".$_GET['w'] ;
			//print("\t\t\t<br><br><center>[ <a href=\"admin.php\">menu principal</a> ]</center>\n") ;
		}
		


    }
}
                  
                  ?>
                <!------------------------------------------------------------>
                <!-- fin de la partie qui permet de gerer la partie reserv� -->
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
