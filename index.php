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
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    
    
    $stmt = $conn->prepare("SELECT COUNT(*) FROM workout_tasks WHERE task_name = ? AND task_description = ?");
    $stmt->bind_param("ss", $task_name, $task_description);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    
    if ($count > 0) {
        echo "Error: Task already exists.";
    } else {
    
        $stmt = $conn->prepare("INSERT INTO workout_tasks (task_name, task_description) VALUES (?, ?)");
        $stmt->bind_param("ss", $task_name, $task_description);
        
        if ($stmt->execute()) {
            echo "New workout task added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

$sql = "SELECT id, task_name, task_description FROM workout_tasks";
$result = $conn->query($sql);

echo "<h1>Workout Tasks</h1>";
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Task Name</th><th>Task Description</th></tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["task_name"] . "</td>";
        echo "<td>" . $row["task_description"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No workout tasks found.";
}

$conn->close();
?>
