<?

/*

fichier qui permet de réaliser toutes les operations necessaire a realiser

sur les fichiers CSV pour l'import et l'export de donnée vers excel



auteur : Julien SIEGA et Emilen PERICO

*/





/* fonction de traitement du fichier csv

liste des paramètres :

$chemin_fichier	: adresse du fichier à traiter */



function traite_fichier($chemin_fichier)

{

	$tab_fichier = array();	// tableau avec toute les lignes du csv

	$i=0;

	

	/* début de la lecture du fichier */

	if(file_exists($chemin_fichier))	// test l'existence du fichier

	{

		$tab_ligne = file($chemin_fichier);	// tableau avec toutes les ligne

		if($fichier = fopen($chemin_fichier, "r"))	// ouverture du fichier

		{

			/* début de la création du tableau avec toutes les informations */

			while($ligne = fgetcsv($fichier, strlen($tab_ligne[$i])*16, ";"))

			{

			 // if($ligne[0] != NULL)

			  //{

				  $tab_fichier[count($tab_fichier)] = $ligne;

        //}

				$i++;

			}

			/* fin de la création du tableau avec toutes les informations */

			fclose($fichier);	// ferme le fichier

		}

	}

	else

	{

	   print "le fichier n'existe pas ";

  }

	/* fin de la lecture du fichier */

	

	return $tab_fichier;	// renvoi le tableau avec toutes les infos

}



/* fonction de traitement du tableau

liste des paramètres :

	$tab_fichier	: tableau avec toutes les infos à inserer*/

function traite_tableau($tab_fichier)

{

	/* début du traitement du tableau */

	$insertion = false;

	$principal = false;

	

	$sous_principal = false;

	$tab = array();

	for($i=1; $i<count($tab_fichier); $i++)	//lecture de toutes les ligne du tableau

	{

		$champs = $valeur = $modif = "";	// on vide les variables

		/* début du traitement de chaque ligne du tableau */

		for($j=0; $j<count($tab_fichier[$i]); $j++)	// lecture des colones

		{

			/* début du test des champs de valeur ou d'infos */

  		

  			if($tab_fichier[$i][$j] == "Remarques: " || $tab_fichier[$i][$j] == "Remarques:")

  			{

  			   $insertion = false;

        }

      

      if($insertion)

      {

        // on regarde si le fichier xml concerne les modules ou les matieres car le traitement n'est pas le meme pour les deux

        if($principal)

        {

          $tab[0] = "principale";

          //on verifie que la premiere colonne n'est pas vide

          if($tab_fichier[$i][0]!= NULL)

          {

            $chaine = str_replace("Š","è",$tab_fichier[$i][$j] );

            $tableau = explode(",", $chaine);

            $finalchaine = "";

            for($k=0;$k<count($tableau);$k++)

            {

              if($k == count($tableau)-1 )

              {

                $finalchaine .= $tableau[$k];

              }

              else

              {

                $finalchaine .= $tableau[$k]."é";

              }

            }

            if($finalchaine == "")

            {

              $finalchaine = 'NULL';

            }

            $valeur = $champs;

            $champs = $valeur.";".$finalchaine;

          }

        }

        if($sous_principal)

        {

            $tab[0]= "sous-principale";

            if($tab_fichier[$i][$j] == "")

            {

              $finalchaine = 'NULL';

            }

            else

            {

              $finalchaine = $tab_fichier[$i][$j];

            }

            $champs .= $finalchaine.";";

        } 

      }

      //if($j == 0)

      //{

      	if($tab_fichier[$i][$j] == "UE")

  			{

  			   $insertion = true;

  			   $principal = true;

  			   $i++;

  			}

  			if($tab_fichier[$i][$j] == "Sous-UE")

  			{

  			   $insertion = true;

  			   	$sous_principal = true;

  			   $i++;

  			}

  		//}

		}

		

		//echo $champs."<br>";

		$champs1 = explode(";", $champs);

		if($champs != "")

		{

		  $tab[] = $champs;

      //echo $champs."<br>";

    }

	

	}

	return $tab;



	/* fin du traitement du tableau */

}



/*

fonction qui permet d'inserer les différentes éléments dans la base

*/

function insertion_base($tableau)

{

    // on regarde si le tableau concerne les modules ou alors les matieres

   if($tableau[0] == "principale")

   {

     for($i=1;$i<count($tableau);$i++)

     {

        // on coupe la chaine pou recuperer les différentes valeures

        $chaine = $tableau[$i];

        $explode = explode(";",$chaine);

        

        $valeur = $explode[1];

        //echo $valeur[2];

        // on recupere le nom du diplome concerné

        if($valeur[2]=='9' || $valeur[2]=='A')

        {

          $diplome = "Master 2";

        }

        if($valeur[2]=='8' || $valeur[2]=='7')

        {

          $diplome = "Master 1";

        }

        if($valeur[2]=='5' || $valeur[2]=='6')

        {

          $diplome = "Licence 3";

        }

        if($valeur[2]=='3' || $valeur[2]=='4')

        {

          $diplome = "Licence 2";

        }

        

        // requete de selection des élémensts dans la table diplome

        $requette_diplome = "select * from diplome where intitule='".$diplome."'";

        $resultat_diplome = DB_Query($requette_diplome);

        $liste_diplome = mysql_fetch_array($resultat_diplome);

        

        $intitule = addcslashes($explode[2],"'");

        

        $numero_module = $valeur[4]."".$valeur[5];

        

        $numero_semestre = $valeur[2];

        if($numero_semestre == "A")

        {

          $numero_semestre = '10';

        }

        

        $responsable = $explode[4];

        $tab = explode(" ",$responsable);

        $requette_responsable = "select * from enseignant where prenom ='".$tab[0]."' and nom= '".$tab[1]."'";

       // echo $requette_responsable."<br>";

        $resultat_responsable = DB_Query($requette_responsable);

        $nb_ligne = mysql_num_rows($resultat_responsable);

        if($nb_ligne == 0)

        {

          $requette_insertion = "INSERT INTO `enseignant` VALUES ('', '".$tab[1]."', '".$tab[0]."', NULL , '', '')";

          $res = DB_Query($requette_insertion);

          $id = mysql_insert_id();        

        }

        else

        {

          $ligne = mysql_fetch_array($resultat_responsable);

          $id = $ligne['id-enseignant'];

        }

        

        $requette_principale = "select * from module where no_module = '".$numero_module."' and `no_semestre` = '".$numero_semestre."'";

        $resultat_principale = DB_Query($requette_principale);

        

        $nb_ligne_principale = mysql_num_rows($resultat_principale);

        

        if($nb_ligne_principale == 0)

        {

            $req = "INSERT INTO `module` VALUES ('', '".$liste_diplome['id-diplome']."', '".$intitule."', '".$numero_module."', 'NULL', '".$explode[5]."', '".$explode[6]."', '".$explode[7]."', '".$explode[8]."', '".$explode[9]."', '".$explode[10]."', '".$explode[11]."', '".$numero_semestre."', '".$id."', 'NULL')";

            $res = DB_Query($req);

        }

        else

        {

            $req_u = "UPDATE `module` SET `id-diplome` = '".$liste_diplome['id-diplome']."',

                      `intitule` = '".$intitule."',

                      `no_module` = '".$numero_module."',

                      `description` = 'NULL',

                      `ECTS` = '".$explode[5]."',

                      `PS_CC` = '".$explode[6]."',

                      `PS_CP` = '".$explode[7]."',

                      `PS_CT` = '".$explode[8]."',

                      `SS_CC` = '".$explode[9]."',

                      `SS_CP` = '".$explode[10]."',

                      `SS_CT` = '".$explode[11]."',

                      `no_semestre` = '".$numero_semestre."',

                      `id-responsable` = '".$id."' WHERE `no_module` = '".$numero_module."' AND `no_semestre` = '".$numero_semestre."'";

            $res_u = DB_Query($req_u);

        }

        

        

     }

    }

    else

    {

        for($i=0; $i < count($tableau);$i++)

        {

          $explode = explode(";", $tableau[$i]);

          

          if($explode[6] != 'NULL')

          {

              if($explode[6] == 'CC')

              {

                if($explode[3] == "")

                {

                  $intitule = 'NULL';

                }

                else

                {

                  $intitule = $explode[3];

                  $valeur = $explode[2];

                }

              }

              if($intitule != 'NULL')

              {

                 if($valeur[2]=='9' || $valeur[2]=='A')

                  {

                    $diplome = "Master 2";

                  }

                  if($valeur[2]=='8' || $valeur[2]=='7')

                  {

                    $diplome = "Master 1";

                  }

                  if($valeur[2]=='5' || $valeur[2]=='6')

                  {

                    $diplome = "Licence 3";

                  }

                  if($valeur[2]=='3' || $valeur[2]=='4')

                  {

                    $diplome = "Licence 2";

                  }

                    $requette = "select * from diplome where intitule='".$diplome."' ";

                    $res = DB_Query($requette);

                    $ligne = mysql_fetch_array($res);

                    

                    $numero = $valeur[4]."".$valeur[5];

                    

                    $requette_module = "select * from module where `id-diplome` ='".$ligne['id-diplome']."' and no_module = '".$numero."' ";

                  

                    $resultat_module = DB_Query($requette_module);

                    $liste_module = mysql_fetch_array($resultat_module);

                    

                    $requette_verif = "select * from matiere where `id-module`= '".$liste_module['id-module']."' and no_matiere = '".$numero."' and nature_epreuve = '".$explode[6]."' ";

                  

                    $resultat_verif = DB_Query ($requette_verif);

                    $nb_ligne_verif = mysql_num_rows($resultat_verif);

                    

                    if($nb_ligne_verif != 0)

                    {

                        $requette_update = "UPDATE `matiere` SET `id-module` = '".$liste_module['id-module']."',

                                            `no_matiere` = '".$numero."',

                                            `coefficient` = '".$explode[4]."',

                                            `intitule` = '".$intitule."',

                                            `nature_epreuve` = '".$explode[6]."',

                                            `nbre-heures` = '',

                                            `PS_ECRIT` = '".$explode[7]."',

                                            `PS_TP` = '".$explode[8]."',

                                            `PS_ORAL` = '".$explode[9]."',

                                            `SS_ECRIT` = '".$explode[11]."',

                                            `SS_TP` = '".$explode[12]."',

                                            `SS_ORAL` = '".$explode[13]."' WHERE `no_matiere` = '".$numero."' AND `id-module` = '".$liste_module['id-module']."' and nature_epreuve = '".$explode[6]."' ";

                      $resu = DB_Query($requette_update);

                      //echo $requette_update;

                    }

                    else

                    {

                    

                        $requette_insertion = "insert into matiere values ('','".$liste_module['id-module']."','".$numero."', '".$explode[4]."', '".$intitule."', '".$explode[6]."', '', '".$explode[7]."', '".$explode[8]."', '".$explode[9]."', '".$explode[11]."', '".$explode[12]."','".$explode[13]."')";

                        $resultat_insertion = DB_Query($requette_insertion);

                    }

                    

              }

            }

            else

            {

              $intitule = "NULL";

            }  

          }

    }

}
