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

    $friend = $db->prepare(
        "SELECT ID from `cliente` where nome=?"
    );
    $friend->bind_param("s", $user);
    $result = $friend->execute();
    if (!$result) {
        echo "ERROR: Trying to verify user";
        return;
    }
    $user = $friend->get_result();
    if (!$user) {

        echo "ERROR: Trying to get result from db";
        return;
    }
    $user = $user->fetch_assoc();
    // // // // // //////////////////////////////////////////////////////////////////
    $ativo = $db->prepare(
        "SELECT ID from `cliente` where isonline='1';"
    );

    $result = $ativo->execute();


    $online = $ativo->get_result();
    if (!$online) {

        echo "ERROR: Trying to get result from db";
        return;
    }
    $online = $online->fetch_assoc();
    ///////////////////////////////////////////////////////////////////////
    $query2 = $db->prepare(
        "INSERT INTO  `contactos` (ID_Client,ID_Friend) VALUES(?,?) "
    );
    $query2->bind_param("ss", $online["ID"], $user["ID"]);
    $result = $query2->execute();
    if (!$result) {
        echo "ERROR: Trying to insert user";
        return;
    }
    ////////////////////////////////////////////////////////////////////



    $query3 = $db->prepare(
        "INSERT INTO  `mensagens` (body,ler, 'type',id_sender,id_receiver,'date') VALUES('Hi!!','0','enviado','?','?',CURRENT_TIMESTAMP) "
    );
    $eu = $online["ID"];
    $friend = $user["ID"];
    $query3->bind_param("ii", $eu, $friend);
    $result = $query3->execute();
    if (!$result) {
        echo "ERROR: Trying to send message to user";
        return;
    }
    $enviado[] = array("message" => "Contact added sucessfully");
    echo json_encode($enviado);
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