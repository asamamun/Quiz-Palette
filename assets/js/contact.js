document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const responseDiv = document.getElementById('formResponse');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Hide any previous messages
            responseDiv.classList.add('d-none');
            
            // Client-side validation
            if (!contactForm.checkValidity()) {
                event.stopPropagation();
                contactForm.classList.add('was-validated');
                return;
            }
            
            // Submit form via AJAX
            const formData = new FormData(contactForm);
            
            fetch('process_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(responseText => {
                responseDiv.textContent = responseText.trim();
                
                if (responseText.trim() === "Submitted") {
                    responseDiv.classList.remove('d-none', 'alert-danger');
                    responseDiv.classList.add('alert-success');
                    contactForm.reset();
                    contactForm.classList.remove('was-validated');
                    
                    // Optional: Auto-hide message after 5 seconds
                    setTimeout(() => {
                        responseDiv.classList.add('d-none');
                    }, 5000);
                } else {
                    responseDiv.classList.remove('d-none', 'alert-success');
                    responseDiv.classList.add('alert-danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                responseDiv.textContent = "Error submitting form";
                responseDiv.classList.remove('d-none', 'alert-success');
                responseDiv.classList.add('alert-danger');
            });
        });
    }
});