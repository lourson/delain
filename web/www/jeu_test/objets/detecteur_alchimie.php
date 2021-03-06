<?php 
include '../verif_connexion.php';
include '../../includes/img_pack.php';
include '../../includes/template.inc';

$t = new template('..');
$t->set_file('FileRef','../template/delain/general_jeu.tpl');
// chemins
$t->set_var('URL',$type_flux.G_URL);
$t->set_var('URL_IMAGES',G_IMAGES);

$contenu_page = '';

$bd=new base_delain;
$req_matos = "select perobj_obj_cod from perso_objets,objets
												where perobj_obj_cod = obj_cod and perobj_perso_cod = $perso_cod and obj_gobj_cod = 726 ";
$bd->query($req_matos);
if($bd->next_record())
{	
	$baguette = $bd->f("perobj_obj_cod");
	if(isset($_POST['methode']))
	{
		$req_pa = "select perso_pa from perso where perso_cod = $perso_cod";
		$bd->query($req_pa);
		$bd->next_record();
		if ($bd->f("perso_pa") < 1)
		{
			$contenu_page .= 'Vous n\'avez pas assez de PA !';
		}
		else
		{
			$req_use = "select use_artefact($baguette)";
			$bd->query($req_use);
			$req_enl_pa = "update perso set perso_pa = perso_pa - 1 where perso_cod = $perso_cod";
			$bd->query($req_enl_pa);		
			$contenu_page .= '	<p>La baguette fait son office et affiche les champs de composants présents dans les environs
				<br>Cela ne signifie pas que des composants seront réellement présents, notamment si quelqu\'un les a ramassé avant vous ...</p>
				<center>';
		// POSITION DU JOUEUR
			$contenu_page .= '<br><br>Table de description des composants présents :<br>
									<table border="1">
									<tr>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#FFFF99"></div></td><td>Pomme</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#FFFF00"></div></td><td>Mandragore</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#FFCCCC"></div></td><td>Absinthe</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#9933CC"></div></td><td>Ache Des Marais</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#FF33CC"></div></td><td>Aconit</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#66FFCC"></div></td><td>Bardane</td>
									</tr>
									<tr>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#9966FF"></div></td><td>Belladone</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#660000"></div></td><td>Chene Rouvre</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#FF0000"></div></td><td>Alkegenge</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#66FF00"></div></td><td>Digitale</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#006600"></div></td><td>Pavot</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#CCFFFF"></div></td><td>Serpolet</td>		
									</tr>
									<tr>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#0000FF"></div></td><td>Menthe</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#000066"></div></td><td>Millepertuis</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#CCFF66"></div></td><td>Noyer</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#003333"></div></td><td>Gentiane</td>
										<td width="20" height="20"><div style="width:25px;height:25px;background:#C0C0C0"></div></td><td>Jusquiame</td>
										<td width="20" height="20"><div style="width:25px;height:25px">
												<table  border="0">
													<tr><td width="10" height="10" style="background:#66FFFF"></td><td width="10" height="10"  style="background:#663399"></td></tr>
													<tr><td width="10" height="10"  style="background:#663399"></td><td width="10" height="10"  style="background:#66FFFF"></td></tr>
												</table>
										</td><td>Plusieurs composants</td>	
									</tr>					
									</table><hr>';
			$contenu_page .= '<table border="1">';
			$req_position = "select pos_x,pos_y,pos_etage
												from perso_position,positions
												where ppos_perso_cod = $perso_cod
												and ppos_pos_cod = pos_cod ";
			$bd->query($req_position);
			$bd->next_record();
			$perso_pos_x = $bd->f("pos_x");
			$perso_pos_y = $bd->f("pos_y");
			$perso_pos_etage = $bd->f("pos_etage");
			$piegeArray = array();
			// POSITION DES composants
			$req_position = "select pos_x,pos_y,pos_etage
															from perso_position,positions
															where ppos_perso_cod = $perso_cod
															and ppos_pos_cod = pos_cod ";
			$db->query($req_position);
			$db->next_record();
			$position_x = $db->f("pos_x");
			$position_y = $db->f("pos_y");
			for ($y=-2; $y<4; $y++)
			{  
				$contenu_page .= '<TR>';
				for ($x=-2; $x<4; $x++)
				{
					if(($y*$y + $x*$x) < 8)
					{
						if($y == 0 && $x == 0)
						{
							$image = "<img height=\"13\" src=\"../../images/rond.gif\">";
						}
						else
						{
							$image = "";
						}
						$req_position = "select pos_cod,pos_x,pos_y from positions where
												 pos_etage = $perso_pos_etage
												 and pos_x = $position_x + $x
												 and pos_y = $position_y - $y";
						$db->query($req_position);
						$db->next_record();
						$position = $db->f("pos_cod");
						$db2 = new base_delain;
						$req_ingredient = "select ingrpos_gobj_cod,ingrpos_max,ingrpos_chance_crea from ingredient_position where
														 ingrpos_pos_cod = $position";
						$db2->query($req_ingredient);
						$nbCouleurs = $db2->nf();
						/*
						#6600FF : bleu utilisé pour la sélection d'une case
						#66FF00 : vert
						#FFFF00 : jaune
						#FF00FF : rose
						#66FFFF : turquoise
						#663399 : violet
						#FFCC00 : orange
						#660000 : marron foncé
						#99FF99 : vert pale
						#C0C0C0 : gris
						#CCCC00 : caca d'oie
						#FFCCFF : rose pale
					
						/* Tableau des couleurs */
						$couleurs = array(
						'562' => '#FFFF99',
						'563' => '#FFFF00',
						'647' => '#FFCCCC',
						'648' => '#9933CC',
						'649' => '#FF33CC',
						'650' => '#FF0000',
						'651' => '#66FFCC',
						'652' => '#9966FF',
						'653' => '#660000',
						'654' => '#66FF00',
						'655' => '#003333',
						'656' => '#C0C0C0',
						'657' => '#0000FF',
						'658' => '#000066',
						'659' => '#CCFF66',
						'660' => '#006600',
						'661' => '#CCFFFF'
						);
						
						if ($nbCouleurs == 0)
						{
							$req_murs = "select mur_creusable from murs where mur_pos_cod = $position";
							$db3 = new base_delain;
							$db3->query($req_murs);
							$color = "#FFFFFF";
							if ($db3->next_record())
                            {
                                $color = ($db3->f("mur_creusable") == 'O') ? "#696969" : "#000000";
                            }
							$contenu_page .= '<td width="20" height="20" ><div id="pos_'. $db->f("pos_cod") .'" style="width:25px;height:25px;background:'. $color .';"> '. $image .'</div>
							</td>';
						}
						else 
						{	
							$ingredientsArray = array();
							$i = 0;
							while ($db2->next_record())
							{
								$ingredientsArray[$i] = $db2->f("ingrpos_gobj_cod");
								$i = ++$i;
							}
			
							$taille = (25 / $nbCouleurs);
							$color = "#00FF00";
							$contenu_page .= '<td width="20" height="20"><div id="pos_'. $position .'" style="width:25px;height:25px">'. $image .'
							<table  cellspacing="0"><tr>';
			/*On initialise le tableau secondaire qu'on va remplir */
							for ($cote = 0 ; $cote < $nbCouleurs ; $cote++)
							{
								$contenu_page .= '<tr>';
								for ($j = $cote ; $j < ($nbCouleurs + $cote) ; $j++)
								{
									$contenu_page .= '<td width="'. $taille .'" height="'. $taille .'"  id="x'. $j .''. $cote .'pos_'. $position .'" style="background:'. $couleurs[$ingredientsArray[$j%$nbCouleurs]] .';"></td>';
								}
								$contenu_page .= '</tr>';
							}
							$contenu_page .= '</tr></table></div></td>';
						}
					}
					else
					{
						$contenu_page .= '<td></td>';
					}
				}
				$contenu_page .= '</tr>';
			}	
			$contenu_page .= '</tr>
					</table>';
		}
	}
	$contenu_page .= '<p align="center">La baguette fonctionne, Appuyer sur le bouton ?<br><br>
		<form method="post" action="'.$PHP_SELF.'">
		<input type="hidden" name="methode" value="detecter">
		<input type="submit" value="Appuyer (1PA)"  class="test">
		</form>
		</p>';
} 
else 
{
	$contenu_page .= '<p>Accès interdit</p>';
}

// on va maintenant charger toutes les variables liées au menu
include('../variables_menu.php');

$t->set_var("CONTENU_COLONNE_DROITE",$contenu_page);
$t->parse("Sortie","FileRef");
$t->p("Sortie");