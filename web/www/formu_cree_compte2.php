<?php
if (!isset($_POST['nom']) || !isset($_POST['mail']))
{
    $template = $twig->load('formu_cree_compte.twig');
    $options_twig = array(
        'ERROR_MESSAGE' => "Erreur de paramètres : nom de compte ou adresse électronique non renseignés"
    );
    echo $template->render(array_merge($options_twig_defaut, $options_twig));
    die('');
}


$recherche = "SELECT f_cherche_compte('$nom') as recherche";
$db->query($recherche);
$db->next_record();
$tab_nom[0] = $db->f("recherche");
if ($tab_nom[0] != -1)
{
    $template = $twig->load('formu_cree_compte.twig');
    $options_twig = array(
        'ERROR_MESSAGE' => "Un aventurier porte déjà ce nom"
    );
    echo $template->render(array_merge($options_twig_defaut, $options_twig));
    die('');
}


if ($compte->getBy_compt_mail(strtolower($mail)))
{
    $template = $twig->load('formu_cree_compte.twig');
    $options_twig = array(
        'ERROR_MESSAGE' => "Un compte existe déjà avec cette adresse mail"
    );
    echo $template->render(array_merge($options_twig_defaut, $options_twig));
    die('');
}


if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
{
    $template = $twig->load('formu_cree_compte.twig');
    $options_twig = array(
        'ERROR_MESSAGE' => "Adresse mail non valide"
    );
    echo $template->render(array_merge($options_twig_defaut, $options_twig));
    die('');
}

if (!isset($regles) || $regles != 1)
{
    $template = $twig->load('formu_cree_compte.twig');
    $options_twig = array(
        'ERROR_MESSAGE' => "Vous devez accepeter la charte des joueurs pour continuer"
    );
    echo $template->render(array_merge($options_twig_defaut, $options_twig));
    die('');
}

$recherche = "select  lancer_des(1, 10000) as des";
$db->query($recherche);
$db->next_record();
$validation = $db->f("des");
// calcul du nombre aléatoire pour validation
$compte->compt_nom = $nom;
$compte->compt_mail = strtolower($mail);
$compte->compt_password = '';
$compte->compt_validation = $validation;
$compte->compt_actif = 'N';
$compte->compt_dcreat = date('Y-m-d H:i:s');
$compte->compt_acc_charte = 'O';
$compte->compt_type_quatrieme = 2;
$compte->compt_passwd_hash = crypt($pass1);
$compte->stocke(true);


// on prépare le texte du mail
$template = $twig->load('mails/forum_cree_compte2.twig');

$options_twig = array(
    'TYPE_FLUX'  => $type_flux,
    'URL'        => G_URL,
    'NOM'        => $nom,
    'VALIDATION' => $validation
);
$corps_mail = $template->render(array_merge($options_twig_defaut, $options_twig));

$mail = new PHPMailer;
// smtp
$mail->Host = SMTP_HOST;
$mail->Port = SMTP_PORT;
if (!empty(SMTP_USER))
{
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASSWORD;
}

$mail->IsHTML(true);
$mail->IsHTML(true);
$mail->CharSet = 'utf-8';
$mail->From = 'noreply@jdr-delain.net';
$mail->FromName = 'Le robot des souterrains';
$mail->AddAddress($compte->compt_mail);
$mail->Subject = 'Inscription à Delain';
$mail->Body = $corps_mail;
if ($mail->Send())
{
    $template = $twig->load('formu_cree_compte2.twig');
    $options_twig = array(
        'MAIL' => $compte->compt_mail
    );
    echo $template->render(array_merge($options_twig_defaut, $options_twig));
} else
{
    $template = $twig->load('formu_cree_compte2.twig');
    $options_twig = array(
        'ERROR_MESSAGE' => print_r($smtp->errors, true)
    );
    echo $template->render(array_merge($options_twig_defaut, $options_twig));
}
unset($mail);


