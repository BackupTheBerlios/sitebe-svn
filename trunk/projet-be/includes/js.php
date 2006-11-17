<?php

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../').'/');

header('Content-Type: text/javascript; charset=utf-8');
js_out();

function getBaseURL($abs=false){
  global $conf;
  //if canonical url enabled always return absolute
  if($conf['canonical']) $abs = true;

  if($conf['basedir']){
    $dir = $conf['basedir'].'/';
  }elseif(substr($_SERVER['SCRIPT_NAME'],-4) == '.php'){
    $dir = dirname($_SERVER['SCRIPT_NAME']).'/';
  }elseif(substr($_SERVER['PHP_SELF'],-4) == '.php'){
    $dir = dirname($_SERVER['PHP_SELF']).'/';
  }elseif($_SERVER['DOCUMENT_ROOT'] && $_SERVER['SCRIPT_FILENAME']){
    $dir = preg_replace ('/^'.preg_quote($_SERVER['DOCUMENT_ROOT'],'/').'/','',
                         $_SERVER['SCRIPT_FILENAME']);
    $dir = dirname('/'.$dir).'/';
  }else{
    $dir = './'; //probably wrong
  }

  $dir = str_replace('\\','/',$dir); #bugfix for weird WIN behaviour
  $dir = preg_replace('#//+#','/',$dir);
  
  //handle script in lib/exe dir
  $dir = preg_replace('!lib/exe/$!','',$dir);

  //finish here for relative URLs
  if(!$abs) return $dir;

  //use config option if available
  if($conf['baseurl']) return $conf['baseurl'].$dir;

  //split hostheader into host and port
  list($host,$port) = explode(':',$_SERVER['HTTP_HOST']);
  if(!$port)  $port = $_SERVER['SERVER_PORT'];
  if(!$port)  $port = 80;

  // see if HTTPS is enabled - apache leaves this empty when not available,
  // IIS sets it to 'off', 'false' and 'disabled' are just guessing
  if (preg_match('/^(|off|false|disabled)$/i',$_SERVER['HTTPS'])){
    $proto = 'http://';
    if ($port == '80') {
      $port='';
    }
  }else{
    $proto = 'https://';
    if ($port == '443') {
      $port='';
    }
  }

  if($port) $port = ':'.$port;

  return $proto.$host.$port.$dir;
}

function js_out()
{   
    $edit  = (bool) $_REQUEST['edit'];
    
    $files = array(
                DOKU_INC.'/includes/scripts/events.js',
                DOKU_INC.'/includes/scripts/script.js',
                DOKU_INC.'/includes/scripts/domLib.js',
                DOKU_INC.'/includes/scripts/domTT.js',
            );
        
    if($edit)
        $files[] = DOKU_INC.'includes/scripts/edit.js';
        
    ob_start();
    print "var DOKU_BASE   = '".getBaseUrl(). "../admin/" . "';";
    
    foreach($files as $file){
        @readfile($file);
    }
    
    if($edit)
    {
        require_once(DOKU_INC.'includes/toolbar.php');
        toolbar_JSdefines('toolbar');
    }
    
    $js = ob_get_contents();
    ob_end_clean();
    
    print $js;
}

function js_escape($string){
    return str_replace('\\\\n','\\n',addslashes($string));
}

function js_runonstart($func){
    print "addEvent(window,'load',function(){ $func; });";
}

function js_compress($s){
    $i = 0;
    $line = 0;
    $s .= "\n";
    $len = strlen($s);

    // items that don't need spaces next to them
    $chars = '^&|!+\-*\/%=:;,{}()<>% \t\n\r';

    ob_start();
    while($i < $len){
        $ch = $s{$i};

        // multiline comments
        if($ch == '/' && $s{$i+1} == '*'){
            $endC = strpos($s,'*/',$i+2);
            if($endC === false) trigger_error('Found invalid /*..*/ comment', E_USER_ERROR);
            $i = $endC + 2;
            continue;
        }

        // singleline
        if($ch == '/' && $s{$i+1} == '/'){
            $endC = strpos($s,"\n",$i+2);
            if($endC === false) trigger_error('Invalid comment', E_USER_ERROR);
            $i = $endC;
            continue;
        }

        // tricky.  might be an RE
        if($ch == '/'){
            // rewind, skip white space
            $j = 1;
            while($s{$i-$j} == ' '){
                $j = $j + 1;
            }
            if( ($s{$i-$j} == '=') || ($s{$i-$j} == '(') ){
                // yes, this is an re
                // now move forward and find the end of it
                $j = 1;
                while($s{$i+$j} != '/'){
                    while( ($s{$i+$j} != '\\') && ($s{$i+$j} != '/')){
                        $j = $j + 1;
                    }
                    if($s{$i+$j} == '\\') $j = $j + 2;
                }
                echo substr($s,$i,$j+1);
                $i = $i + $j + 1;
                continue;
            }
        }

        // double quote strings
        if($ch == '"'){
            $j = 1;
            while( $s{$i+$j} != '"' ){
                while( ($s{$i+$j} != '\\') && ($s{$i+$j} != '"') ){
                    $j = $j + 1;
                }
                if($s{$i+$j} == '\\') $j = $j + 2;
            }
            echo substr($s,$i,$j+1);
            $i = $i + $j + 1;
            continue;
        }

        // single quote strings
        if($ch == "'"){
            $j = 1;
            while( $s{$i+$j} != "'" ){
                while( ($s{$i+$j} != '\\') && ($s{$i+$j} != "'") ){
                    $j = $j + 1;
                }
                if ($s{$i+$j} == '\\') $j = $j + 2;
            }
            echo substr($s,$i,$j+1);
            $i = $i + $j + 1;
            continue;
        }

        // newlines
        if($ch == "\n" || $ch == "\r"){
            $i = $i+1;
            continue;
        }

        // leading spaces
        if( ( $ch == ' ' ||
              $ch == "\n" ||
              $ch == "\t" ) &&
            !preg_match('/['.$chars.']/',$s{$i+1}) ){
            $i = $i+1;
            continue;
        }

        // trailing spaces
        if( ( $ch == ' ' ||
              $ch == "\n" ||
              $ch == "\t" ) &&
            !preg_match('/['.$chars.']/',$s{$i-1}) ){
            $i = $i+1;
            continue;
        }

        // other chars
        echo $ch;
        $i = $i + 1;
    }


    $out = ob_get_contents();
    ob_end_clean();
    return $out;
}
?>