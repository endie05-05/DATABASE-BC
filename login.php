<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Cafe BC UNAND</title>
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
        }

        .login-container {
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

        .login-tabs {
            display: flex;
            margin-bottom: 2rem;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .tab-btn {
            flex: 1;
            padding: 12px;
            background: #DEB887;
            border: none;
            cursor: pointer;
            font-weight: bold;
            color: #8B4513;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background: #8B4513;
            color: #F5F5DC;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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

        .login-btn {
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

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
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

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #DEB887;
        }

        .register-link a {
            color: #8B4513;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #A0522D;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
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
    <div class="login-container">
        <div class="logo">
            <h1>🏪 BC UNAND</h1>
            <p>Sistem Manajemen Cafe</p>
        </div>

        <div class="login-tabs">
            <button type="button" class="tab-btn active" onclick="switchTab('customer')">Customer</button>
            <button type="button" class="tab-btn" onclick="switchTab('admin')">Admin</button>
        </div>

        <form id="loginForm" method="POST" action="auth.php">
            <input type="hidden" id="loginType" name="login_type" value="customer">
            
            <div class="form-group">
                <label for="username" id="usernameLabel">Nama Pembeli</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Masuk</button>
        </form>

        <div class="register-link">
            <p>Belum punya akun customer? <a href="register.php">Daftar disini</a></p>
        </div>
    </div>

    <script>
        function switchTab(type) {
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update form
            document.getElementById('loginType').value = type;
            
            if (type === 'admin') {
                document.getElementById('usernameLabel').textContent = 'Username Admin';
                document.getElementById('username').placeholder = 'Masukkan username admin';
            } else {
                document.getElementById('usernameLabel').textContent = 'Nama Pembeli';
                document.getElementById('username').placeholder = 'Masukkan nama pembeli';
            }
        }

        // Handle form submission with animation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.querySelector('.login-btn');
            btn.style.transform = 'scale(0.95)';
            btn.textContent = 'Memproses...';
            
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 150);
        });
    </script>

    <?php
    // Display messages if any
    if (isset($_GET['error'])) {
        $error_msg = '';
        switch ($_GET['error']) {
            case 'invalid':
                $error_msg = 'Username atau password salah!';
                break;
            case 'empty':
                $error_msg = 'Harap isi semua field!';
                break;
            case 'logout':
                $error_msg = 'Anda telah logout!';
                break;
        }
        if ($error_msg) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('loginForm');
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-error';
                    alert.textContent = '$error_msg';
                    form.insertBefore(alert, form.firstChild);
                });
            </script>";
        }
    }

    if (isset($_GET['success'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('loginForm');
                const alert = document.createElement('div');
                alert.className = 'alert alert-success';
                alert.textContent = 'Registrasi berhasil! Silakan login.';
                form.insertBefore(alert, form.firstChild);
            });
        </script>";
    }
    ?>
</body>
</html>