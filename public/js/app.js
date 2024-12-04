document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('create-question-form');
    const submitButton = document.getElementById('create-question-submit-button');

    form.addEventListener('submit', function() {
        submitButton.disabled = true;
    });
});

