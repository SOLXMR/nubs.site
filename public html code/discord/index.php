<?php
// Enable error reporting (optional during development)
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// Start session if needed
session_start();

// Database connection settings
$host = 'localhost';
$db   = 'nubsmrbf_discord_users'; // Replace with your actual database name
$user = 'nubsmrbf_dbuser';        // Replace with your actual database username
$pass = '!coYb.,(*zA4';                 // Replace with your actual database password
$charset = 'utf8mb4';

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a PDO instance
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Discord OAuth2 credentials (define these before the if statement)
$client_id = '1300533868335468564';
$client_secret = 'Y8RRbsgVS-CpxMxPL3UP_dDcVowhUSqt'; // Replace with your actual client secret
$redirect_uri = 'https://nubs.site/discord';

// Check if 'code' parameter is present
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange code for access token
    $token_url = 'https://discord.com/api/oauth2/token';

    $data = [
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'grant_type'    => 'authorization_code',
        'code'          => $code,
        'redirect_uri'  => $redirect_uri,
        'scope'         => 'identify'
    ];

    $curl = curl_init($token_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);

    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        $access_token  = $token_data['access_token'];
        $refresh_token = $token_data['refresh_token'];
        $expires_in    = $token_data['expires_in'];

        // Fetch user information
        $user_url = 'https://discord.com/api/users/@me';

        $headers = [
            "Authorization: Bearer $access_token"
        ];

        $curl = curl_init($user_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $user_response = curl_exec($curl);
        curl_close($curl);

        $user_info = json_decode($user_response, true);

        if (isset($user_info['id'])) {
            // Prepare user data
            $discord_id    = $user_info['id'];
            $username      = $user_info['username'];
            $discriminator = $user_info['discriminator'];
            $avatar        = $user_info['avatar'];

            // Insert or update user in the database
            $stmt = $pdo->prepare("INSERT INTO discord_users (discord_id, username, discriminator, avatar, access_token, refresh_token, expires_in)
                VALUES (:discord_id, :username, :discriminator, :avatar, :access_token, :refresh_token, :expires_in)
                ON DUPLICATE KEY UPDATE
                    username = VALUES(username),
                    discriminator = VALUES(discriminator),
                    avatar = VALUES(avatar),
                    access_token = VALUES(access_token),
                    refresh_token = VALUES(refresh_token),
                    expires_in = VALUES(expires_in),
                    timestamp = CURRENT_TIMESTAMP");

            $stmt->execute([
                ':discord_id'    => $discord_id,
                ':username'      => $username,
                ':discriminator' => $discriminator,
                ':avatar'        => $avatar,
                ':access_token'  => $access_token,
                ':refresh_token' => $refresh_token,
                ':expires_in'    => $expires_in
            ]);

            // Optionally, store user info in session
            $_SESSION['user'] = $user_info;

            // Display user information
            $safe_username      = htmlspecialchars($username);
            $safe_discriminator = htmlspecialchars($discriminator);
            $safe_user_id       = htmlspecialchars($discord_id);
            $avatar_url = $avatar ? "https://cdn.discordapp.com/avatars/$discord_id/$avatar.png" : "https://cdn.discordapp.com/embed/avatars/" . ($discriminator % 5) . ".png";

            echo "<h1>Welcome, $safe_username#$safe_discriminator!</h1>";
            echo "<p>Your Discord ID is: $safe_user_id</p>";
            echo "<img src='$avatar_url' alt='Avatar' style='width:150px;height:150px;'>";
            echo "<p><a href='view_users.php'>View All Connected Users</a></p>";

        } else {
            echo "Error fetching user information.";
        }
    } else {
        echo "Error obtaining access token.";
    }
} else {
    // Redirect to Discord authorization page
    $params = [
        'client_id'     => $client_id,
        'response_type' => 'code',
        'scope'         => 'identify',
        'redirect_uri'  => $redirect_uri
    ];
    $auth_url = 'https://discord.com/oauth2/authorize?' . http_build_query($params);
    header('Location: ' . $auth_url);
    exit();
}
?>
