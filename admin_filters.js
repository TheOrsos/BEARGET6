document.addEventListener('DOMContentLoaded', function () {
    // --- Elementi per filtri ---
    const filterId = document.getElementById('filter-id');
    const filterSearch = document.getElementById('filter-search');
    const filterSubscriptionStatus = document.getElementById('filter-subscription-status');
    const filterAccountStatus = document.getElementById('filter-account-status');
    const filterReceivesEmails = document.getElementById('filter-receives-emails');
    const resetFiltersBtn = document.getElementById('reset-filters-btn');
    const userTableBody = document.getElementById('user-table-body');
    const paginationContainer = document.getElementById('pagination-container');

    // Se gli elementi dei filtri non esistono, probabilmente non siamo nella pagina admin.php
    if (!filterId || !userTableBody || !paginationContainer) {
        return;
    }

    let currentPage = 1;

    // --- Funzione di Debounce ---
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // --- Funzione per recuperare e aggiornare gli utenti ---
    function fetchUsers(page = 1) {
        currentPage = page;
        const id = filterId.value.trim();
        const search = filterSearch.value.trim();
        const subStatus = filterSubscriptionStatus.value;
        const accStatus = filterAccountStatus.value;
        const emailStatus = filterReceivesEmails.value;

        // Mostra un indicatore di caricamento
        userTableBody.innerHTML = `<tr><td colspan="8" class="text-center p-6 text-gray-400">Caricamento...</td></tr>`;

        const params = new URLSearchParams({
            page: currentPage,
            id: id,
            search: search,
            subscription_status: subStatus,
            account_status: accStatus,
            receives_emails: emailStatus,
        });

        fetch(`ajax_filter_users.php?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    userTableBody.innerHTML = `<tr><td colspan="8" class="text-center p-6 text-red-400">Errore: ${data.error}</td></tr>`;
                    return;
                }
                userTableBody.innerHTML = data.table_body_html;
                paginationContainer.innerHTML = data.pagination_html;

                // **CRUCIALE**: Re-inizializza la logica per le azioni di gruppo dopo l'aggiornamento della tabella
                initializeBulkActions();
            })
            .catch(error => {
                console.error('Errore di rete:', error);
                userTableBody.innerHTML = `<tr><td colspan="8" class="text-center p-6 text-red-400">Si è verificato un errore di rete.</td></tr>`;
            });
    }

    const debouncedFetch = debounce(() => fetchUsers(1), 400);

    // --- Event Listener per i filtri ---
    filterId.addEventListener('input', debouncedFetch);
    filterSearch.addEventListener('input', debouncedFetch);
    filterSubscriptionStatus.addEventListener('change', () => fetchUsers(1));
    filterAccountStatus.addEventListener('change', () => fetchUsers(1));
    filterReceivesEmails.addEventListener('change', () => fetchUsers(1));

    paginationContainer.addEventListener('click', function(e) {
        if (e.target.matches('.pagination-link')) {
            e.preventDefault();
            const page = e.target.dataset.page;
            if (page) {
                fetchUsers(parseInt(page, 10));
            }
        }
    });

    resetFiltersBtn.addEventListener('click', function() {
        filterId.value = '';
        filterSearch.value = '';
        filterSubscriptionStatus.value = '';
        filterAccountStatus.value = '';
        filterReceivesEmails.value = '';
        fetchUsers(1);
    });

    // --- Logica per Azioni di Gruppo (spostata da admin_bulk_actions.js) ---
    function initializeBulkActions() {
        const selectAllCheckbox = document.getElementById('select-all-users');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const bulkActionButtons = document.querySelectorAll('.bulk-action-btn');
        const confirmModal = document.getElementById('bulk-action-confirm-modal');
        const modalMessage = document.getElementById('bulk-action-modal-message');
        const confirmButton = document.getElementById('bulk-action-confirm-button');

        if (!selectAllCheckbox) return;

        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        bulkActionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const action = this.dataset.action;
                const selectedUserIds = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);

                if (selectedUserIds.length === 0) {
                    showToast('Per favore, seleziona almeno un utente.', 'warning');
                    return;
                }

                if (action === 'send_email') {
                    openSendEmailModal(selectedUserIds);
                    return;
                }

                const actionTextMap = {
                    'suspend': 'sospendere', 'reactivate': 'riattivare', 'delete': 'eliminare definitivamente',
                    'disable_emails': 'disattivare la ricezione di email per', 'enable_emails': 'attivare la ricezione di email per'
                };
                modalMessage.textContent = `Sei sicuro di voler ${actionTextMap[action] || `eseguire l'azione '${action}' su`} ${selectedUserIds.length} utente/i?`;

                confirmButton.dataset.action = action;
                confirmButton.dataset.userIds = JSON.stringify(selectedUserIds);

                confirmButton.className = 'font-semibold py-2 px-5 rounded-lg';
                if (action === 'delete' || action === 'suspend') {
                    confirmButton.classList.add('bg-red-600', 'hover:bg-red-700');
                } else {
                    confirmButton.classList.add('bg-green-600', 'hover:bg-green-700');
                }
                openModal('bulk-action-confirm-modal');
            });
        });

        if (confirmButton) {
            // Rimuovi vecchi listener per evitare duplicazioni
            const newConfirmButton = confirmButton.cloneNode(true);
            confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);

            newConfirmButton.addEventListener('click', function() {
                const action = this.dataset.action;
                const userIds = JSON.parse(this.dataset.userIds);
                fetch('admin_user_actions.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ action: action, userIds: userIds })
                })
                .then(response => response.json())
                .then(data => {
                    showToast(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        // Aggiorna la vista invece di ricaricare la pagina
                        fetchUsers(currentPage);
                    }
                })
                .catch(error => {
                    console.error('Errore:', error);
                    showToast('Si è verificato un errore durante l\'esecuzione dell\'azione.', 'error');
                })
                .finally(() => closeModal('bulk-action-confirm-modal'));
            });
        }
    }

    // Chiamata iniziale per la logica delle azioni di gruppo
    initializeBulkActions();
});
