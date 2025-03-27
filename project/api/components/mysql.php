<?php
require "HttpResponse.php";

$user = "circuitforge";
$db = "circuitforge";
$port = 3306;
$password = "123";
$server = "db_server";


try {
    $conn = new mysqli($server, $user, $password, $db);

    if ($conn->connect_error) {
        (new HttpResponse(500, "Internal Server Error"))->sendResponse();
    }
} catch (Exception $e) {
    (new HttpResponse(500, "Internal Server Error"))->sendResponse();
}
