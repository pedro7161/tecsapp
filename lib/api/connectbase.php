<?php

// function getDb()
// {
$endservidor = "";
$username = "root";
$password = "";

$db = new mysqli($endservidor, $username, $password, "webwhats");
if ($db->connect_error) {
    die("Connection error:" . $db->connect_error);
}

return $db;
// }
