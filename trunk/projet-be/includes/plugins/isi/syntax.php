<?php
    if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
    if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'plugins/');
    require_once(DOKU_PLUGIN.'syntax.php');
    require_once(DOKU_INC.'includes/lib-db.php');
    
    class syntax_plugin_isi extends DokuWiki_Syntax_Plugin {
        /**
         * return some info
         */
        function getInfo(){
            return array(
                'author' => 'Thibault Normand',
                'email'  => 'thibault.normand@gmail.com',
                'date'   => '2005-11-25',
                'name'   => 'ISI Plugin',
                'desc'   => 'Plugin servant à la création des composants dynamiques',
                'url'    => '',
            );
        }
        
        /**
         * What kind of syntax are we?
         */
        function getType(){
            return 'substition';
        }
        
        /**
         * What about paragraphs?
         */
        function getPType(){
            return 'block';
        }
        
        /**
         * Where to sort in?
         */ 
        function getSort(){
            return 301;
        }
        
        /**
         * Connect pattern to lexer
         */
        function connectTo($mode) {
            $this->Lexer->addSpecialPattern('\{\{isi>[^}]*\}\}',$mode,'plugin_isi');
        }
        
        /**
         * Handle the match
         */
        function handle($match, $state, $pos, &$handler){
            $match = substr($match,6,-2); //strip markup from start and end
            
            $data = array();
            list($fn,$params) = explode('?',$match,2);
            
            $data['fn'] = $fn;
            //ID de traitement
            if(preg_match('/\b(\d+)\b/i',$params,$match)){
                $data['id'] = $match[1];
            }
            return $data;
        }
        
        /**
         * Create output
         */
        function render($mode, &$renderer, $data) {
            if($mode == 'xhtml'){
                $renderer->doc .= $this->_processISI($data);
                return true;
            }
            return false;
        }
        
        function _processISI($data)
        {
            switch(strtolower($data['fn']))
            {
                case "ue":
                    return $this->_createUETable($data);
                    break;
                case "ens":
                    return $this->_createEnseignantTable($data);
                    break;
                case "uenav":
                    return $this->_createUENav($data);
                    break;
                case "actu":
                    return $this->_createActualite($data);
                    break;
                case "apog":
                    return $this->_createApogeeDesc($data);
                    break;
                default:
                    return "";
            }
        }
        
        function _createUETable($data)
        {
            $ret = "";
            
            $sql = "SELECT * FROM matiere WHERE `id-module` = " . (int)$data['id'] . " ORDER BY intitule";
            $res = DB_query($sql);
            
            $ret = "<table class=\"ue\">";
            $ret .= "<tr><th>Intitul&eacute;</th><th>Coefficient</th><th>Nombre d'heures</th></tr>";
            while($row = DB_fetchArray($res))
            {
                $ret .= "<tr><td>" . $row['intitule'] . "</td><td>" . $row['coefficient'] . "</td><td>" . $row['nbre-heures'] . "</td></tr>";
            }
            $ret .= "</table>";
            
            return $ret;
        }
        
        function _createEnseignantTable($data)
        {
            $ret = "";
            
            $sql = "SELECT E.nom, E.prenom FROM enseignant E, module M WHERE M.`id-module` = " . (int)$data['id'] . " AND M.`id-responsable` = E.`id-enseignant`";
            $res = DB_query($sql);
            
            $ret = "<ul>";
            while($row = DB_fetchArray($res))
            {
                $ret .="<li>". $row['prenom'] . " " . $row['nom'] . " (Responsable de l'UE)\n";
            }
            
            $sql = "SELECT E.nom, E.prenom FROM matiere M, enseignement S, enseignant E WHERE M.`id-module` = ". (int)$data['id'] ." AND M.`id-matiere` = S.`id-matiere` AND S.`id-enseignant` = E.`id-enseignant`";
            $res = DB_query($sql);
            while($row = DB_fetchArray($res))
            {
                $ret .="<li>". $row['prenom'] . " " . $row['nom'] . "\n";
            }
            $ret .= "</ul>";
            
            return $ret;
        }
        
        function _createUENav($data)
        {
            $ret = "";
            $old_sem = 0;
            
            // On récupère la liste des modules pour l'UE id
            $sql = "SELECT * FROM module WHERE `id-diplome` = ".(int)$data['id']." ORDER BY no_semestre, intitule";
            $res = DB_query($sql);
            while($row = DB_fetchArray($res))
            {
                if($old_sem != (int)$row['no_semestre'])
                {
                    if($old_sem > 0)
                        $ret .= "<br \>";
                    $ret .= "<h3>Semestre " . (int)$row['no_semestre'] . "</h3><br />";
                    $old_sem = (int)$row['no_semestre'];
                }
                $ret .= "<a href=\"?p=" . (int)$row['id_node'] . "\" alt=\"". $row['description'] . "\">". $row['intitule'] . "</a><br />";
            }
            return $ret;
        }
        
        function _createActualite($data)
        {
            $ret = "";
            
            $sql = "SELECT * FROM information WHERE etat = 1 ORDER BY DATE_CREATION DESC";
            $res = DB_query($sql);
            while($row = DB_fetchArray($res))
            {
                $year = substr($row['DATE_CREATION'], 0, 4);
                $month = substr($row['DATE_CREATION'], 5, 2);
                $day = substr($row['DATE_CREATION'], 8, 2);
                
                $ret .= "<h3>" . stripslashes($row['titre']) . "</h3>";
                $ret .= "<cite>$day.$month.$year</cite><br />";
                $ret .= stripslashes($row['contenu'])."<br />";
            }
            
            return $ret;
        }
        
        function _createApogeeDesc($data)
        {
            $ret = "";
            
            // Calcul de la ref.
            $sql = "SELECT M.intitule, D.intitule, M.no_module, M.no_semestre, D.code, M.ECTS, SUM(T.`nbre-heures`) AS nbh FROM module M, diplome D, matiere T WHERE M.`id-module` = ".(int)$data['id']." AND M.`id-diplome` = D.`id-diplome` AND T.`id-module` = M.`id-module` GROUP BY T.`id-module`";
            $res = DB_query($sql);
            $row = DB_fetchArray($res);
            
            $ref = "U" . $row['code'] .  $row['no_semestre'] . "A" . (($row['no_module'] < 10)?("0".$row['no_module']):($row['no_module'])) . "M";
            
            return "<h3>".$ref . " - " . $row[0]." - ECTS " . $row['ECTS'] . " - " . $row['nbh'] . " heures </h3>";
        }
    }
?>