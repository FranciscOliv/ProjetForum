<?php
/**
 * Auteur: Fonseca de Oliveira, Francisco Daniel
 * Classe: I.DA-P3B
 * Année 2018-2019
 * Projet : Forum
 * Version : 1.0.0
 */
require_once "security.inc.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isLogged()) {
    header("Location:main.php");
    exit;
}

// Initialisation de tous les champs de la session lors de la 1ère ouverture
//VARIALES DANS LA SESSION
if (!isset($_SESSION['logged'])) {

    //Session vide -> créer tous les champs
    $_SESSION['logged'] = FALSE;
    $_SESSION['username'] = "";
    $_SESSION['index'] = 0;
    $_SESSION['users'] = array();
}

$errors = array();

if (filter_has_var(INPUT_POST, 'login')) {
    $username = filter_input(INPUT_POST, 'idLogin', FILTER_SANITIZE_STRING);
    $pwd = filter_input(INPUT_POST, 'pwdLogin', FILTER_SANITIZE_STRING);

    if(empty($username)){
        $errors['username'] = "Veuillez tapez votre username";
    }
    if(empty($pwd)){
        $errors['password'] = "Veuillez tapez votre password";
    }
    $userOk = false;

    if (usernameVerify($username)) {
        $_SESSION['username'] = $username;
        if (passwordVerify($_SESSION['index'], $pwd)) {

            $_SESSION['logged'] = TRUE;
            header("Location:main.php");
            exit;
        } else {
            $errors['username'] = "Le mot de passe ne correspond pas.";
        }

    } else {
        $errors['password'] = "Votre identifiant n'existe pas. Inscrivez vous!";
    }
}

function usernameVerify($username)
{
    $_SESSION['index'] = -1;
    $userOk = false;
    foreach ($_SESSION['users'] as $array) {
        $_SESSION['index']++;
        if ($array[2] == $username) {
            $userOk = true;
            break;
        }
    }
    return $userOk;

}

function passwordVerify($index, $pwd)
{
    $pwdOk = false;
    if ($_SESSION['users'][$index][3] == $pwd) {
        $pwdOk = true;
    }

    return $pwdOk;
}