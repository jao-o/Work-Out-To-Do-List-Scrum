document.addEventListener("DOMContentLoaded", function () {
    // Add event listener to the form for adding workouts
    const form = document.getElementById("workout-form");
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        addWorkout();
    });

    // Add event listener for the display button
    const displayButton = document.getElementById("display-button");
    displayButton.addEventListener("click", function () {
        window.location.href = 'page2.html'; // Navigate to the display page
    });
});

// Add a new workout
function addWorkout() {
    const taskName = document.getElementById("task_name").value;
    const taskDescription = document.getElementById("task_description").value;

    const formData = new FormData();
    formData.append("task_name", taskName);
    formData.append("task_description", taskDescription);

    fetch('index.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Workout added successfully!');
            document.getElementById("workout-form").reset(); // Clear the form
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error adding workout:', error));
}
document.addEventListener("DOMContentLoaded", function () {
    // Add event listener to the form for adding workouts
    const form = document.getElementById("workout-form");
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        addWorkout();
    });

    // Add event listener for the display button
    const displayButton = document.getElementById("display-button");
    displayButton.addEventListener("click", function () {
        window.location.href = 'page2.html'; // Navigate to the display page
    });
});

// Add a new workout
function addWorkout() {
    const taskName = document.getElementById("task_name").value;
    const taskDescription = document.getElementById("task_description").value;

    const formData = new FormData();
    formData.append("task_name", taskName);
    formData.append("task_description", taskDescription);

    fetch('index.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Workout added successfully!');
            document.getElementById("workout-form").reset(); // Clear the form
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error adding workout:', error));
}
