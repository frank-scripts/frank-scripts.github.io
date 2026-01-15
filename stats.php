<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow from GitHub Pages

$discordId = $_GET['discord'] ?? '';
if (!$discordId) die(json_encode(['error' => 'No ID']));

// Your DB connection (replace with yours!)
$pdo = new PDO('mysql:host=localhost;dbname=your_db', 'user', 'pass');
$stmt = $pdo->prepare("SELECT playtime, first_joined FROM users WHERE discord = ? OR identifier LIKE ?");
$stmt->execute(["discord:$discordId", "%discord:$discordId%"]);
$row = $stmt->fetch();

if ($row) {
    $minutes = $row['playtime'];
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    echo json_encode([
        'playtime' => "{$hours}h {$mins}m",
        'firstJoin' => date('Y-m-d', $row['first_joined'] ?? time())
    ]);
} else {
    echo json_encode(['error' => 'No data found']);
}
?>
