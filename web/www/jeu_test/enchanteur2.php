<?php

include "blocks/_tests_appels_page_externe.php";
//
// on regarde si le joueur est bien sur le lieu qu'on attend
//
$erreur = 0;
$db2 = new base_delain;
$contenu_page = '';
//
// on vérifie que le type d'appel soit bien passé
// s'il n'est pas passé, on considère qu'on est sur un lieu
//
include "blocks/_verif_enchanteur.php";
//
// fin des controles principaux
//
if (!isset($methode))
    $methode = 'debut';

if ($erreur == 0)
{
    switch ($methode)
    {
        case "debut":
            $contenu_page .= '<strong>Un enchanteur vous aborde :</strong><br>';
            //
            // requête pour voir si on a des objets enchantables
            //
            $req = 'select obj_cod,obj_nom
				from objets,perso_objets
				where perobj_perso_cod = ' . $perso_cod . '
				and perobj_identifie = \'O\'
				and perobj_obj_cod = obj_cod
				and obj_enchantable = 1 ';
            $db->query($req);
            if ($db->nf() == 0)
                $contenu_page .= '« <em>Désolé, vous ne possédez aucun objet sur lequel je puisse lancer un enchantement.</em>»';
            else
            {
                $contenu_page .= '« <em>Vous possédez peut être un objet sur lequel je puisse lancer un enchantement, voyons voir.... <br>
				Voici les objets sur lesquels je peux intervenir : </em>»<br>';
                while ($db->next_record())
                    $contenu_page .= '<br><strong><a href="' . $PHP_SELF . '?methode=enc&obj=' . $db->f('obj_cod') . '&type_appel=' . $type_appel . '">' . $db->f('obj_nom') . '</a></strong>';
            }
            $contenu_page .= '<br><br>';
            if ($comp_enchantement == 0)
            {
                $req = "select pquete_param_texte from quete_perso,perso_competences
											where pquete_quete_cod = 15 
											and pquete_nombre = 1
											and pquete_perso_cod = " . $perso_cod;
                $db->query($req);
                if ($db->nf() == 0)
                {
                    $contenu_page .= '« <em>Mais j\'y pense, vous voulez peut-être devenir vous même un enchanteur de renom ?
														<br>Si c\'est le cas Dites le moi, et je vous proposerais une énigme à résoudre pour passer ce premier cap.</em>»
														<br><br>Hum, voilà quelque chose de tentant ! <a href="' . $PHP_SELF . '?methode=niv1&comp=88"><strong>Allez je me lance !</strong></a><br><br>';
                } else
                {
                    $contenu_page .= '« <em>Vous voilà de nouveau ? Vous avez donc bien cogité sur mon problème ?
														<br>Quelle est la solution que vous me proposez ?</em>»<br><br>
														Quel code proposez vous ? <form method="post" action="' . $PHP_SELF . '">
														<input type="hidden" name="methode" value="code">
														<input type="text" name="code">
														<input type="submit" value="Valider" class="test">';
                }
            } else if ($comp_enchantement == 88)
            {
                if ($comp_enchantement_percent < 85)
                {
                    $contenu_page .= '« <em>Vous revoilà déjà ?
													<br>Vous manquez de pratique pour prétendre à ce que je vous apprenne autre chose !
													Revenez donc lorsque vous serez un peu plus expérimenté. 
													<br>L\'enseignement est une chose, la pratique et l\'expérience une autre !
													</em>»
													<br><br>Un niveau minimum de <strong>85%</strong> dans votre compétence en forgeamage est nécessaire avant de pouvoir passer au niveau 2<br><br>';
                } else
                {
                    $contenu_page .= '« <em>Ah, je vois que vous avez investi sur l\'enseignement que je vous avais donné !
														C\'est une bonne chose, et je me verrais ravi de vous en apprendre un peu plus.
													<br>Bon, malheureusement, je manque un peu de moyen en ce moment, et il faudra que vous me fournissiez quelques brouzoufs pour que puisse acheter des composants.
													<br>Donnez moi <strong>10000 brouzoufs</strong>, et je ferais de vous un enchanteur accompli !
													</em>»
													<br><br>Hum, voilà quelque chose de tentant ! <a href="' . $PHP_SELF . '?methode=niv2&comp=102"><strong>Allez je me lance !</strong></a><br><br>';
                }
            } else if ($comp_enchantement == 102)
            {
                if ($comp_enchantement_percent < 100)
                {
                    $contenu_page .= '« <em>Vous revoilà déjà ?
													<br>Vous manquez de pratique pour prétendre à ce que je vous apprenne autre chose !
													Revenez donc lorsque vous serez un peu plus expérimenté. 
													<br>L\'enseignement est une chose, la pratique et l\'expérience une autre !
													</em>»
													<br><br>Un niveau minimum de <strong>100%</strong> dans votre compétence en forgeamage est nécessaire avant de pouvoir passer au niveau 2<br><br>';
                } else
                {
                    $contenu_page .= '« <em>Ah, je vois que vous avez investi sur l\'enseignement que je vous avais donné !
														C\'est une bonne chose, et je me verrais ravi de vous en apprendre un peu plus.
													<br>Bon, malheureusement, je manque un peu de moyen en ce moment, et il faudra que vous me fournissiez quelques brouzoufs pour que puisse acheter des composants.
													<br>Donnez moi <strong>20000 brouzoufs</strong>, et je ferais de vous un enchanteur expérimenté !
													</em>»
													<br><br>Hum, voilà quelque chose de tentant ! <a href="' . $PHP_SELF . '?methode=niv3&comp=103"><strong>Allez je me lance !</strong></a><br><br>';
                }

            } else if ($comp_enchantement == 103)
            {
                $contenu_page .= '« <em>Cher confrère ! Nous pouvons deviser si vous le souhaitez des meilleurs endroits pour lancer nos enchantements !
													<br>Ces vents magiques sont tellement difficiles à capturer ...</em>»
													<br><br>Et l\'enchanteur se lance dans des palabres sans fin ...<br><br>';
            }

            break;
        case "enc":
            //
            // on regarde si l'objet est bien enchantable, et quels enchantements on peut lui associer
            //
            include "blocks/_enchanteur_enc.php";
            break;
        case "niv1": // Code aléatoire
            $req = "select pquete_param_texte from quete_perso,perso_competences
											where pquete_quete_cod = 15 
											and pquete_nombre = 1
											and pquete_perso_cod = " . $perso_cod;
            $db->query($req);
            if ($db->nf() == 0)
            {
                $req = "select enchanteur(" . $perso_cod . "," . $comp . ") as resultat";
                $db2->query($req);
                $db2->next_record();
                $contenu_page .= $db2->f('resultat');
            } else
            {
                $contenu_page .= '« <em>Vous voilà de nouveau ? Vous avez donc bien cogité sur mon problème ?
														<br>Quelle est la solution que vous me proposez ?</em>»<br><br>
														Quel code proposez vous ? <form method="post" action="' . $PHP_SELF . '">
														<input type="hidden" name="methode" value="code">
														<input type="text" name="code">
														<input type="submit" value="Valider" class="test">';
            }

            break;
        case "niv2": // 10000 brouzoufs et limite comp
            $req = "select enchanteur(" . $perso_cod . "," . $comp . ") as resultat";
            $db->query($req);
            $db->next_record();
            $contenu_page .= $db->f('resultat');
            break;
        case "niv3": // 20000 brouzoufs et limite comp
            $req = "select enchanteur(" . $perso_cod . "," . $comp . ") as resultat";
            $db->query($req);
            $db->next_record();
            $contenu_page .= $db->f('resultat');
            break;
        case "code":
            $code = $_POST['code'];
            $req = "select pquete_param_texte,perso_pa from quete_perso,perso
											where pquete_quete_cod = 15 
											and pquete_perso_cod = " . $perso_cod . "
											and perso_cod = pquete_perso_cod
											and pquete_nombre = 1";
            $db->query($req);
            $db->next_record();
            if ($db->nf() == 0)
            {
                $contenu_page .= 'Vous n\'avez rien à faire ici !';
                break;
            } else if ($db->f('perso_pa') != 12)
            {
                $contenu_page .= 'Vous n\'avez pas suffisamment de PA pour réaliser cette action !';
                break;
            } else if ($code == $db->f('pquete_param_texte'))
            {
                //Mise à jour de la comp enchanteur
                $req2 = "select enchanteur(" . $perso_cod . ",88) as resultat";
                $db2->query($req2);
                $db2->next_record();
                $contenu_page .= '« <em>' . $db2->f('resultat') . '</em>»<br><br>
																		Vous bénéficiez maintenant d\'une nouvelle compétence. Bonne découverte !';
            } else
            {
                $contenu_page .= '« <em>Hum, je crois qu\'il y a méprise, vous n\'y êtes pas du tout !
														<br>Prenez un peu de temps pour réfléchir un peu plus ...</em>»<br><br>';
                $req2 = "update perso set perso_pa = perso_pa - 6 where perso_cod = " . $perso_cod;
                $db2->query($req2);
                $db2->next_record();
            }
            break;
    }

}

