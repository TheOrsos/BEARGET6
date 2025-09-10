function sendAdminEmail(userId, emailType, params = {}) {
    const endpoint = 'admin_send_email.php';
    
    const body = {
        user_id: userId,
        email_type: emailType,
        ...params // Aggiunge tutti i parametri extra al corpo della richiesta
    };

    // La funzione fetch restituisce già una Promise
    return fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
    })
    .then(response => {
        if (!response.ok) {
            // Se la risposta non è OK (es. 404, 500), lancia un errore
            throw new Error('Errore di rete o del server.');
        }
        return response.json();
    })
    .then(data => {
        // Mostra una notifica del risultato
        showToast(data.message, data.success ? 'success' : 'error');
        if (!data.success) {
            // Se il server riporta un fallimento, rigetta la promise
            // così il .catch() nel chiamante può gestire l'errore.
            return Promise.reject(data);
        }
        return data; // Passa i dati al .then() del chiamante
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-users');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkActionButtons = document.querySelectorAll('.bulk-action-btn');
    
    // Modal elements
    const confirmModal = document.getElementById('bulk-action-confirm-modal');
    const modalMessage = document.getElementById('bulk-action-modal-message');
    const confirmButton = document.getElementById('bulk-action-confirm-button');

    if (!selectAllCheckbox) {
        // This can happen if the page has no users, it's not a critical error.
        return;
    }

    // "Select All" functionality
    selectAllCheckbox.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Add listener to all bulk action buttons
    bulkActionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            
            const selectedUserIds = Array.from(userCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (selectedUserIds.length === 0) {
                showToast('Per favore, seleziona almeno un utente.', 'warning');
                return;
            }

            // Handle the custom email action separately, as it opens a different modal.
            if (action === 'send_email') {
                openSendEmailModal(selectedUserIds);
                return;
            }

            // Prepare and open the confirmation modal for other actions
            const actionTextMap = {
                'suspend': 'sospendere',
                'reactivate': 'riattivare',
                'delete': 'eliminare definitivamente',
                'disable_emails': 'disattivare la ricezione di email per',
                'enable_emails': 'attivare la ricezione di email per'
            };
            const actionText = actionTextMap[action] || `eseguire l'azione '\${action}' su`;
            
            if (!confirmModal || !modalMessage || !confirmButton) {
                 console.error("Elementi del modale di conferma per azioni di gruppo non trovati.");
                 return;
            }
            
            modalMessage.textContent = `Sei sicuro di voler \${actionText} \${selectedUserIds.length} utente/i?`;
            
            // Store action and user IDs on the confirm button
            confirmButton.dataset.action = action;
            confirmButton.dataset.userIds = JSON.stringify(selectedUserIds);

            // Change modal button color based on action
            confirmButton.className = 'font-semibold py-2 px-5 rounded-lg'; // Reset classes
            if (action === 'delete' || action === 'suspend') {
                confirmButton.classList.add('bg-red-600', 'hover:bg-red-700');
            } else if (action === 'reactivate' || action === 'enable_emails') {
                 confirmButton.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                 confirmButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            }

            openModal('bulk-action-confirm-modal');
        });
    });

    // Add listener for the final confirmation button inside the modal
    if (confirmButton) {
        confirmButton.addEventListener('click', function() {
            const action = this.dataset.action;
            const userIds = JSON.parse(this.dataset.userIds);

            if (!action || !userIds || userIds.length === 0) {
                showToast('Errore: Azione o utenti non specificati.', 'error');
                return;
            }

            fetch('admin_user_actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    action: action,
                    userIds: userIds
                })
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message, data.success ? 'success' : 'error');
                if (data.success) {
                    // Reload the page after a short delay to allow the user to read the toast
                    setTimeout(() => window.location.reload(), 1500);
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                showToast('Si è verificato un errore durante l\'esecuzione dell\'azione.', 'error');
            })
            .finally(() => {
                closeModal('bulk-action-confirm-modal');
            });
        });
    }
});