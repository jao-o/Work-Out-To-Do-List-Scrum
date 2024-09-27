let currentEditingIndex = null; // Variable to track the current editing workout index
let workoutToDeleteIndex = null; // Variable to track the workout to delete

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
        workoutCell.innerHTML = `<i class="fas fa-pencil-alt edit-icon" data-index="${index}"></i> ${item.workout}
                                 <i class="fas fa-trash-alt delete-icon" data-index="${index}"></i>`; // Added delete icon

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
                updateCompletedCount();
            }
        });

        actionCell.appendChild(markDoneButton);
        row.appendChild(workoutCell);
        row.appendChild(descriptionCell);
        row.appendChild(statusCell);
        row.appendChild(actionCell);

        workoutList.appendChild(row);
    });

    // Initial update of the completed workouts count
    updateCompletedCount();

    // Add click event to edit icons
    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.addEventListener('click', (event) => {
            const index = event.target.dataset.index; // Get index from dataset
            openEditModal(workouts[index], index); // Open modal with workout data
        });
    });

    // Add click event to delete icons
    document.querySelectorAll('.delete-icon').forEach(icon => {
        icon.addEventListener('click', (event) => {
            workoutToDeleteIndex = event.target.dataset.index; // Store the index to delete
            openDeleteModal(); // Open delete confirmation modal
        });
    });
}

// Function to update completed workouts count
function updateCompletedCount() {
    const completedCountLabel = document.getElementById('completed-count');
    const workouts = JSON.parse(localStorage.getItem('workouts')) || [];
    const completedCount = workouts.filter(item => item.completed).length;
    completedCountLabel.textContent = `Completed Workouts: ${completedCount}`;
}

// Function to open edit modal
function openEditModal(workout, index) {
    const editModal = document.getElementById('editModal');
    const editWorkoutInput = document.getElementById('edit-workout');
    const editDescriptionInput = document.getElementById('edit-description');

    editWorkoutInput.value = workout.workout;
    editDescriptionInput.value = workout.description;

    currentEditingIndex = index; // Set the current editing index
    editModal.style.display = 'block'; // Show the edit modal
}

// Function to close edit modal
function closeEditModal() {
    const editModal = document.getElementById('editModal');
    editModal.style.display = 'none';
}

// Function to submit edited workout
function submitEdit() {
    const workouts = JSON.parse(localStorage.getItem('workouts')) || [];
    const editWorkoutInput = document.getElementById('edit-workout');
    const editDescriptionInput = document.getElementById('edit-description');

    workouts[currentEditingIndex] = {
        workout: editWorkoutInput.value,
        description: editDescriptionInput.value,
        completed: workouts[currentEditingIndex].completed
    };

    localStorage.setItem('workouts', JSON.stringify(workouts)); // Update local storage
    displayWorkouts(); // Refresh the workout list
    closeEditModal(); // Close the edit modal
}

// Function to open delete confirmation modal
function openDeleteModal() {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.style.display = 'block';
}

// Function to close delete confirmation modal
function closeDeleteModal() {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.style.display = 'none';
}

// Function to delete the workout
function deleteWorkout() {
    const workouts = JSON.parse(localStorage.getItem('workouts')) || [];
    workouts.splice(workoutToDeleteIndex, 1); // Remove the workout
    localStorage.setItem('workouts', JSON.stringify(workouts)); // Update local storage
    displayWorkouts(); // Refresh the workout list
    closeDeleteModal(); // Close the delete modal
}

// Event listeners for modal buttons
document.getElementById('confirm-delete').addEventListener('click', deleteWorkout);
document.getElementById('cancel-delete').addEventListener('click', closeDeleteModal);
document.getElementById('closeDeleteModal').addEventListener('click', closeDeleteModal);
document.getElementById('submit-edit').addEventListener('click', submitEdit);
document.getElementById('closeEditModal').addEventListener('click', closeEditModal);

// Display workouts when page2.html is loaded
if (document.getElementById('workout-items')) {
    displayWorkouts();
}
