<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workout_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create
function createTask($conn) {
    if (isset($_POST['create_task'])) {
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];

        // Duplicate check
        $stmt = $conn->prepare("SELECT COUNT(*) FROM workout_tasks WHERE task_name = ? AND task_description = ?");
        $stmt->bind_param("ss", $task_name, $task_description);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            return "Task exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO workout_tasks (task_name, task_description) VALUES (?, ?)");
            $stmt->bind_param("ss", $task_name, $task_description);
            $stmt->execute();
            $stmt->close();
            header("Location: page2.php");
            exit();
        }
    }
    return null;
}

// Edit
function editTask($conn) {
    if (isset($_POST['edit_task'])) {
        return $_POST['task_id']; // Return ID
    }
    return null;
}

// Update
function updateTask($conn) {
    if (isset($_POST['update_task'])) {
        $task_id = $_POST['task_id'];
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];

        // Duplicate check
        $stmt = $conn->prepare("SELECT COUNT(*) FROM workout_tasks WHERE task_name = ? AND task_description = ? AND id != ?");
        $stmt->bind_param("ssi", $task_name, $task_description, $task_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            return "Duplicate task.";
        } else {
            $stmt = $conn->prepare("UPDATE workout_tasks SET task_name = ?, task_description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $task_name, $task_description, $task_id);
            $stmt->execute();
            $stmt->close();
            header("Location: index.php");
            exit();
        }
    }
    return null;
}

// Complete
function markCompleted($conn) {
    if (isset($_POST['mark_completed'])) {
        $task_id = $_POST['task_id'];
        $stmt = $conn->prepare("UPDATE workout_tasks SET completed = 1 WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    }
}

$error_message = createTask($conn);
$task_id = editTask($conn);
$error_message = updateTask($conn) ?? $error_message;
markCompleted($conn);

// Display
$result = $conn->query("SELECT id, task_name, task_description, completed FROM workout_tasks");

// Counter
$count_stmt = $conn->prepare("SELECT COUNT(*) FROM workout_tasks WHERE completed = 1");
$count_stmt->execute();
$count_stmt->bind_result($completed_count);
$count_stmt->fetch();
$count_stmt->close();

$conn->close();
?>
