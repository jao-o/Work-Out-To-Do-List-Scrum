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

// Create or Update Task
function createOrUpdateTask($conn) {
    if (isset($_POST['task_name']) && isset($_POST['task_description'])) {
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];
        $task_id = $_POST['task_id'] ?? null; // Check if task_id is set for updating

        if ($task_id) {
            // Update Task
            $stmt = $conn->prepare("UPDATE workout_tasks SET task_name = ?, task_description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $task_name, $task_description, $task_id);
        } else {
            // Check for duplicate tasks
            $stmt = $conn->prepare("SELECT COUNT(*) FROM workout_tasks WHERE task_name = ? AND task_description = ?");
            $stmt->bind_param("ss", $task_name, $task_description);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Task already exists.']);
                exit();
            } else {
                // Insert new task
                $stmt = $conn->prepare("INSERT INTO workout_tasks (task_name, task_description) VALUES (?, ?)");
                $stmt->bind_param("ss", $task_name, $task_description);
            }
        }

        $stmt->execute();
        $stmt->close();
        echo json_encode(['status' => 'success']);
        exit();
    }
}

// Fetch Workouts
function fetchWorkouts($conn) {
    $stmt = $conn->prepare("SELECT id, task_name, task_description, completed FROM workout_tasks");
    $stmt->execute();
    $result = $stmt->get_result();
    $workouts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $workouts;
}

// Mark Task as Completed
function markAsCompleted($conn) {
    if (isset($_POST['mark_completed'])) {
        $task_id = $_POST['task_id'];
        $completed = $_POST['completed'];
        $stmt = $conn->prepare("UPDATE workout_tasks SET completed = ? WHERE id = ?");
        $stmt->bind_param("ii", $completed, $task_id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['status' => 'success']);
        exit();
    }
}

// Delete Task
function deleteTask($conn) {
    if (isset($_POST['delete_task'])) {
        $task_id = $_POST['task_id'];
        $stmt = $conn->prepare("DELETE FROM workout_tasks WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['status' => 'success', 'message' => 'Task deleted successfully.']);
        exit();
    }
}

// Check if request is POST and handle it
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    createOrUpdateTask($conn);
    markAsCompleted($conn);
    deleteTask($conn);
}

// Fetch and return workouts
$workouts = fetchWorkouts($conn);
echo json_encode($workouts);

$conn->close();
?>
