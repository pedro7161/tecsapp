<?php
// header("Content-Type: application/json");
// include('includes/json.php');
// mysqli_report(MYSQLI_REPORT_ALL);
require("connectbase.php");
require("lib\api\login.php");
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
        $pass = password_hash($_POST["password"], PASSWORD_DEFAULT),
        $_POST["confirm_password"],
        $_POST["contact"]
    );
}

// login
if (isset($_POST["username"]) && isset($_POST["password"])) {
    login($_POST["username"], $_POST["password"]);
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
        getcontacts();
    }
}
