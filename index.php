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

// Create Task
function createTask($conn) {
    if (isset($_POST['task_name']) && isset($_POST['task_description'])) {
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];

        // Check for duplicate tasks
        $stmt = $conn->prepare("SELECT COUNT(*) FROM workout_tasks WHERE task_name = ? AND task_description = ?");
        $stmt->bind_param("ss", $task_name, $task_description);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            // Return error message for duplicate task
            echo json_encode(['status' => 'error', 'message' => 'Task already exists.']);
            exit();
        } else {
            // Insert new task
            $stmt = $conn->prepare("INSERT INTO workout_tasks (task_name, task_description) VALUES (?, ?)");
            $stmt->bind_param("ss", $task_name, $task_description);
            $stmt->execute();
            $stmt->close();
            // Return success response
            echo json_encode(['status' => 'success', 'redirect_url' => 'page2.html']);
            exit();
        }
    }
    return null;
}

// Handle Task Update
function updateTask($conn) {
    if (isset($_POST['update_task'])) {
        $task_id = $_POST['task_id'];
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];

        // Check for duplicate tasks (exclude the current task)
        $stmt = $conn->prepare("SELECT COUNT(*) FROM workout_tasks WHERE task_name = ? AND task_description = ? AND id != ?");
        $stmt->bind_param("ssi", $task_name, $task_description, $task_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Task already exists.']);
            exit();
        } else {
            // Update the task
            $stmt = $conn->prepare("UPDATE workout_tasks SET task_name = ?, task_description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $task_name, $task_description, $task_id);
            $stmt->execute();
            $stmt->close();
            echo json_encode(['status' => 'success', 'redirect_url' => 'page2.html']);
            exit();
        }
    }
    return null;
}

// Mark Task as Completed
function markCompleted($conn) {
    if (isset($_POST['mark_completed'])) {
        $task_id = $_POST['task_id'];
        $stmt = $conn->prepare("UPDATE workout_tasks SET completed = 1 WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $stmt->close();
    }
}

$error_message = createTask($conn);
$error_message = updateTask($conn) ?? $error_message;
markCompleted($conn);

$conn->close();
?>
