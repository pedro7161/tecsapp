<?php

function registo($user, $email, $pass, $c_confirm, $contact)
{
    require("connectbase.php");
    // $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $newuserquery = $db->prepare("INSERT INTO `user`(`username`,`pass`,`email`,`contact`) VALUES (?,?,?,?)");

    $newuserquery->bind_param("ssss", $user, $pass, $email, $contact);

    if ($pass == $c_confirm) {
        $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
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
