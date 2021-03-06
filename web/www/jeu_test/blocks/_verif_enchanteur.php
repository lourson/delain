<?php
/**
 * Created by PhpStorm.
 * User: steph
 * Date: 20/12/18
 * Time: 13:13
 */
if (!isset($type_appel))
{
    $type_appel = 0;
}


//
// en fonction du type d'appel, on vérifie, soit le lieu, soit la compétence.
//
$comp_enchantement = 0;
$req = 'select pcomp_modificateur,pcomp_pcomp_cod from perso_competences
			where pcomp_perso_cod = ' . $perso_cod . '
			and pcomp_pcomp_cod in (88,102,103)';
$db->query($req);
if ($db->nf() != 0)
{
    $db->next_record();
    $comp_enchantement = $db->f('pcomp_pcomp_cod');
    $comp_enchantement_percent = $db->f('pcomp_modificateur');
}


switch ($type_appel)
{
    case 0:
        $type_lieu = 26;
        $nom_lieu = 'une boutique de l\'enchanteur';

        include "blocks/_test_lieu.php";
        break;

    case 2: //Cette fois, on vérifie qu'un perso sur la case est un enchanteur PNJ
        $tab_quete = $db->get_perso_quete($perso_cod);
        foreach ($tab_quete as $key => $val)
        {
            if ($val == 'enchanteur.php')
            {
                $erreur = 0;
            }
        }
        if ($erreur != 0)
        {
            $contenu_page .= "Aucun enchanteur ne se trouve près de vous";
            $erreur = 1;
        }
        break;
    //
    // on rajoute un cas "default" pour les petits malins qui essaieraient de tricher
    //
    default:
        $contenu_page .= "<p>Erreur sur le type d'appel !";
        $erreur = 1;
        break;
}