<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $server['REQUEST_METHOD'];

    if ($method = 'OPTIONS') {
        header ('Access-Control_Allow-Methods: GET, POST, PUT, DELETE');
        header ('Access-Control_Allow-headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }