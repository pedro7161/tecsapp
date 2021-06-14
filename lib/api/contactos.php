<?php

function getcontacts($term)
{
    require("connectbase.php");
    $query = $db->prepare(
        "SELECT `nome` FROM `cliente` WHERE contacto LIKE CONCAT('%',?,'%') or nome LIKE CONCAT('%',?,'%'); "
    );

    $query->bind_param("is", $term, $term);
    $query->execute();
    // $query->store_result();
    $query->bind_result($username);
    // $return = [];

    // $query->fetch();
    while ($query->fetch()) {
        $nomes[] = array("username" => $username);
    }
    echo json_encode($nomes);
    // echo json_encode($return);


    // echo json_encode($query);
    // $return = [];
    // echo $return;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function addContact($user)
{
    require("connectbase.php");
    echo $onlinne;
    $query = $db->prepare(
        "SELECT ID from `cliente` where nome=?"
    );


    $query2 = $db->prepare(
        "INSERT INTO  `contactos` (ID_Client,ID_Friend) VALUES(?,?) "
    );
    $cheat1 = "type";
    $cheat2 = "date";
    $body = "Hi!!";

    $query3 = $db->prepare(
        "INSERT INTO  `mensagens` (body,ler, $cheat1,id_sender,id_receiver,$cheat2) VALUES($body,'0','enviado',?,?,datetime.now) "
    );
    $query4 = $db->prepare(
        "SELECT ID from `cliente` where nome=?"
    );

    $query->bind_param("s", $user);
    $result = $query->execute();
    if (!$result) {
        echo "ERROR: Trying to verify user";
        return;
    }
    $user = $query->get_result();
    if (!$user) {

        echo "ERROR: Trying to get result from db";
        return;
    }
    $user = $user->fetch_assoc();
    $query4->bind_param("s", $onlinne);
    $result = $query->execute();
    if (!$result) {
        echo "ERROR: Trying to verify user";
        return;
    }
    $online = $query->get_result();
    if (!$online) {

        echo "ERROR: Trying to get result from db";
        return;
    }
    $online = $online->fetch_assoc();



    $query2->bind_param("ss", $online["ID"], $user["ID"]);
    $query3->bind_param("ss", $online["ID"], $user["ID"]);
}


// function getcontacts($term)
// {
//     echo $term;
//     require("connectbase.php");
//     $query = $db->prepare(
//         'SELECT `username` FROM `user` WHERE contact LIKE CONCAT(" % ",?," % ") or username LIKE CONCAT(" % ",?," % ");'
//     );

//     $query->bind_param("is", $term, $term);
//     $query->execute();
//     $query->store_result();

//     $query->bind_result($username);


//     $return = [];
//     $return["username"] = json_encode($query);
//     echo json_encode($return);
//     // $return = [];
//     // echo $return;
// }