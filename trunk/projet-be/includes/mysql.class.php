<?php
class database {

    var $_host = '';
    var $_name = '';
    var $_user = '';
    var $_pass = '';
    var $_display_error;

    // PRIVATE METHODS

    function _connect()
    {
        // Connect to MySQL server
        $conn = mysql_connect($this->_host,$this->_user,$this->_pass) or die('Cannnot connect to DB server');

        // Set the database
        @mysql_select_db($this->_name) or die('error selecting database');

        if (!$conn) {
            // damn, got an error.
            $this->dbError();
        }

        // return connection object
        return $conn;
    }

    // PUBLIC METHODS

    function database($dbhost,$dbname,$dbuser,$dbpass)
    {
        $this->_host = $dbhost;
        $this->_name = $dbname;
        $this->_user = $dbuser;
        $this->_pass = $dbpass;
        $this->_display_error = true;
    }

    function dbQuery($sql,$ignore_errors=0)
    {
        // Connect to database server
        $db = $this->_connect();

        // Run query
        if ($ignore_errors == 1) {
            $result = @mysql_query($sql,$db);
        } else {
            $result = @mysql_query($sql,$db) or die($this->dbError($sql));
        }

        // If OK, return otherwise echo error
        if (mysql_errno() == 0 && !empty($result)) {
            return $result;

        } else {
            // callee may want to supress printing of errors
            if ($ignore_errors == 1) return false;
        }
    }

    function dbSave($table,$fields,$values)
    {
        $sql = "REPLACE INTO $table ($fields) VALUES ($values)";

        $this->dbQuery($sql);
    }

    function dbDelete($table,$id,$value)
    {
        $sql = "DELETE FROM $table";

        if (is_array($id) || is_array($value)) {
            if (is_array($id) && is_array($value) && count($id) == count($value)) {
                // they are arrays, traverse them and build sql
                $sql .= ' WHERE ';
                for ($i = 1; $i <= count($id); $i++) {
                    if ($i == count($id)) {
                        $sql .= current($id) . " = '" . current($value) . "'";
                    } else {
                        $sql .= current($id) . " = '" . current($value) . "' AND ";
                    }
                    next($id);
                    next($value);
                }
            } else {
                // error, they both have to be arrays and of the
                // same size
                return false;
            }
        } else {
            // just regular string values, build sql
            if (!empty($id) && ( isset($value) || $value != "")) { 
                $sql .= " WHERE $id = '$value'";
            }
        }

        $this->dbQuery($sql);
        
        return true;
    }

    function dbChange($table,$item_to_set,$value_to_set,$id,$value, $supress_quotes=false)
    {
        if ($supress_quotes) {
            $sql = "UPDATE $table SET $item_to_set = $value_to_set";
        } else {
            $sql = "UPDATE $table SET $item_to_set = '$value_to_set'";
        } 

        if (is_array($id) || is_array($value)) {
            if (is_array($id) && is_array($value) && count($id) == count($value)) {
                // they are arrays, traverse them and build sql
                $sql .= ' WHERE ';
                for ($i = 1; $i <= count($id); $i++) {
                    if ($i == count($id)) {
                        $sql .= current($id) . " = '" . current($value) . "'";
                    } else {
                        $sql .= current($id) . " = '" . current($value) . "' AND ";
                    }
                    next($id);
                    next($value);
                }
            } else {
                // error, they both have to be arrays and of the
                // same size
                return false;
            }
        } else {
            // These are regular strings, build sql
            if (!empty($id) && ( isset($value) || $value != "")) { 
                $sql .= " WHERE $id = '$value'";
            }
        }

        $this->dbQuery($sql);
    }

    function dbCount($table,$id='',$value='')
    {
        $sql = "SELECT COUNT(*) FROM $table";

        if (is_array($id) || is_array($value)) {
            if (is_array($id) && is_array($value) && count($id) == count($value)) {
                // they are arrays, traverse them and build sql
                $sql .= ' WHERE ';
                for ($i = 1; $i <= count($id); $i++) {
                    if ($i == count($id)) {
                        $sql .= current($id) . " = '" . current($value) . "'";
                    } else {
                        $sql .= current($id) . " = '" . current($value) . "' AND ";
                    }
                    next($id);
                    next($value);
                }
            } else {
                // error, they both have to be arrays and of the
                // same size
                return false;
            }
        } else {
            if (!empty($id) && ( isset($value) || $value != "")) { 
                $sql .= " WHERE $id = '$value'";
            }
        }

        $result = $this->dbQuery($sql);

        return ($this->dbResult($result,0));

    }

    function dbCopy($table,$fields,$values,$tablefrom,$id,$value)
    {
        $sql = "REPLACE INTO $table ($fields) SELECT $values FROM $tablefrom";

        if (is_array($id) || is_array($value)) {
            if (is_array($id) && is_array($value) && count($id) == count($value)) {
                // they are arrays, traverse them and build sql
                $sql .= ' WHERE ';
                for ($i = 1; $i <= count($id); $i++) {
                    if ($i == count($id)) {
                        $sql .= current($id) . " = '" . current($value) . "'";
                    } else {
                        $sql .= current($id) . " = '" . current($value) . "' AND ";
                    }
                    next($id);
                    next($value);
                }
            } else {
                // error, they both have to be arrays and of the
                // same size
                return false;
            }
        } else {
            if (!empty($id) && ( isset($value) || $value != "")) { 
                $sql .= " WHERE $id = '$value'";
            }
        }

        $this->dbQuery($sql);
        $this->dbDelete($tablefrom,$id,$value);
    }

    function dbNumRows($recordset)
    {
        // return only if recordset exists, otherwise 0
        if ($recordset) {
            return @mysql_numrows($recordset);
        } else {
            return 0;
        }
    }

    function dbResult($recordset,$row,$field=0)
    {
        return @mysql_result($recordset,$row,$field);
    }

    function dbNumFields($recordset)
    {
        return @mysql_numfields($recordset);
    }

    function dbFieldName($recordset,$fnumber)
    {
        return @mysql_fieldname($recordset,$fnumber);
    }

    function dbAffectedRows($recordset)
    {
        return @mysql_affected_rows();
    }

    function dbFetchArray($recordset)
    {
        return @mysql_fetch_array($recordset);
    }

    function dbInsertId($recordset='')
    {
        if (empty($recordset)) {
            return @mysql_insert_id();
        } else {
            return @mysql_insert_id($recordset);
        }
    }

    function dbError($sql='')
    {
        if (mysql_errno()) {
            if ($this->_display_error) {
                return  @mysql_errno() . ': ' . @mysql_error();
            } else {
                return 'An SQL error has occured. Please see error.log for details.';
            }
        }
	
	return;
    }
    
    function dbCompact()
    {
        // Connect to database server
        $db = $this->_connect();
        
        $tables = mysql_list_tables($this->_name, $db);
        $ok=true;
        
        while($enr = mysql_fetch_row($tables)) {
            $sql = "OPTIMIZE TABLE ".$enr[0];
            if(!$this->dbQuery($sql)){
                $ok=false;
            }
        }
        
        return $ok;
    }
}

?>
