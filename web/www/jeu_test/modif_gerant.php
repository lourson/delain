<?php
include "blocks/_header_page_jeu.php";
ob_start();
include "blocks/_test_admin_echoppe.php";
if (!isset($methode))
{
    $methode = "entree";
}
$_admin_echoppe_type = "";
include "blocks/_admin_echoppe.php";
include "blocks/_footer_page_jeu.php";