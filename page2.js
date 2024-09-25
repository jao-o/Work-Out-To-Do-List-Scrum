// Function to fetch workouts from the database
function fetchWorkouts() {
    fetch('fetch_workouts.php')
        .then(response => response.json())
        .then(workouts => displayWorkouts(workouts))
        .catch(error => console.error('Error fetching workouts:', error));
}

// Function to display workouts
function displayWorkouts(workouts) {
    const workoutList = document.getElementById('workout-items');
    const completedCountLabel = document.getElementById('completed-count');

    // Clear the current list
    workoutList.innerHTML = '';

    // Populate the list with workouts from the database
    workouts.forEach((item) => {
        const row = document.createElement('tr');

        const workoutCell = document.createElement('td');
        // Adding icon and workout name
        workoutCell.innerHTML = `<i class="fas fa-pencil-alt edit-icon" data-id="${item.id}"></i> ${item.task_name}`;

        const descriptionCell = document.createElement('td');
        descriptionCell.textContent = item.task_description;

        const statusCell = document.createElement('td');
        statusCell.textContent = item.completed ? 'Completed' : 'Pending'; // Show status

        const actionCell = document.createElement('td');
        const markDoneButton = document.createElement('button');
        markDoneButton.textContent = 'Mark as Done';
        markDoneButton.classList.add('mark-done');

        // Add event listener for the button
        markDoneButton.addEventListener('click', () => {
            if (!item.completed) {
                statusCell.textContent = 'Completed'; // Update status
                markDoneButton.disabled = true; // Disable button after marking
                item.completed = true; // Update completed status

                // Mark as completed in the database
                fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `mark_completed=true&task_id=${item.id}`
                });

                // Update completed workouts count
                const completedCount = workouts.filter(item => item.completed).length;
                completedCountLabel.textContent = `Completed Workouts: ${completedCount}`;
            }
        });

        actionCell.appendChild(markDoneButton);
        row.appendChild(workoutCell);
        row.appendChild(descriptionCell);
        row.appendChild(statusCell);
        row.appendChild(actionCell);

        workoutList.appendChild(row);
    });

    // Update the completed workouts count at the start
    completedCountLabel.textContent = `Completed Workouts: ${workouts.filter(item => item.completed).length}`;
}

// Fetch and display workouts when page2.html is loaded
if (document.getElementById('workout-items')) {
    fetchWorkouts();
}
