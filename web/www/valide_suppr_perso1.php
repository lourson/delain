<?php
include "includes/classes.php";
include "ident.php";

// ces deux lignes sont temporaires
// sans ça, le variable_menu ne fonctionne pas
include_once 'includes/template.inc';
$t = new template;

include(G_CHE . '/jeu_test/variables_menu.php');

$perso_cible = 1 * $_REQUEST['perso'];

$class_perso_cible = new perso;
$class_perso_cible->charge($perso_cible);

$compte = new compte();
$compte->charge($compt_cod);

//$logger->debug('Perso_cible ' . $class_perso_cible);


$template     = $twig->load('valide_suppr_perso1.twig');
$options_twig = array(
'COMPTE'       => $compte,
'PERSO_CIBLE'    => $class_perso_cible
);


echo $template->render(array_merge($var_twig_defaut, $options_twig));
