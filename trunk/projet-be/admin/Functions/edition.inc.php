<?php

/*

** Fichier : edition.inc

** Date de creation : 03/01/2005

** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil

** Version : 1.0

** Description : Contient les fonctions pour l'edition dans sections par exemple

*/





/*

** ATTENTION : utilise les scripts du fichier Scripts/script_admin.js

*/





/*

** fonction 	: printEditingTools

** entrees		: form : nom du formulaire, field : nom du textearea, colspan : colonnes du tableau

** sorties		: rien

** description	: affiche un ensemble de boutons permettant l'edititon des balises html

*/

/*

** NOTES : aspect visuel : cette boite a outils est plusieurs lignes de tableau

*/

function printEditingTools($formName, $fieldName, $colspan)

{

	

	// pour la mise en forme du texte

	print("\t\t\t<tr>\n") ;

	print("\t\t\t\t<td  class=\"toolBox\" width=\"700\" colspan=\"$colspan\">Mise en forme du texte<br><br>\n") ;

	print("\t\t\t\t\t<input type=\"button\" class=\"toolButton\" value=\"Gras\" onClick=\"makeSimpleTag('$formName', '$fieldName', 'b')\">") ;

	print(" <input type=\"button\" class=\"toolButton\" value=\"Italique\" onClick=\"makeSimpleTag('$formName', '$fieldName', 'i')\">") ;

	print(" <input type=\"button\" class=\"toolButton\" value=\"Soulign&eacute;\" onClick=\"makeSimpleTag('$formName', '$fieldName', 'u')\">") ;

	print(" <input type=\"button\" class=\"toolButton\" value=\"Barr&eacute;\" onClick=\"makeSimpleTag('$formName', '$fieldName', 's')\">") ;

	print(" <input type=\"button\" class=\"toolButton\" value=\"+ Grand\" onClick=\"makeSimpleTag('$formName', '$fieldName', 'big')\">") ;

	print(" <input type=\"button\" class=\"toolButton\" value=\"+ Petit\" onClick=\"makeSimpleTag('$formName', '$fieldName', 'small')\">") ;

	print("\t\t\t\t</td>\n") ;

	print("\t\t\t</tr>\n") ;

	

	

	// liens images

	print("\t\t\t<tr>\n") ;

	print("\t\t\t\t<td  class=\"toolBox\" width=\"700\" colspan=\"$colspan\">Insertions<br><br>\n") ;

	print("\t\t\t\t\t<input type=\"button\" class=\"toolButton\" value=\"URL\" onClick=\"makeURL('$formName', '$fieldName', 1)\">") ;

	print(" <input type=\"button\" class=\"toolButton\" value=\"Lien\" onClick=\"makeURL('$formName', '$fieldName', 2)\">") ;

	print(" <input type=\"button\" class=\"toolButton\" value=\"Mail\" onClick=\"makeMail('$formName', '$fieldName')\">") ;

	print(" <input type=\"button\" class=\"toolButton\" value=\"Image\" onClick=\"makeImage('$formName', '$fieldName')\">") ;

	print("\t\t\t\t</td>\n") ;

	print("\t\t\t</tr>\n") ;

	// liens images

	

	// listes

	/*print("\t\t\t<tr>\n") ;

	print("\t\t\t\t<td  class=\"toolBox\" width=\"700\" colspan=\"$colspan\">Listes<br><br>\n") ;

	print("\t\t\t\t\t<input type=\"button\" class=\"toolButton\" value=\"Element\" onClick=\"makeURL('$formName', '$fieldName', 1)\">") ;

	print(" <input type=\"button\" class=\"largeToolButton\" value=\"Ouvrir liste\" onClick=\"makeURL('$formName', '$fieldName', 2)\">") ;

	print(" <input type=\"button\" class=\"largeToolButton\" value=\"Fermer Liste\" onClick=\"makeURL('$formName', '$fieldName', 2)\">") ;

	print(" <input type=\"button\" class=\"largeToolButton\" value=\"Ouvrir liste n°\" onClick=\"makeURL('$formName', '$fieldName', 2)\">") ;

	print(" <input type=\"button\" class=\"largeToolButton\" value=\"Fermer liste n°\" onClick=\"makeMail('$formName', '$fieldName')\">") ;

	print("\t\t\t\t</td>\n") ;

	print("\t\t\t</tr>\n") ;*/

}









/*

** EOF edition.inc

*/

?>

