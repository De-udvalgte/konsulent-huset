<?php

require('api/config/database.php');
require('api/objects/product.php');

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$product = new Product($db);

// get posted data
//$data = json_decode(file_get_contents("php://input"));

// set product property values
$product->productName = $_POST["productName"];
$product->productDesc = $_POST["productDesc"];
$product->productTitle = $_POST["productTitle"];
$product->price = $_POST["price"];


// create the product
if(
    !empty($product->productName) &&
    !empty($product->productDesc) &&
    !empty($product->productTitle) &&
    !empty($product->price) &&
    $product->create()
){
    // set response code
    http_response_code(200);
    header("Location: /konsulent-huset/products");
    // display message: product was created
    echo json_encode(array("message" => "Product was created."));
}
// message if unable to create product
else{
    // set response code
    http_response_code(400);
    // display message: unable to create product
    echo json_encode(array("message" => "Unable to create product."));
}