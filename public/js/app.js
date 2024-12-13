document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('create-question-form');
    const submitButton = document.getElementById('create-question-submit-button');

    if (form && submitButton) {
        form.addEventListener('submit', function() {
            submitButton.disabled = true;
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    function handleAlerts() {
        const alerts = document.querySelectorAll('.alert');
        if (alerts.length === 0) return; 

        alerts.forEach(alert => {
            alert.style.opacity = '1';
            alert.style.transition = 'opacity 0.6s ease-out';
            
            setTimeout(() => {
                alert.style.opacity = '0';
                
                setTimeout(() => {
                    if (alert && alert.parentElement) {
                        alert.parentElement.removeChild(alert);
                    }
                }, 600);
            }, 5000);
        });
    }

    handleAlerts();
});