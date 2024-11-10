<?php
// Start session if needed
session_start();

// Database connection settings (same as in index.php)
$host = 'localhost';
$db   = 'nubsmrbf_discord_users';
$user = 'nubsmrbf_dbuser';
$pass = '!coYb.,(*zA4';
$charset = 'utf8mb4';

// DSN and options
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Fetch users
$stmt = $pdo->query("SELECT * FROM discord_users ORDER BY timestamp DESC");
$users = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Connected Users</title>
    <style>
        /* Basic styling for table */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        img {
            width: 50px;
            height: 50px;
            border-radius: 25px;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Connected Discord Users</h1>
    <table>
        <tr>
            <th>Avatar</th>
            <th>Username</th>
            <th>Discord ID</th>
            <th>Connected At</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <?php
                $discord_id = htmlspecialchars($user['discord_id']);
                $username = htmlspecialchars($user['username']);
                $discriminator = htmlspecialchars($user['discriminator']);
                $avatar = htmlspecialchars($user['avatar']);
                $timestamp = htmlspecialchars($user['timestamp']);

                $avatar_url = $avatar ? "https://cdn.discordapp.com/avatars/$discord_id/$avatar.png" : "https://cdn.discordapp.com/embed/avatars/" . ($discriminator % 5) . ".png";
            ?>
            <tr>
                <td><img src="<?php echo $avatar_url; ?>" alt="Avatar"></td>
                <td><?php echo "$username#$discriminator"; ?></td>
                <td><?php echo $discord_id; ?></td>
                <td><?php echo $timestamp; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
