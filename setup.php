<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Default password laragon

try {
    // Koneksi ke MySQL server tanpa memilih database
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Buat Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `uas_portofolio` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    $pdo->exec("USE `uas_portofolio`;");

    // 2. Buat Tabel Users
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL,
      `password` varchar(255) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $pdo->exec("INSERT INTO `users` (`username`, `password`) VALUES ('admin', '$2y$10\$wE99qF2FhP9X7vF8t1H1hOpVj/WbHj9Y0/D9G.qI.N/V1v9/b0Z1q');");

    // 3. Buat Tabel Skills
    $pdo->exec("CREATE TABLE IF NOT EXISTS `skills` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL,
      `proficiency` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $pdo->exec("INSERT INTO `skills` (`name`, `proficiency`) VALUES ('Public Speaking', 95), ('Event Management', 90), ('Communication', 98), ('Team Leadership', 85);");

    // 4. Buat Tabel Events
    $pdo->exec("CREATE TABLE IF NOT EXISTS `events` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(150) NOT NULL,
      `description` text NOT NULL,
      `role` varchar(100) NOT NULL,
      `event_date` date NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $pdo->exec("INSERT INTO `events` (`title`, `description`, `role`, `event_date`) VALUES ('PIONIR Permadani 2026', 'Mengurus data staf, memastikan semua kebutuhan administrasi dan koordinasi berjalan lancar.', 'Koordinator Data & Administrasi', '2026-08-15'), ('Tedi Games: Electronic Games', 'Menyusun agenda parade, mengelola turnamen e-sports, dan memastikan rundown acara.', 'Ketua Pelaksana', '2025-11-20'), ('Seminar Nasional Komunikasi', 'Menjadi Master of Ceremony (MC) dan memandu jalannya seminar nasional.', 'Master of Ceremony', '2025-05-10');");

    // 5. Buat Tabel Event Galleries
    $pdo->exec("CREATE TABLE IF NOT EXISTS `event_galleries` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `event_id` int(11) NOT NULL,
      `image_path` varchar(255) NOT NULL,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $pdo->exec("INSERT INTO `event_galleries` (`event_id`, `image_path`) VALUES (1, 'uploads/pionir_1.jpg'), (1, 'uploads/pionir_2.jpg'), (2, 'uploads/tedi_1.jpg'), (3, 'uploads/seminar_1.jpg');");

    // 6. Buat Tabel Contact Messages
    $pdo->exec("CREATE TABLE IF NOT EXISTS `contact_messages` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `sender_name` varchar(100) DEFAULT NULL,
      `sender_email` varchar(100) NOT NULL,
      `message` text NOT NULL,
      `submitted_at` datetime DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $pdo->exec("INSERT INTO `contact_messages` (`sender_name`, `sender_email`, `message`, `submitted_at`) VALUES ('John Doe', 'john@example.com', 'Halo, saya tertarik dengan pengalaman event organizer Anda.', '2026-06-10 10:00:00'), ('Jane Smith', 'jane@example.com', 'Bisakah kita berkolaborasi untuk acara kampus bulan depan?', '2026-06-11 14:30:00');");

    // 7. Buat Trigger (tanpa DELIMITER karena dieksekusi satu per satu)
    $pdo->exec("DROP TRIGGER IF EXISTS `before_contact_insert`;");
    $pdo->exec("CREATE TRIGGER `before_contact_insert` BEFORE INSERT ON `contact_messages` FOR EACH ROW BEGIN IF NEW.submitted_at IS NULL THEN SET NEW.submitted_at = NOW(); END IF; IF NEW.sender_name IS NULL OR TRIM(NEW.sender_name) = '' THEN SET NEW.sender_name = 'Guest'; END IF; END;");

    // 8. Buat View
    $pdo->exec("CREATE OR REPLACE VIEW `v_detail_portofolio_event` AS SELECT e.id AS event_id, e.title, e.description, e.role, e.event_date, g.image_path FROM `events` e LEFT JOIN `event_galleries` g ON e.id = g.event_id;");
    $pdo->exec("CREATE OR REPLACE VIEW `v_pesan_terbaru` AS SELECT id, sender_name, sender_email, message, submitted_at FROM `contact_messages` ORDER BY submitted_at DESC LIMIT 5;");

    // 9. Buat Function
    $pdo->exec("DROP FUNCTION IF EXISTS `hitung_total_event`;");
    $pdo->exec("CREATE FUNCTION `hitung_total_event`() RETURNS int(11) DETERMINISTIC BEGIN DECLARE total INT; SELECT COUNT(*) INTO total FROM `events`; RETURN total; END;");
    
    $pdo->exec("DROP FUNCTION IF EXISTS `hitung_total_pesan`;");
    $pdo->exec("CREATE FUNCTION `hitung_total_pesan`() RETURNS int(11) DETERMINISTIC BEGIN DECLARE total INT; SELECT COUNT(*) INTO total FROM `contact_messages`; RETURN total; END;");

    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 100px;'>";
    echo "<h1 style='color: #10b981;'>Database Berhasil Diimport! 🎉</h1>";
    echo "<p>Database <strong>'uas_portofolio'</strong> telah berhasil dibuat beserta trigger dan functions.</p>";
    echo "<p>Silakan hapus file ini (setup.php) untuk keamanan.</p>";
    echo "<a href='index.php' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 5px;'>Buka Halaman Portfolio</a>";
    echo "</div>";
    
} catch(PDOException $e) {
    die("<h3 style='color: red;'>Setup Gagal:</h3> " . $e->getMessage());
}
?>
