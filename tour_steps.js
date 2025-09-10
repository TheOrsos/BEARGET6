const tourSteps = {
    'dashboard.php': [
        {
            element: 'header h1',
            title: 'Welcome to Your Dashboard!',
            text: 'Ciao! This is your central hub. Let me give you a quick tour of the main sections.'
        },
        {
            element: '.grid.grid-cols-1.md\\:grid-cols-3.gap-6',
            title: 'Financial Summary',
            text: 'These three cards give you a vital, at-a-glance summary: your total balance, total income this month, and total expenses this month.'
        },
        {
            element: '#recent-transactions-list',
            title: 'Recent Transactions',
            text: 'Here you can see a list of your most recent transactions. Every new expense or income you log will appear here instantly.'
        },
        {
            element: '#expensesChart',
            title: 'Expenses by Category',
            text: 'This chart gives you a visual breakdown of your spending. It helps you understand where your money is going!'
        },
        {
            element: '#sidebar',
            title: 'Navigation',
            text: 'Use this sidebar to navigate to all the different sections of the app, like Transactions, Accounts, and Budgets. This is your main control panel!',
            position: 'right'
        }
    ],
    'dashboard_mobile': [
        {
            element: 'header h1',
            title: 'Welcome to Your Dashboard!',
            text: 'Ciao! This is your central hub. Let me give you a quick tour of the main sections.'
        },
        {
            element: '.grid.grid-cols-1.md\\:grid-cols-3.gap-6',
            title: 'Financial Summary',
            text: 'These three cards give you a vital, at-a-glance summary: your total balance, total income this month, and total expenses this month.'
        },
        {
            element: '#recent-transactions-list',
            title: 'Recent Transactions',
            text: 'Here you can see a list of your most recent transactions. Every new expense or income you log will appear here instantly.'
        },
        {
            element: '#expensesChart',
            title: 'Expenses by Category',
            text: 'This chart gives you a visual breakdown of your spending. It helps you understand where your money is going!'
        },
        {
            element: '#menu-button',
            title: 'Navigation Menu',
            text: 'Use this button to open the navigation menu, where you can access all the different sections of the app.',
            position: 'right'
        }
    ],
    'transactions.php': [
        {
            element: 'header h1',
            title: 'La Pagina delle Transazioni',
            text: 'Benvenuto nella pagina delle transazioni. Qui puoi vedere, cercare e gestire tutti i tuoi movimenti finanziari in un unico posto.'
        },
        {
            element: 'button[onclick="openModal(\'add-transaction-modal\')"]',
            title: 'Aggiungi un Movimento',
            text: 'Usa questo pulsante per aggiungere una nuova spesa, un\'entrata o un trasferimento tra i tuoi conti.',
            position: 'bottom'
        },
        {
            element: '#toggle-filter-btn',
            title: 'Filtra e Cerca',
            text: 'Clicca qui per aprire il pannello dei filtri. Puoi cercare transazioni per data, descrizione, conto, categoria e altro.',
            position: 'bottom'
        },
        {
            element: '#transactions-table-body',
            title: 'La Tua Lista di Movimenti',
            text: 'Tutte le tue transazioni appariranno qui. Le entrate sono in verde e le uscite in rosso.',
            position: 'top'
        },
        {
            element: 'tr[data-transaction-id] .action-buttons',
            title: 'Azioni Rapide',
            text: 'Per ogni transazione, puoi aggiungere una nota, visualizzare un allegato (se presente), modificare i dettagli o eliminarla.',
            position: 'left'
        }
    ],
    'accounts.php': [
        {
            element: 'header h1',
            title: 'I Tuoi Conti',
            text: 'Questa pagina mostra tutti i tuoi conti. Pensa a loro come a dei contenitori per il tuo denaro, come conti bancari, carte di credito o contanti.'
        },
        {
            element: 'button[onclick="openModal(\'add-account-modal\')"]',
            title: 'Aggiungi un Nuovo Conto',
            text: 'Usa questo pulsante per creare un nuovo conto. Puoi specificare un nome e un saldo iniziale.',
            position: 'bottom'
        },
        {
            element: 'div[data-account-id]',
            title: 'Scheda del Conto',
            text: 'Ogni conto è rappresentato da una scheda come questa, che mostra il nome e il saldo attuale.',
            position: 'bottom'
        },
        {
            element: 'div[data-account-id] .flex.items-center.justify-end',
            title: 'Modifica o Elimina',
            text: 'Da qui puoi modificare il nome e il saldo iniziale del conto, o eliminarlo definitivamente.',
            position: 'left'
        }
    ],
    'categories.php': [
        {
            element: 'header h1',
            title: 'Gestione Categorie',
            text: 'Benvenuto nella pagina delle categorie. Qui puoi organizzare le etichette per le tue spese ed entrate, personalizzando l\'app secondo le tue esigenze.'
        },
        {
            element: '#expense-list-container',
            title: 'Categorie di Spesa',
            text: 'Questa è la lista delle tue categorie di spesa. Ogni volta che registri un\'uscita, la assegnerai a una di queste.',
            position: 'right'
        },
        {
            element: '#income-list-container',
            title: 'Categorie di Entrata',
            text: 'Similmente, qui trovi le categorie per le tue entrate, come \'Stipendio\', \'Regali\' o \'Vendite\'.',
            position: 'left'
        },
        {
            element: '#expense-list-container + .add-form',
            title: 'Aggiungi Nuove Categorie',
            text: 'Usa questo modulo per creare una nuova categoria. Inserisci un nome, un\'icona (emoji) e clicca \'Aggiungi\'. Puoi fare lo stesso per le entrate.',
            position: 'top'
        },
        {
            element: '.handle',
            title: 'Riordina le Categorie',
            text: 'Puoi cambiare l\'ordine delle categorie come preferisci. Tieni premuto su questa icona e trascina la categoria nella posizione desiderata.',
            position: 'right'
        }
    ],
    'purchase_planner.php': [
        {
            element: 'header h1',
            title: 'Pianificatore di Acquisti',
            text: 'Benvenuto nel Pianificatore di Acquisti! Questo strumento ti aiuta a capire se e come puoi permetterti gli acquisti che desideri, analizzando le tue finanze.'
        },
        {
            element: '#wishlist-items',
            title: 'La Tua Lista dei Desideri',
            text: 'Questo pannello conterrà tutti gli oggetti che vuoi acquistare. Inizia aggiungendo il tuo primo desiderio.',
            position: 'right'
        },
        {
            element: '#add-wish-btn',
            title: 'Aggiungi un Desiderio',
            text: 'Clicca qui per aggiungere un nuovo oggetto alla tua lista. Potrai inserire il nome, il costo e altri dettagli.',
            position: 'top'
        },
        {
            element: '#analyze-button',
            title: 'Analizza i Tuoi Desideri',
            text: 'Una volta aggiunti uno o più desideri, questo pulsante si attiverà. Cliccalo per avviare l\'analisi e scoprire i consigli di Bearget.',
            position: 'top'
        },
        {
            element: '#analysis-result-container',
            title: 'I Risultati',
            text: 'I risultati dell\'analisi appariranno in questo pannello, con un verdetto e suggerimenti pratici su come raggiungere i tuoi obiettivi.',
            position: 'left'
        }
    ],
    'reports.php': [
        {
            element: 'header h1',
            title: 'Report Finanziari',
            text: 'Benvenuto nella sezione dei report. Qui puoi visualizzare i tuoi dati finanziari sotto forma di grafici per avere una visione d\'insieme chiara.'
        },
        {
            element: '.bg-gray-800.rounded-2xl.p-4',
            title: 'Filtra i Tuoi Dati',
            text: 'Usa questi filtri per personalizzare i report. Puoi selezionare un intervallo di date, un conto specifico o un\'etichetta per analizzare i dati che ti interessano.',
            position: 'bottom'
        },
        {
            element: '#expensesChart',
            title: 'Spese per Categoria',
            text: 'Questo grafico a ciambella ti mostra come sono distribuite le tue spese tra le varie categorie. È perfetto per capire dove va a finire la maggior parte del tuo denaro.',
            position: 'top'
        },
        {
            element: '#incomeExpenseChart',
            title: 'Entrate vs. Uscite',
            text: 'Questo grafico mostra l\'andamento delle tue entrate e uscite nel tempo. Ti aiuta a capire se il tuo flusso di cassa è positivo.',
            position: 'top'
        },
        {
            element: '#netWorthChart',
            title: 'Andamento Patrimonio Netto',
            text: 'Questo grafico a barre mostra l\'evoluzione del tuo patrimonio netto mese per mese. È l\'indicatore più importante della tua salute finanziaria complessiva.',
            position: 'top'
        }
    ],
    'budgets.php': [
        {
            element: 'header h1',
            title: 'I Tuoi Budget Mensili',
            text: 'Benvenuto nella sezione Budget. Qui puoi impostare dei limiti di spesa per le tue categorie e monitorare i tuoi progressi durante il mese.'
        },
        {
            element: 'button[onclick="openModal(\'add-budget-modal\')"]',
            title: 'Crea un Nuovo Budget',
            text: 'Usa questo pulsante per impostare un nuovo limite di spesa per una delle tue categorie di spesa che non ha ancora un budget.',
            position: 'bottom'
        },
        {
            element: '.flex.items-center.bg-gray-800.rounded-lg.p-1',
            title: 'Cambia Visualizzazione',
            text: 'Puoi visualizzare i tuoi budget come una lista dettagliata o come un grafico comparativo.',
            position: 'bottom'
        },
        {
            element: 'div[data-budget-id]',
            title: 'Dettaglio Budget',
            text: 'Ogni budget mostra la categoria, quanto hai speso rispetto al limite e una barra di progresso. Puoi modificare o eliminare il budget usando le icone a destra.',
            position: 'bottom'
        }
    ],
    'goals.php': [
        {
            element: 'header h1',
            title: 'Obiettivi di Risparmio',
            text: 'Benvenuto nella sezione Obiettivi. Qui puoi impostare traguardi di risparmio, come una vacanza o un nuovo gadget, e monitorare i tuoi progressi.'
        },
        {
            element: 'button[onclick="openModal(\'add-goal-modal\')"]',
            title: 'Crea un Nuovo Obiettivo',
            text: 'Usa questo pulsante per creare un nuovo obiettivo di risparmio. Inserisci un nome, l\'importo che vuoi raggiungere e una data di scadenza.',
            position: 'bottom'
        },
        {
            element: 'div[data-goal-id]',
            title: 'Scheda Obiettivo',
            text: 'Ogni obiettivo ha la sua scheda. Qui vedi il nome, quanto hai risparmiato finora rispetto al totale e una barra di progresso.',
            position: 'bottom'
        },
        {
            element: 'button[onclick^="openContributionModal"]',
            title: 'Aggiungi Fondi',
            text: 'Quando vuoi mettere da parte dei soldi per questo obiettivo, clicca qui. Potrai specificare l\'importo e da quale conto prelevarlo.',
            position: 'top'
        }
    ],
    'recurring.php': [
        {
            element: 'header h1',
            title: 'Transazioni Ricorrenti',
            text: 'In questa pagina puoi automatizzare le tue entrate e uscite regolari, come stipendi, affitti o abbonamenti.'
        },
        {
            element: 'button[onclick="openAddRecurringModal()"]',
            title: 'Aggiungi una Ricorrenza',
            text: 'Usa questo pulsante per creare una nuova transazione ricorrente. Potrai impostare la descrizione, l\'importo e la frequenza.',
            position: 'bottom'
        },
        {
            element: '#recurring-table-body',
            title: 'Le Tue Ricorrenze',
            text: 'Qui vedi tutte le tue transazioni ricorrenti, con la data della prossima scadenza e la frequenza.',
            position: 'top'
        },
        {
            element: 'tr[data-recurring-id] .flex.justify-center',
            title: 'Modifica o Elimina',
            text: 'Usa queste icone per modificare i dettagli di una ricorrenza o per eliminarla.',
            position: 'left'
        }
    ],
    'shared_funds.php': [
        {
            element: 'header h1',
            title: 'Fondi Comuni',
            text: 'Benvenuto nei Fondi Comuni. Questa funzione è perfetta per gestire spese di gruppo, come un regalo, una vacanza o le bollette tra coinquilini.'
        },
        {
            element: 'button[onclick="openModal(\'add-fund-modal\')"]',
            title: 'Crea un Nuovo Fondo',
            text: 'Usa questo pulsante per creare un nuovo fondo. Potrai dargli un nome e un obiettivo di raccolta.',
            position: 'bottom'
        },
        {
            element: 'div[data-fund-id]',
            title: 'Scheda del Fondo',
            text: 'Ogni fondo che crei o a cui partecipi apparirà qui. Mostra quanto è stato raccolto rispetto all\'obiettivo.',
            position: 'bottom'
        },
        {
            element: 'a[href^="fund_details.php"]',
            title: 'Visualizza Dettagli',
            text: 'Clicca qui per entrare nella pagina dedicata al fondo, dove potrai aggiungere contributi, invitare membri e vedere tutte le spese.',
            position: 'top'
        }
    ],
    'fund_details.php': [
        {
            element: 'header h1',
            title: 'Dettaglio del Fondo',
            text: 'Questa è la pagina principale del tuo fondo comune. Qui puoi vedere tutte le attività, i membri e i bilanci.'
        },
        {
            element: 'header .flex.gap-2',
            title: 'Azioni Principali',
            text: 'Da qui puoi aggiungere un contributo al fondo, registrare una spesa di gruppo o, se sei il creatore, chiudere il conto per saldare i debiti.',
            position: 'bottom'
        },
        {
            element: '.lg\\:col-span-2.space-y-6',
            title: 'Spese e Contributi',
            text: 'In questa colonna principale trovi il riepilogo del fondo, la lista delle spese di gruppo e lo storico di tutti i contributi versati dai membri.',
            position: 'right'
        },
        {
            element: '.lg\\:col-span-1.space-y-6',
            title: 'Membri e Bilanci',
            text: 'In questa colonna laterale vedi chi partecipa al fondo, i bilanci individuali (chi deve dare e chi deve ricevere) e puoi invitare nuovi membri.',
            position: 'left'
        }
    ],
    'friends.php': [
        {
            element: 'header h1',
            title: 'Gestione Amici',
            text: 'Benvenuto nella sezione Amici. Da qui puoi gestire i tuoi contatti, chattare e gestire prestiti o spese.'
        },
        {
            element: '#friend-code-panel',
            title: 'Il Tuo Codice Amico',
            text: 'Questo è il tuo codice amico univoco. Condividilo con i tuoi amici per permettere loro di aggiungerti. Cliccalo per copiarlo!',
            position: 'right'
        },
        {
            element: '#add-friend-panel',
            title: 'Aggiungi un Amico',
            text: 'Incolla qui il codice di un tuo amico e clicca \'Invia Richiesta\' per aggiungerlo alla tua lista.',
            position: 'right'
        },
        {
            element: '#friends-table-body',
            title: 'La Tua Lista di Amici',
            text: 'Qui vedrai tutti i tuoi amici. Per ogni amico, hai delle azioni rapide sulla destra.',
            position: 'top'
        },
        {
            element: 'tr .text-right',
            title: 'Azioni Rapide',
            text: 'Usa questi pulsanti per chattare, gestire prestiti, bloccare o rimuovere un amico.',
            position: 'left'
        }
    ],
    'tags.php': [
        {
            element: 'header h1',
            title: 'Gestione Etichette',
            text: 'Benvenuto nella gestione delle etichette (o \'tag\'). Le etichette sono un modo potente per raggruppare transazioni specifiche, ad esempio per un progetto o un evento.'
        },
        {
            element: 'button[onclick="openModal(\'add-tag-modal\')"]',
            title: 'Crea una Nuova Etichetta',
            text: 'Usa questo pulsante per creare una nuova etichetta. Ricorda di usare un nome semplice, senza spazi o caratteri speciali.',
            position: 'bottom'
        },
        {
            element: '#tags-list-container',
            title: 'Le Tue Etichette',
            text: 'Qui vedrai tutte le etichette che hai creato. Puoi modificarle o eliminarle usando le icone a destra.',
            position: 'bottom'
        }
    ],
    'notes.php': [
        {
            element: 'header h1',
            title: 'Le Tue Note',
            text: 'Benvenuto nella sezione Note. Questo è il tuo spazio personale per appunti, liste di cose da fare, o per annotare dettagli importanti legati alle transazioni.'
        },
        {
            element: '#add-note-btn',
            title: 'Crea una Nuova Nota',
            text: 'Clicca qui per creare istantaneamente una nuova nota e aprire l\'editor per iniziare a scrivere.',
            position: 'bottom'
        },
        {
            element: '#toggle-filter-btn',
            title: 'Filtra e Cerca',
            text: 'Se hai molte note, usa i filtri per trovarle rapidamente per testo, ID o data di creazione.',
            position: 'bottom'
        },
        {
            element: 'div[data-note-id]',
            title: 'Anteprima della Nota',
            text: 'Ogni nota è mostrata qui con un\'anteprima del suo contenuto. Clicca sulla scheda per aprirla e modificarla.',
            position: 'bottom'
        }
    ],
    'notifications.php': [
        {
            element: 'header h1',
            title: 'Notifiche',
            text: 'Qui trovi tutti gli avvisi importanti: richieste di amicizia, inviti a fondi comuni e altre comunicazioni.'
        },
        {
            element: '.notification-item',
            title: 'Elemento di Notifica',
            text: 'Ogni notifica è un elemento a sé. Quelle non lette sono più evidenti. Molte notifiche, come questa, hanno azioni rapide che puoi compiere direttamente.',
            position: 'bottom'
        }
    ],
    'changelog.php': [
        {
            element: 'header h1',
            title: 'Novità e Aggiornamenti',
            text: 'Benvenuto nel Changelog! Qui pubblichiamo tutti gli aggiornamenti e le nuove funzionalità di Bearget.'
        },
        {
            element: 'article',
            title: 'Articolo di Aggiornamento',
            text: 'Ogni articolo descrive un aggiornamento, con la versione, la data e i dettagli delle novità introdotte.',
            position: 'bottom'
        }
    ],
    'settings.php': [
        {
            element: 'header h1',
            title: 'Impostazioni',
            text: 'Questa è la pagina delle Impostazioni. Da qui puoi personalizzare la tua esperienza su Bearget.'
        },
        {
            element: '#theme-selection-box',
            title: 'Selezione del Tema',
            text: 'Scegli il tuo tema preferito! Clicca su un colore per applicarlo istantaneamente a tutta l\'applicazione.',
            position: 'top'
        },
        {
            element: '#profile-management-grid',
            title: 'Gestione Profilo',
            text: 'In questa sezione puoi aggiornare la tua foto profilo, modificare il tuo username e cambiare la password.',
            position: 'top'
        },
        {
            element: '#friend-code-box',
            title: 'Il Tuo Codice Amico',
            text: 'Questo è il tuo codice amico personale. Condividilo per farti aggiungere dagli amici o invitare nei fondi comuni.',
            position: 'top'
        },
        {
            element: '#subscription-management-box',
            title: 'Gestione Abbonamento',
            text: 'Da qui puoi gestire il tuo abbonamento Pro, aggiornare i dati di pagamento o passare a un piano superiore.',
            position: 'top'
        }
    ]
};
