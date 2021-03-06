<?php
// header("Content-Type: application/json");
// include('includes/json.php');
// mysqli_report(MYSQLI_REPORT_ALL);
require("connectbase.php");
require("lib/api/login.php");
require("lib/api/registo.php");
require("lib/api/mensagens.php");
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
// o login nao funciona em funçao  devido a perder-se o valor do username
if (isset($_POST["username"]) && isset($_POST["password"])) {

    // login($_POST["username"], $_POST["password"]); por alguma razao a variavel vai root0 e nao encontra o utelizador porque nao existe um utelizador root'

    $username = $_POST["username"];
    $password = $_POST["password"];
    $onlinne = $username;
    echo $onlinne;
    echo $username;
    $meteron = $db->prepare("UPDATE cliente SET isonline = '1' WHERE nome=?; ");
    $meteron->bind_param("s", $username);
    $meteron->execute();
    $meteroff = $db->prepare("UPDATE cliente SET isonline = '0' WHERE NOT nome=?; ");
    $meteroff->bind_param("s", $username);
    $meteroff->execute();
    $verifyuser = $db->prepare('SELECT * FROM `cliente` WHERE nome=?;');

    $verifyuser->bind_param("s", $username);

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

        if (password_verify($password, $user["password"])) {
            $_SESSION["username"] = $user["username"];
            header("Location: \Project_Final\chat.html");
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
if (isset($_GET["action"]) && $_GET["action"] == "addContact") {
    if (isset($_GET["user"])) {

        addContact($_GET["user"]);
    }
}

if (isset($_GET["action"]) && $_GET["action"] == "getMessages") {
    getMessages();
}
if (isset($_GET["action"]) && $_GET["action"] == "getChat") {
    if (isset($_GET["user"])) {
        getChat($_GET["user"]);
    }
}

if (isset($_POST["action"]) && $_POST["action"] == "sendMessage") {
    if (isset($_POST["body"]) && isset($_POST["user"])) {
        sendMessage($_POST["body"], $_POST["user"]);
    }
}
if (isset($_GET["action"]) && $_GET["action"] == "getLoggedUser") {
    GetLoggedUser();
}
