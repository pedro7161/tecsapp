<?php


function login($username, $password)
{
    require("connectbase.php");
    //Operação de Login

    //Verificação se os campos username e pass foram realmente enviados
    echo $username;
    $onlinne = $username;
    global $onlinne;
    // $username = $_POST["username"]; usar no functions.php para funcionar
    // $password = $_POST["password"];
    echo $username;
    $verifyuser = $db->prepare("SELECT * FROM `Cliente` WHERE nome=?;");

    $verifyuser->bind_param("s", $username);

    $result = $verifyuser->execute();
    echo json_encode((array($result)));
    echo "////////";


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
    header("Location: /projecto_a");
}


// function user_loggedin()
// {
//     if (isset($_SESSION) && isset($_SESSION["username"])) {
//         return true;
//     } else {
//         return false;
//     }
// }














//     require("connectbase.php");

//     $email = $username;
//     $verifyuser = $db->prepare("SELECT * FROM `user` WHERE username=? OR email=?;");

//     $verifyuser->bind_param('ss', $username, $email);

//     $result = $verifyuser->execute();

//     echo json_encode((array($verifyuser)));

//     if (!$result) {
//         echo ("ola");
//         login_fail("1");
//     }


//     $user = $verifyuser->get_result();
//     if (!$user) {
//         login_fail("2");
//     }

//     $data = $user->fetch_assoc();

//     if ($data) {
//         if (password_verify($password, $data["pass"])) {
//             $_SESSION["username"] = $data["username"];
//             login_ok();
//         } else {
//             login_fail("3");
//         }
//     } else {
//         login_fail();
//     }
// }

// function login_ok()
// {
//     echo json_encode(array("message", "ok"));
//     exit;
// }

// function login_fail($code = 0)
// {

//     echo json_encode(array("message", "error trying to login (code $code)"));
//     exit;
// }
