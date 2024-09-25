<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workout_tracker";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all workouts from the database
$result = $conn->query("SELECT id, task_name, task_description, completed FROM workout_tasks");
$workouts = [];

while ($row = $result->fetch_assoc()) {
    $workouts[] = $row;
}

echo json_encode($workouts);

$conn->close();
?>
