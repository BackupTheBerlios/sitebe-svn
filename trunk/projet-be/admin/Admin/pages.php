<?php

/*

** Fichier : pages

** Date de creation : 30/11/2004

** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil

** Version : 1.0

** Description : Fichier inclu charge de la gestion des pages cote administration

**	ajout, suppression, modification

*/



// !!! on s'assure toujours que l'utilisateur est bien loggue...

if (is_numeric(strpos($_SERVER['PHP_SELF'], "admin.php")))

{	    

	// aucune action precisee : menu principal

	if (!isset($_GET['a']))

	{

		centeredInfoMessage(3, 3, "Administration des pages : accueil") ;

		print("\t\t\t<center>[ <a href=\"admin.php?w=pages&a=add\">ajouter page</a> ] - ") ;

		print("[ <a href=\"admin.php?w=pages&a=mod\"> modifier page</a> ] - ") ;

		print("[ <a href=\"admin.php?w=pages&a=del\"> supprimer page</a> ]<br>\n") ;

	} // fin de if (!isset($_GET['a']))

	

	// une action est precisee

	else

	{

	

		// ajout d'un element

		if ($_GET['a'] == "add")

		{               

            centeredInfoMessage(3, 3, "Administration des pages : ajout") ;

			print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=pages\" method=\"post\">\n") ;

            print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;

            print("\t\t\t\t<tr>\n") ;

            print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b> Titre de la page *</b></td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"pageTitre\" size=\"40\" value=\"{$titre}\"></td>\n") ;

            print("\t\t\t\t</tr>\n") ;			

                            

            echo '<select name="id">';

            $res = dbQuery("SELECT M.`id-module`, M.intitule, D.intitule, M.no_semestre

                    FROM module M, diplome D 

                    WHERE D.`id-diplome` = M.`id-diplome`

                    ORDER BY D.intitule, M.no_semestre");

            

            echo '<option value="0">Aucune page associ&eacute;e</option>';

            

            while($row = mysql_fetch_array($res))

            {

                echo '<option value="'.$row[0].'">'.$row[2].' - SEM'.$row[3].' - '.$row[1].'</option>\n';

            }

            echo '</select>';

            

            print("\t\t\t\t<tr>\n") ;

            print("\t\t\t\t\t<td align=\"left\" colspan=\"2\"><div id=\"toolbar1\"></div><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"pageContent\" id=\"pageContent\">{$content}</textarea><br /></td>\n") ;

            print("\t\t\t\t</tr>\n") ;

            print("\t\t\t\t<tr><td colspan=\"2\" align=\"right\"><div id=\"sizectl1\"></div></td></tr>\n");

            

            print("\t\t\t\t<tr>\n") ;

            print("\t\t\t\t\t<td colspan=\"2\"><hr /></td>\n") ;

            print("\t\t\t\t</tr>\n") ;

            

            print("\t\t\t\t<tr>\n") ;

            print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b>Style de notes</b> (cf style css)</td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"pageSNote\" size=\"40\" value=\"{$snote}\"></td>\n") ;

            print("\t\t\t\t</tr>\n") ;

            

            print("\t\t\t\t<tr>\n") ;

            print("\t\t\t\t<td align=\"left\"><b>Remplissez ce cadre si vous souhaitez avoir un cadre de r&eacute;sum&eacute;.</b>\n") ;

            print("\t\t\t\t</tr>\n") ;

            print("\t\t\t\t<tr>\n") ;

            print("\t\t\t\t\t<td align=\"left\" colspan=\"2\"><div id=\"toolbar2\"></div><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"pageNote\" id=\"pageNote\">{$note}</textarea></td>\n") ;

            print("\t\t\t\t</tr>\n") ;

            print("\t\t\t\t<tr><td colspan=\"2\" align=\"right\"><div id=\"sizectl2\"></div></td></tr>\n");

            

            print("\t\t\t\t<tr>\n") ;

            print("\t\t\t\t\t<td colspan=\"2\"><hr /></td>\n") ;

            print("\t\t\t\t</tr>\n") ;

            

            print("\t\t\t\t<tr>\n") ;

            print("\t\t\t\t<td align=\"left\"><b>Remplissez ce cadre si vous souhaitez avoir un cadre de r&eacute;sumer.</b>\n") ;

            print("\t\t\t\t</tr>\n") ;

            print("\t\t\t\t<tr>\n") ;

            print("\t\t\t\t\t<td align=\"left\" colspan=\"2\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"pageMenu\">{$menu}</textarea></td>\n") ;

            print("\t\t\t\t</tr>\n") ;

                

            print("\t\t\t\t<tr>\n") ;

			print("\t\t\t\t\t<td width=\"600\" align=\"left\" colspan=\"3\"><br><input type=\"hidden\" name=\"pageAdd\" value=\"true\"><input class=\"defaultButton\" type=\"button\" name=\"addButton\" value=\"Ajouter\" onClick=\"checkPageAdd('defaultForm')\"> <input class=\"defaultButton\" type=\"button\" name=\"visuButton\" value=\"Visualiser\" onClick=\"checkPageAdd('defaultForm')\"></td>\n") ;

			print("\t\t\t\t</tr>\n") ;

			print("\t\t\t</table>\n") ;

			print("\t\t\t</form></center>\n") ;	

            

            print("<script language=\"javascript\" charset=\"utf-8\">");

            toolbar_JSdefines('toolbar');

            print("initSizeCtl('sizectl1','pageContent');");

            print("initSizeCtl('sizectl2','pageNote');");

            

            print("addEvent(window,'load',function(){ initToolbar('toolbar1','pageContent',toolbar); });");

            print("addEvent(window,'load',function(){ initToolbar('toolbar2','pageNote',toolbar); });");

            

            print("</script>");

            

		} // end of if add

		

		// modification d'un element

		elseif ($_GET['a'] == "mod")

		{

			centeredInfoMessage(3, 3, "Administration des pages : modification") ;

			// on ne connait pas encore l'element a modifier on affiche la liste

			if (!isset($_POST['id']) && !isset($_GET['id']))

			{

                dbConnect() ;

                $menusList = dbQuery('SELECT id_node, titre

                    FROM node

                    ORDER BY titre') ;

                $menusCount = mysql_num_rows($menusList) ;

            

                // aucun menu pour le moment

                if ($menusCount == 0)

                {

                    centeredInfoMessage(2, 2, "Aucun menu section pour le moment") ;

                    return ;

                }

            

                print("\t\t\t<center><b>Choisissez la page &agrave; modifier</b></center>\n") ;

                print("\t\t\t<center><form action=\"admin.php?w=pages&a=mod\" method=\"post\">\n") ;

                print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;

                

                for ($i = 0 ; $i < $menusCount ; $i++)

                {

                    $fMenusList = mysql_fetch_array($menusList) ;

                    print("\t\t\t\t<tr>\n") ;

                    print("\t\t\t\t\t<td width=\"400\" align=\"left\"><input type=\"radio\" name=\"id\" value=\"{$fMenusList['id_node']}\" onClick=\"submit()\"> {$fMenusList['titre']}</td>\n") ;

                    print("\t\t\t\t</tr>\n") ;

                }

            

                print("\t\t\t\t<tr>\n") ;

                print("\t\t\t\t\t<td width=\"400\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"pageMod\" value=\"Choisir\"></td>\n") ;

                print("\t\t\t\t</tr>\n") ;

                print("\t\t\t</table>\n") ;

                print("\t\t\t</form></center>\n") ;

                

                dbClose() ;

			} // end of if not id

			

			// on connait l'element a modifier

			else

			{

                /*if (isset($_POST['id'])) { $eID = $_POST['id'] ; }

				else { $eID = $_GET['id'] ; }*/

                $eID = (int)abs((isset($_POST['id']))?($_POST['id']):($_GET['id']));

                

				// connexion a la base et recuperation des infos

				dbConnect() ;

				

				$pageDetails = dbQuery('SELECT N.*, M.`id-module`

                    FROM node N, module M

                    WHERE M.id_node = N.id_node AND N.id_node = '.$eID);

                            

				// on verifie si le resutat est correct

				$pageExists = mysql_num_rows($pageDetails) ;

				if ($pageExists == 0)

				{

					centeredInfoMessage(2, 2, "Rien ne correspond &agrave; cette page") ;

					return ;

				}

				

				$pageDetails = mysql_fetch_array($pageDetails) ;   

                $titre = stripslashes($pageDetails['TITRE']);

                $content = stripslashes($pageDetails['CONTENT']);

                $snote = stripslashes($pageDetails['NOTE_STYLE']);

                $note = stripslashes($pageDetails['NOTE']);

                $menu = stripslashes($pageDetails['MENU']);

                $module = $pageDetails['id-module'];

                

				print("\t\t\t<center><form name=\"defaultForm\" action=\"database.php?w=pages\" method=\"post\">\n") ;

				print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\" border=\"1\">\n") ;

				print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td width=\"100\" align=\"left\"><b> Titre de la page *</b></td><td align=\"left\"><input class=\"defaultInput\" name=\"pageTitre\" size=\"40\" value=\"{$titre}\"></td>\n") ;

				print("\t\t\t\t</tr>\n") ;			

			

                print("\t\t\t\t<tr>\n") ;

                print("\t\t\t\t\t<td width=\"100\" align=\"left\"><b> Module associ&eacute; *</b></td><td>");

                echo '<select name="idModule" class=\"defaultInput\">';

                $res = dbQuery("SELECT M.`id-module`, M.intitule, D.intitule, M.no_semestre

                        FROM module M, diplome D 

                        WHERE D.`id-diplome` = M.`id-diplome`

                        ORDER BY D.intitule, M.no_semestre");

                

                echo '<option value="0">Aucune page associ&eacute;e</option>';

                while($row = mysql_fetch_array($res))

                {

                    echo '<option value="'.$row[0].'" '.(($row[0]==$module)?("selected"):("")).'>'.$row[2].' - SEM'.$row[3].' - '.$row[1].'</option>\n';

                }

                echo '</select>';

                print("\t\t\t\t</td></tr>\n") ;

                

                print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td align=\"center\" colspan=\"2\"><div id=\"toolbar1\"></div><textarea class=\"defaultInput\" rows=\"10\" cols=\"100%\" name=\"pageContent\" id=\"pageContent\">{$content}</textarea><br /></td>\n") ;

				print("\t\t\t\t</tr>\n") ;

                print("\t\t\t\t<tr><td colspan=\"2\" align=\"right\"><div id=\"sizectl1\"></div></td></tr>\n");

                

                print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td colspan=\"2\"><hr /></td>\n") ;

				print("\t\t\t\t</tr>\n") ;

                

                print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td width=\"200\" align=\"left\"><b>Style de notes</b> (cf style css)</td><td width=\"100\" align=\"left\"><input class=\"defaultInput\" name=\"pageSNote\" size=\"40\" value=\"{$snote}\"></td>\n") ;

				print("\t\t\t\t</tr>\n") ;

                

                print("\t\t\t\t<tr>\n") ;

                print("\t\t\t\t<td align=\"left\"><b>Remplissez ce cadre si vous souhaitez avoir un cadre de r&eacute;sum&eacute;.</b>\n") ;

				print("\t\t\t\t</tr>\n") ;

                print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td align=\"center\" colspan=\"2\"><div id=\"toolbar2\"></div><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" id=\"pageNote\" name=\"pageNote\">{$note}</textarea></td>\n") ;

				print("\t\t\t\t</tr>\n") ;

                print("\t\t\t\t<tr><td colspan=\"2\" align=\"right\"><div id=\"sizectl2\"></div></td></tr>\n");

                

                print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td colspan=\"2\"><hr /></td>\n") ;

				print("\t\t\t\t</tr>\n") ;

                

                print("\t\t\t\t<tr>\n") ;

                print("\t\t\t\t<td align=\"left\"><b>Remplissez ce cadre si vous souhaitez avoir un cadre de r&eacute;sumer.</b>\n") ;

				print("\t\t\t\t</tr>\n") ;

                print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td align=\"center\" colspan=\"2\"><textarea class=\"defaultInput\" rows=\"10\" cols=\"100\" name=\"pageMenu\">{$menu}</textarea></td>\n") ;

				print("\t\t\t\t</tr>\n") ;

                

				print("\t\t\t\t<tr>\n") ;

				print("\t\t\t\t\t<td width=\"600\" align=\"left\" colspan=\"2\"><br><input type=\"hidden\" name=\"pageMod\" value=\"true\"><input type=\"hidden\" name=\"pageID\" value=\"{$eID}\"><input class=\"defaultButton\" type=\"button\" name=\"modButton\" value=\"Modifier\" onClick=\"checkPageMod('defaultForm')\"> <input class=\"defaultButton\" type=\"button\" name=\"visuButton\" value=\"Visualiser\" onClick=\"checkPageVisu('defaultForm')\"></td>\n") ;

				print("\t\t\t\t</tr>\n") ;

				print("\t\t\t</table>\n") ;

				print("\t\t\t</form></center>\n") ;



                print("<script language=\"javascript\" charset=\"utf-8\">");

                toolbar_JSdefines('toolbar');

                print("initSizeCtl('sizectl1','pageContent');");

                print("initSizeCtl('sizectl2','pageNote');");

            

                print("addEvent(window,'load',function(){ initToolbar('toolbar1','pageContent',toolbar); });");

                print("addEvent(window,'load',function(){ initToolbar('toolbar2','pageNote',toolbar); });");

            

                print("</script>");

            

				dbClose() ;

			}

		} // end of if mod

		

        // suppression d'un element

		elseif ($_GET['a'] == "del")

		{

			centeredInfoMessage(3, 3, "Administration des pages : suppression") ;

			

			// connexion a la base de donnees et recuperation des infos

			// on ne connait pas le menu

			if (!isset($_POST['menuID']))

			{

                dbConnect() ;

                $menusList = dbQuery('SELECT id_node, titre

                    FROM node

                    ORDER BY titre') ;

                $menusCount = mysql_num_rows($menusList) ;

            

                // aucun menu pour le moment

                if ($menusCount == 0)

                {

                    centeredInfoMessage(2, 2, "Aucun menu section pour le moment") ;

                    return ;

                }

            

                print("\t\t\t<center><b>Choisissez la page &agrave; supprimer</b></center>\n") ;

                print("\t\t\t<center><form action=\"database.php?w=pages\" method=\"post\">\n") ;

                print("\t\t\t<table cellspacing=\"3\" cellpadding=\"0\">\n") ;

                

                for ($i = 0 ; $i < $menusCount ; $i++)

                {

                    $fMenusList = mysql_fetch_array($menusList) ;

                    print("\t\t\t\t<tr>\n") ;

                    print("\t\t\t\t\t<td width=\"400\" align=\"left\"><input type=\"checkbox\" name=\"id[]\" id=\"chbox$i\" value=\"{$fMenuList['id_node']}\"> {$fMenusList['titre']}</td>\n") ;

                    print("\t\t\t\t</tr>\n") ;

                }

            

                print("\t\t\t\t<tr>\n") ;

                print("\t\t\t\t\t<td width=\"400\" align=\"left\"><br><input class=\"defaultButton\" type=\"submit\" name=\"pageDel\" value=\"Choisir\"></td>\n") ;

                print("\t\t\t\t</tr>\n") ;

                print("\t\t\t</table>\n") ;

                print("\t\t\t</form></center>\n") ;

                

                dbClose() ;

			}

			

			// on connait le menu

		} // end of if del

		

		// cas critique : action inconnue : message erreur et redirection

		else

		{

			centeredErrorMessage(3, 3, "Administration des pages : choix incorrect, redirection") ;

			print("<meta http-equiv=\"refresh\" content=\"1;url=admin.php?w=pages\">\n") ;

		}

		

	} // fin de else(!isset($_GET['a']))



} // fin de if (isset($_SESSION['connecte']))





// l'utilisateur n'est pas authentifie

else

{

	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;

}





/*

** EOF pages

*/

?>

