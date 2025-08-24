document.addEventListener('DOMContentLoaded', function() {
    const sidebarContainer = document.getElementById('sidebar');
    const mainContent = document.querySelector('main');
    const toggleButton = document.getElementById('sidebar-toggle-button');

    if (!sidebarContainer || !mainContent || !toggleButton) {
        return;
    }

    // Controlla lo stato iniziale dal localStorage
    if (localStorage.getItem('sidebar_is_collapsed') === 'true') {
        document.body.classList.add('sidebar-collapsed');
    }

    toggleButton.addEventListener('click', () => {
        const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebar_is_collapsed', isCollapsed);
    });

    function updateActiveLink(clickedLink) {
        // Rimuovi lo stato attivo da tutti i link nella sidebar
        const allLinks = sidebarContainer.querySelectorAll('.sidebar-link');
        allLinks.forEach(link => {
            link.classList.remove('text-white', 'bg-gray-900', 'font-semibold');
            link.classList.add('text-gray-400', 'hover:bg-gray-700', 'hover:text-white');
        });

        // Aggiungi lo stato attivo al link cliccato
        clickedLink.classList.add('text-white', 'bg-gray-900', 'font-semibold');
        clickedLink.classList.remove('text-gray-400', 'hover:bg-gray-700', 'hover:text-white');
    }

    function handleSidebarAjaxNavigation(e) {
        const clickedLink = e.target.closest('.sidebar-link');

        // Se non è stato cliccato un link valido, o se il link deve aprirsi in un'altra scheda, non fare nulla
        if (!clickedLink || clickedLink.target === '_blank') {
            return;
        }

        // Ignora il link di logout
        if (clickedLink.href.includes('logout.php')) {
            return;
        }

        e.preventDefault();
        const url = clickedLink.href;

        // Non ricaricare se si clicca sulla pagina corrente
        if (url === window.location.href) {
            return;
        }

        updateActiveLink(clickedLink);

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
                    mainContent.style.transition = 'opacity 0.2s ease-in-out';
                    mainContent.style.opacity = 0;

                    setTimeout(() => {
                        mainContent.innerHTML = newMain.innerHTML;
                        document.title = newTitle ? newTitle.textContent : 'Bearget';
                        history.pushState({path: url}, '', url);

                        // Esegui gli script del nuovo contenuto
                        const scripts = mainContent.querySelectorAll('script');
                        scripts.forEach(oldScript => {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => {
                                newScript.setAttribute(attr.name, attr.value);
                            });
                            if (oldScript.src) {
                                newScript.src = oldScript.src;
                            } else {
                                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                            }
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });

                        mainContent.style.opacity = 1;

                    }, 200);
                } else {
                    // Fallback nel caso la nuova pagina non abbia un <main>
                    window.location.href = url;
                }
            })
            .catch(error => {
                console.error('Errore nel caricamento della pagina:', error);
                window.location.href = url; // Fallback in caso di errore
            });
    }

    sidebarContainer.addEventListener('click', handleSidebarAjaxNavigation);

    // Gestisci i pulsanti avanti/indietro del browser per ricaricare la pagina
    // Questo garantisce che lo stato della pagina (incluso lo stato attivo della sidebar) sia corretto.
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.path) {
            window.location.href = e.state.path;
        } else {
            // Se non c'è state, ricarica la pagina corrente
            window.location.reload();
        }
    });
});
