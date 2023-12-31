<?php

require('api/config/database.php');
require('api/objects/user.php');

session_name("konsulent_huset");
session_start();

if (!in_array($_SESSION['rolesId'], [1, 2])) {
    header("Location: /konsulent-huset/404");
    exit();
}

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

// set ID property of record to read
$user->userId = $userId;

$user->getById();

if ($user != null) {
    $users_arr = array(
        "userId" => $user->userId,
        "firstName" => $user->firstName,
        "lastName" => $user->lastName,
        "email" => $user->email,
        "created" => $user->created,
        "modified" => $user->modified,
        "rolesId" => $user->rolesId,
    );

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($users_arr);
} else {

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user wasnt found
    echo json_encode(
        array("message" => "No users found.")
    );

    // log no user found
    trigger_error("Was unable to find user by Id: " . $userId, E_USER_WARNING);

}