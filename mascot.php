<!-- Mascot Container -->
<div id="mascot-container">
    <!-- This is the trigger for the interactive tour -->
    <div id="mascot-trigger">
        <img src="assets/images/Guide.png" style="border-radius: 50%" alt="Mascot" style="width: 40px; height: 40px;"/>
    </div>
</div>

<style>
    #mascot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000; /* High z-index to stay on top */
        cursor: pointer;
    }

    #mascot-trigger {
        background-color: #007bff; /* A nice blue background */
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transition: transform 0.2s ease-in-out;
    }

    #mascot-trigger:hover {
        transform: scale(1.1);
    }
    /**
    #mascot-trigger img {
        filter: brightness(0) invert(1);  Make the SVG icon white 
    }
*/
</style>