document.addEventListener('DOMContentLoaded', () => {

    // 1. Remove Loader
    const loader = document.getElementById('loader');
    if (loader) {
        setTimeout(() => {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 800);
        }, 500); // simulate loading time
    }

    // 2. Mobile Menu Toggle
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // 3. Form Validation & AJAX Submission
    const contactForm = document.getElementById('contactForm');

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Elements
            const emailInput = document.getElementById('email');
            const messageInput = document.getElementById('message');
            const emailError = document.getElementById('emailError');
            const messageError = document.getElementById('messageError');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoader = submitBtn.querySelector('.btn-loader');

            // Reset Errors
            emailInput.parentElement.classList.remove('error');
            messageInput.parentElement.classList.remove('error');
            emailError.textContent = '';
            messageError.textContent = '';

            let isValid = true;

            // Validate Email
            const emailValue = emailInput.value.trim();
            if (emailValue === '') {
                emailInput.parentElement.classList.add('error');
                emailError.textContent = 'Email is required';
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                emailInput.parentElement.classList.add('error');
                emailError.textContent = 'Please enter a valid email address';
                isValid = false;
            }

            // Validate Message
            const messageValue = messageInput.value.trim();
            if (messageValue === '') {
                messageInput.parentElement.classList.add('error');
                messageError.textContent = 'Message is required';
                isValid = false;
            }

            if (isValid) {
                // Show Loading State on Button
                btnText.style.display = 'none';
                btnLoader.style.display = 'inline-block';
                submitBtn.disabled = true;

                // Prepare Data
                const formData = new FormData(contactForm);

                // AJAX Request (Fetch API)
                fetch('api/contact.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showSnackbar(data.message, 'success');
                            contactForm.reset();
                        } else {
                            showSnackbar(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showSnackbar('An error occurred while sending the message.', 'error');
                    })
                    .finally(() => {
                        // Reset Button State
                        btnText.style.display = 'inline-block';
                        btnLoader.style.display = 'none';
                        submitBtn.disabled = false;
                    });
            }
        });
    }

    // 4. Snackbar Function
    function showSnackbar(message, type = 'success') {
        const snackbar = document.getElementById('snackbar');
        snackbar.textContent = message;

        if (type === 'error') {
            snackbar.classList.add('error-toast');
        } else {
            snackbar.classList.remove('error-toast');
        }

        snackbar.className = 'show';

        // Remove class after 3 seconds
        setTimeout(function () {
            snackbar.className = snackbar.className.replace('show', '');
        }, 3000);
    }
});
