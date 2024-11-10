<?php
require_once 'config.php';

function logMessage($message, $isError = false) {
    $logFile = $isError ? 'error_log' : 'bot_log.txt';
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

function fetchData($url, $cacheFile) {
    $cacheDir = __DIR__ . '/cache';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }
    
    $cachePath = "$cacheDir/$cacheFile";
    $cacheTime = 60 * 5; // 5 minutes cache time

    if (file_exists($cachePath) && (time() - filemtime($cachePath) < $cacheTime)) {
        return json_decode(file_get_contents($cachePath), true);
    } else {
        $data = file_get_contents($url);
        if ($data === false) {
            logMessage("Failed to retrieve data from $url", true);
            return null;
        }

        file_put_contents($cachePath, $data);
        return json_decode($data, true);
    }
}

function getSolPrice() {
    $url = 'https://api.coingecko.com/api/v3/simple/price?ids=solana&vs_currencies=usd';
    $data = fetchData($url, 'sol_price.json');
    return $data['solana']['usd'] ?? null;
}

function sendMessage($chatId, $text) {
    $botToken = BOT_TOKEN;
    $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($text);
    $response = file_get_contents($url);

    if ($response === false) {
        logMessage("Failed to send message to $chatId", true);
    } else {
        logMessage("Message sent to $chatId: $text");
    }
}

function sendPhoto($chatId, $photoPath, $caption) {
    $botToken = BOT_TOKEN;
    $url = "https://api.telegram.org/bot$botToken/sendPhoto";

    $postData = [
        'chat_id' => $chatId,
        'caption' => $caption,
        'photo' => new CURLFile(realpath($photoPath)) // Use local path for the photo
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);

    $response = curl_exec($ch);
    if ($response === false) {
        logMessage("Curl error: " . curl_error($ch), true);
    } else {
        logMessage("Photo sent successfully: " . $response);
    }
    curl_close($ch);
}

function getMarketChart() {
    $url = 'https://api.coingecko.com/api/v3/coins/solana/market_chart?vs_currency=usd&days=1';
    $data = fetchData($url, 'market_chart.json');

    if (!$data || !isset($data['prices'])) {
        logMessage("Error retrieving market chart data", true);
        return false;
    }

    $imagePath = __DIR__ . '/solana_chart.png';
    $img = imagecreatetruecolor(700, 300);
    $bgColor = imagecolorallocate($img, 240, 240, 240);
    $lineColor = imagecolorallocate($img, 50, 100, 150);
    $textColor = imagecolorallocate($img, 0, 0, 0);
    imagefilledrectangle($img, 0, 0, 700, 300, $bgColor);

    $maxPrice = max(array_column($data['prices'], 1));
    $minPrice = min(array_column($data['prices'], 1));

    $prevX = 0;
    $prevY = 300 - (($data['prices'][0][1] - $minPrice) / ($maxPrice - $minPrice)) * 300;

    for ($i = 1; $i < count($data['prices']); $i++) {
        $x = ($i / (count($data['prices']) - 1)) * 700;
        $y = 300 - (($data['prices'][$i][1] - $minPrice) / ($maxPrice - $minPrice)) * 300;

        imageline($img, $prevX, $prevY, $x, $y, $lineColor);
        $prevX = $x;
        $prevY = $y;
    }

    $fontPath = __DIR__ . '/fonts/arial.ttf';
    if (!file_exists($fontPath)) {
        logMessage("Font file not found at $fontPath", true);
    } else {
        imagettftext($img, 10, 0, 10, 290, $textColor, $fontPath, "Solana (SOL) Market Chart - Last 24 hours");
    }

    imagepng($img, $imagePath);
    imagedestroy($img);

    return $imagePath;
}

function logAnalytics($chatId, $username, $command) {
    $analyticsDir = __DIR__ . '/analytics';
    if (!is_dir($analyticsDir)) {
        mkdir($analyticsDir, 0777, true);
    }

    $userFile = "$analyticsDir/user_$chatId.json";

    if (file_exists($userFile)) {
        $analyticsData = json_decode(file_get_contents($userFile), true);
    } else {
        $analyticsData = [
            'user_id' => $chatId,
            'username' => $username,
            'first_use' => date('Y-m-d H:i:s'),
            'last_use' => null,
            'commands_used' => []
        ];
    }

    $analyticsData['username'] = $username; // Ensure username is updated in each interaction
    $analyticsData['last_use'] = date('Y-m-d H:i:s');
    if (!isset($analyticsData['commands_used'][$command])) {
        $analyticsData['commands_used'][$command] = 0;
    }
    $analyticsData['commands_used'][$command] += 1;

    file_put_contents($userFile, json_encode($analyticsData, JSON_PRETTY_PRINT));
}

// Fetching bot updates
$update = json_decode(file_get_contents('php://input'), true);

// Extract user details
$chatId = $update['message']['chat']['id'];
$username = isset($update['message']['from']['username']) ? $update['message']['from']['username'] : "User_$chatId";

// Log an explicit message if username is missing to aid troubleshooting
if ($username === "User_$chatId") {
    logMessage("Username not found for user ID $chatId, defaulting to placeholder.");
}

$text = $update['message']['text'];

// Call logAnalytics with the correct or placeholder username
logAnalytics($chatId, $username, $text);

if ($text === '/start') {
    $logoPath = __DIR__ . '/logo1.png';
    if (file_exists($logoPath)) {
        $welcomeMessage = "Welcome to nubsBOT! Type / to see the full list of commands!";
        sendPhoto($chatId, $logoPath, $welcomeMessage);
    } else {
        logMessage("Logo file not found at $logoPath", true);
        sendMessage($chatId, "Welcome to nubsBOT! However, the logo image is currently unavailable.");
    }
} elseif ($text === '/sol_price') {
    $price = getSolPrice();
    if ($price) {
        sendMessage($chatId, "The current price of Solana (SOL) is: \$$price USD.");
    } else {
        sendMessage($chatId, "Error retrieving Solana price.");
    }
} elseif ($text === '/market_chart') {
    $chartPath = getMarketChart();
    if ($chartPath) {
        sendPhoto($chatId, $chartPath, 'Here is the Solana market chart for the last 24 hours.');
    } else {
        sendMessage($chatId, "Error retrieving market chart data.");
    }
} elseif ($text === '/connect_discord') {
    sendMessage($chatId, "Connect your Discord to Nubs for a surprise! Connect here: https://nubs.site/discord");
}
?>
