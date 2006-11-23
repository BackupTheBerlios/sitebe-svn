<?php

// Fonctions de gestion de la base de données

require_once('mysql.class.php');



// Initialisation de la classe DB

$_DB = new database($_DB_host, $_DB_name, $_DB_user, $_DB_pass);



// Fonctions d'accès

// Execute la requête $SQL

function DB_query($sql, $ignore_errors=0)

{

    global $_DB;

    

    return $_DB->dbQuery($sql,$ignore_errors);

}



// Execute une requête de type 'DELETE FROM $table WHERE $id=$value' 

// puis renvoie sur la page $return_page

function DB_delete($table,$id,$value,$return_page='')

{

    global $_DB;

    $_DB->dbDelete($table,$id,$value);



    if (!empty($return_page)) {

        print refresh("$return_page");

    }

}



function DB_getItem($table,$what,$selection='') 

{

    if (!empty($selection)) {

        $result = DB_query("SELECT $what FROM $table WHERE $selection");

    } else {

        $result = DB_query("SELECT $what FROM $table");

    }

    $ITEM = DB_fetchArray($result);

    return $ITEM[0];

}



function DB_change($table,$item_to_set,$value_to_set,$id='',$value='',$return_page='',$supress_quotes=false) 

{

    global $_DB;



    $_DB->dbChange($table,$item_to_set,$value_to_set,$id,$value,$supress_quotes);



    if (!empty($return_page)) {

        print refresh("$return_page");

    }

}



function DB_count($table,$id='',$value='') 

{

    global $_DB;



    return $_DB->dbCount($table,$id,$value);

}



function DB_copy($table,$fields,$values,$tablefrom,$id,$value,$return_page='') 

{

    global $_DB;



    $_DB->dbCopy($table,$fields,$values,$tablefrom,$id,$value);



    if (!empty($return_page)) {

        print refresh("$return_page");

    }

}



function DB_numRows($recordset)

{

    global $_DB;



    return $_DB->dbNumRows($recordset);

}



function DB_result($recordset,$row,$field)

{

    global $_DB;



    return $_DB->dbResult($recordset,$row,$field);

}



function DB_numFields($recordset)

{

    global $_DB;



    return $_DB->dbNumFields($recordset);

}



function DB_fieldName($recordset,$fnumber)

{

    global $_DB;



    return $_DB->dbFieldName($recordset,$fnumber);

}



function DB_affectedRows($recordset)

{

    global $_DB;



    return $_DB->dbAffectedRows($recordset);

}



function DB_fetchArray($recordset)

{

    global $_DB;



    return $_DB->dbFetchArray($recordset);

}



function DB_insertId($recordset='')

{

    global $_DB;



    return $_DB->dbInsertId($recordset);

}



function DB_error()

{

    global $_DB;



    return $_DB->dbError();

}



function DB_compact()

{

    global $_DB;

}

?>
