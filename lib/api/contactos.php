<?php

function getcontacts($term)
{
    require("connectbase.php");
    $query = $db->prepare(
        "SELECT `username` FROM `user` WHERE contact LIKE CONCAT('%',?,'%') or username LIKE CONCAT('%',?,'%'); "
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