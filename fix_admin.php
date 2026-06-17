<?php
$host = 'localhost';
$username = 'root';
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=uas_portofolio;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create new hash safely without string interpolation bugs
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    
    // Update the database
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = 'admin'");
    $stmt->bindParam(':password', $hash);
    $stmt->execute();
    
    // If username 'admin' somehow got deleted or inserted wrong, let's just make sure it exists
    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES ('admin', :password)");
        $stmt->bindParam(':password', $hash);
        $stmt->execute();
    }
    
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 100px;'>";
    echo "<h1 style='color: #10b981;'>Perbaikan Berhasil! 🎉</h1>";
    echo "<p>Password akun <strong>admin</strong> telah di-reset ke <strong>admin123</strong> dengan aman.</p>";
    echo "<a href='admin/login.php' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 5px;'>Kembali ke Login</a>";
    echo "</div>";
    
} catch(PDOException $e) {
    die("<h3 style='color: red;'>Gagal:</h3> " . $e->getMessage());
}
?>
