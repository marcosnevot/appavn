// Redesigned customer-mail.js for CCO Tags

document.addEventListener('DOMContentLoaded', () => {
    const openMailButton = document.getElementById('open-mail-modal');
    const mailModal = document.getElementById('customer-mail-modal');
    const closeModalButton = document.getElementById('close-customer-mail');
    const cancelButton = document.getElementById('discard-email');
    const ccoContainer = document.getElementById('recipients-container');
    const addRecipientInput = document.getElementById('add-recipient-input');
    const sendEmailButton =  document.getElementById('send-email');

    // Open the email modal
    openMailButton.addEventListener('click', async () => {
        try {
            const customerEmails = await fetchFilteredCustomerEmails();
            populateCCOContainer(customerEmails);

            // Muestra el fondo negro con animación de opacidad
            mailModal.classList.remove('hidden');
            mailModal.classList.add('show');
            const mailContainer = mailModal.querySelector('.customer-mail-container');
            setTimeout(() => mailContainer.classList.add('show'), 10); // Activa la animación del contenedor
        } catch (error) {
            console.error('Error loading customer emails:', error);
        }
    });

    // Close modal functionality
    const closeModal = () => {
        const mailContainer = mailModal.querySelector('.customer-mail-container');

        mailContainer.classList.remove('show'); // Elimina la animación del contenedor

        mailModal.classList.remove('show');
        mailModal.classList.add('hidden'); // Oculta el modal después de la animación
        clearCCOContainer();
        document.getElementById('email-subject').value = ''; // Limpia el asunto
    };



    closeModalButton.addEventListener('click', closeModal);
    cancelButton.addEventListener('click', closeModal);

    // Fetch filtered customer emails dynamically
    async function fetchFilteredCustomerEmails() {
        const filters = window.currentFilters || {};
        const response = await fetch('/clientes/emails', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(filters)
        });

        if (!response.ok) {
            throw new Error(`Error fetching emails: ${response.status}`);
        }

        const data = await response.json();
        if (!data.success) {
            throw new Error(data.message || 'Unknown error fetching emails');
        }

        return data.emails.filter(email => email); // Filter out null/undefined emails
    }

    // Populate the CCO container with customer emails
    function populateCCOContainer(emails) {
        clearCCOContainer();
        emails.forEach(email => {
            addEmailTag(email);
        });
    }

    // Clear the CCO container
    function clearCCOContainer() {
        ccoContainer.innerHTML = '';
        ccoContainer.appendChild(addRecipientInput); // Ensure input remains operational
    }

    // Add email tag
    function addEmailTag(email) {
        email = email.trim().toLowerCase(); // Limpia espacios y convierte a minúsculas
        if (!validateEmail(email)) {
            alert('Correo no válido: ' + email);
            return;
        }

        const span = document.createElement('span');
        span.textContent = email;
        span.classList.add('recipient-item');

        const removeButton = document.createElement('button');
        removeButton.textContent = 'x';
        removeButton.classList.add('remove-recipient');
        removeButton.addEventListener('click', () => {
            span.remove();
        });

        span.appendChild(removeButton);
        ccoContainer.insertBefore(span, addRecipientInput);
    }


    // Add new recipient manually
    addRecipientInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && this.value.trim() !== '') {
            const email = this.value.trim();
            addEmailTag(email);
            this.value = ''; // Clear input for next entry
        }
    });

    // Validate email format
    function validateEmail(email) {
        var email = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
        return email
    }


    // Handle send email action
    document.getElementById('send-email').addEventListener('click', () => {
        const recipients = Array.from(ccoContainer.querySelectorAll('.recipient-item'))
            .map(item => item.textContent.replace('x', '').trim());

        const subject = document.getElementById('email-subject').value.trim();

        if (recipients.length === 0) {
            alert('Por favor, añade al menos un destinatario en el campo CCO.');
            return;
        }

        // Deshabilitar el botón
        sendEmailButton.disabled = true;
        sendEmailButton.classList.add('disabled');

        const mailtoLink = `mailto:?bcc=${encodeURIComponent(recipients.join(';'))}&subject=${encodeURIComponent(subject)}`;
        window.location.href = mailtoLink;

        // Reactivar el botón cuando el usuario interactúe de nuevo con la página
        window.addEventListener('focus', () => {
            sendEmailButton.disabled = false;
            sendEmailButton.classList.remove('disabled');
        }, { once: true }); // Se ejecuta solo una vez
    });

});