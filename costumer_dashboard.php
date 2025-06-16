<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Customer - BC UNAND Cafe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #8B4513 0%, #D2B48C 100%);
            min-height: 100vh;
            color: #2c1810;
        }

        .header {
            background: rgba(139, 69, 19, 0.9);
            color: #F5F5DC;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header h1 {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: #A0522D;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #8B4513;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .section {
            background: rgba(245, 245, 220, 0.95);
            margin-bottom: 2rem;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .section h2 {
            color: #8B4513;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            border-bottom: 2px solid #D2B48C;
            padding-bottom: 0.5rem;
        }

        .meja-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .meja-card {
            background: linear-gradient(135deg, #DEB887, #F5F5DC);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .meja-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.3);
            border-color: #8B4513;
        }

        .meja-card.selected {
            background: linear-gradient(135deg, #8B4513, #A0522D);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.4);
        }

        .meja-card.occupied {
            background: #ccc;
            color: #666;
            cursor: not-allowed;
        }

        .cafe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 1rem;
        }

        .cafe-card {
            background: linear-gradient(135deg, #A0522D, #8B4513);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .cafe-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }

        .cafe-icon {
            font-size: 2rem;
        }

        .menu-item {
            background: rgba(245, 245, 220, 0.95);
            color: #2c1810;
            margin-bottom: 0.5rem;
            padding: 1rem;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        .item-info {
            flex-grow: 1;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .item-price {
            color: #8B4513;
            font-weight: bold;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .qty-btn {
            background: #8B4513;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .qty-btn:hover {
            background: #A0522D;
            transform: scale(1.1);
        }

        .qty-display {
            background: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }

        .order-summary {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #8B4513, #A0522D);
            color: white;
            padding: 1rem 2rem;
            transform: translateY(100%);
            transition: transform 0.3s ease;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.3);
            z-index: 1000;
        }

        .order-summary.show {
            transform: translateY(0);
        }

        .summary-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-info {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .checkout-btn {
            background: #F5F5DC;
            color: #8B4513;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            background: #DEB887;
            transform: translateY(-2px);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: #F5F5DC;
            padding: 2rem;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal h3 {
            color: #8B4513;
            margin-bottom: 1rem;
            text-align: center;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #DEB887;
        }

        .payment-methods {
            margin: 1rem 0;
        }

        .payment-method {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.5rem 0;
        }

        .confirm-btn {
            width: 100%;
            background: #8B4513;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 1rem;
        }

        .close-btn {
            background: #ccc;
            color: #666;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 1rem;
            margin-right: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .cafe-grid {
                grid-template-columns: 1fr;
            }
            
            .summary-content {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php
    require_once 'config.php';

    // Check if user is logged in and is customer
    if (!isLoggedIn() || !isCustomer()) {
        redirect('login.php');
    }

    // Get customer info
    $customer_id = $_SESSION['user_id'];
    $customer_name = $_SESSION['nama'];

    // Get available tables
    $tables_query = "SELECT * FROM meja ORDER BY No_Meja";
    $tables_result = mysqli_query($conn, $tables_query);

    // Get cafes and their items
    $cafes_query = "SELECT * FROM toko ORDER BY Nama_Toko";
    $cafes_result = mysqli_query($conn, $cafes_query);
    ?>

    <div class="header">
        <h1>🏪 BC UNAND Cafe</h1>
        <div class="user-info">
            <span>Selamat datang, <?php echo $customer_name; ?></span>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </div>

    <div class="container">
        <!-- Table Selection Section -->
        <div class="section">
            <h2>📍 Pilih Meja</h2>
            <div class="meja-section">
                <?php while ($table = mysqli_fetch_assoc($tables_result)): ?>
                    <div class="meja-card <?php echo $table['Status_Meja'] == 'TERISI' ? 'occupied' : ''; ?>" 
                         onclick="selectTable('<?php echo $table['No_Meja']; ?>')"
                         data-table="<?php echo $table['No_Meja']; ?>">
                        <h3>Meja <?php echo $table['No_Meja']; ?></h3>
                        <p><?php echo $table['Status_Meja'] == 'TERISI' ? 'Terisi' : 'Tersedia'; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Menu Section -->
        <div class="section">
            <h2>🍽️ Menu Cafe</h2>
            <div class="cafe-grid">
                <?php while ($cafe = mysqli_fetch_assoc($cafes_result)): ?>
                    <div class="cafe-card">
                        <div class="cafe-header">
                            <div class="cafe-icon">🏪</div>
                            <h3><?php echo $cafe['Nama_Toko']; ?></h3>
                        </div>
                        
                        <?php
                        $items_query = "SELECT * FROM item WHERE ID_Toko = '{$cafe['ID_Toko']}' ORDER BY Nama_Item";
                        $items_result = mysqli_query($conn, $items_query);
                        ?>
                        
                        <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                            <div class="menu-item" data-item-id="<?php echo $item['ID_Item']; ?>">
                                <div class="item-info">
                                    <div class="item-name"><?php echo $item['Nama_Item']; ?></div>
                                    <div class="item-price"><?php echo formatRupiah($item['Harga_Item']); ?></div>
                                </div>
                                <div class="quantity-controls">
                                    <button class="qty-btn" onclick="changeQuantity('<?php echo $item['ID_Item']; ?>', -1)">-</button>
                                    <span class="qty-display" id="qty-<?php echo $item['ID_Item']; ?>">0</span>
                                    <button class="qty-btn" onclick="changeQuantity('<?php echo $item['ID_Item']; ?>', 1)">+</button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary" id="orderSummary">
        <div class="summary-content">
            <div class="total-info">
                <span>Total: <span id="totalAmount">Rp 0</span></span>
                <span id="itemCount" style="font-size: 0.9rem; margin-left: 1rem;">0 item</span>
            </div>
            <button class="checkout-btn" onclick="showCheckoutModal()">Pesan Sekarang</button>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal" id="checkoutModal">
        <div class="modal-content">
            <h3>📋 Rincian Pesanan</h3>
            <div id="orderDetails"></div>
            
            <div class="payment-methods">
                <h4>💳 Metode Pembayaran:</h4>
                <div class="payment-method">
                    <input type="radio" name="payment" value="TUNAI" id="tunai" checked>
                    <label for="tunai">Tunai</label>
                </div>
                <div class="payment-method">
                    <input type="radio" name="payment" value="NON-TUNAI" id="nontunai">
                    <label for="nontunai">Non-Tunai</label>
                </div>
            </div>
            
            <button class="close-btn" onclick="closeCheckoutModal()">Tutup</button>
            <button class="confirm-btn" onclick="confirmOrder()">Konfirmasi Pesanan</button>
        </div>
    </div>

    <script>
        let selectedTable = null;
        let orderItems = {};
        let totalAmount = 0;
        let itemCount = 0;

        // Menu items data (populated from PHP)
        const menuItems = {
            <?php
            mysqli_data_seek($cafes_result, 0);
            while ($cafe = mysqli_fetch_assoc($cafes_result)) {
                $items_query = "SELECT * FROM item WHERE ID_Toko = '{$cafe['ID_Toko']}'";
                $items_result = mysqli_query($conn, $items_query);
                while ($item = mysqli_fetch_assoc($items_result)) {
                    echo "'{$item['ID_Item']}': {";
                    echo "name: '{$item['Nama_Item']}',";
                    echo "price: {$item['Harga_Item']},";
                    echo "toko: '{$cafe['Nama_Toko']}'";
                    echo "},";
                }
            }
            ?>
        };

        function selectTable(tableNumber) {
            if (document.querySelector(`[data-table="${tableNumber}"]`).classList.contains('occupied')) {
                alert('Meja ini sudah terisi!');
                return;
            }

            // Remove previous selection
            document.querySelectorAll('.meja-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Select new table
            document.querySelector(`[data-table="${tableNumber}"]`).classList.add('selected');
            selectedTable = tableNumber;
        }

        function changeQuantity(itemId, change) {
            if (!selectedTable) {
                alert('Silakan pilih meja terlebih dahulu!');
                return;
            }

            const currentQty = parseInt(document.getElementById(`qty-${itemId}`).textContent);
            const newQty = Math.max(0, currentQty + change);
            
            document.getElementById(`qty-${itemId}`).textContent = newQty;
            
            if (newQty > 0) {
                orderItems[itemId] = {
                    quantity: newQty,
                    name: menuItems[itemId].name,
                    price: menuItems[itemId].price,
                    toko: menuItems[itemId].toko
                };
            } else {
                delete orderItems[itemId];
            }
            
            updateOrderSummary();
        }

        function updateOrderSummary() {
            totalAmount = 0;
            itemCount = 0;
            
            for (let itemId in orderItems) {
                const item = orderItems[itemId];
                totalAmount += item.price * item.quantity;
                itemCount += item.quantity;
            }
            
            document.getElementById('totalAmount').textContent = formatRupiah(totalAmount);
            document.getElementById('itemCount').textContent = `${itemCount} item`;
            
            const summary = document.getElementById('orderSummary');
            if (itemCount > 0) {
                summary.classList.add('show');
            } else {
                summary.classList.remove('show');
            }
        }

        function showCheckoutModal() {
            if (!selectedTable) {
                alert('Silakan pilih meja terlebih dahulu!');
                return;
            }
            
            if (itemCount === 0) {
                alert('Belum ada item yang dipilih!');
                return;
            }
            
            let orderDetailsHTML = `<p><strong>Meja: ${selectedTable}</strong></p><hr>`;
            
            for (let itemId in orderItems) {
                const item = orderItems[itemId];
                const subtotal = item.price * item.quantity;
                orderDetailsHTML += `
                    <div class="order-item">
                        <div>
                            <strong>${item.name}</strong><br>
                            <small>${item.toko}</small><br>
                            ${formatRupiah(item.price)} x ${item.quantity}
                        </div>
                        <div><strong>${formatRupiah(subtotal)}</strong></div>
                    </div>
                `;
            }
            
            orderDetailsHTML += `
                <div class="order-item" style="font-weight: bold; font-size: 1.1rem; border-top: 2px solid #8B4513; margin-top: 1rem; padding-top: 1rem;">
                    <div>Total</div>
                    <div>${formatRupiah(totalAmount)}</div>
                </div>
            `;
            
            document.getElementById('orderDetails').innerHTML = orderDetailsHTML;
            document.getElementById('checkoutModal').classList.add('show');
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.remove('show');
        }

        function confirmOrder() {
            const paymentMethod = document.querySelector('input[name="payment"]:checked').value;
            
            // Prepare order data
            const orderData = {
                table: selectedTable,
                items: orderItems,
                total: totalAmount,
                payment: paymentMethod,
                customer_id: '<?php echo $customer_id; ?>'
            };
            
            // Send order via AJAX
            fetch('process_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pesanan berhasil dikirim!');
                    closeCheckoutModal();
                    resetOrder();
                    // Show order status popup
                    showOrderStatus(data.transaction_id);
                } else {
                    alert('Gagal mengirim pesanan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem!');
            });
        }

        function resetOrder() {
            orderItems = {};
            totalAmount = 0;
            itemCount = 0;
            selectedTable = null;
            
            // Reset UI
            document.querySelectorAll('.qty-display').forEach(qty => {
                qty.textContent = '0';
            });
            document.querySelectorAll('.meja-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.getElementById('orderSummary').classList.remove('show');
        }

        function showOrderStatus(transactionId) {
            // This will show a popup with order status
            const popup = document.createElement('div');
            popup.className = 'modal show';
            popup.innerHTML = `
                <div class="modal-content">
                    <h3>✅ Pesanan Berhasil!</h3>
                    <p>Nomor Transaksi: <strong>${transactionId}</strong></p>
                    <p>Status: <strong>Menunggu Konfirmasi</strong></p>
                    <p>Meja: <strong>${selectedTable}</strong></p>
                    <button class="confirm-btn" onclick="this.parentElement.parentElement.remove()">OK</button>
                </div>
            `;
            document.body.appendChild(popup);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (popup.parentElement) {
                    popup.remove();
                }
            }, 5000);
        }

        function formatRupiah(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        function logout() {
            if (confirm('Yakin ingin logout?')) {
                window.location.href = 'logout.php';
            }
        }

        // Auto refresh order status every 30 seconds
        setInterval(() => {
            // Check for order updates
            fetch('check_order_status.php')
            .then(response => response.json())
            .then(data => {
                // Handle order status updates
                if (data.updates && data.updates.length > 0) {
                    data.updates.forEach(update => {
                        showNotification(`Pesanan ${update.id}: ${update.status}`, 'info');
                    });
                }
            })
            .catch(error => console.error('Error checking order status:', error));
        }, 30000);

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                z-index: 3000;
                animation: slideInRight 0.3s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 4000);
        }
    </script>
</body>
</html>