<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bearget - Informazioni sul Servizio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .section-title {
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        .faq {
            max-width: 800px;
            margin: 40px auto;
        }
        .faq h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .faq-item {
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .faq-question {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            font-size: 18px;
            font-weight: 600;
            padding: 15px;
            cursor: pointer;
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.3s ease;
            padding: 0 15px;
        }
        .faq-answer.open {
            max-height: 500px; /* abbastanza grande per il contenuto */
            padding: 15px;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex items-center">
            <img class=" h-12 w-auto text-indigo-600" style="width:100px; height: 100px" src="assets/images/logo_free.png" alt="Bearget Free Logo" class="w-10 h-10">
            <a href="https://bearget.kesug.com/dashboard.php" class="ml-3 text-3xl font-extrabold text-gray-900">Bearget</a>
        </div>
    </header>

    <main class="container mx-auto px-6 py-12">

        <!-- Sezione Descrizione Prodotto -->
        <section id="description" class="mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6 section-title">Cos'è Bearget</h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                Bearget è un'applicazione web di finanza personale (Software as a Service - SaaS) progettata per aiutare gli utenti a prendere il pieno controllo delle proprie finanze in modo semplice e intuitivo. L'applicazione permette di tracciare entrate e uscite, gestire più conti, creare budget personalizzati, impostare obiettivi di risparmio e automatizzare le transazioni ricorrenti.
            </p>
            <p class="mt-4 text-lg text-gray-700 leading-relaxed">
                Una delle nostre funzionalità distintive è la gestione dei <strong>Fondi Comuni</strong>, che permette a gruppi di amici o familiari di collaborare per raggiungere obiettivi finanziari condivisi, come l'organizzazione di una vacanza o la raccolta di fondi per un regalo.
            </p>
        </section>

        <!-- Sezione Funzionalità e Prezzi -->
        <section id="pricing" class="mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-8 section-title">Piani e Prezzi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Piano Gratuito -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border">
                    <h3 class="text-2xl font-bold text-gray-900">Bearget Free</h3>
                    <p class="text-gray-600 mt-2">Le funzionalità essenziali per iniziare a gestire le tue finanze.</p>
                    <p class="text-4xl font-extrabold text-gray-900 my-6">€0 <span class="text-lg font-medium text-gray-500">/ per sempre</span></p>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Gestione Transazioni Illimitate</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Gestione Conti e Categorie</li>
                    </ul>
                </div>
                <!-- Piano Pro Beta -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-indigo-600 relative">
                    <span class="absolute top-0 -translate-y-1/2 bg-indigo-600  text-sm font-semibold px-3 py-1 rounded-full">Beta</span>
                    <h3 class="text-2xl font-bold text-indigo-600">Bearget Pro (Beta)</h3>
                    <p class="text-gray-600 mt-2">Sblocca tutte le funzionalità avanzate per un controllo totale. Pagamento a titolo di supporto durante la fase beta.</p>
                    <p class="text-4xl font-extrabold text-gray-900 my-6">€4,99 <span class="text-lg font-medium text-gray-500">/ mese</span></p>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Tutto del piano Free, più:</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Report Finanziari Dettagliati</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Creazione di Budget Mensili</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Obiettivi di Risparmio</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Transazioni Ricorrenti Automatiche</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Fondi Comuni Collaborativi</li>
                    </ul>
                    <p class="mt-4 text-sm text-yellow-700 bg-yellow-100 p-3 rounded">
                        <strong>Nota legale:</strong> questa versione è in fase beta. I pagamenti servono a sostenere lo sviluppo del progetto. Non costituiscono vendita commerciale ufficiale e non verranno emesse fatture. Quando il servizio sarà ufficialmente lanciato, tutte le vendite saranno gestite legalmente con partita IVA.
                    </p>
                </div>
            </div>
        </section>

        <!-- Sezione Termini e Privacy -->
        <section id="legal" class="mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6 section-title">Termini e Condizioni</h2>
            <div class="space-y-4 text-gray-700 bg-white p-6 rounded-lg shadow">
                <h4 class="font-bold">1. Accettazione dei Termini</h4>
                <p>Utilizzando l'applicazione Bearget, l'utente accetta di essere vincolato da questi Termini di Servizio. Il servizio è fornito "così com'è".</p>
                <h4 class="font-bold">2. Descrizione del Servizio</h4>
                <p>Bearget offre un piano gratuito e un abbonamento a pagamento ("Bearget Pro Beta"). I pagamenti sono raccolti a titolo di supporto durante la fase beta e non costituiscono vendita commerciale ufficiale. L'abbonamento si rinnova automaticamente su base mensile, salvo annullamento da parte dell’utente. I pagamenti vengono elaborati in modo sicuro tramite <a href="https://stripe.com" target="_blank" class="text-indigo-600 underline">Stripe</a>, che può richiedere autenticazione aggiuntiva (Strong Customer Authentication - PSD2).</p>
                <h4 class="font-bold">3. Politica di Rimborso e Recesso</h4>
                <p>I pagamenti per gli abbonamenti beta non sono rimborsabili, salvo diversa indicazione prevista dalla legge. Gli utenti dell’UE hanno diritto a recedere entro 14 giorni, salvo che abbiano accettato l’avvio immediato del servizio e la conseguente rinuncia al diritto di recesso.</p>
                <h4 class="font-bold">4. Sicurezza</h4>
                <p>Stripe è certificata PCI DSS Level 1. L'integrazione di Bearget segue le pratiche raccomandate di sicurezza, ma l'utente è responsabile della riservatezza delle proprie credenziali di accesso.</p>
            </div>

            <h2 class="text-4xl font-bold text-gray-900 mt-12 mb-6 section-title">Informativa sulla Privacy</h2>
            <div class="space-y-4 text-gray-700 bg-white p-6 rounded-lg shadow">
                <h4 class="font-bold">1. Dati Raccolti</h4>
                <p>Raccogliamo solo i dati strettamente necessari per il funzionamento del servizio (es. email, nome utente). I dati finanziari inseriti dall'utente restano di sua esclusiva proprietà.</p>
                <h4 class="font-bold">2. Dati di Pagamento</h4>
                <p>Non memorizziamo i dati delle carte di credito. Tutte le transazioni sono gestite in modo sicuro da Stripe, conforme agli standard PCI DSS.</p>
                <h4 class="font-bold">3. Finalità e Conservazione</h4>
                <p>I dati sono trattati per fornire il servizio, adempiere a obblighi legali e, se prestato consenso, inviare comunicazioni promozionali. Sono conservati solo per il tempo necessario.</p>
                <h4 class="font-bold">4. Diritti dell’Utente</h4>
                <p>L’utente può esercitare i diritti di accesso, rettifica, cancellazione, portabilità, limitazione e opposizione.</p>
                <h4 class="font-bold">5. Cookie</h4>
                <p>Il sito utilizza cookie tecnici e, previo consenso, cookie analitici o di terze parti. Consulta la <a href="/cookie-policy.html" class="text-indigo-600 underline">Cookie Policy</a>.</p>
            </div>
        </section>

        <section class="faq">
            <h2>Domande Frequenti</h2>

            <div class="faq-item">
                <button class="faq-question">Come posso aggiungere amici?</button>
                <div class="faq-answer">
                    <p>Per aggiungere un amico, accedi alla sezione <b>“Amici”</b> e inserisci il codice dell’utente nel campo <b>“Aggiungi un Amico”</b>. Verrà inviata una richiesta che il destinatario potrà accettare o rifiutare.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Come posso cambiare la foto profilo?</button>
                <div class="faq-answer">
                    <p>Vai nella sezione <b>“Visualizza profilo”</b>, carica una nuova immagine nella sezione <b>“Foto profilo”</b> e salva le modifiche.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Come posso cambiare la password?</button>
                <div class="faq-answer">
                    <p>Accedi a <b>“Visualizza profilo”</b> e apri la sezione <b>“Cambia password”</b>. Inserisci la password attuale, digita quella nuova e confermala.  
                    Se hai dimenticato la password, nella pagina di login clicca su <b>“Password dimenticata?”</b>, inserisci la tua email e segui le istruzioni per reimpostarla.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Come posso modificare il tema grafico?</button>
                <div class="faq-answer">
                    <p>Vai su <b>“Visualizza profilo”</b> e scegli uno dei temi disponibili. I temi animati sono riservati agli utenti con piano <b>PRO</b>.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Come posso cambiare il piano di abbonamento?</button>
                <div class="faq-answer">
                    <p>Accedi a <b>“Visualizza profilo”</b>, entra nella sezione <b>“Gestisci abbonamento”</b> e clicca sul pulsante dedicato. Segui le istruzioni che verranno mostrate nella pagina successiva.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Perché il mio account è stato sospeso?</button>
                <div class="faq-answer">
                    <p>La sospensione di un account può avvenire su decisione dell’amministratore. Controlla la tua email per verificare le motivazioni.  
                    Se desideri richiedere il ripristino, contatta il supporto all’indirizzo <b>bearget.theorsos@gmail.com</b>.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Perché non ricevo le email?</button>
                <div class="faq-answer">
                    <p>Verifica che le notifiche siano abilitate: vai su <b>“Visualizza profilo”</b> → <b>“Modifica profilo”</b> e controlla l’opzione <b>“Ricevi email e notifiche”</b>.  
                    Se l’opzione è già attiva e il problema persiste, contatta il supporto a <b>bearget.theorsos@gmail.com</b>.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Perché non trovo la mia fattura?</button>
                <div class="faq-answer">
                    <p>Al momento Bearget è in fase <b>beta</b>. I pagamenti effettuati servono a sostenere lo sviluppo del progetto e non costituiscono una transazione commerciale ufficiale, pertanto non vengono emesse fatture.  
                    Per ulteriori chiarimenti puoi contattare il supporto a <b>bearget.theorsos@gmail.com</b>.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">È possibile importare o esportare i miei dati?</button>
                <div class="faq-answer">
                    <p>Sì. Nella sezione <b>“Transazioni”</b> troverai in alto i pulsanti per importare o esportare i dati in formato <b>CSV</b> (Data, Descrizione, Importo, Categoria). Puoi anche selezionare i conti da cui esportare o su cui importare i dati.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Come posso contattare il supporto?</button>
                <div class="faq-answer">
                    <p>Ti invitiamo prima a consultare le domande frequenti, che spesso contengono la soluzione più rapida.  
                    Se il problema persiste, puoi contattare il supporto scrivendo a <b>bearget.theorsos@gmail.com</b>.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Ho un problema diverso, cosa posso fare?</button>
                <div class="faq-answer">
                    <p>In caso di problemi non elencati, scrivi a <b>bearget.theorsos@gmail.com</b>. Il nostro team di supporto ti risponderà il prima possibile.</p>
                </div>
            </div>
        </section>

        <!-- Sezione Contatti -->
        <section id="contact">
            <h2 class="text-4xl font-bold text-gray-900 mb-6 section-title">Contatti</h2>
            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-lg text-gray-700">Per qualsiasi domanda, richiesta di supporto o informazione, non esitare a contattarci.</p>
                <ul class="mt-4 space-y-2">
                    <li><strong>Nome:</strong> Orso Christian</li>
                    <li><strong>Email:</strong> <a href="bearget.theorsos@gmail.com" class="text-indigo-600 hover:underline">bearget.theorsos@gmail.com</a></li>
                    <li><strong>Locazione:</strong> Italia</li>
                </ul>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-white mt-12">
        <div class="container mx-auto px-6 py-4 text-center text-gray-600">
            &copy; <?php echo date("Y"); ?> Bearget. Tutti i diritti riservati.
        </div>
    </footer>

    <!-- Banner Cookie -->
    <div id="cookie-banner" class="fixed bottom-0 left-0 w-full bg-white shadow-lg p-4 z-50 hidden">
      <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
        <p class="text-gray-700 text-sm">
          Questo sito utilizza cookie tecnici e, previo consenso, cookie analitici e di terze parti.
          Puoi accettare, rifiutare o personalizzare le tue preferenze.
        </p>
        <div class="flex space-x-2">
          <button id="accept-cookies" class="px-3 py-1 bg-green-600  rounded hover:bg-green-700">Accetta</button>
          <button id="reject-cookies" class="px-3 py-1 bg-red-600  rounded hover:bg-red-700">Rifiuta</button>
          <button id="customize-cookies" class="px-3 py-1 bg-gray-600  rounded hover:bg-gray-700">Personalizza</button>
        </div>
      </div>
    </div>

    <script>
    // Mostra banner se non è stata fatta una scelta
    if (!localStorage.getItem("cookieChoice")) {
      document.getElementById("cookie-banner").classList.remove("hidden");
    }

    document.getElementById("accept-cookies").addEventListener("click", function() {
      localStorage.setItem("cookieChoice", "accepted");
      document.getElementById("cookie-banner").classList.add("hidden");
    });

    document.getElementById("reject-cookies").addEventListener("click", function() {
      localStorage.setItem("cookieChoice", "rejected");
      document.getElementById("cookie-banner").classList.add("hidden");
    });

    document.getElementById("customize-cookies").addEventListener("click", function() {
      alert("Qui puoi aprire un pannello per gestire le preferenze dei cookie.");
    });

  const questions = document.querySelectorAll(".faq-question");

  questions.forEach(button => {
    button.addEventListener("click", () => {
      const answer = button.nextElementSibling;
      const isOpen = answer.classList.contains("open");

      // Chiudi tutte le altre
      document.querySelectorAll(".faq-answer").forEach(a => {
        a.classList.remove("open");
      });

      // Se non era già aperta, aprila
      if (!isOpen) {
        answer.classList.add("open");
      }
    });
  });
    </script>

</body>
</html>
