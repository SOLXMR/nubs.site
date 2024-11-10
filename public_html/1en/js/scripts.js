window.addEventListener('load', () => {
    const preloader = document.getElementById('preloader');
    preloader.style.display = 'none';

    // Elements for User Interaction
    const audioPlayer = document.querySelector('.audio-player');
    const startButton = document.getElementById('start-audio');
    const audioPrompt = document.getElementById('audio-prompt');
    const audio = document.getElementById('audio');
    const playPauseBtn = document.getElementById('play-pause');
    const timeline = document.getElementById('timeline');
    const progress = document.getElementById('progress');
    const timestamp = document.getElementById('timestamp');

    // Start Audio on Button Click
    startButton.addEventListener('click', () => {
        audioPlayer.style.display = 'flex';
        audioPrompt.style.display = 'none';
        startButton.style.display = 'none';

        audio.play().catch((error) => {
            console.error('Error playing audio:', error);
        });
    });

    // Toggle Play/Pause
    playPauseBtn.addEventListener('click', () => {
        if (audio.paused) {
            audio.play();
            playPauseBtn.classList.remove('play');
            playPauseBtn.classList.add('pause');
            playPauseBtn.setAttribute('aria-label', 'Pause');
        } else {
            audio.pause();
            playPauseBtn.classList.remove('pause');
            playPauseBtn.classList.add('play');
            playPauseBtn.setAttribute('aria-label', 'Play');
        }
    });

    // Update Progress Bar and Timestamp
    audio.addEventListener('timeupdate', () => {
        const percentage = (audio.currentTime / audio.duration) * 100;
        progress.style.width = `${percentage}%`;

        let minutes = Math.floor(audio.currentTime / 60);
        let seconds = Math.floor(audio.currentTime % 60);
        if (seconds < 10) seconds = '0' + seconds;
        timestamp.textContent = `${minutes}:${seconds}`;
    });

    // Seek Audio Position
    timeline.addEventListener('click', (e) => {
        const timelineWidth = window.getComputedStyle(timeline).width;
        const timeToSeek = (e.offsetX / parseInt(timelineWidth)) * audio.duration;
        audio.currentTime = timeToSeek;
    });

    // Accessibility: Keyboard Controls
    document.addEventListener('keydown', (e) => {
        if (e.code === 'Space') {
            e.preventDefault();
            playPauseBtn.click();
        }
    });
});
