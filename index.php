<?php
// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workout_tracker";
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create task
function createTask($conn) {
    if (isset($_POST['task_name']) && isset($_POST['task_description'])) {
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
            // Error message
            echo json_encode(['status' => 'error', 'message' => 'Task already exists.']);
            exit();
        } else {
            // Insert task
            $stmt = $conn->prepare("INSERT INTO workout_tasks (task_name, task_description) VALUES (?, ?)");
            $stmt->bind_param("ss", $task_name, $task_description);
            $stmt->execute();
            $stmt->close();
            // Success response
            echo json_encode(['status' => 'success', 'redirect_url' => 'page2.html']);
            exit();
        }
    }
    return null;
}

// Update task
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
            // Error message
            echo json_encode(['status' => 'error', 'message' => 'Task already exists.']);
            exit();
        } else {
            // Update task
            $stmt = $conn->prepare("UPDATE workout_tasks SET task_name = ?, task_description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $task_name, $task_description, $task_id);
            $stmt->execute();
            $stmt->close();
            // Success response
            echo json_encode(['status' => 'success', 'redirect_url' => 'page2.html']);
            exit();
        }
    }
    return null;
}

// Mark completed
function markCompleted($conn) {
    if (isset($_POST['mark_completed'])) {
        $task_id = $_POST['task_id'];
        $stmt = $conn->prepare("UPDATE workout_tasks SET completed = 1 WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Delete task
function deleteTask($conn) {
    if (isset($_POST['delete_task'])) {
        $task_id = $_POST['task_id'];
        $stmt = $conn->prepare("DELETE FROM workout_tasks WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $stmt->close();
        // Success message
        echo json_encode(['status' => 'success', 'message' => 'Task deleted successfully.']);
        exit();
    }
}

$error_message = createTask($conn);
$error_message = updateTask($conn) ?? $error_message;
markCompleted($conn);
deleteTask($conn);

$conn->close();
?>
