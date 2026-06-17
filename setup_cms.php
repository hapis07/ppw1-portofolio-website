<?php
require_once 'config/database.php';

try {
    // Create settings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `settings` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `hero_title` varchar(100) NOT NULL,
            `hero_highlight` varchar(100) NOT NULL,
            `hero_desc` text NOT NULL,
            `profile_image` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    
    // Check if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM settings");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO `settings` (`hero_title`, `hero_highlight`, `hero_desc`, `profile_image`) VALUES 
        ('Hi, I''m', 'A Passionate Event Organizer', 'A university student with high-level communication skills, public speaking expertise, and extensive experience in coordinating successful events.', 'images/profile.png')");
    }
    
    echo "Settings table created and seeded.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
