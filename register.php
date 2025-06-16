<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Customer - Sistem Cafe BC UNAND</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-container {
            background: rgba(245, 245, 220, 0.95);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 69, 19, 0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo h1 {
            color: #8B4513;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .logo p {
            color: #A0522D;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #8B4513;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #DEB887;
            border-radius: 10px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 10px rgba(139, 69, 19, 0.3);
            transform: translateY(-1px);
        }

        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8B4513, #A0522D);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #DEB887;
        }

        .login-link a {
            color: #8B4513;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #A0522D;
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: bold;
            text-align: center;
            animation: slideIn 0.3s ease;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef5350;
        }

        .alert-success {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .register-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .logo h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <h1>🏪 BC UNAND</h1>
            <p>Daftar Akun Customer</p>
        </div>

        <?php
        require_once 'config.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama = clean_input($_POST['nama']);
            $password = clean_input($_POST['password']);
            $confirm_password = clean_input($_POST['confirm_password']);

            if (empty($nama) || empty($password) || empty($confirm_password)) {
                echo '<div class="alert alert-error">Harap isi semua field!</div>';
            } elseif ($password !== $confirm_password) {
                echo '<div class="alert alert-error">Password tidak cocok!</div>';
            } elseif (strlen($password) < 6) {
                echo '<div class="alert alert-error">Password minimal 6 karakter!</div>';
            } else {
                // Cek apakah nama sudah ada
                $check_query = "SELECT * FROM pelanggan WHERE Nama_Pembeli = '$nama'";
                $check_result = mysqli_query($conn, $check_query);

                if (mysqli_num_rows($check_result) > 0) {
                    echo '<div class="alert alert-error">Nama pembeli sudah terdaftar!</div>';
                } else {
                    // Generate ID pelanggan
                    $id_pelanggan = generateID('PLG', 'pelanggan', 'ID_Pelanggan');
                    
                    // Insert customer baru
                    $insert_query = "INSERT INTO pelanggan (ID_Pelanggan, Nama_Pembeli, Password) VALUES ('$id_pelanggan', '$nama', MD5('$password'))";
                    
                    if (mysqli_query($conn, $insert_query)) {
                        redirect('login.php?success=1');
                    } else {
                        echo '<div class="alert alert-error">Terjadi kesalahan saat mendaftar!</div>';
                    }
                }
            }
        }
        ?>

        <form method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter">
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Ulangi password">
            </div>

            <button type="submit" class="register-btn">Daftar</button>
        </form>

        <div class="login-link">
            <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
        </div>
    </div>

    <script>
        // Validasi form
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password tidak cocok!');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return;
            }
            
            // Animation
            const btn = document.querySelector('.register-btn');
            btn.style.transform = 'scale(0.95)';
            btn.textContent = 'Memproses...';
        });
    </script>
</body>
</html>