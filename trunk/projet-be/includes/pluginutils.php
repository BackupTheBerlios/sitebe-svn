<?php
/**
 * Utilities for handling plugins
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC."includes/plugins/");

/**
 * prints needed HTML to include plugin CSS and JS files
 *
 * @deprecated - now handled by the style and script loader in lib/exe
 */
function plugin_printCSSJS(){
    global $conf;
    
    if (isset($conf['pluginmanager']) && $conf['pluginmanager']  && 
        // implicit check that plugin manager has setup the aggregated files - it has styles of its own
        @file_exists(DOKU_INC.'lib/plugins/plugin_style.css'))  {
        // individual plugin instances of the files swept into one file each
        $dir = "lib/plugins/plugin_";
        if(@file_exists(DOKU_INC.$dir.'style.css')){
            print '  <link rel="stylesheet" type="text/css" href="'.DOKU_BASE.$dir.'style.css" />'."\n";
        }
        if(@file_exists(DOKU_INC.$dir.'screen.css')){
            print '  <link rel="stylesheet" media="screen" type="text/css" href="'.DOKU_BASE.$dir.'screen.css" />'."\n";
        }
        if(@file_exists(DOKU_INC.$dir.'print.css')){
            print '  <link rel="stylesheet" media="print" type="text/css" href="'.DOKU_BASE.$dir.'print.css" />'."\n";
        }
        if(@file_exists(DOKU_INC.$dir.'script.js')){
            print '  <script type="text/javascript" language="javascript" charset="utf-8" src="'.DOKU_BASE.$dir.'script.js"></script>'."\n";
        }
    } else {
        // no plugin manager (or aggregate files not setup) so individual instances of these files for any plugin that uses them
        $plugins = plugin_list();
        foreach ($plugins as $p){
            $dir = "lib/plugins/$p/";
            if(@file_exists(DOKU_INC.$dir.'style.css')){
                print '  <link rel="stylesheet" type="text/css" href="'.DOKU_BASE.$dir.'style.css" />'."\n";
            }
            if(@file_exists(DOKU_INC.$dir.'screen.css')){
                print '  <link rel="stylesheet" media="screen" type="text/css" href="'.DOKU_BASE.$dir.'screen.css" />'."\n";
            }
            if(@file_exists(DOKU_INC.$dir.'print.css')){
                print '  <link rel="stylesheet" media="print" type="text/css" href="'.DOKU_BASE.$dir.'print.css" />'."\n";
            }
            if(@file_exists(DOKU_INC.$dir.'script.js')){
                print '  <script type="text/javascript" language="javascript" charset="utf-8" src="'.DOKU_BASE.$dir.'script.js"></script>'."\n";
            }
        }
    }
} 

/**
 * Returns a list of available plugins of given type
 *
 * Returns all plugins if no type given
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function plugin_list($type=''){
  $plugins = array();
  if ($dh = opendir(DOKU_PLUGIN)) {
    while (false !== ($plugin = readdir($dh))) {      
      if ($plugin == '.' || $plugin == '..' || $plugin == 'tmp') continue;
      if (is_file(DOKU_PLUGIN.$plugin)) continue;
      if ($type=='' || @file_exists(DOKU_PLUGIN."$plugin/$type.php")){
          $plugins[] = $plugin;
      } else {
        if ($dp = opendir(DOKU_PLUGIN."$plugin/$type/")) {
          while (false !== ($component = readdir($dp))) {
            if ($component == '.' || $component == '..' || strtolower(substr($component, -4)) != ".php") continue;
            if (is_file(DOKU_PLUGIN."$plugin/$type/$component")) {
              $plugins[] = $plugin.'_'.substr($component, 0, -4);
            }
          }
        closedir($dp);
        }
      }
    }
    closedir($dh);
  }
  return $plugins;
}

/**
 * Loads the given plugin and creates an object of it
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param  $type string     type of plugin to load
 * @param  $name string     name of the plugin to load
 * @return objectreference  the plugin object or null on failure
 */
function &plugin_load($type,$name){
  //we keep all loaded plugins available in global scope for reuse
  global $DOKU_PLUGINS;


  //plugin already loaded?
  if($DOKU_PLUGINS[$type][$name] != null){
    return $DOKU_PLUGINS[$type][$name];
  }

  //try to load the wanted plugin file
  if (file_exists(DOKU_PLUGIN."$name/$type.php")){
    include_once(DOKU_PLUGIN."$name/$type.php");
  }else{
    list($plugin, $component) = preg_split("/_/",$name, 2);
    if (!$component || !include_once(DOKU_PLUGIN."$plugin/$type/$component.php")) {
        return null;
    }
  }

  //construct class and instanciate
  $class = $type.'_plugin_'.$name;
  if (!class_exists($class)) return null;
  
  $DOKU_PLUGINS[$type][$name] = new $class;
  return $DOKU_PLUGINS[$type][$name];
}
