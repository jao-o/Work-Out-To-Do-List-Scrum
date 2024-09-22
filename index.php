<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workout_tracker";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle task completion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_completed'])) {
    $task_id = $_POST['task_id'];
    $stmt = $conn->prepare("UPDATE workout_tasks SET completed = 1 WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

// Handle task creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_task'])) {
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM workout_tasks WHERE task_name = ? AND task_description = ?");
    $stmt->bind_param("ss", $task_name, $task_description);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Redirect to page1 with an error message
        header("Location: page1.html?error=Task already exists.");
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO workout_tasks (task_name, task_description) VALUES (?, ?)");
        $stmt->bind_param("ss", $task_name, $task_description);
        if ($stmt->execute()) {
            header("Location: page2.php");
            exit();
        }
        $stmt->close();
    }
}


// Display tasks
$result = $conn->query("SELECT id, task_name, task_description, completed FROM workout_tasks");

echo "<h1>Workout Tasks</h1>";
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Task Name</th><th>Task Description</th><th>Status</th><th>Action</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["task_name"] . "</td>";
        echo "<td>" . $row["task_description"] . "</td>";
        echo "<td>" . ($row["completed"] ? "Completed" : "Incomplete") . "</td>";
        echo "<td>";
        if (!$row["completed"]) {
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='task_id' value='" . $row["id"] . "'>";
            echo "<input type='submit' name='mark_completed' value='Mark as Completed'>";
            echo "</form>";
        } else {
            echo "Already completed";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No workout tasks found.";
}

$conn->close();
?>
