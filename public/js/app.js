document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-question-form');
    const submitButton = document.getElementById('create-question-submit-button');

    form.addEventListener('submit', function() {
        submitButton.disabled = true;
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('create-question-form');
    const submitButton = document.getElementById('create-question-submit-button');

    if (form && submitButton) {
        form.addEventListener('submit', function () {
            submitButton.disabled = true;
        });
    }

    // Seleciona todos os elementos com a classe .alert
    const alerts = document.querySelectorAll('.alert');
    const timeout = 3000; // Tempo antes de iniciar a ocultação

    alerts.forEach(alert => {
        // Define um timeout para cada alerta
        setTimeout(() => {
            alert.style.transition = 'opacity 0.6s ease-out';
            alert.style.opacity = '0'; // Diminui a opacidade

            // Remove o alerta do DOM após a transição
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.parentElement.removeChild(alert);
                }
            }, 600); // Tempo da transição
        }, timeout);
    });
});
