function sendAdminEmail(userId, emailType, params = {}) {
    const endpoint = 'admin_send_email.php';
    const body = {
        user_id: userId,
        email_type: emailType,
        ...params
    };
    return fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Errore di rete o del server.');
        }
        return response.json();
    })
    .then(data => {
        showToast(data.message, data.success ? 'success' : 'error');
        if (!data.success) {
            return Promise.reject(data);
        }
        return data;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // --- Elementi per filtri ---
    const filterId = document.getElementById('filter-id');
    const filterSearch = document.getElementById('filter-search');
    const filterSubscriptionStatus = document.getElementById('filter-subscription-status');
    const filterAccountStatus = document.getElementById('filter-account-status');
    const filterReceivesEmails = document.getElementById('filter-receives-emails');
    const resetFiltersBtn = document.getElementById('reset-filters-btn');
    const userTableBody = document.getElementById('user-table-body');
    const paginationContainer = document.getElementById('pagination-container');

    // --- Elementi per azioni di gruppo ---
    const selectAllCheckbox = document.getElementById('select-all-users');
    const bulkActionButtons = document.querySelectorAll('.bulk-action-btn');
    const confirmModal = document.getElementById('bulk-action-confirm-modal');
    const modalMessage = document.getElementById('bulk-action-modal-message');
    const confirmButton = document.getElementById('bulk-action-confirm-button');

    // Se gli elementi base dei filtri non esistono, la pagina non è quella giusta.
    if (!filterId || !userTableBody || !paginationContainer) {
        return;
    }

    let currentPage = 1;

    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function fetchUsers(page = 1) {
        currentPage = page;
        const id = filterId.value.trim();
        const search = filterSearch.value.trim();
        const subStatus = filterSubscriptionStatus.value;
        const accStatus = filterAccountStatus.value;
        const emailStatus = filterReceivesEmails.value;

        userTableBody.innerHTML = `<tr><td colspan="8" class="text-center p-6 text-gray-400">Caricamento...</td></tr>`;

        const params = new URLSearchParams({
            page: currentPage, id, search,
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

                // Re-inizializza la logica per le checkbox dopo aver aggiornato la tabella
                initializeBulkActionCheckboxes();
            })
            .catch(error => {
                console.error('Errore di rete:', error);
                userTableBody.innerHTML = `<tr><td colspan="8" class="text-center p-6 text-red-400">Si è verificato un errore di rete.</td></tr>`;
            });
    }

    const debouncedFetch = debounce(() => fetchUsers(1), 400);

    filterId.addEventListener('input', debouncedFetch);
    filterSearch.addEventListener('input', debouncedFetch);
    filterSubscriptionStatus.addEventListener('change', () => fetchUsers(1));
    filterAccountStatus.addEventListener('change', () => fetchUsers(1));
    filterReceivesEmails.addEventListener('change', () => fetchUsers(1));

    paginationContainer.addEventListener('click', function(e) {
        if (e.target.matches('.pagination-link')) {
            e.preventDefault();
            const page = e.target.dataset.page;
            if (page) fetchUsers(parseInt(page, 10));
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

    // --- Logica per Azioni di Gruppo ---
    function initializeBulkActionCheckboxes() {
        const currentSelectAll = document.getElementById('select-all-users');
        const currentUserCheckboxes = document.querySelectorAll('.user-checkbox');

        if (currentSelectAll) {
            currentSelectAll.addEventListener('change', function() {
                currentUserCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    }

    initializeBulkActionCheckboxes(); // Chiamata iniziale

    if (bulkActionButtons.length > 0) {
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
    }

    if (confirmButton) {
        confirmButton.addEventListener('click', function() {
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
                    // Non ricaricare più la pagina, ma aggiorna la vista
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
});
