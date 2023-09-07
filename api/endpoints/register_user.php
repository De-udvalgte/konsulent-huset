<?php

require('api/config/database.php');
require('api/objects/user.php');

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->firstName = $data->firstName;
$user->lastName = $data->lastName;
$user->email = $data->email;
$user->password = $data->password;


if (
    !empty($user->firstName) &&
    !empty($user->lastName) &&
    !empty($user->email) &&
    !empty($user->password) &&
    $user->create()

) {

    // set response code
    http_response_code(200);
    header("Location: login");
    // display message: product was created
    echo json_encode(array("message" => "User was created."));
}
// message if unable to create product
else {
    // set response code
    http_response_code(400);
    // display message: unable to create product
    echo json_encode(array("message" => "Unable to create user."));
}
