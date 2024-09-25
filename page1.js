document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');

    form.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevent the default form submission

        const workoutName = form.querySelector('input[placeholder="Ex. Push-up"]').value;
        const description = form.querySelector('input[placeholder="About"]').value;

        if (workoutName && description) {
            // Simulate a successful task addition with a futuristic message
            displaySuccessMessage();
            form.reset(); // Reset the form after successful submission
        } else {
            alert('Please fill in all fields.');
        }
    });

    function displaySuccessMessage() {
        const successMessage = document.createElement('div');
        successMessage.textContent = 'Task added successfully!';
        successMessage.style.position = 'fixed';
        successMessage.style.bottom = '20px';
        successMessage.style.left = '50%';
        successMessage.style.transform = 'translateX(-50%)';
        successMessage.style.background = '#0ff';
        successMessage.style.color = '#222';
        successMessage.style.padding = '10px 20px';
        successMessage.style.borderRadius = '8px';
        successMessage.style.boxShadow = '0 0 15px rgba(0, 255, 0, 0.8)';
        successMessage.style.fontSize = '1.2em';
        successMessage.style.zIndex = '1000';
        successMessage.style.opacity = '1';
        successMessage.style.transition = 'opacity 0.5s ease-out';
        document.body.appendChild(successMessage);

        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 500);
        }, 2000);
    }
});
