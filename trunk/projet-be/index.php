<?php
    error_reporting(0);
    
    include('includes/config.php');    
    require_once('includes/lib-db.php');
    include('includes/wiki.php');
    //require_once('Cache.php');
    
    // Récupération de l'identifiant de la page
    $pageid = (int)abs((isset($_GET['p']))?($_GET['p']):(1));
    if($pageid < 1) $pageid = 1;
    
    $fupdate = (int)abs((isset($_GET['u']))?($_GET['u']):(0));
    
    // Récupération des données depuis la base de données.
    $sql = "SELECT * FROM node WHERE ID_NODE='$pageid'";
    $res = DB_query($sql);            
    $row = DB_fetchArray($res);
    
    // Initialise le cache
    //$cache_group = md5($row['TITRE']);
    //$cache = new Cache('file', array('cache_dir' => 'cache/') );
    //$id = $cache->generateID(array('url' => $REQUEST_URI, 'last_mod' => $row['DATE_MODIFICATION']));
    //$data = $cache->get($id, $cache_group);
    //if ($data && !$fupdate) {
    //    print $data;
    //} else {
    //    $cache->flush($cache_group);
        ob_start();
       
        // Affectation des variables
        $titre = stripslashes($row['TITRE']);
        $result = html_entity_decode(p_render('xhtml', p_get_instructions(stripslashes($row['CONTENT'])), $info));
        $note = html_entity_decode(p_render('xhtml', p_get_instructions(stripslashes($row['NOTE'])), $info));
        $snote = $row['NOTE_STYLE'];
        $menu = $row['MENU'];
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
    <style type="text/css">
        img {
            behavior: url("styles/pngbehavior.htc");
        }
    </style>
    <link rel="stylesheet"
          href="styles/isi.css"
          type="text/css" />
</head>

<body>   
    <div id="page">
        <div id="bandeau">
                <!--======================= DÃ©but logo ==================-->
                <a title="Retour en page d'accueil"
               href="index.php"><img style="BORDER-RIGHT: 0px solid; BORDER-TOP: 0px solid; BORDER-LEFT: 0px solid; BORDER-BOTTOM: 0px solid;"
                 alt="IUP ISI"
                 src="img/logo-trans.png" />
                 </a>
        </div><!-- DÃ©but Texte -->
        
        <table width="100%" valign="top" cellspacing="15">
        <tr>
            <td width="150" valign="top">
                <DIV id=themepromo>
                    <H2>CHOIX DE L'ANNEE</H2>
                    <DIV>
                      
                      <table  align="center" width="80%" border="0">
                      <tr>
                      <td>
                        <b><LI><A href="http://www.iup-ups.ups-tlse.fr/isi/l2.html">L2</A></b>
                      </td>
                      <td>
                        <b><A href="?p=15">L3</A></b>
                        </td>
                      <td>
                        <b><A href="http://www.iup-ups.ups-tlse.fr/isi/m1.html">M1</A></b>
                        </td>
                      <td>
                        <b><A href="http://www.iup-ups.ups-tlse.fr/isi/m2.html">M2</A></b>
                        </td>
                      </tr>
                      </table>
                      
                    </DIV>
                </DIV>
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
                            echo "<li><a href='" . $row1['PATH'] . "' alt='".$row1['DESCRIPTION']."'>" . $row1['INTITULE'] . "</a></li>\n";
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
                    <!--==========dÃ©but pied de page======--><a href="http://www.ups-tlse.fr/"
                         title="UPS"><img 
                         src="img/logoups.gif"
                         alt="UPS" /></a>
                </div>
            </td>
            <td valign="top">
                <div id="texteaccueil">
                    <div id="intro">
                    <!-- Fin Intro --><!-- DÃ©but UE -->
                        <?if (!empty($note)) {?>
                        <div id="<?=$snote?>">
                            <?=$note?>
                        </div><!-- Fin UE-->
                        <?}?>
                    </div><!-- DÃ©but PrÃ©sentation -->
                    <div id="texte">
                        <?=$result?>
                    </div><!-- Fin PrÃ©sentation -->
                </div>
            </td>
        </tr>
        </table>
        
           
        <div id="menus">

            <div id="sectionmenus">
                <?if(!empty($menu)) {?>
       
                <?}?>

            </div>
        </div>
    
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
<?php
    /*    $data = ob_get_contents(); 
        ob_end_flush();
        $cache->save($id, $data, 0, $cache_group);
    }*/
?>
