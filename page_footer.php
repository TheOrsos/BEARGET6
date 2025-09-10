</main>
    </div>

    <script>
        // ========================================================================
        // GLOBAL UTILITY FUNCTIONS
        // ========================================================================

        /**
         * Opens a modal with a smooth transition.
         */
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            const backdrop = modal.querySelector('.modal-backdrop');
            const content = modal.querySelector('.modal-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                if (backdrop) backdrop.classList.remove('opacity-0');
                if (content) content.classList.remove('opacity-0', 'scale-95');
            }, 10);
        }

        /**
         * Closes a modal with a smooth transition.
         */
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            const backdrop = modal.querySelector('.modal-backdrop');
            const content = modal.querySelector('.modal-content');
            if (backdrop) backdrop.classList.add('opacity-0');
            if (content) content.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        /**
         * Shows a toast notification in the bottom right.
         */
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            if (!toast || !toastMessage || !toastIcon) {
                console.error('Toast notification elements not found.');
                alert(message);
                return;
            }

            toastMessage.textContent = message;
            const icons = {
                success: `<svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                error: `<svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                warning: `<svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`,
                info: `<svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
            };
            const colors = {
                success: 'bg-gray-800 border-green-600',
                error: 'bg-gray-800 border-red-600',
                warning: 'bg-gray-800 border-yellow-600',
                info: 'bg-gray-800 border-blue-600'
            };

            toastIcon.innerHTML = icons[type] || icons['info'];
            toast.className = `fixed bottom-5 right-5 w-full max-w-xs p-4 rounded-lg shadow-lg text-white border-l-4 transition-all duration-300 ease-in-out opacity-0 hidden ${colors[type] || colors['info']}`;
            
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.remove('opacity-0'), 10);
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.classList.add('hidden'), 300);
            }, 5000);
        }

        /**
         * Shows a generic confirmation modal.
         */
        function showConfirmationModal(message, onConfirm, title = 'Conferma Azione') {
            const modal = document.getElementById('generic-confirm-modal');
            const modalTitle = document.getElementById('generic-confirm-title');
            const modalMessage = document.getElementById('generic-confirm-message');
            const confirmButton = document.getElementById('generic-confirm-button');

            if (!modal || !modalMessage || !confirmButton || !modalTitle) {
                if (confirm(message)) { onConfirm(); }
                return;
            }

            modalTitle.textContent = title;
            modalMessage.textContent = message;

            const newConfirmButton = confirmButton.cloneNode(true);
            confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);

            newConfirmButton.addEventListener('click', function() {
                onConfirm();
                closeModal('generic-confirm-modal');
            });

            openModal('generic-confirm-modal');
        }

        // ========================================================================
        // GLOBAL INITIALIZATION SCRIPT
        // ========================================================================
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- 1. Responsive Sidebar Logic ---
            const sidebar = document.getElementById('sidebar');
            const menuButton = document.getElementById('menu-button');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');
            const toggleSidebar = () => {
                if (sidebar) sidebar.classList.toggle('-translate-x-full');
                if (sidebarBackdrop) sidebarBackdrop.classList.toggle('hidden');
            };
            if (menuButton) menuButton.addEventListener('click', toggleSidebar);
            if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', toggleSidebar);

            // --- 2. Real-time Notification Polling ---
            function fetchNotifications() {
                fetch('api_fetch_notifications.php')
                    .then(response => {
                        if (!response.ok) return Promise.reject(null);
                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            return response.json();
                        }
                        console.error('Notification server response is not JSON.');
                        return Promise.reject(null);
                    })
                    .then(data => {
                        if (data && data.success && data.notifications.length > 0) {
                            data.notifications.forEach(notification => {
                                showToast(notification.message, notification.type || 'info');
                            });
                        }
                    })
                    .catch(error => {
                        if (error) console.error('Error fetching notifications:', error);
                    });
            }
            // Only run if the user is logged in (check for sidebar presence as a proxy)
            if(document.getElementById('sidebar')) {
                setInterval(fetchNotifications, 15000);
                setTimeout(fetchNotifications, 2000);
            }
        });
    </script>

    <!-- Mascot & Custom Tour Feature -->
    <?php include 'mascot.php'; ?>
    <script src="tour_steps.js"></script>
    <script src="custom_tour.js"></script>

</body>
</html>