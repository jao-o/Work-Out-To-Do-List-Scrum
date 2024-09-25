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
                const completedCount = workouts.filter(workout => workout.completed).length + 1; // Increment completed count
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

    // Add event listeners for edit icons after populating the list
    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.addEventListener('click', (e) => {
            const taskId = e.target.getAttribute('data-id');
            const taskName = e.target.parentElement.textContent.trim(); // Get the workout name
            const taskDescription = e.target.parentElement.nextElementSibling.textContent.trim(); // Get the description

            // Prefill the modal fields
            document.getElementById('edit-workout').value = taskName;
            document.getElementById('edit-description').value = taskDescription;

            // Show the modal
            editModal.style.display = 'block';

            // Add event listener for submit button
            document.getElementById('submit-edit').onclick = () => {
                fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `update_task=true&task_id=${taskId}&task_name=${taskName}&task_description=${taskDescription}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = data.redirect_url; // Redirect to page2.html
                    } else {
                        alert(data.message); // Show error message
                    }
                });

                // Close the modal after submission
                editModal.style.display = 'none';
            };
        });
    });
}

// Modal functionality
const editModal = document.getElementById('editModal');
const closeModal = document.querySelector('.close');

// Close modal
closeModal.onclick = () => {
    editModal.style.display = 'none';
};

// Fetch workouts on page load
fetchWorkouts();
