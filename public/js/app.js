document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-question-form');
    const submitButton = document.getElementById('create-question-submit-button');

    form.addEventListener('submit', function() {
        submitButton.disabled = true;
    });
});
document.addEventListener('DOMContentLoaded', function () {
    // Seleciona todos os alertas
    const alerts = document.querySelectorAll('.alert');

    // Define um tempo (em milissegundos) antes de remover os alertas
    const timeout = 5000; // 5 segundos

    // Itera sobre os alertas encontrados
    alerts.forEach(alert => {
        setTimeout(() => {
            // Adiciona animação de fade-out (opcional)
            alert.classList.add('fade');
            alert.style.opacity = '0';

            // Remove o elemento do DOM após a animação
            setTimeout(() => alert.remove(), 600); // 600ms para o fade-out terminar
        }, timeout);
    });
});