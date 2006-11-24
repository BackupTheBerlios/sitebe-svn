<?php
/**
 * Editing toolbar functions
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */

  if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../').'/');

require_once(DOKU_INC.'includes/JSON.php');


/**
 * Prepares and prints an JavaScript array with all toolbar buttons
 *
 * @todo add toolbar plugins
 * @param  string $varname Name of the JS variable to fill
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function toolbar_JSdefines($varname){
    global $ID;
    global $conf;
    global $lang;    

    // build button array  
    $menu = array(
       array(
            'type'   => 'format',
            'title'  => 'Gras',
            'icon'   => 'bold.png',
            'key'    => 'b',
            'open'   => '**',
            'close'  => '**',
            ),
       array(
            'type'   => 'format',
            'title'  => 'Italique',
            'icon'   => 'italic.png',
            'key'    => 'i',
            'open'   => '//',
            'close'  => '//',
            ),
       array(
            'type'   => 'format',
            'title'  => 'Souligné',
            'icon'   => 'underline.png',
            'key'    => 'u',
            'open'   => '__',
            'close'  => '__',
            ),
       array(
            'type'   => 'format',
            'title'  => 'Code',
            'icon'   => 'mono.png',
            'key'    => 'c',
            'open'   => "''",
            'close'  => "''",
            ),
       array(
            'type'   => 'format',
            'title'  => 'Texte barré',
            'icon'   => 'strike.png',
            'key'    => 'd',
            'open'  => '<del>',
            'close'   => '</del>',
            ),
       array(
            'type'   => 'format',
            'title'  => 'En-tête 1',
            'icon'   => 'h1.png',
            'key'    => '1',
            'open'   => '====== ',
            'close'  => ' ======\n',
            ),
       array(
            'type'   => 'format',
            'title'  => 'En-tête 2',
            'icon'   => 'h2.png',
            'key'    => '2',
            'open'   => '===== ',
            'close'  => ' =====\n',
            ),
       array(
            'type'   => 'format',
            'title'  => 'En-tête 3',
            'icon'   => 'h3.png',
            'key'    => '3',
            'open'   => '==== ',
            'close'  => ' ====\n',
            ),
       array(
            'type'   => 'format',
            'title'  => 'En-tête 4',
            'icon'   => 'h4.png',
            'key'    => '4',
            'open'   => '=== ',
            'close'  => ' ===\n',
            ),
       array(
            'type'   => 'format',
            'title'  => 'En-tête 5',
            'icon'   => 'h5.png',
            'key'    => '5',
            'open'   => '== ',
            'close'  => ' ==\n',
            ),
       array(
            'type'   => 'format',
            'title'  => 'Lien interne',
            'icon'   => 'link.png',
            'key'    => 'l',
            'open'   => '[[',
            'close'  => ']]',
            ),
       array(
            'type'   => 'format',
            'title'  => 'Lien externe',
            'icon'   => 'linkextern.png',
            'open'   => '[[',
            'close'  => ']]',
            'sample' => 'http://example.com|Lien externe',
            ),
       array(
            'type'   => 'format',
            'title'  => 'Liste numérotée',
            'icon'   => 'ol.png',
            'open'   => '  - ',
            'close'  => '\n',
            ),
       array(
            'type'   => 'format',
            'title'  => 'Liste libre',
            'icon'   => 'ul.png',
            'open'   => '  * ',
            'close'  => '\n',
            ),
       array(
            'type'   => 'insert',
            'title'  => 'Ligne horizontale',
            'icon'   => 'hr.png',
            'insert' => '----\n',
            ),
      array(
            'type'   => 'picker',
            'title'  => 'Insérer des characteres spéciaux',
            'icon'   => 'chars.png',
            'list'   => explode(' ','À à �? á Â â Ã ã Ä ä �? ǎ Ă ă Å å Ā �? Ą ą Æ æ Ć ć Ç ç Č �? Ĉ ĉ Ċ ċ �? đ ð Ď �? È è É é Ê ê Ë ë Ě ě Ē ē Ė ė Ę ę Ģ ģ Ĝ �? Ğ ğ Ġ ġ Ĥ ĥ Ì ì �? í Î î �? ï �? �? Ī ī İ ı Į į Ĵ ĵ Ķ ķ Ĺ ĺ Ļ ļ Ľ ľ �? ł Ŀ ŀ Ń ń Ñ ñ Ņ ņ Ň ň Ò ò Ó ó Ô ô Õ õ Ö ö Ǒ ǒ Ō �? �? ő Ø ø Ŕ ŕ Ŗ ŗ Ř ř Ś ś Ş ş Š š Ŝ �? Ţ ţ Ť ť Ù ù Ú ú Û û Ü ü Ǔ ǔ Ŭ ŭ Ū ū Ů ů ǖ ǘ ǚ ǜ Ų ų Ű ű Ŵ ŵ �? ý Ÿ ÿ Ŷ ŷ Ź ź Ž ž Ż ż Þ þ ß Ħ ħ ¿ ¡ ¢ £ ¤ ¥ € ¦ § ª ¬ ¯ ° ± ÷ ‰ ¼ ½ ¾ ¹ ² ³ µ ¶ † ‡ · • º ∀ ∂ ∃ �? ə ∅ ∇ ∈ ∉ ∋ �? ∑ ‾ − ∗ √ �? ∞ ∠ ∧ ∨ ∩ ∪ ∫ ∴ ∼ ≅ ≈ ≠ ≡ ≤ ≥ ⊂ ⊃ ⊄ ⊆ ⊇ ⊕ ⊗ ⊥ ⋅ ◊ ℘ ℑ ℜ ℵ ♠ ♣ ♥ ♦ α β Γ γ Δ δ ε ζ η Θ θ ι κ Λ λ μ Ξ ξ Π π �? Σ σ Τ τ υ Φ φ χ Ψ ψ Ω ω'),
           ),

       array(
            'type'   => 'picker',
            'title'  => 'Insérer un module dynamique',
            'icon'   => 'chars.png',
            'list'   => explode(';','{{isi>ue?ID_DIPLOME}};{{isi>ens?ID_MODULE}};{{isi>uenav?ID_MODULE}};{{isi>actu}};{{isi>apog?ID_MODULE}}'),
            ),
      /*array(
            'type'   => 'signature',
            'title'  => $lang['qb_sig'],
            'icon'   => 'sig.png',
            'key'    => 'y',
           ),*/
    );
    
    // use JSON to build the JavaScript array
    $json = new JSON();
    print "var $varname = ".$json->encode($menu).";\n";
}

/**
 * prepares the signature string as configured in the config
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function toolbar_signature(){
  global $conf;

  $sig = $conf['signature'];
  $sig = strftime($sig);
  $sig = str_replace('@USER@',$_SERVER['REMOTE_USER'],$sig);
  $sig = str_replace('@NAME@',$_SESSION[$conf[title]]['auth']['info']['name'],$sig);
  $sig = str_replace('@MAIL@',$_SESSION[$conf[title]]['auth']['info']['mail'],$sig);
  $sig = str_replace('@DATE@',date($conf['dformat']),$sig);
  $sig = str_replace('\\\\n','\\n',addslashes($sig));
  return $sig;
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
