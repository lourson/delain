<?php 
ob_start();

// on cherche la position
$req_etage = "select pos_etage,pos_cod,pos_x,pos_y,etage_affichage from perso_position,positions,etage ";
$req_etage = $req_etage . "where ppos_perso_cod = $perso_cod ";
$req_etage = $req_etage . "and ppos_pos_cod = pos_cod ";
$req_etage = $req_etage . "and pos_etage = etage_numero ";
$db->query($req_etage);
if ($db->nf())
{
	$db->next_record();
	$aff_etage = $db->f("etage_affichage");
	$etage_actuel = $db->f("pos_etage");
	$pos_actuelle = $db->f("pos_cod");
	$x_actuel = $db->f("pos_x");
	$y_actuel = $db->f("pos_y");
}
?>
<link rel="stylesheet" type="text/css" href="style_vue.php?num_etage=<?php echo $etage_actuel ?>" title="essai">
<script type="text/javascript">	//# sourceURL=vue_gauche.js
	function vue_clic(pos_cod, distance)
	{
		document.getElementsByName('position').value = pos_cod;
		document.forms['destdroite'].dist.value = distance;
		if (distance == 1 || document.forms['destdroite'].action.value != 'action.php') // En cas de déplacement, on vérifie la distance
		{
			voirList(document.forms['destdroite'], document.forms['destdroite'].action.value + '?position=' + pos_cod + '&t_frdr=<?php echo $t_frdr; ?>', document.forms['destdroite'].destcadre.value);
			document.getElementById("cell" + pos_cod).className = 'vu';
			if (document.forms['destdroite'].action.value == 'action.php')
				clignoter("cell" + pos_cod, 10);	// clic pour déplacement
			else
				window.setTimeout("cligno_fin('cell" + pos_cod + "')", 1000);	// clic pour détails
		}
	}
	function clignoter(cell, nombre)
	{
		nombre--;
		if (document.getElementById(cell).style.opacity == '0.5')
		{
			document.getElementById(cell).style.opacity = '1';
			document.getElementById(cell).style.backgroundColor = 'transparent';
		}
		else
		{
			document.getElementById(cell).style.opacity = '0.5';
			document.getElementById(cell).style.backgroundColor = '#8888FF';
		}
		if (nombre == 0)
			window.setTimeout("cligno_fin('" + cell + "')", 500);
		else
			window.setTimeout("clignoter('" + cell + "', " + nombre + ")", 500);
	}
	function cligno_fin(cell)
	{
		document.getElementById(cell).className = 'pasvu';
	}
</script>
<?php 


$req_distance = "select distance_vue($perso_cod) as distance";
$db->query($req_distance);
$db->next_record();
$distance_vue = $db->f("distance");

if (isset($etage_actuel))
{

?>


<table border="0" cellspacing="0" cellpadding="0" ID="tab_vue" bgcolor="#FFFFFF" >

<?php 
	$req_x = "select distinct pos_x from positions where pos_x between ($x_actuel - $distance_vue) and ($x_actuel + $distance_vue) and pos_etage = $etage_actuel order by pos_x";
	$db->query($req_x);
	$db->next_record();
	$ssize = ($distance_vue *2 + 2 )*30;
	// echo "<tr><td style=\"coord2\"><a href=\"javascript:parent.set('".$ssize.",*','".$ssize.",*');\" class=\"coord\"><img alt=\"Cliquez ici pour élargir la vue\" title=\"Cliquez ici pour élargir la vue\" src=\"../images/agrandir.gif\" border=\"0\"></a></td>";
	echo "<tr><td class='coord'></td>";

	$min_x = $db->f("pos_x");
	echo  '<td class="coord">' . $db->f("pos_x") . '</td>';
	while($db->next_record())
	{
		echo  '<td class="coord">' . $db->f("pos_x") . '</td>';
	}
	//echo '</tr>';
	$req_y = "select distinct pos_y from positions where pos_y between ($y_actuel - $distance_vue) and ($y_actuel + $distance_vue) and pos_etage = $etage_actuel order by pos_y desc";
	$db->query($req_y);
	$db->next_record();
	$min_y = $db->f("pos_y");

	$y_encours = -2000;

	$req_map_vue = "select * from vue_perso7($perso_cod)";
	$db->query($req_map_vue);
	while($db->next_record())
	{
		$titre = '';
		$detail = 0;
		$texte = '';
		$isobjet = 0;
		$comment = '';
		$code_image = 0;
		if ($y_encours != $db->f('t_y'))
		{
			$y_encours = $db->f('t_y');
			echo "\n" . '</tr><tr class="vueoff" height="10"><td height="10" class="coord">' . $db->f('t_y') . '</td>';
		}
		$style = 'caseVue v' . $db->f('t_type_case');
		echo '<td class="' . $style . '">';
		if ($db->f('t_decor') != 0)
		{
			echo '<div class="caseVue decor' . $db->f('t_decor') . '">';
		}
		if ($db->f('t_nb_perso') != 0)
		{
			$comment .= $db->f('t_nb_perso') . ' aventurier(s), ';
			$detail = 1;
			echo '<div class="joueur">';
			$titre .= $db->f('t_nb_perso') . ' aventuriers, ';
		}
		if ($db->f('t_nb_monstre') != 0)
		{
			$comment .= $db->f('t_nb_monstre') . ' monstre(s), ';
			$detail = 1;
			echo '<div class="monstre">';
			$titre .= $db->f('t_nb_monstre') . ' monstres.';
		}
		if ($db->f('t_nb_lock') != 0)
		{
			$detail = 1;
			echo '<div class="lock">';
		}
		if ($db->f('t_nb_obj') != 0)
		{
			$comment .= $db->f('t_nb_obj') . ' objet(s), ';
			$detail = 1;
			echo '<div class="objet">';
			$isobjet = 1;
		}
		if ($db->f('t_or') != 0)
		{
			$comment .= $db->f('t_or') . ' tas d’or, ';
			$detail = 1;
			if ($isobjet == 0)
			{
				$isobjet = 1;
				echo '<div class="objet">';
			}
		}
		if ($db->f('t_type_aff') == 1)
		{
			$comment .= '1 mur';
			echo '<div class="caseVue mur_' . $db->f('t_type_mur') . '">';
		}
		if ($db->f('t_type_bat') != 0)
		{
			$comment .= '1 lieu, ';
			echo '<div class="caseVue lieu' . $db->f('t_type_bat')  . '">';
		}
		if ($db->f('t_dist') == 0)
		{
			echo '<div class="oncase caseVue">';
		}
		echo '<div id="dep' . $db->f('t_pos_cod') . '" class="main caseVue" onClick="javascript:vue_clic(' . $db->f('t_pos_cod') . ', ' . $db->f('t_dist') . ');" title="' . $titre . '">';
		if (($db->f('t_traj') == 0) && ($db->f('t_type_aff') != 1))
		{
			echo '<div class="br caseVue">';
		}
		if ($db->f('t_traj') == 1)
		{
			echo '<div id="cell2' . $db->f('t_pos_cod') . ' caseVue">';
		}
		if ($db->f('t_decor_dessus') != 0)
		{
			echo '<div class="caseVue decor' . $db->f('t_decor_dessus') . '">';
		}
		echo '<div id="cell' . $db->f('t_pos_cod') . '" class="pasvu caseVue" title="' . $titre . '">';
		echo '<img src="' . G_IMAGES . 'del.gif" width="28" height="28" alt="' . $comment . '" />';
		echo '</div>';
		if ($db->f('t_decor_dessus') != 0)
		{
			echo '</div>';
		}
		if ($db->f('t_traj') == 1)
		{
			echo '</div>';
		}
		if (($db->f('t_traj') == 0) && ($db->f('t_type_aff') != 1))
		{
			echo '</div>';
		}
		echo '</div>';
		if ($db->f('t_dist') == 0)
		{
			echo '</div>';
		}
		if ($db->f('t_type_bat') != 0)
		{
			echo '</div>';
		}
		if ($db->f('t_type_aff') == 1)
		{
			echo '</div>';
		}

		if ($isobjet == 1)
		{
			echo '</div>';
		}

		if ($db->f('t_nb_lock') != 0)
		{
			echo '</div>';
		}

		if ($db->f('t_nb_monstre') != 0)
		{
			echo '</div>';
		}

		if ($db->f('t_nb_perso') != 0)
		{
			echo '</div>';
		}
		if ($db->f('t_decor')!= 0)
		{
			echo '</div>';
		}
		echo '</td>';

	}
	echo '</table>';
}
$vue_gauche = ob_get_contents();
ob_end_clean();
//ob_flush();
//$t->set_var('VUE_GAUCHE',$vue_gauche);

?>
