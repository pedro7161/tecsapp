<?php
function getMessages()
{
    //  [{"online":1,"contact_name":"joao","body":"ola","sent_time":"2020-05-18 11:23:29","receiver_id":"1","sender_id":"2"},{"online":"0","contact_name":"teste","body":"teste ola","sent_time":"2020-05-18 11:35:13","receiver_id":"4","sender_id":"2"}]
    require("connectbase.php");
    $query = $db->prepare(
        "SELECT `ID` from `cliente` where isonline='1';"
    );

    $query->execute();


    $online = $query->get_result();
    $online = $online->fetch_assoc();


    $query2 = $db->prepare(
        "SELECT * from `mensagens` Inner join `cliente` ON mensagens.id_cliente=cliente.ID where id_cliente=? and type ='sent' ORDER BY `date` ASC; "
    );
    $query2->bind_param("i", $online["ID"]);
    $query2->execute();
    $teste = $query2->get_result();
    // $enviado = $teste->fetch_assoc();

    $query3 = $db->prepare(
        "SELECT * from `mensagens` Inner join `cliente` ON mensagens.id_cliente=cliente.ID where id_cliente=? and type ='received' ORDER BY `date` ASC; "
    );
    $query3->bind_param("i", $online["ID"]);
    $query3->execute();
    $teste2 = $query3->get_result();

    // $recebido = $teste2->fetch_assoc();
    while ($enviado = $teste->fetch_assoc()) {

        $msginf[] = array("online" => $enviado["isonline"], "contact_name" => $enviado["nome"], "body" => $enviado["body"], "sent_time" => $enviado["date"], "receiver_id" => $enviado["id_cliente"], "sender_id" => $enviado["id_cliente"]);
    }

    // echo json_encode($msginf);
    while ($enviado = $teste->fetch_assoc() && $recebido = $teste->fetch_assoc()) {

        $msginf[] = array("online" => $enviado["isonline"], "contact_name" => $enviado["nome"], "body" => $enviado["body"], "sent_time" => $enviado["date"], "receiver_id" => $enviado["id_cliente"], "sender_id" =>  $recebido["id_cliente"]);
    }
    echo json_encode($msginf);
}
function getChat($user)
{
    require("connectbase.php");
    $query = $db->prepare(
        "SELECT `ID` from `cliente` where isonline='1';"
    );

    $query->execute();


    $online = $query->get_result();
    $online = $online->fetch_assoc();

    $query3 = $db->prepare(
        "SELECT `ID` from `cliente` where nome=?;"
    );
    $query3->bind_param("s", $user);
    $query3->execute();


    $recei = $query3->get_result();
    $recei = $recei->fetch_assoc();

    $query2 = $db->prepare(
        "SELECT * from `mensagens` Inner join `cliente` ON mensagens.id_cliente=cliente.ID where id_cliente=? and id_cliente=? ORDER BY `date` ASC;"
    );
    $query2->bind_param("ii", $online["ID"], $recei["ID"]);
    $result = $query2->execute();
    if (!$result) {
        $enviado[] = array("message" => "no messages");
        echo json_encode($enviado);
        return;
    }
    $teste = $query2->get_result();

    // $ordem = $teste->fetch_all();

    while ($ordem = $teste->fetch_assoc()) {

        $msginf[] = array("type" => $ordem["type"], "body" => $ordem["body"], "read" => $ordem["ler"]);
    }
    echo json_encode($msginf);



    // echo json_encode($msginf2);
}
function sendMessage($body, $user)
{

    require("connectbase.php");
    $query = $db->prepare(
        "SELECT `ID` from `cliente` where isonline='1';"
    );

    $query->execute();


    $online = $query->get_result();
    $online = $online->fetch_assoc();

    $query3 = $db->prepare(
        "SELECT `ID` from `cliente` where nome=?;"
    );
    $query3->bind_param("s", $user);
    $query3->execute();


    $recei = $query3->get_result();
    $recei = $recei->fetch_assoc();



    $queryF = $db->prepare(
        "INSERT INTO  `mensagens` (body,ler,type,id_cliente,date) VALUES(?,'0','sent',?,CURRENT_TIMESTAMP()); "
    );

    $queryF->bind_param("si", $body, $online["ID"]);
    $queryF->execute();
    $queryR = $db->prepare(
        "INSERT INTO  `mensagens` (body,ler,type,id_cliente,date) VALUES(?,'0','received',?,CURRENT_TIMESTAMP()); "
    );

    $queryR->bind_param("si", $body, $recei["ID"]);
    $queryR->execute();
}
