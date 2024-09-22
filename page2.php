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
$sql = "SELECT id, task_name, task_description, completed FROM workout_tasks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Tasks</title>
    <link rel="stylesheet" href="page2.css">
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212; /* Dark background */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Full screen height */
            padding: 20px;
        }

        /* Wrapper for task display */
        .task-display-wrapper {
            background-color: rgba(20, 20, 20, 0.9); /* Dark translucent background */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.7); /* Neon green shadow */
            text-align: center;
            width: 90%;
            max-width: 600px;
            max-height: 80vh; /* Limit the height of the wrapper */
            overflow-y: auto; /* Adds vertical scrollbar if content exceeds */
            backdrop-filter: blur(5px); /* Frosted glass effect */
            border: 2px solid #0f0; /* Neon green border */
        }

        /* Title styling */
        h1, h2 {
            font-size: 2.5rem;
            color: #0f0; /* Neon green */
            margin-bottom: 20px;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.7); /* Neon glow effect */
        }

        /* Form inputs styling */
        input[type="text"] {
            text-align: center; /* Center align text in input fields */
            margin-bottom: 10px; /* Space between inputs */
            width: 80%; /* Set width for input fields */
            padding: 10px; /* Padding for better aesthetics */
            border: 2px solid #0f0; /* Neon green border */
            border-radius: 5px; /* Rounded corners */
            background-color: #2e2e2e; /* Input background */
            color: #fff; /* Text color */
        }

        /* Button styling */
        input[type="submit"] {
            background-color: #0f0;
            color: #121212;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 100%; /* Full width for submit button */
        }

        input[type="submit"]:hover {
            background-color: #00ff7f;
            transform: scale(1.05);
        }

        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.7); 
            padding-top: 60px; 
        }

        .modal-content {
            background-color: #1e1e1e;
            margin: 5% auto; 
            padding: 20px;
            border: 2px solid #0f0; 
            width: 80%; 
            max-width: 500px; 
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.5); 
            text-align: center; /* Centering text in the modal */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #00ff7f;
            cursor: pointer;
        }

        /* Center the labels */
        label {
            display: block;
            margin-bottom: 5px; /* Space between label and input */
            color: #fff; /* Label color */
        }
    </style>
</head>
<body>

    <div class="task-display-wrapper">
        <h1>Task List</h1>

        <table id="task-table">
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Task Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["task_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["task_description"]) . "</td>";
                        echo "<td>" . ($row["completed"] ? "Completed" : "Incomplete") . "</td>";
                        echo "<td>";
                        echo "<button onclick='openEditModal(" . $row["id"] . ", \"" . addslashes($row["task_name"]) . "\", \"" . addslashes($row["task_description"]) . "\")'>Edit</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No tasks available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Task Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Workout</h2>
            <form method="POST" action="index.php">
                <input type="hidden" name="task_id" id="edit_task_id">
                
                <label for="edit_task_name">Workout Name</label>
                <input type="text" name="task_name" id="edit_task_name" required>
                
                <label for="edit_task_description">Description</label>
                <input type="text" name="task_description" id="edit_task_description" required>

                <input type="submit" name="update_task" value="Submit">
            </form>
        </div>
    </div>

    <script>
        // Open Modal for Editing
        function openEditModal(id, name, description) {
            document.getElementById('edit_task_id').value = id;
            document.getElementById('edit_task_name').value = name;
            document.getElementById('edit_task_description').value = description;
            document.getElementById('editModal').style.display = "block";
        }

        // Close Modal
        function closeModal() {
            document.getElementById('editModal').style.display = "none";
        }

        // Close Modal on Outside Click
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                document.getElementById('editModal').style.display = "none";
            }
        }
    </script>
    
</body>
</html>

<?php
$conn->close();
?>
