<?php
function getcontacts($term)
{
    $query = getDb()->prepare(
        "SELECT `username` FROM `user` WHERE contact LIKE CONCAT('%',?,'%') or WHERE username LIKE CONCAT('%',?,'%') "
    );

    $query->bind_param("is", $term, $term);
    $query->execute();
    $query->store_result();

    $query->bind_result($username);

    $resposta = $query->fetch_all();

    echo json_encode($resposta);
    // $return = [];
    // echo $return;
}
