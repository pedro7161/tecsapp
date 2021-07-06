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


    // bind param nao funciona nem com SS nem com II            S=string i=integer
    //o erro provavel dever ser no sql em si para conseguir inserir a hora local exata

    $query3 = $db->prepare(
        "INSERT INTO  `mensagens` (body,ler,type,id_cliente,date) VALUES('Hi!!','0','sent',?,CURRENT_TIMESTAMP()); "
    );

    $query3->bind_param("i", $online["ID"]);
    $query3->execute();
    $teste = $query3->get_result();
    echo $teste;
    if (!$result) {
        $enviado[] = array("message" => "add contact failed!");
        echo json_encode($enviado);
        return;
    }
    $enviado[] = array("message" => "Contact added sucessfully");
    $query3again = $db->prepare(
        "INSERT INTO  `mensagens` (body,ler,type,id_cliente,date) VALUES('Hi!!','0','received',?,CURRENT_TIMESTAMP()); "
    );

    $query3again->bind_param("i", $user["ID"]);
    $query3again->execute();
    echo json_encode($enviado);

    // $checkenviado = $db->prepare(
    //     "SELECT * from mensagens where = "
    // );

    // $checkenviado->bind_param("ss",  $user["ID"], $online["ID"],);
    // $checkenviado->execute();
    // $checkenviado = $db->prepare(
    //     "INSERT INTO  `mensagens` (body,ler,type,id_sender,id_receiver,date) VALUES('Hi!!','0','received',?,?,CURRENT_TIMESTAMP()); "
    // );

    // $recebido->bind_param("ss",  $user["ID"], $online["ID"],);
    // $recebido->execute();
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