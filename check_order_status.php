<?php
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is logged in and is customer
if (!isLoggedIn() || !isCustomer()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$customer_id = $_SESSION['user_id'];

try {
    // Get active sessions for this customer
    $session_query = "SELECT s.ID_Sesi, s.No_Meja, s.Status_Sesi, s.Tanggal_Pemesanan,
                             t.ID_Transaksi, t.Status_Transaksi, t.Total_Transaksi,
                             tk.Nama_Toko
                      FROM sesi_pemesanan s
                      LEFT JOIN transaksi t ON s.ID_Sesi = t.ID_Sesi
                      LEFT JOIN toko tk ON t.ID_Toko = tk.ID_Toko
                      WHERE s.ID_Pelanggan = '$customer_id' 
                      AND s.Status_Sesi = 'AKTIF'
                      ORDER BY s.Tanggal_Pemesanan DESC";
    
    $session_result = mysqli_query($conn, $session_query);
    
    $sessions = [];
    $updates = [];
    
    while ($row = mysqli_fetch_assoc($session_result)) {
        $session_id = $row['ID_Sesi'];
        
        if (!isset($sessions[$session_id])) {
            $sessions[$session_id] = [
                'session_id' => $session_id,
                'table' => $row['No_Meja'],
                'status' => $row['Status_Sesi'],
                'date' => $row['Tanggal_Pemesanan'],
                'transactions' => []
            ];
        }
        
        if ($row['ID_Transaksi']) {
            $sessions[$session_id]['transactions'][] = [
                'transaction_id' => $row['ID_Transaksi'],
                'toko' => $row['Nama_Toko'],
                'status' => $row['Status_Transaksi'],
                'total' => $row['Total_Transaksi']
            ];
            
            // Check if there are any status updates
            if (in_array($row['Status_Transaksi'], ['DIPROSES', 'SELESAI', 'DIBATALKAN'])) {
                $updates[] = [
                    'id' => $row['ID_Transaksi'],
                    'status' => $row['Status_Transaksi'],
                    'toko' => $row['Nama_Toko']
                ];
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'sessions' => array_values($sessions),
        'updates' => $updates
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>