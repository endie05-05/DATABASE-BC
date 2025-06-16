<?php
// config.php - Konfigurasi Database dan Functions
session_start();

// Database Configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'database_bc_unand';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset untuk mendukung karakter Indonesia
mysqli_set_charset($conn, "utf8");

// Function untuk membersihkan input
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

// Function untuk generate ID otomatis
function generateID($prefix, $table, $column) {
    global $conn;
    $query = "SELECT $column FROM $table WHERE $column LIKE '$prefix%' ORDER BY $column DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastID = $row[$column];
        $number = intval(substr($lastID, strlen($prefix))) + 1;
        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    } else {
        return $prefix . '001';
    }
}

// Function untuk format rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Function untuk cek login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function untuk cek role
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function isCustomer() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'customer';
}

// Function untuk redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function untuk show alert
function showAlert($message, $type = 'info') {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification('$message', '$type');
        });
    </script>";
}
?>