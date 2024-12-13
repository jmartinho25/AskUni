document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded'); // Debug

    // Form submission handling
    const form = document.getElementById('create-question-form');
    const submitButton = document.getElementById('create-question-submit-button');

    if (form && submitButton) {
        form.addEventListener('submit', function() {
            submitButton.disabled = true;
        });
    }

    // Alert handling
    const alerts = document.querySelectorAll('.alert');
    console.log('Alerts found:', alerts.length); // Debug

    alerts.forEach(alert => {
        // Certifique-se de que o alerta começa visível
        alert.style.opacity = '1';
        alert.style.transition = 'opacity 0.6s ease-out';
        
        setTimeout(() => {
            console.log('Starting fade out'); // Debug
            alert.style.opacity = '0';
            
            setTimeout(() => {
                console.log('Removing alert'); // Debug
                if (alert.parentElement) {
                    alert.parentElement.removeChild(alert);
                }
            }, 600);
        }, 5000);
    });
});