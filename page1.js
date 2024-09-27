// page1.js

// Function to add a workout
function addWorkout() {
    const workoutInput = document.getElementById('workout');
    const descriptionInput = document.getElementById('description');

    // Get the values from the inputs
    const workout = workoutInput.value.trim();  // Trim whitespace
    const description = descriptionInput.value.trim();  // Trim whitespace

    if (workout && description) {
        // Get existing workouts from local storage
        const workouts = JSON.parse(localStorage.getItem('workouts')) || [];

        // Check for duplicates (only for workout name)
        const duplicate = workouts.some(item => item.workout.toLowerCase() === workout.toLowerCase());

        if (duplicate) {
            alert("This workout already exists. Please enter a different workout.");
            return;  // Exit the function to prevent adding the duplicate
        }

        // Add the new workout to the array with a default status of "Pending"
        workouts.push({ workout, description, completed: false }); // Set completed to false

        // Save the updated workouts back to local storage
        localStorage.setItem('workouts', JSON.stringify(workouts));

        // Clear the input fields
        workoutInput.value = '';
        descriptionInput.value = '';
    } else {
        alert("Please fill in both fields.");
    }
}

// Add event listener for the Add button
if (document.getElementById('add')) {
    document.getElementById('add').addEventListener('click', addWorkout);
}
