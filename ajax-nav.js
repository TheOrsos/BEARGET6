document.addEventListener('DOMContentLoaded', function() {
    const navContainer = document.querySelector('.mobile-nav');
    const mainContent = document.querySelector('main');
    const moreMenu = document.getElementById('mobile-nav-more-menu');
    const moreButton = document.getElementById('mobile-nav-more-button');

    if (!navContainer || !mainContent || !moreMenu || !moreButton) {
        return;
    }

    // --- LOGICA MENU "ALTRO" ---
    moreButton.addEventListener('click', function(e) {
        e.stopPropagation(); // Impedisce che il click si propaghi al document
        moreMenu.classList.toggle('expanded');
        moreButton.classList.toggle('active');
    });

    // Funzione per chiudere il menu
    function closeMoreMenu() {
        moreMenu.classList.remove('expanded');
        moreButton.classList.remove('active');
    }

    // Chiudi il menu se si clicca fuori
    document.addEventListener('click', function(e) {
        if (!moreMenu.contains(e.target) && !moreButton.contains(e.target)) {
            closeMoreMenu();
        }
    });

    // --- LOGICA DI NAVIGAZIONE AJAX (per entrambi i menu) ---
    function handleAjaxNavigation(e) {
        const navItem = e.target.closest('.nav-item');
        if (!navItem || navItem.id === 'mobile-nav-more-button') {
            // Se è il pulsante "Altro" o non è un nav-item valido, non fare nulla
            if (navItem && navItem.id !== 'mobile-nav-more-button') e.preventDefault();
            return;
        }

        e.preventDefault();
        const url = navItem.href;

        // Non ricaricare se si clicca sulla pagina corrente
        if (url === window.location.href) {
            closeMoreMenu();
            return;
        }

        // Rimuovi 'active' da tutti gli item in entrambi i menu
        document.querySelectorAll('.mobile-nav .nav-item, .mobile-nav-more-menu .nav-item').forEach(item => {
            item.classList.remove('active');
        });

        // Aggiungi 'active' all'item cliccato
        navItem.classList.add('active');

        // Se l'item attivo è nel menu "Altro", mantieni il pulsante "Altro" non attivo
        const activeInMoreMenu = moreMenu.contains(navItem);
        if (activeInMoreMenu) {
             moreButton.classList.remove('active'); // Assicura che "Altro" non rimanga evidenziato
        }


        closeMoreMenu();

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newMain = doc.querySelector('main');
                const newTitle = doc.querySelector('title');

                if (newMain) {
                    mainContent.style.opacity = 0;
                    setTimeout(() => {
                        mainContent.innerHTML = newMain.innerHTML;
                        document.title = newTitle ? newTitle.textContent : '';
                        history.pushState({path: url}, '', url);
                        mainContent.style.opacity = 1;

                        // Esegui gli script del nuovo contenuto
                        const scripts = mainContent.querySelectorAll('script');
                        scripts.forEach(oldScript => {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => {
                                newScript.setAttribute(attr.name, attr.value);
                            });
                            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });
                    }, 200);
                }
            })
            .catch(error => {
                console.error('Errore nel caricamento della pagina:', error);
                window.location.href = url;
            });
    }

    navContainer.addEventListener('click', handleAjaxNavigation);
    moreMenu.addEventListener('click', handleAjaxNavigation);


    // Gestisci i pulsanti avanti/indietro del browser
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.path) {
            window.location.href = e.state.path;
        }
    });
});