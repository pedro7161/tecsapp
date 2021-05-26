<?php
function login($username, $password)
{
    echo "LOGGING";
    $verifyuser = getDb()->prepare("SELECT * FROM `user` WHERE username=? OR email=?;");
    $verifyuser->bind_param("ss", $username, $username);

    $result = $verifyuser->execute();

    if (!$result) {
        login_fail();
    }

    $user = $verifyuser->get_result();

    if (!$user) {
        login_fail();
    }

    $user = $user->fetch_assoc();

    if (isset($user) && sizeof($user) > 0) {
        if (password_verify($password, $user["pass"])) {
            $_SESSION["username"] = $user["username"];
            login_ok();
        } else {
            login_fail();
        }
    } else {
        login_fail();
    }
}

function login_ok()
{
    echo json_encode(array("message", "ok"));
}

function login_fail()
{
    echo json_encode(array("message", "error trying to login"));
}
