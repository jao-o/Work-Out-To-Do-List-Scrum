<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "workout_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Display tasks
$sql = "SELECT task_name, task_description, completed FROM workout_tasks";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Tasks</title>
    <link rel="stylesheet" href="page2.css">
</head>
<body>

    <div class="task-display-wrapper">
        <h1>Task List</h1>
        <ul id="task-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>";
                    echo $row["task_name"] . ": " . $row["task_description"] . " (" . ($row["completed"] ? "Completed" : "Incomplete") . ")";
                    echo "</li>";
                }
            } else {
                echo "<li>No tasks found.</li>";
            }
            ?>
        </ul>

        <a href="page1.html">
            <button type="button">Go Back to Add Task</button>
        </a>
    </div>

</body>
</html>

<?php
$conn->close();
?>
