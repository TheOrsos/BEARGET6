class CustomTour {
    constructor(steps) {
        this.steps = steps;
        this.currentStep = 0;
        this.overlay = null;
        this.tooltip = null;
        
        // Bind methods to ensure 'this' context is correct
        this.next = this.next.bind(this);
        this.back = this.back.bind(this);
        this.end = this.end.bind(this);
        this.handleResize = this.handleResize.bind(this);
    }

    createDOMElements() {
        // Create overlay
        this.overlay = document.createElement('div');
        this.overlay.id = 'custom-tour-overlay';
        this.overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 10000;
            background: rgba(0, 0, 0, 0.5);
            pointer-events: none;
            transition: box-shadow 0.3s ease-in-out;
        `;

        // Create tooltip
        this.tooltip = document.createElement('div');
        this.tooltip.id = 'custom-tour-tooltip';
        this.tooltip.style.cssText = `
            position: fixed;
            z-index: 10001;
            background: #333;
            color: white;
            padding: 15px;
            border-radius: 8px;
            max-width: 300px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: top 0.3s, left 0.3s, opacity 0.3s;
            opacity: 0;
        `;
        this.tooltip.innerHTML = `
            <div id="tour-tooltip-content">
                <h3 id="tour-title" style="margin: 0 0 10px; font-size: 1.1em; font-weight: bold;"></h3>
                <p id="tour-text" style="margin: 0;"></p>
            </div>
            <div id="tour-tooltip-nav" style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                <button id="tour-close-btn" style="background: none; border: none; color: #aaa; cursor: pointer;">Close</button>
                <div>
                    <button id="tour-back-btn" style="background: #555; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; margin-right: 5px;">Back</button>
                    <button id="tour-next-btn" style="background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Next</button>
                </div>
            </div>
        `;

        document.body.appendChild(this.overlay);
        document.body.appendChild(this.tooltip);

        // Add event listeners
        document.getElementById('tour-next-btn').addEventListener('click', this.next);
        document.getElementById('tour-back-btn').addEventListener('click', this.back);
        document.getElementById('tour-close-btn').addEventListener('click', this.end);
        window.addEventListener('resize', this.handleResize);
    }

    async showStep(index) {
        if (index < 0 || index >= this.steps.length) {
            this.end();
            return;
        }
        this.currentStep = index;
        const step = this.steps[index];

        // --- NEW: beforeShow Hook ---
        if (step.beforeShow && typeof step.beforeShow === 'function') {
            await step.beforeShow();
        }
        // --- END NEW ---

        const targetElement = document.querySelector(step.element);

        if (!targetElement) {
            console.error(`Tour step ${index}: Element "${step.element}" not found. Tour cannot continue.`);
            this.showErrorStep(step.element);
            return;
        }

        // --- NEW: Scroll into View ---
        targetElement.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
        // Wait for the smooth scroll to finish before proceeding.
        // This is a simple way to handle it, as scrollIntoView doesn't return a promise.
        await new Promise(resolve => setTimeout(resolve, 500));
        // --- END NEW ---

        const rect = targetElement.getBoundingClientRect();

        // Update tooltip content
        document.getElementById('tour-title').textContent = step.title;
        document.getElementById('tour-text').textContent = step.text;
        
        // Update button visibility
        document.getElementById('tour-back-btn').style.display = (index === 0) ? 'none' : 'inline-block';
        const nextBtn = document.getElementById('tour-next-btn');
        nextBtn.textContent = (index === this.steps.length - 1) ? 'Finish' : 'Next';

        // --- NEW: Save original style before modifying ---
        targetElement.dataset.originalCssText = targetElement.style.cssText;
        // --- END NEW ---

        // Highlight element with box-shadow
        this.overlay.style.boxShadow = `0 0 0 9999px rgba(0, 0, 0, 0.7)`;
        targetElement.style.transition = 'box-shadow 0.3s';
        targetElement.style.boxShadow = `0 0 0 5px rgba(51, 153, 255, 0.5)`;
        targetElement.style.position = 'relative';
        targetElement.style.zIndex = '10001';

        // Position tooltip
        const tooltipRect = this.tooltip.getBoundingClientRect();
        let top, left;

        // Default position is 'bottom'
        const position = step.position || 'bottom';

        if (position === 'bottom') {
            top = rect.bottom + 10;
            left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
        } else if (position === 'top') {
            top = rect.top - tooltipRect.height - 10;
            left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
        } else if (position === 'right') {
            top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
            left = rect.right + 10;
        } else if (position === 'left') {
            top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
            left = rect.left - tooltipRect.width - 10;
        }

        // Adjust if off-screen
        if (left < 0) left = 5;
        if (top < 0) top = 5;
        if (left + tooltipRect.width > window.innerWidth) left = window.innerWidth - tooltipRect.width - 5;
        if (top + tooltipRect.height > window.innerHeight) top = window.innerHeight - tooltipRect.height - 5;

        this.tooltip.style.top = `${top}px`;
        this.tooltip.style.left = `${left}px`;
        this.tooltip.style.opacity = '1';
    }
    
    showErrorStep(selector) {
        document.getElementById('tour-title').textContent = 'Tour Error';
        document.getElementById('tour-text').textContent = `Required element ("${selector}") was not found on the page. The tour cannot continue.`;
        
        // Hide nav buttons, show only close button
        document.getElementById('tour-back-btn').style.display = 'none';
        document.getElementById('tour-next-btn').style.display = 'none';
        document.getElementById('tour-close-btn').style.display = 'inline-block';

        // Position tooltip in the center of the screen
        this.tooltip.style.top = '50%';
        this.tooltip.style.left = '50%';
        this.tooltip.style.transform = 'translate(-50%, -50%)';
        this.tooltip.style.opacity = '1';

        // Make overlay visible but without a "hole"
        this.overlay.style.boxShadow = `0 0 0 9999px rgba(0, 0, 0, 0.7)`;
    }

    cleanupCurrentStep() {
        if (this.currentStep >= 0 && this.currentStep < this.steps.length) {
            const step = this.steps[this.currentStep];
            const targetElement = document.querySelector(step.element);
            if (targetElement) {
                // --- NEW: Restore original style ---
                if (targetElement.dataset.originalCssText !== undefined) {
                    targetElement.style.cssText = targetElement.dataset.originalCssText;
                } else {
                    // Fallback for safety, though originalCssText should always exist
                    targetElement.style.boxShadow = '';
                    targetElement.style.zIndex = '';
                    targetElement.style.position = '';
                }
                // --- END NEW ---
            }
        }
    }

    async next() {
        this.cleanupCurrentStep();
        await this.showStep(this.currentStep + 1);
    }

    async back() {
        this.cleanupCurrentStep();
        await this.showStep(this.currentStep - 1);
    }
    
    handleResize() {
        this.showStep(this.currentStep);
    }

    end() {
        this.cleanupCurrentStep();
        if (this.overlay) this.overlay.remove();
        if (this.tooltip) this.tooltip.remove();
        window.removeEventListener('resize', this.handleResize);
    }

    async start() {
        this.createDOMElements();
        // A small delay to ensure the tooltip has dimensions for positioning
        await new Promise(resolve => setTimeout(resolve, 50));
        await this.showStep(0);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const mascotTrigger = document.getElementById('mascot-trigger');
    if (!mascotTrigger) {
        console.error('Mascot trigger not found!');
        return;
    }

    function getCurrentPage() {
        const path = window.location.pathname;
        const page = path.split('/').pop();
        return page === '' ? 'index.php' : page;
    }

    mascotTrigger.addEventListener('click', async () => {
        const currentPage = getCurrentPage();
        let stepsForPage = tourSteps[currentPage];

        // --- NEW: Dynamic tour selection for dashboard ---
        if (currentPage === 'dashboard.php') {
            const menuButton = document.getElementById('menu-button');
            // If menuButton is visible, it means we are in mobile view.
            if (menuButton && menuButton.offsetParent !== null) {
                stepsForPage = tourSteps['dashboard_mobile'];
            }
        }
        // --- END NEW ---

        // Handle special case for transactions page if it's empty
        if (currentPage === 'transactions.php' && document.getElementById('empty-state-transactions')) {
            // Use a shorter version of the tour if no transactions exist
            stepsForPage = [
                tourSteps['transactions.php'][0], // Welcome message
                tourSteps['transactions.php'][1], // Add transaction button
                tourSteps['transactions.php'][2]  // Filter button
            ];
            // Add a custom final step for the empty state
            stepsForPage.push({
                element: '#empty-state-transactions',
                title: 'Inizia ad Aggiungere!',
                text: 'Questa lista è vuota. Clicca sul pulsante "Aggiungi Movimento" per registrare la tua prima transazione e vedere la magia!',
                position: 'top'
            });
        } else if (currentPage === 'accounts.php' && document.getElementById('empty-state')) {
            // Use a shorter version of the tour if no accounts exist
            stepsForPage = [
                tourSteps['accounts.php'][0], // Welcome message
                {
                    element: '#empty-state',
                    title: 'Inizia da Qui',
                    text: 'Per iniziare a tracciare le tue finanze, devi prima creare un conto. Usa il pulsante qui sotto o quello in alto a destra.',
                    position: 'top'
                }
            ];
        } else if (currentPage === 'budgets.php' && document.getElementById('empty-state-budgets')) {
            // Use a shorter version of the tour if no budgets exist
            stepsForPage = [
                tourSteps['budgets.php'][0], // Welcome message
                {
                    element: '#empty-state-budgets',
                    title: 'Crea il Tuo Primo Budget',
                    text: 'Sembra che tu non abbia ancora impostato nessun budget. Clicca il pulsante qui sotto o quello in alto per iniziare!',
                    position: 'top'
                }
            ];
        } else if (currentPage === 'goals.php' && document.getElementById('empty-state-goals')) {
            // Use a shorter version of the tour if no goals exist
            stepsForPage = [
                tourSteps['goals.php'][0], // Welcome message
                {
                    element: '#empty-state-goals',
                    title: 'Crea il Tuo Primo Obiettivo',
                    text: 'Questa pagina è pronta per accogliere i tuoi sogni. Clicca il pulsante qui sotto o quello in alto per creare il tuo primo obiettivo di risparmio.',
                    position: 'top'
                }
            ];
        } else if (currentPage === 'recurring.php' && document.getElementById('empty-state-recurring')) {
            // Use a shorter version of the tour if no recurring transactions exist
            stepsForPage = [
                tourSteps['recurring.php'][0], // Welcome message
                {
                    element: '#empty-state-recurring',
                    title: 'Aggiungi la Tua Prima Ricorrenza',
                    text: 'Non hai ancora nessuna transazione ricorrente. Clicca sul pulsante \'Aggiungi\' in alto a destra per iniziare. È perfetto per stipendi, abbonamenti, affitti, ecc.',
                    position: 'top'
                }
            ];
        } else if (currentPage === 'shared_funds.php' && document.getElementById('empty-state-funds')) {
            // Use a shorter version of the tour if no shared funds exist
            stepsForPage = [
                tourSteps['shared_funds.php'][0], // Welcome message
                {
                    element: '#empty-state-funds',
                    title: 'Crea il Tuo Primo Fondo',
                    text: 'Non fai ancora parte di nessun fondo. Creane uno per iniziare a raccogliere soldi per un obiettivo comune!',
                    position: 'top'
                }
            ];
        } else if (currentPage === 'friends.php' && document.querySelector('#friends-table-body td[colspan="5"]')) {
            // Use a shorter version of the tour if no friends exist
            stepsForPage = [
                tourSteps['friends.php'][0], // Welcome message
                tourSteps['friends.php'][1], // Your code
                tourSteps['friends.php'][2]  // Add friend
            ];
        } else if (currentPage === 'tags.php' && document.getElementById('empty-state-tags')) {
            // Use a shorter version of the tour if no tags exist
            stepsForPage = [
                tourSteps['tags.php'][0], // Welcome message
                {
                    element: '#empty-state-tags',
                    title: 'Crea la Tua Prima Etichetta',
                    text: 'Non hai ancora creato nessuna etichetta. Clicca il pulsante \'Nuova Etichetta\' in alto a destra per iniziare. Potrai poi usarle per filtrare le tue transazioni.',
                    position: 'bottom'
                }
            ];
        } else if (currentPage === 'notes.php' && document.getElementById('empty-state-notes')) {
            // Use a shorter version of the tour if no notes exist
            stepsForPage = [
                tourSteps['notes.php'][0], // Welcome message
                tourSteps['notes.php'][1], // Add note button
                {
                    element: '#empty-state-notes',
                    title: 'Crea la Tua Prima Nota',
                    text: 'Questa pagina è vuota. Clicca sul pulsante "Crea Nuova Nota" per iniziare a scrivere il tuo primo appunto!',
                    position: 'bottom'
                }
            ];
        } else if (currentPage === 'notifications.php' && document.getElementById('empty-state-notifications')) {
            // Use a shorter version of the tour if no notifications exist
            stepsForPage = [
                tourSteps['notifications.php'][0], // Welcome message
                {
                    element: '#empty-state-notifications',
                    title: 'Nessuna Notifica',
                    text: 'Al momento non ci sono nuove notifiche. Torna più tardi!',
                    position: 'bottom'
                }
            ];
        } else if (currentPage === 'changelog.php' && document.querySelector('.text-center.py-16')) {
            // Use a shorter version of the tour if no changelog entries exist
            stepsForPage = [
                tourSteps['changelog.php'][0], // Welcome message
                {
                    element: '.text-center.py-16',
                    title: 'Nessun Aggiornamento',
                    text: 'Sembra che non ci siano ancora aggiornamenti da mostrare, ma stiamo lavorando a tante novità!',
                    position: 'bottom'
                }
            ];
        }

        if (stepsForPage && stepsForPage.length > 0) {
            const tour = new CustomTour(stepsForPage);
            await tour.start();
        } else {
            // Fallback for pages without a defined tour
            const tour = new CustomTour([{
                element: '#mascot-trigger',
                title: 'No Tour Here Yet!',
                text: "I haven't been taught a tour for this page yet, but I'm learning new things every day!",
                position: 'top'
            }]);
            await tour.start();
        }
    });
});
