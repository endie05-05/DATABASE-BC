<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check if user is logged in and is customer
if (!isLoggedIn() || !isCustomer()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit;
}

$table = clean_input($input['table']);
$items = $input['items'];
$total = floatval($input['total']);
$payment_method = clean_input($input['payment']);
$customer_id = clean_input($input['customer_id']);

// Validate input
if (empty($table) || empty($items) || $total <= 0 || empty($payment_method)) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Check if table is available
    $table_check = "SELECT * FROM meja WHERE No_Meja = '$table' AND Status_Meja = 'KOSONG'";
    $table_result = mysqli_query($conn, $table_check);
    
    if (mysqli_num_rows($table_result) == 0) {
        throw new Exception('Meja tidak tersedia');
    }

    // Generate session ID
    $session_id = generateID('SS', 'sesi_pemesanan', 'ID_Sesi');
    
    // Create session
    $session_query = "INSERT INTO sesi_pemesanan (ID_Sesi, No_Meja, ID_Pelanggan, Tanggal_Pemesanan, Status_Sesi) 
                      VALUES ('$session_id', '$table', '$customer_id', NOW(), 'AKTIF')";
    
    if (!mysqli_query($conn, $session_query)) {
        throw new Exception('Gagal membuat sesi pemesanan');
    }

    // Update table status and link to session
    $update_table = "UPDATE meja SET Status_Meja = 'TERISI', ID_Sesi = '$session_id' WHERE No_Meja = '$table'";
    if (!mysqli_query($conn, $update_table)) {
        throw new Exception('Gagal update status meja');
    }

    // Group items by toko
    $items_by_toko = [];
    foreach ($items as $item_id => $item_data) {
        // Get item details
        $item_query = "SELECT * FROM