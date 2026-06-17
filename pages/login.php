<?php
session_start();
if(isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Portfolio</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time() ?>">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--bg-main);
            margin: 0;
            overflow: hidden;
            font-family: var(--font-sans);
        }
        .login-wrapper { 
            width: 100%;
            max-width: 450px; 
            padding: 50px; 
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: 4px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.5); 
            z-index: 10;
        }
        .login-wrapper h2 { 
            text-align: center; 
            margin-bottom: 40px; 
            color: var(--white); 
            font-size: 2.5rem;
        }
        .login-wrapper .form-group label {
            color: var(--text-muted);
        }
        .login-wrapper .btn {
            width: 100%;
            margin-top: 10px;
            padding: 16px;
            font-size: 1.1rem;
        }
        .back-link {
            display: block;
            text-align: center; 
            margin-top: 30px; 
            color: var(--text-muted); 
            text-decoration: none;
            font-weight: 600;
            font-family: var(--font-sans);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            transition: var(--transition);
        }
        .back-link:hover {
            color: var(--primary);
        }
        
        /* Input Styles matching Admin Panel */
        .login-wrapper input[type="text"], .login-wrapper input[type="password"] {
            width: 100%; 
            padding: 16px 20px; 
            border-radius: 12px; 
            background: rgba(255, 255, 255, 0.03); 
            border: 1px solid var(--border-light); 
            color: var(--text-main); 
            font-family: var(--font-sans); 
            font-size: 1rem; 
            transition: var(--transition);
            box-sizing: border-box;
            margin-top: 8px;
        }
        .login-wrapper input:focus {
            outline: none;
            border-color: var(--primary);
            background: transparent;
        }
        
        /* Fix ugly browser autofill white background */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #131313 inset !important;
            -webkit-text-fill-color: #e8e6e3 !important;
            transition: background-color 5000s ease-in-out 0s;
        }
        
        /* Floating shapes for aesthetics */
        .shape {
            position: absolute;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.5;
        }
        .shape-1 {
            width: 300px; height: 300px;
            background: rgba(201, 185, 154, 0.15);
            top: -100px; left: -100px;
            border-radius: 50%;
        }
        .shape-2 {
            width: 400px; height: 400px;
            background: rgba(160, 141, 110, 0.1);
            bottom: -150px; right: -150px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <!-- Custom Cursor -->

    <!-- Background Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-wrapper">
        <h2>System Login</h2>
        <?php if(isset($_SESSION['error'])): ?>
            <div style="padding: 15px; background: rgba(255,161,161,0.1); color: var(--error); border: 1px solid var(--error); border-radius: 10px; margin-bottom: 25px; text-align: center; font-size: 0.9rem;">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <form action="auth.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Authenticate</button>
        </form>
        <a href="../index.php" class="back-link">&larr; Back to Portfolio</a>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>
