<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workout_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle task completion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complete_task'])) {
    $task_id = $_POST['task_id'];
    $complete_sql = "UPDATE workout_tasks SET completed = 1 WHERE id = ?";
    $stmt = $conn->prepare($complete_sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle task deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_task'])) {
    $task_id = $_POST['task_id'];
    $delete_sql = "DELETE FROM workout_tasks WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle task editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_task'])) {
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    
    $update_sql = "UPDATE workout_tasks SET task_name = ?, task_description = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $task_name, $task_description, $task_id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Display tasks
$sql = "SELECT id, task_name, task_description, completed FROM workout_tasks";
$result = $conn->query($sql);

// Count completed tasks
$completed_tasks_sql = "SELECT COUNT(*) FROM workout_tasks WHERE completed = 1";
$completed_tasks_result = $conn->query($completed_tasks_sql);
$completed_tasks = $completed_tasks_result->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout Tasks</title>
    <link rel="stylesheet" href="page2.css">
</head>
<body>

    <div class="task-display-wrapper">
        <h1>Workout Tasks</h1>
        <h2>Completed Workouts: <?php echo $completed_tasks; ?></h2>
        
        <!-- Back Button -->
        <button class="back-button" onclick="window.location.href='page1.html'" style="margin-bottom: 20px; float: left;">Back</button>
        
        <table style="clear: both;">
            <tr>
                <th>Task Name</th>
                <th>Task Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["task_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["task_description"]); ?></td>
                        <td><?php echo $row["completed"] ? "Completed" : "Not Completed"; ?></td>
                        <td>
                            <form method="POST" action="page2.php" style="display:inline;">
                                <input type="hidden" name="task_id" value="<?php echo $row["id"]; ?>">
                                <input type="submit" name="complete_task" value="Complete" class="complete-button" <?php echo $row["completed"] ? 'disabled' : ''; ?>>
                            </form>
                            <button onclick="openModal('<?php echo $row["id"]; ?>', '<?php echo htmlspecialchars($row["task_name"]); ?>', '<?php echo htmlspecialchars($row["task_description"]); ?>')">Edit</button>
                            <form method="POST" action="page2.php" style="display:inline;">
                                <input type="hidden" name="task_id" value="<?php echo $row["id"]; ?>">
                                <input type="submit" name="delete_task" value="Delete" class="delete-button">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No tasks found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- Modal for editing tasks -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Task</h2>
            <form method="POST" action="page2.php">
                <input type="hidden" id="edit_task_id" name="task_id">
                <label for="task_name">Task Name:</label>
                <input type="text" id="task_name" name="task_name" required>
                <br>
                <label for="task_description">Task Description:</label>
                <textarea id="task_description" name="task_description" required></textarea>
                <br>
                <input type="submit" name="edit_task" value="Save Changes">
            </form>
        </div>
    </div>

    <script>
        function openModal(taskId, taskName, taskDescription) {
            document.getElementById('edit_task_id').value = taskId;
            document.getElementById('task_name').value = taskName;
            document.getElementById('task_description').value = taskDescription;
            document.getElementById('editModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('editModal').style.display = "none";
        }

        // Close modal if user clicks anywhere outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
