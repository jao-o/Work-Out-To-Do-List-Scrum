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

// Handle different form actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create_task'])) {
        // Handle task creation
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];

        $stmt = $conn->prepare("SELECT COUNT(*) FROM workout_tasks WHERE task_name = ? AND task_description = ?");
        $stmt->bind_param("ss", $task_name, $task_description);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            header("Location: page2.php?error=Task already exists.");
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

    } elseif (isset($_POST['update_task'])) {
        // Handle task update
        $task_id = $_POST['task_id'];
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];

        $stmt = $conn->prepare("UPDATE workout_tasks SET task_name = ?, task_description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $task_name, $task_description, $task_id);
        $stmt->execute();
        $stmt->close();
        header("Location: page2.php");
        exit();

    } elseif (isset($_POST['mark_completed'])) {
        // Handle task completion
        $task_id = $_POST['task_id'];
        $stmt = $conn->prepare("UPDATE workout_tasks SET completed = 1 WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    }
}

// Display tasks
$result = $conn->query("SELECT id, task_name, task_description, completed FROM workout_tasks");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout Tracker</title>
</head>
<body>

    <h1>Workout Tasks</h1>
    <form method="POST" action="">
        <input type="text" name="task_name" placeholder="Task Name" required>
        <input type="text" name="task_description" placeholder="Task Description" required>
        <input type="submit" name="create_task" value="Add Task">
    </form>

    <?php if (isset($task_name)): ?>
        <h2>Edit Task</h2>
        <form method="POST" action="">
            <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
            <input type="text" name="task_name" value="<?php echo htmlspecialchars($task_name); ?>" required>
            <input type="text" name="task_description" value="<?php echo htmlspecialchars($task_description); ?>" required>
            <input type="submit" name="update_task" value="Update Task">
        </form>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Task Name</th>
            <th>Task Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo htmlspecialchars($row["task_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["task_description"]); ?></td>
                <td><?php echo $row["completed"] ? "Completed" : "Incomplete"; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="task_id" value="<?php echo $row["id"]; ?>">
                        <input type="submit" name="mark_completed" value="Mark as Completed">
                        <input type="submit" name="edit_task" value="Edit">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>

<?php
$conn->close();
?>
