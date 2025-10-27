function showConfetti() {
    confetti({
        particleCount: 500,
        spread: 2000,
        origin: {
            y: 0.2
        }
    });

    confetti({
        particleCount: 100,
        spread: 200,
        origin: {
            y: 0.5
        }
    });
}

// Show confetti when the page loads
window.addEventListener('load', function() {
    showConfetti(); // Initial display
    // Wait for 2 seconds (2000 milliseconds) and then show confetti again
    setTimeout(function() {
        showConfetti();
    }, 500);
});
