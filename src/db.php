<?php  

$con = mysqli_connect("localhost","root","","coreapi");

if (!$con) {
    trigger_error("Database connection failed", E_CORE_ERROR);
} 



?>