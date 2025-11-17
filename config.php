<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'logistic_corner');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

date_default_timezone_set('Asia/Jakarta');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    die(json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal.'
    ]));
}

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sendJSON($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function calculateFine($tanggalMasuk, $pdo) {
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key IN ('denda_per_hari', 'hari_gratis')");
    $stmt->execute();
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $dendaPerHari = isset($settings['denda_per_hari']) ? (int)$settings['denda_per_hari'] : 1000;
    $hariGratis = isset($settings['hari_gratis']) ? (int)$settings['hari_gratis'] : 1;

    $now = new DateTime();
    $masuk = new DateTime($tanggalMasuk);

    $workingDays = 0;
    $current = clone $masuk;

    while ($current <= $now) {
        $weekday = $current->format('w');
        $dateStr = $current->format('Y-m-d');

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM holidays WHERE tanggal = ?");
        $stmt->execute([$dateStr]);
        $isHoliday = $stmt->fetchColumn() > 0;

        if ($weekday != 0 && !$isHoliday) {
            $workingDays++;
        }

        $current->modify('+1 day');
    }

    $hariDenda = max(0, $workingDays - $hariGratis);
    return $hariDenda * $dendaPerHari;
}

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function logActivity($pdo, $userId, $action, $description, $ipAddress = null) {
    if ($ipAddress === null) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO activity_logs (user_id, action, description, ip_address)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $action, $description, $ipAddress]);
    } catch (PDOException $e) {
        error_log("Log Error: " . $e->getMessage());
    }
}

function sendWhatsAppNotification($phone, $message) {
    error_log("WhatsApp to $phone: $message");
    return true;
}

function sendEmailNotification($email, $subject, $message) {
    error_log("Email to $email: $subject - $message");
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    exit(0);
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

?>
