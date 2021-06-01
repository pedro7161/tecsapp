<?php
// header("Content-Type: application/json");
// include('includes/json.php');
// mysqli_report(MYSQLI_REPORT_ALL);
require("connectbase.php");
require("lib/api/login.php");
require("lib/api/registo.php");
require("lib\api\contactos.php");
session_start();

//registo
if (
    isset($_POST["username"]) &&
    isset($_POST["email"]) &&
    isset($_POST["password"]) &&
    isset($_POST["confirm_password"]) &&
    isset($_POST["contact"])
) {

    registo(
        $_POST["username"],
        $_POST["email"],
        $_POST["password"],
        $_POST["confirm_password"],
        $_POST["contact"]
    );
}

// login
if (isset($_POST["username"]) && isset($_POST["password"])) {

    // login($_POST["username"], $_POST["password"]);
    echo $username;
    $username = $_POST["username"];
    $password = $_POST["password"];
    $verifyuser = $db->prepare('SELECT * FROM `user` WHERE username=? OR email=?;');

    $verifyuser->bind_param("ss", $username, $username);

    $result = $verifyuser->execute();

    if (!$result) {
        echo "ERROR: Trying to verify user";
        return;
    }

    $user = $verifyuser->get_result();

    if (!$user) {

        echo "ERROR: Trying to get result from db";
        return;
    }

    $user = $user->fetch_assoc();

    if (isset($user) && sizeof($user) > 0) {
        //echo var_dump($_POST);
        //echo var_dump($user);

        if (password_verify($password, $user["pass"])) {
            $_SESSION["username"] = $user["username"];
            header("Location: /projecto_a");
        } else {
            echo "Password incorrecta";
        }
    } else {
        echo "O utilizador não existe";
    }
}

//O que significa $_ => Variáveis do tipo SuperGlobal
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location:/Project_Final");
}

//login

function user_loggedin()
{
    if (isset($_SESSION) && isset($_SESSION["username"])) {
        return true;
    } else {
        return false;
    }
}

//-------------------------------------------------------------------------------------------------------------
if (isset($_GET["action"]) && $_GET["action"] == "getcontacts") {
    if (isset($_GET["term"])) {
        getcontacts($_GET["term"]);
    }
}
