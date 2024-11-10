document.addEventListener("DOMContentLoaded", () => {
    const fudButton = document.getElementById("fudButton");
    const hypeButton = document.getElementById("hypeButton");
    const message = document.getElementById("message");

    fudButton.addEventListener("click", () => {
        message.textContent = "💥 FUD successfully obliterated!";
        message.style.color = "#76ff03";
        message.classList.add("flash-message");
        setTimeout(() => message.classList.remove("flash-message"), 1000);
    });

    hypeButton.addEventListener("click", () => {
        message.textContent = "🚀 Hype is off the charts! $FIRSTAI is taking over!";
        message.style.color = "#ff4081";
        message.classList.add("flash-message");
        setTimeout(() => message.classList.remove("flash-message"), 1000);
    });
});

/* Matrix Background Effect */
(function createMatrixEffect() {
    const canvas = document.getElementById("matrix");
    const ctx = canvas.getContext("2d");

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    const columns = canvas.width / 20;
    const drops = Array.from({ length: columns }).map(() => Math.floor(Math.random() * canvas.height));

    function drawMatrix() {
        ctx.fillStyle = "rgba(0, 0, 0, 0.05)";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = "#00ff00";
        ctx.font = "20px monospace";

        for (let i = 0; i < drops.length; i++) {
            const text = String.fromCharCode(Math.floor(65 + Math.random() * 33));
            ctx.fillText(text, i * 20, drops[i] * 20);

            if (drops[i] * 20 > canvas.height && Math.random() > 0.975) {
                drops[i] = 0;
            }
            drops[i]++;
        }
    }

    setInterval(drawMatrix, 33);
})();
