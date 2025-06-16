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
        $item_query = "SELECT i.*, t.ID_Toko FROM item i JOIN toko t ON i.ID_Toko = t.ID_Toko WHERE i.ID_Item = '$item_id'";
        $item_result = mysqli_query($conn, $item_query);
        
        if (mysqli_num_rows($item_result) > 0) {
            $item = mysqli_fetch_assoc($item_result);
            $toko_id = $item['ID_Toko'];
            
            if (!isset($items_by_toko[$toko_id])) {
                $items_by_toko[$toko_id] = [];
            }
            
            $items_by_toko[$toko_id][] = [
                'item_id' => $item_id,
                'quantity' => $item_data['quantity'],
                'price' => $item['Harga_Item'],
                'name' => $item['Nama_Item']
            ];
        }
    }

    // Create transactions for each toko
    $transaction_ids = [];
    foreach ($items_by_toko as $toko_id => $toko_items) {
        // Generate transaction ID
        $transaction_id = generateID('TRX', 'transaksi', 'ID_Transaksi');
        $transaction_ids[] = $transaction_id;
        
        // Calculate total for this toko
        $toko_total = 0;
        foreach ($toko_items as $item) {
            $toko_total += $item['price'] * $item['quantity'];
        }
        
        // Insert transaction
        $transaction_query = "INSERT INTO transaksi (ID_Transaksi, ID_Sesi, ID_Toko, Tanggal_Transaksi, Total_Transaksi, Metode_Pembayaran, Status_Transaksi) 
                             VALUES ('$transaction_id', '$session_id', '$toko_id', NOW(), $toko_total, '$payment_method', 'MENUNGGU')";
        
        if (!mysqli_query($conn, $transaction_query)) {
            throw new Exception('Gagal membuat transaksi');
        }
        
        // Insert detail transactions
        foreach ($toko_items as $item) {
            $detail_id = generateID('DTL', 'detail_transaksi', 'ID_Detail');
            $subtotal = $item['price'] * $item['quantity'];
            
            $detail_query = "INSERT INTO detail_transaksi (ID_Detail, ID_Transaksi, ID_Item, Jumlah_Item, Subtotal_Item) 
                            VALUES ('$detail_id', '$transaction_id', '{$item['item_id']}', {$item['quantity']}, $subtotal)";
            
            if (!mysqli_query($conn, $detail_query)) {
                throw new Exception('Gagal membuat detail transaksi');
            }
        }
    }

    // Commit transaction
    mysqli_commit($conn);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Pesanan berhasil dibuat',
        'transaction_id' => implode(', ', $transaction_ids),
        'session_id' => $session_id
    ]);

} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>