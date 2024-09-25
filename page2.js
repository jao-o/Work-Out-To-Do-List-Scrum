// page2.js

let currentEditingIndex = null; // Variable to track the current editing workout index

// Function to display workouts
function displayWorkouts() {
    const workoutList = document.getElementById('workout-items');
    const completedCountLabel = document.getElementById('completed-count');

    // Get existing workouts from local storage
    const workouts = JSON.parse(localStorage.getItem('workouts')) || [];

    // Clear the current list
    workoutList.innerHTML = '';

    // Populate the list with workouts
    workouts.forEach((item, index) => {
        const row = document.createElement('tr');

        const workoutCell = document.createElement('td');
        // Adding icon and workout name
        workoutCell.innerHTML = `<i class="fas fa-pencil-alt edit-icon" data-index="${index}"></i> ${item.workout}`;

        const descriptionCell = document.createElement('td');
        descriptionCell.textContent = item.description;

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

                // Update completed workouts count
                const completedCount = parseInt(completedCountLabel.textContent.match(/\d+/)[0]) + 1;
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
    completedCountLabel.textContent = `Completed Workouts: ${workouts.filter(item => item.completed).length || 0}`;

    // Add click event to edit icons
    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.addEventListener('click', (event) => {
            const index = event.target.dataset.index; // Get index from dataset
            openEditModal(workouts[index], index); // Open modal with workout data
        });
    });
}

// Function to open the edit modal
function openEditModal(workout, index) {
    const modal = document.getElementById('editModal');
    const workoutInput = document.getElementById('edit-workout');
    const descriptionInput = document.getElementById('edit-description');
    const submitButton = document.getElementById('submit-edit');
    const closeButton = document.querySelector('.close');

    // Fill the modal with current workout details
    workoutInput.value = workout.workout;
    descriptionInput.value = workout.description;

    currentEditingIndex = index; // Set current editing index
    modal.style.display = 'block'; // Show the modal

    // Close modal on clicking close button
    closeButton.onclick = () => {
        modal.style.display = 'none';
    };

    // Close modal on clicking outside of modal
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

    // Handle submit for editing workout
    submitButton.onclick = () => {
        workout.workout = workoutInput.value; // Update workout name
        workout.description = descriptionInput.value; // Update description

        // Save updated workouts back to local storage
        const workouts = JSON.parse(localStorage.getItem('workouts')) || [];
        workouts[index] = workout; // Update the specific workout
        localStorage.setItem('workouts', JSON.stringify(workouts));

        modal.style.display = 'none'; // Close the modal
        displayWorkouts(); // Refresh the displayed workouts
    };
}

// Display workouts when page2.html is loaded
if (document.getElementById('workout-items')) {
    displayWorkouts();
}
