<?php
// header("Content-Type: application/json");
// include('includes/json.php');
// mysqli_report(MYSQLI_REPORT_ALL);
require("lib\Connection\connectbase.php");

session_start();

if (isset($_GET["operation"]) && $_GET["operation"] == "getcontacts") {
    echo ("ola2");
    if (isset($_GET["usernamef"])) {
        $searchuserquery = $db->prepare(
            "SELECT `username` FROM `user` WHERE username LIKE CONCAT('%',?,'%') "
        );

        $searchuserquery->bind_param("s", $_GET["usernamef"]);
        $searchuserquery->execute();
        $resultset = $searchuserquery->get_result();
        $result = $resultset->fetch_all();

        // if (!$result) {
        //     $response = array(
        //         'status' => false,
        //         'message' => 'An error occured...'
        //     );
        // } else {
        //     $response = array(
        //         'status' => true,
        //         'message' => 'Success',
        //         'data' => pg_fetch_all($result)
        //     );
        // }
        echo json_encode($result);
        // echo ($result);
        return $resultset;
    }
}
