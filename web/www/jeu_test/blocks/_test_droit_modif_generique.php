<?php
/**
 * Created by PhpStorm.
 * User: steph
 * Date: 20/12/18
 * Time: 17:33
 * -------------------------------------------
 * La variable $droit_modif doit être déclarée
 * Retourne la valeur de $erreur =>
 *   0 ok
 *   1 pas ok
 */
$erreur = 0;
$req = "select * from compt_droit where dcompt_compt_cod = $compt_cod ";
$db->query($req);
if ($db->nf() == 0)
{
    $erreur = 1;
} else
{
    $db->next_record();
    if ($db->f($droit_modif) != 'O')
    {
        $erreur = 1;
    }
}