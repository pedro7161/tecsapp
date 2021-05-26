<?php
// header("Content-Type: application/json");
// include('includes/json.php');
// mysqli_report(MYSQLI_REPORT_ALL);
require("connectbase.php");

session_start();


//registo
if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])   && isset($_POST["contact"])) {

    $newuserquery = $db->prepare("INSERT INTO `user`(`username`,`pass`,`email`,`contact`) VALUES (?,?,?,?)");
    $user = $_POST["username"];
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = $_POST["email"];
    $contact = $_POST["contact"];
    $newuserquery->bind_param("ssss", $user, $pass, $email, $contact);

    if ($_POST["password"] == $_POST["confirm_password"]) {




        $result = $newuserquery->execute();
        echo ("inserido");
        if ($result) {
            echo "<script>alert('user added successfully');</script>";
            header("Location:/Project_Final/login.html");
            return;
        } else {
            echo "<script>alert('error trying to register');</script>";
            return;
        }
    } else {
        echo "<script>alert('Password mismatch');</script>";
        return;
    }
}
// login
if (isset($_POST["username"]) && isset($_POST["password"])) {

    $verifyuser = $db->prepare("SELECT * FROM `user` WHERE username=? OR email=?;");

    $verifyuser->bind_param("ss", $_POST["username"], $_POST["username"]);

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

        if (password_verify($_POST["password"], $user["pass"])) {
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


if (isset($_GET["action"]) && $_GET["action"] == "getcontacts") {

    if (isset($_GET["term"])) {
        if (is_numeric($_GET["term"])) {
            $searchuserquery = $db->prepare(
                "SELECT `username` FROM `user` WHERE contact LIKE CONCAT('%',?,'%') "
            );
        } else {
            $searchuserquery = $db->prepare(
                "SELECT `username` FROM `user` WHERE username LIKE CONCAT('%',?,'%') "
            );
        }
        $termo = $_GET["term"];
        $searchuserquery->bind_param("s", $termo);
        $searchuserquery->execute();
        $searchuserquery->store_result();
        $num_of_rows = $searchuserquery->num_rows;

        $searchuserquery->bind_result($username);
        $i = 0;

        for ($i = 0; $i < $searchuserquery->fetch(); $i++) {
            $resposta[$i] = $username;
            echo  $resposta[$i];
        }
    }
}
