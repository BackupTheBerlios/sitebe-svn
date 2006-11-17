<?php
var_dump($_POST);

/*
** Fichier : wikifun
** Date de creation : 6/12/2005
** Auteurs : Thibault Normand
** Version : 1.0
** Description : Gestion des fonctions wiki
*/

require_once('../Includes/settings.inc.php');
require_once('../Functions/database.inc.php');

function createModuleCombo()
{
    dbConnect();
    echo '<select name="id">';
    $res = dbQuery("SELECT M.`id-module`, M.intitule, D.intitule, M.no_semestre
            FROM module M, diplome D 
            WHERE D.`id-diplome` = M.`id-diplome`
            ORDER BY D.intitule, M.no_semestre");
    
    while($row = mysql_fetch_array($res))
    {
        echo '<option value="'.$row[0].'">'.$row[2].' - SEM'.$row[3].' - '.$row[1].'</option>\n';
    }
    echo '</select>';
    dbClose();
}

function js_setFunVar($fun)
{
    echo '<input type="hidden" name="function" value="'.$fun.'">';
}

if(isset($_POST['id']) && isset($_POST['function']))
{
    echo '<script language="Javascript">
            window.close();
          </script>';
    exit(0);
}

// !!! on s'assure toujours que l'utilisateur est bien loggue...
$action = (int)abs(((isset($_GET['a']))?($_GET['a']):(1)));
?>
<html>
<head>
<script language="Javascript" src="../../includes/js.php" />
</head>

<body>
<form action="" method="POST">
<?php
switch($action)
{
    case 1: // Insertion composant Table UE
        js_setFunVar("ue");
        createModuleCombo();
        break;
    
    case 2: // Insertion composant Table Enseignant
        break;
    
    case 3: // Insertion composant UE Navigation
        break;
        
    case 4: // Actualite
        break;
        
    case 5: // Apogée
        break;
        
    default:
}
?>
<input type="submit" name="insert" onSubmit('insertFunction();return true;')>
</form>
</body>
</html>