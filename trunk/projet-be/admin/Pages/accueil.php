<?php
/*
** Fichier : accueil
** Date de creation : 23/07/2004
** Auteurs : Avetisyan Gohar
** Version : 2.0
** Description : Fichier inclu par l'index dont le role est d'afficher l'accueil
*/



// on verifie toujours que cette page a ete appelee a partir de index
if (is_numeric(strpos($_SERVER['PHP_SELF'], "index.php")))
{



	//on verifie s'il y a une information a afficher (idem est-ce que le fichier contenu.html existe ds le repertoire Info)
	/*if (file_exists('Data/Info/contenu.php'))
	{

		//print("<body onload=window.open('Data/Info/info.html',\"Informations\",\"resizable,fullscreen,scrollbar=no,width=500,height=700\")>");
                require("Data/Info/contenu.php");
        }

	//il n'ya rien a afficher (donc pas de fichier contenu.html ds le repertoire Info...)
	else
	{
		print("<body>");
	}
         */



// pour une plus grande facilite de maintenance cette partie est en html pur

?>
			<table cellpadding="0" cellspacing="0" width="800">
				<tr>
					<td id="centerTitle">Navigation &raquo;&nbsp; Accueil</td>
				</tr>
				
                                 <tr>
                                     <td align="center">
                                     
                                         <table cellpadding="0" cellspacing="0">

				            <?php

                                                 //on verifie s'il y a une information a afficher (idem est-ce que le fichier contenu.html existe ds le repertoire Info)
	                                         dbConnect();
	                                         $infoList = dbQuery('SELECT titre, contenu, URL
                                                           FROM information');


                                                 $info_exists = mysql_num_rows($infoList);

                                                 if ($info_exists != 0)
	                                         {

                                                             for ($i = 0 ; $i < $info_exists ; $i++)
                                                             {
				                                 $fInfoList = mysql_fetch_array($infoList) ;
				                                 $fInfoList['contenu'] = nl2br($fInfoList['contenu']);

                                                                 print("\t\t\t\t<tr>\n") ;
                                                                 //insertion de l'image
				                                 //print("\t\t\t\t\t<td width=\"400\" align=\"center\"><img src=\"{$fInfoList['URL']}\"><br><br></td>\n") ;
                                                                 print("\t\t\t\t\t<td width=\"800\" align=\"left\"><div class=\"blueZone\"><h1><u>{$fInfoList['titre']}</u></h1><br>{$fInfoList['contenu']}</div></td>\n") ;
                                                                 print("\t\t\t\t</tr>\n") ;
				                             }
                                                 }

                                            ?>

                                         </table>

                                       </td>
                                  </tr>
				<tr>
					<td align="center">
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td width="400" align="left">
								<br><br>								
								<h1>Bienvenue sur le site de l'IUP ISI</h1>
								<br>
								<a href="http://www.ups-tlse.fr" target="_blank"><img src="Gfx/logo_ups.gif"></a>
								<br>
								<div class="grayZone">
								<u>Directeurs de l'IUP</u><br><br>
								Henri MASSIE <a href="mailto:massie@irit.fr">massie@irit.fr</a> et Louis FERAUD <a href="feraud@irit.fr">feraud@irit.fr</a><br>
								Institut de Recherches en Informatique de Toulouse<br>
								118 Route de Narbonne<br>
								31 062 Toulouse CEDEX<br><br>
								T&eacute;l : 05 61 55 63 52<br><br>
								</div>						
								<br>
								<div class="grayZone">
								<u>S&eacute;cr&eacute;tariat de l'IUP</u><br><br>
								Christine AIROLA <a href="airola@cict.fr">airola@cict.fr</a><br>
								B&acirc;timent U3<br>
								118 Route de Narbonne<br>
								31 062 Toulouse CEDEX<br><br>
								T&eacute;l : 05 61 55 75 46<br><br>
								</div>
								<br>
								
								
								</td>
								<td width="400" align="right">
								<img src="Gfx/accueil_img1.jpg" border="1">
								</td>
							<tr>
						</table>
					</td>
				</tr>				
			</table>


	</body>


<?php
}



// cette page est appelee directement...
else
{
	print("<br><br><br><center><u>Impossible d'utiliser cette page directement</u></center>") ;
}





/*
** EOF accueil
*/
?>
