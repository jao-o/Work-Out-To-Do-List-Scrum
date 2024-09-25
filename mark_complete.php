<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workout_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['task_id'])) {
        $task_id = $_POST['task_id'];

        $stmt = $conn->prepare("UPDATE workout_tasks SET completed = 1 WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        if ($stmt->execute()) {
            header("Location: page2.php"); // Redirect to page2 after marking as complete
            exit();
        }
        $stmt->close();
    }
}

$conn->close();
?>
