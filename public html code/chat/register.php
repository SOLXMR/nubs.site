<?php
// Include database connection
include 'db.php'; // Ensure you have error reporting enabled in db.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Nubs Chat</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc, #00b09b);
            color: #fff;
            padding: 20px;
            box-sizing: border-box; /* Include padding in height */
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Card style for the main content */
        .card {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 15px;
            padding: 30px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            text-align: center; /* Center the text in card */
            animation: slideIn 0.5s; /* Slide in animation */
        }

        /* Input styles */
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #fff;
            color: #333;
        }

        button {
            width: 100%;
            padding: 15px;
            font-size: 20px;
            cursor: pointer;
            background-color: #9886e5;
            border: none;
            border-radius: 25px; /* More rounded corners */
            color: white;
            transition: background-color 0.3s, transform 0.3s; /* Add scale effect on hover */
        }

        button:hover {
            background-color: #8c7bc4;
            transform: scale(1.05); /* Slight scale effect on hover */
        }

        /* Message styles */
        #message {
            margin-top: 20px;
            font-size: 20px;
            text-align: center; 
            color: red; /* Default error color */
        }

        /* Animation keyframes */
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #ccc;
        }

        .footer a {
            color: #fff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Register</h2>
        <form action="register.php" method="post">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <div id="message">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Validate input
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                if ($password !== $confirm_password) {
                    echo "Passwords do not match!";
                } else {
                    // Add your registration logic here
                    // Example: Insert into the database

                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert into database (Make sure to handle errors properly)
                    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                    $stmt->bind_param("ss", $username, $hashed_password);

                    if ($stmt->execute()) {
                        // Redirect to registered page after successful registration
                        header("Location: https://nubs.site/registered");
                        exit(); // Ensure no further code is executed
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                }
            }
            ?>
        </div>
        <div class="footer">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>
</body>
</html>
