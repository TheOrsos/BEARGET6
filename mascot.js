document.addEventListener('DOMContentLoaded', () => {
    const mascotTrigger = document.getElementById('mascot-trigger');
    const mascotBubble = document.getElementById('mascot-bubble');
    const mascotText = document.getElementById('mascot-text');

    if (!mascotTrigger || !mascotBubble || !mascotText) {
        console.error('Mascot elements not found! Make sure mascot.php is included.');
        return;
    }

    let messages = {};

    // Function to get the current page name from the URL (e.g., "dashboard.php")
    function getCurrentPage() {
        const path = window.location.pathname;
        const page = path.split('/').pop();
        // If the path is just "/", it's the root, so default to index.php
        return page === '' ? 'index.php' : page;
    }

    // Fetch messages from the JSON file
    fetch('mascot_messages.json')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            messages = data;
            const currentPage = getCurrentPage();
            // Use the specific page message or fall back to the default message
            const message = messages[currentPage] || messages['default'];
            mascotText.innerHTML = message;
        })
        .catch(error => {
            console.error('Failed to load mascot messages:', error);
            mascotText.innerHTML = 'Oops! I had trouble finding my notes for this page. Please try again later.';
        });

    // Toggle bubble visibility on mascot click
    mascotTrigger.addEventListener('click', (event) => {
        event.stopPropagation(); // Prevents the 'click outside' listener from firing immediately

        const isHidden = mascotBubble.classList.contains('hidden');
        if (isHidden) {
            mascotBubble.classList.remove('hidden');
            // We need a tiny delay to allow the 'display' property to kick in before transitioning opacity
            setTimeout(() => {
                mascotBubble.classList.add('visible');
            }, 10);
        } else {
            mascotBubble.classList.remove('visible');
            // Wait for the transition to finish before setting display to none
            setTimeout(() => {
                mascotBubble.classList.add('hidden');
            }, 300); // Should match the transition duration in CSS
        }
    });

    // Hide bubble when clicking outside of it
    document.addEventListener('click', (event) => {
        // If the bubble is visible and the click is outside the bubble and the trigger
        if (mascotBubble.classList.contains('visible') && !mascotBubble.contains(event.target) && !mascotTrigger.contains(event.target)) {
            mascotBubble.classList.remove('visible');
            setTimeout(() => {
                mascotBubble.classList.add('hidden');
            }, 300);
        }
    });
});