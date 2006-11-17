<?php
    require('includes/parser/parser.php');
    require('includes/parser/xhtml.php');
        
    function p_get_parsermodes(){
      global $conf;
    
      //reuse old data
      static $modes = null;
      if($modes != null){
        return $modes;
      }
    
      //import parser classes and mode definitions
      require_once DOKU_INC . 'includes/parser/parser.php';
    
      // we now collect all syntax modes and their objects, then they will
      // be sorted and added to the parser in correct order
      $modes = array();
    
      // add syntax plugins
      $pluginlist = plugin_list('syntax');
      if(count($pluginlist)){
        global $PARSER_MODES;
        $obj = null; 
        foreach($pluginlist as $p){
          if(!$obj =& plugin_load('syntax',$p)) continue; //attempt to load plugin into $obj
          $PARSER_MODES[$obj->getType()][] = "plugin_$p"; //register mode type
          //add to modes
          $modes[] = array(
                       'sort' => $obj->getSort(),
                       'mode' => "plugin_$p",
                       'obj'  => $obj,
                     );
          unset($obj); //remove the reference
        }
      }
      
      // add default modes
      $std_modes = array('listblock','preformatted','notoc','nocache',
                         'header','table','linebreak','footnote','hr',
                         'unformatted','php','html','code','file','quote',
                         'multiplyentity','quotes','internallink','rss',
                         'media','externallink','emaillink','windowssharelink',
                         'eol');
      foreach($std_modes as $m){
        $class = "Doku_Parser_Mode_$m";
        $obj   = new $class();
        $modes[] = array(
                     'sort' => $obj->getSort(), 
                     'mode' => $m,
                     'obj'  => $obj
                   );
      }
      
      // add formatting modes
      $fmt_modes = array('strong','emphasis','underline','monospace',
                         'subscript','superscript','deleted');
      foreach($fmt_modes as $m){
        $obj   = new Doku_Parser_Mode_formatting($m);
        $modes[] = array( 
                     'sort' => $obj->getSort(),
                     'mode' => $m,
                     'obj'  => $obj
                   );
      }
      
      //sort modes
      usort($modes,'p_sort_modes');
    
      return $modes;
    }
    
    function p_sort_modes($a, $b){
      if($a['sort'] == $b['sort']) return 0;
      return ($a['sort'] < $b['sort']) ? -1 : 1;
    }
    
    function p_get_instructions($text){
    
      $modes = p_get_parsermodes();
    
      // Create the parser
      $Parser = & new Doku_Parser();
      
      // Add the Handler
      $Parser->Handler = & new Doku_Handler();
    
      //add modes to parser
      foreach($modes as $mode){
        $Parser->addMode($mode['mode'],$mode['obj']);
      }
    
      // Do the parsing
      $p    = $Parser->parse(cleanText($text));
      //dbg($p);
      return $p;
    }  
    
    function dbg($msg,$hidden=false){
      (!$hidden) ? print '<pre class="dbg">' : print "<!--\n";
      print_r($msg);
      (!$hidden) ? print '</pre>' : print "\n-->";
    }

    function p_render($mode,$instructions,& $info){
      if(is_null($instructions)) return '';
    
      // Create the renderer
      if(!@file_exists(DOKU_INC."includes/parser/$mode.php")){
        msg("No renderer for $mode found",-1);
        return null;
      }
    
      require_once DOKU_INC."includes/parser/$mode.php";
      $rclass = "Doku_Renderer_$mode";
      $Renderer = & new $rclass(); #FIXME any way to check for class existance?
    
      /*$Renderer->smileys = getSmileys();
      $Renderer->entities = getEntities();
      $Renderer->acronyms = getAcronyms();
      $Renderer->interwiki = getInterwiki();
      #$Renderer->badwords = getBadWords();*/
      
      // Loop through the instructions
      foreach ( $instructions as $instruction ) {
          // Execute the callback against the Renderer
          call_user_func_array(array(&$Renderer, $instruction[0]),$instruction[1]);
      }
    
      //set info array 
      $info = $Renderer->info;
    
      // Return the output
      return $Renderer->doc;
    }
    
    function cleanText($text){
        $text = preg_replace("/(\015\012)|(\015)/","\012",$text);
        return $text;
    }
?>