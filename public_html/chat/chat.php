<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Add viewport meta tag for mobile -->
    <title>Nubs Chat</title>
    <style>
        /* General Styles */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc, #00b09b);
            color: #fff;
            padding-top: 20px;
            padding-bottom: 80px;
            box-sizing: border-box;
            overflow: hidden; /* Prevent body scrolling */
        }

        /* Header Styles */
        header {
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 10px 0;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 100%; /* Adjust to full width */
            align-items: center;
        }

        .nav-links {
            display: flex;
            gap: 10px; /* Reduce gap for smaller screens */
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 1em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        /* Content Styling */
        #content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            margin-top: 80px;
            padding-bottom: 100px;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden; /* Prevent content scrolling */
        }

        #chatMessages {
            width: 100%;
            max-width: 100%;
            height: 60vh;
            overflow-y: auto;
            border: 2px solid #9886e5;
            border-radius: 5px;
            background: rgba(0, 0, 0, 0.8);
            padding: 15px;
            margin-bottom: 20px;
            scroll-behavior: smooth;
        }

        #chatForm {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 10px; /* Add gap between input and button */
        }

        #message {
            width: 70%;
            padding: 12px;
            border-radius: 5px;
            border: 2px solid #9886e5;
            background-color: rgba(255, 255, 255, 0.25);
            color: #fff;
            outline: none;
            box-sizing: border-box;
        }

        button {
            padding: 15px; /* Larger button for better readability */
            font-size: 18px; /* Increased font size */
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            flex-grow: 1; /* Make button take available space */
        }

        button:hover {
            background-color: #218838;
        }

        /* Footer Styles */
        footer {
            text-align: center;
            padding: 15px;
            font-size: 0.9em;
            color: #ddd;
            background-color: rgba(0, 0, 0, 0.5);
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            /* Fix the height and prevent scrolling on mobile */
            body, html {
                height: 100vh;
                overflow: hidden;
            }

            #content {
                height: calc(100vh - 80px); /* Adjust height to fill remaining space */
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            #chatMessages {
                height: 45vh; /* Adjust height for chat box */
                width: 100%;
                max-width: 100%;
            }

            #chatForm {
                flex-direction: column;
                align-items: center;
                width: 100%;
                padding: 10px;
                gap: 10px; /* Add gap between input and button */
            }

            #message {
                width: 100%;
                padding: 15px; /* Larger padding for better readability */
            }

            button {
                width: 100%;
                padding: 15px;
                font-size: 18px;
            }
        }

        /* Small devices (phone landscape and smaller) */
        @media (max-width: 480px) {
            #chatMessages {
                height: 40vh; /* Reduce height for smaller devices */
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <nav class="navbar">
            <div class="nav-links">
                <a href="https://nubs.site/">Home</a>
                <a href="https://nubs.site/menu">Menu</a>
                <a href="https://nubs.site/support">Support</a>
            </div>
        </nav>
    </header>

    <!-- Content below the header -->
    <div id="content">
        <div id="chatMessages"></div>
        <form id="chatForm">
            <input type="text" id="message" required placeholder="Type your message...">
            <button type="submit">Send</button>
        </form>

        <!-- Adding audio for notification sound -->
        <audio id="notificationSound" src="notification.mp3" preload="auto"></audio>
    </div>

    <!-- Footer Section -->
    <footer>
        &copy; 2024 All rights reserved.
    </footer>

    <script>
        const currentUsername = '<?php echo $_SESSION["username"]; ?>'; // Store current user’s username

        let hasScrolledToBottomOnce = false; // Flag to track if it scrolled once
        let userScrolled = false; // Track whether the user manually scrolled

        // Function to scroll chat to the bottom
        function scrollToBottom(force = false) {
            const chatMessages = document.getElementById('chatMessages');
            if (!userScrolled || force) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
                hasScrolledToBottomOnce = true; // Set flag to true after first scroll
            }
        }

        // Detect if the user scrolls manually
        document.getElementById('chatMessages').addEventListener('scroll', () => {
            const chatMessages = document.getElementById('chatMessages');
            // Check if the user has scrolled away from the bottom
            if (chatMessages.scrollTop + chatMessages.clientHeight < chatMessages.scrollHeight - 50) {
                userScrolled = true;
            } else {
                userScrolled = false;
            }
        });

        document.getElementById('chatForm').onsubmit = function(e) {
            e.preventDefault();
            const message = document.getElementById('message').value;

            fetch('send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: message })
            }).then(response => {
                if (response.ok) {
                    document.getElementById('message').value = '';
                    loadMessages(); // Reload messages after sending
                    hasScrolledToBottomOnce = false; // Reset flag so it scrolls to bottom after sending a new message
                }
            });
        };

        let lastMessageCount = 0;  // To detect new messages
        const userColors = {};  // To store colors for each username

        // Function to generate random colors
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // Assign a random color to a username if not already assigned
        function assignColorToUsername(username) {
            if (!userColors[username]) {
                userColors[username] = getRandomColor();  // Assign a new random color
            }
            return userColors[username];  // Return the assigned color
        }

        function loadMessages() {
            fetch('get_messages.php')
                .then(response => response.json())
                .then(data => {
                    const chatMessages = document.getElementById('chatMessages');
                    chatMessages.innerHTML = ''; // Clear previous messages
                    data.forEach((msg, index) => {
                        const div = document.createElement('div');
                        div.className = 'message';
                        
                        // Get color for the username
                        const usernameColor = assignColorToUsername(msg.username);

                        // Break message into multiple lines if needed (130 characters per line)
                        const formattedMessage = msg.message.replace(/(.{130})/g, "$1<br>");

                        // Style the username with the assigned color and display the formatted message
                        div.innerHTML = `<span class="username" style="color: ${usernameColor};">${msg.username}</span>: ${formattedMessage} <span class="timestamp">(${new Date(msg.timestamp).toLocaleString()})</span>`;
                        
                        chatMessages.appendChild(div);

                        // Play notification if a new message comes from someone else
                        if (msg.username !== currentUsername && index >= lastMessageCount) {
                            document.getElementById('notificationSound').play();
                        }
                    });
                    lastMessageCount = data.length;  // Update the message count
                    scrollToBottom(); // Scroll to the bottom after loading new messages, unless the user scrolled up
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        // Load messages every 2 seconds
        setInterval(loadMessages, 2000);
        loadMessages(); // Initial load

        window.onload = function() {
            loadMessages(); // Load messages when the page is loaded
        };
    </script>
</body>
</html>
