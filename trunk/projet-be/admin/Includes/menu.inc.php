<?php
/*
** Fichier : menu.inc
** Date de creation : 26/11/2004
** Auteurs : Conde Mickael, Badaoui Kassem, Canaye Kurvin, Guenatri Kamil
** Version : 1.0
** Description : Fichier contenant le menu du site (y compris les liens divers)
*/


/*
Structure generale : une seule table 
 - [ ] premiere ligne : haut proprement dit
 - [X] deuxieme ligne : menu dans la base de donnees + liens divers
 - [ ] ligne suivante : corps du site
 - [ ] derniere ligne : bas du site
*/


// menu bd

$menuList = dbQuery('SELECT intitule, type, `id-menu`
	FROM menu
	ORDER BY ordre') ;

print("\t<tr>\n\t\t<td id=\"mainMenu\" align=\"center\">") ;

while ($menuDetails = mysql_fetch_array($menuList))
{
	print("&nbsp;&nbsp;<a class=\"menu\" href=\"index.php?p={$menuDetails['type']}&id={$menuDetails['id-menu']}\">{$menuDetails['intitule']}</a>&nbsp;&nbsp;") ;
}

print("\n\t\t</td>\n\t</tr>\n") ;

// fin du menu de la bede
	


	
// menu secondaire

?>
	<tr>
		<td class="cellSeparator"></td>
	</tr>
	<tr>
		<td align="right" id="secMenu">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td id="secMenuInfo"></td>
					<td width="85" align="right"><a href="http://www.etudiants.ups-tlse.fr/edtisi" target="_blank"><img name=\"edt\" src="Gfx/btn_edt.gif" onMouseOver="this.src = 'Gfx/btn_edt_on.gif'" onMouseOut="this.src = 'Gfx/btn_edt.gif'"></a></td>
					<td width="85" align="right"><a href="http://marine.edu.ups-tlse.fr/~isistage/" target="_blank"><img name=\"stages\" src="Gfx/btn_stages.gif" onMouseOver="this.src = 'Gfx/btn_stages_on.gif'" onMouseOut="this.src = 'Gfx/btn_stages.gif'"></a></td>
					<td width="85" align="right"><a href="http://www.ups-tlse.fr" target="_blank"><img name="ups" src="Gfx/btn_ups.gif" onMouseOver="this.src = 'Gfx/btn_ups_on.gif'" onMouseOut="this.src = 'Gfx/btn_ups.gif'"></a></td>
				</tr>					
			</table>
		</td>
	</td>
	<tr>
		<td class="cellSeparator"></td>
	</tr>
<?php



/*
** EOF menu.inc
*/
?>
