<?php



    /**

     * Fonctionde protection des variables pour requ�tes SQL

     * @param $value Valeur � prot�ger

     *

     * @return La valeur de la variable prot�g�e.

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
