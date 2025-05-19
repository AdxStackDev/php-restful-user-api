<?php  
header('Content-Type: application/json');
require_once('src/db.php');
require_once('src/functions.php');

$request_type = $_SERVER['REQUEST_METHOD'];

switch ($request_type) {

    case 'POST':
        Functions::addnewuser($con);
        break;

    case 'GET':
        Functions::viewusers($con);
        break;

    case 'PUT':
        Functions::updateuser($con);
        break;

    case 'DELETE':
        Functions::removeuser($con);
        break;

    case 'PATCH':
        Functions::updateuserdetails($con);
        break;

    default:
        break;
}











?>