<?php



    /**

     * Fonctionde protection des variables pour requêtes SQL

     * @param $value Valeur à protéger

     *

     * @return La valeur de la variable protégée.

    */

    function quote_smart($value)

    {

        if(get_magic_quotes_gpc()) {

            $value = stripslashes($value);

        }

        

        if(!is_numeric($value))

        {

            $value = "'" . mysql_real_escape_string($value) . "'";

        }

        

        return $value;

    }

    

?>
