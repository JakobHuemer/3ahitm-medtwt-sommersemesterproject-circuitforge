<?php

header("Content-Type: application/json; charset=utf-8");

//echo "This is index";
//
//// echo current url
//
//echo "<br>";
//echo "Current URL: " . $_SERVER['REQUEST_URI'];

$res = [
    "on" => "index.php",
    "route" => $_SERVER['REQUEST_URI'],
];

echo json_encode($res);