// Function to add a workout
function addWorkout() {
    const workoutInput = document.getElementById('workout');
    const descriptionInput = document.getElementById('description');

    // Get the values from the inputs
    const workout = workoutInput.value;
    const description = descriptionInput.value;

    if (workout && description) {
        // Send the workout data to the PHP backend using POST
        fetch('index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `task_name=${encodeURIComponent(workout)}&task_description=${encodeURIComponent(description)}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Redirect to page2.html after successful task creation
                window.location.href = data.redirect_url;
            } else if (data.status === 'error') {
                // Display error message (e.g., for duplicate tasks)
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        alert("Please fill in both fields.");
    }
}

// Add event listener for the Add button on page1.html
if (document.getElementById('add')) {
    document.getElementById('add').addEventListener('click', addWorkout);
}
