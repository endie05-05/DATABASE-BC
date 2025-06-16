<?php
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is logged in and is customer
if (!isLoggedIn() || !isCustomer()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$customer_id = $_SESSION['user_id'];

// Get active transactions for this customer
$query = "SELECT t.ID_Transaksi, t.Status_Transaksi, s.No_Meja, tk.Nama_Toko
          FROM transaksi t 
          JOIN sesi_pemesanan s ON t.ID_Sesi = s.ID_Sesi
          JOIN toko tk ON t.ID_Toko = tk.ID_Toko
          WHERE s.ID_Pelanggan = '$customer_id' 
          AND s.Status_Sesi = 'AKTIF'
          ORDER BY t.Tanggal_Transaksi DESC";

$result = mysqli_query($conn, $query);

$updates = [];
while ($row = mysqli_fetch_assoc($result)) {
    $updates[] = [
        'id' => $row['ID_Transaksi'],
        'status' => $row['Status_Transaksi'],
        'table' => $row['No_Meja'],
        'toko' => $row['Nama_Toko']
    ];
}

echo json_encode(['success' => true, 'updates' => $updates]);
?>