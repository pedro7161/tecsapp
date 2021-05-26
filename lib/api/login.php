<?php
require "connectbase.php";

function login($username, $password)
{
    $verifyuser = getDb()->prepare("SELECT username, pass FROM user WHERE username='?' OR email='?';");
    $email = $username;
    $verifyuser->bind_param('ss', $username, $email);

    $result = $verifyuser->execute();

    if (!$result) {
        login_fail("1");
    }

    $result = $verifyuser->get_result();

    if (!$result) {
        login_fail("2");
    }

    $data = $result->fetch_assoc();

    if ($data) {
        if (password_verify($password, $data["pass"])) {
            $_SESSION["username"] = $data["username"];
            login_ok();
        } else {
            login_fail("3");
        }
    } else {
        login_fail();
    }
}

function login_ok()
{
    echo json_encode(array("message", "ok"));
    exit;
}

function login_fail($code = 0)
{
    echo json_encode(array("message", "error trying to login (code $code)"));
    exit;
}
