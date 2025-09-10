import Shepherd from 'https://cdn.jsdelivr.net/npm/shepherd.js@13.0.0/dist/esm/shepherd.mjs';

document.addEventListener('DOMContentLoaded', () => {
    const mascotTrigger = document.getElementById('mascot-trigger');
    if (!mascotTrigger) {
        console.error('Mascot trigger element (#mascot-trigger) not found!');
        return;
    }

    // Function to get the current page filename from the URL
    function getCurrentPage() {
        const path = window.location.pathname;
        const page = path.split('/').pop();
        return page === '' ? 'index.php' : page;
    }

    // --- Tour Definitions ---
    // All tours are defined here. The key is the PHP filename.
    const tours = {
        'dashboard.php': [
            {
                id: 'welcome',
                title: 'Welcome to Your Dashboard!',
                text: 'Ciao! This is your central hub. Let me give you a quick tour of the main sections.',
                attachTo: { element: 'header h1', on: 'bottom' },
                buttons: [{ text: 'Next', action() { return this.next(); } }]
            },
            {
                id: 'summary-cards',
                title: 'Financial Summary',
                text: 'These three cards give you a vital, at-a-glance summary: your total balance, total income this month, and total expenses this month.',
                attachTo: { element: '.grid.grid-cols-1.md\\:grid-cols-3.gap-6', on: 'bottom' },
                buttons: [
                    { text: 'Back', secondary: true, action() { return this.back(); } },
                    { text: 'Next', action() { return this.next(); } }
                ]
            },
            {
                id: 'recent-transactions',
                title: 'Recent Transactions',
                text: 'Here you can see a list of your most recent transactions. Every new expense or income you log will appear here instantly.',
                attachTo: { element: '#recent-transactions-list', on: 'top' },
                buttons: [
                    { text: 'Back', secondary: true, action() { return this.back(); } },
                    { text: 'Next', action() { return this.next(); } }
                ]
            },
            {
                id: 'expenses-chart',
                title: 'Expenses by Category',
                text: 'This chart gives you a visual breakdown of your spending. It helps you understand where your money is going!',
                attachTo: { element: '#expensesChart', on: 'top' },
                buttons: [
                    { text: 'Back', secondary: true, action() { return this.back(); } },
                    { text: 'Next', action() { return this.next(); } }
                ]
            },
            {
                id: 'sidebar-nav',
                title: 'Navigation',
                text: 'Use this sidebar to navigate to all the different sections of the app, like Transactions, Accounts, and Budgets. This is your main control panel!',
                attachTo: { element: '#sidebar', on: 'right' },
                buttons: [
                    { text: 'Back', secondary: true, action() { return this.back(); } },
                    { text: 'Finish', action() { return this.complete(); } }
                ]
            }
        ]
        // Future tours for other pages can be added here.
    };

    // --- Tour Initialization ---
    mascotTrigger.addEventListener('click', () => {
        const currentPage = getCurrentPage();
        const tourSteps = tours[currentPage];

        // If a tour is defined for the current page, start it
        if (tourSteps) {
            const tour = new Shepherd.Tour({
                useModalOverlay: true,
                defaultStepOptions: {
                    cancelIcon: {
                        enabled: true
                    },
                    classes: 'shepherd-theme-dark', // A modern, dark theme
                    scrollTo: { behavior: 'smooth', block: 'center' }
                }
            });

            tour.addSteps(tourSteps);
            tour.start();
        } else {
            // Fallback behavior for pages without a defined tour
            const tour = new Shepherd.Tour({
                useModalOverlay: true,
                defaultStepOptions: {
                    classes: 'shepherd-theme-dark',
                    cancelIcon: { enabled: true }
                }
            });
            tour.addStep({
                title: 'No Tour Here Yet!',
                text: "I haven't been taught a tour for this page yet, but I'm learning new things every day!",
                buttons: [{ text: 'Got it!', action() { this.complete(); } }]
            });
            tour.start();
        }
    });
});