// page1.js

// Function to add a workout
function addWorkout() {
    const workoutInput = document.getElementById('workout');
    const descriptionInput = document.getElementById('description');

    // Get the values from the inputs
    const workout = workoutInput.value;
    const description = descriptionInput.value;

    if (workout && description) {
        // Get existing workouts from local storage
        const workouts = JSON.parse(localStorage.getItem('workouts')) || [];

        // Add the new workout to the array
        workouts.push({ workout, description });

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
