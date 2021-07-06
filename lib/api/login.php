<?php


function login($username, $password)
{
    require("connectbase.php");
    // $username = $_POST["username"];
    // $password = $_POST["password"];

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

function GetLoggedUser()
{
    require("connectbase.php");
    $query = $db->prepare(
        "SELECT * from `cliente` where isonline='1';"
    );

    $result = $query->execute();
    if (!$result) {
        $msginf[] = array("message" => "no messages");
        echo json_encode($msginf);
        return;
    }

    $online = $query->get_result();
    $online = $online->fetch_assoc();
    $msginf[] = array("username" => $online["nome"], "message" => "ok");
    echo json_encode($msginf);
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
