document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('create-question-form');
    const submitButton = document.getElementById('create-question-submit-button');

    if (form && submitButton) {
        form.addEventListener('submit', function() {
            submitButton.disabled = true;
        });
    }

    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            let username = document.getElementById('username').value;
            let name = document.getElementById('name').value;
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('password-confirm').value;

            if (username === "") {
                alert("Username must be filled out");
                event.preventDefault();
            }
            if (name === "") {
                alert("Name must be filled out");
                event.preventDefault();
            }
            if (email === "") {
                alert("Email must be filled out");
                event.preventDefault();
            }
            if (!email.endsWith("@fe.up.pt")) {
                alert("Email must be in the format @fe.up.pt");
                event.preventDefault();
            }
            if (password === "") {
                alert("Password must be filled out");
                event.preventDefault();
            }
            if (password.length < 8) {
                alert("Password must be at least 8 characters long");
                event.preventDefault();
            }
            if (password !== confirmPassword) {
                alert("Passwords do not match");
                event.preventDefault();
            }
        });
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;

            if (email === "") {
                alert("Email must be filled out");
                event.preventDefault();
            }
            if (!email.endsWith("@fe.up.pt")) {
                alert("Email must be in the format @fe.up.pt");
                event.preventDefault();
            }
            if (password === "") {
                alert("Password must be filled out");
                event.preventDefault();
            }
        });
    }

    const editProfileForm = document.getElementById('editProfileForm');
    if (editProfileForm) {
        editProfileForm.addEventListener('submit', function(event) {
            let name = document.getElementById('name').value;
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('password_confirmation').value;
            let description = document.getElementById('description').value;

            if (name === "") {
                alert("Name must be filled out");
                event.preventDefault();
            }
            if (email === "") {
                alert("Email must be filled out");
                event.preventDefault();
            }
            if (!email.endsWith("@fe.up.pt")) {
                alert("Email must be in the format @fe.up.pt");
                event.preventDefault();
            }
            if (password !== confirmPassword) {
                alert("Passwords do not match");
                event.preventDefault();
            }
            if (description.length > 255) {
                alert("Description must have at most 255 characters");
                event.preventDefault();
            }
        });
    }

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