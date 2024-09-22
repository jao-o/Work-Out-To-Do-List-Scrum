document.addEventListener('DOMContentLoaded', () => {
    const taskList = document.getElementById('task-list');

    // Retrieve tasks from localStorage
    const tasks = JSON.parse(localStorage.getItem('tasks')) || [];

    // If no tasks are found, show a message
    if (tasks.length === 0) {
        taskList.innerHTML = '<li>No tasks added yet.</li>';
    } else {
        // Loop through tasks and display them
        tasks.forEach((task) => {
            const li = document.createElement('li');
            li.innerHTML = `<strong>Workout:</strong> ${task.workoutName}<br><strong>Description:</strong> ${task.description}`;
            taskList.appendChild(li);
        });
    }
});