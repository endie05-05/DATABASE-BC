<?php
// auth.php - Fixed authentication
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_type = clean_input($_POST['login_type']);
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    if (empty($username) || empty($password)) {
        redirect('login.php?error=empty');
    }

    if ($login_type == 'admin') {
        // Admin login
        $query = "SELECT * FROM admin WHERE Username = '$username' AND Password = MD5('$password')";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $admin['ID_Admin'];
            $_SESSION['username'] = $admin['Username'];
            $_SESSION['nama'] = $admin['Nama_Admin'];
            $_SESSION['role'] = 'admin';
            redirect('admin_dashboard.php');
        } else {
            redirect('login.php?error=invalid');
        }
    } else {
        // Customer login - Fix nama file dashboard
        $query = "SELECT * FROM pelanggan WHERE Nama_Pembeli = '$username' AND Password = MD5('$password')";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $customer = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $customer['ID_Pelanggan'];
            $_SESSION['username'] = $customer['Nama_Pembeli'];
            $_SESSION['nama'] = $customer['Nama_Pembeli'];
            $_SESSION['role'] = 'customer';
            // Fix: Redirect ke file yang benar (costumer_dashboard.php)
            redirect('costumer_dashboard.php');
        } else {
            redirect('login.php?error=invalid');
        }
    }
} else {
    redirect('login.php');
}
?>