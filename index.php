<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "workout_tracker";  


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


echo "Connection to the database '$dbname' was successful!";


$conn->close();
?>
