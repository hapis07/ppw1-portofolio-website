SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `uas_portofolio` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `uas_portofolio`;

-- 1. TABEL users (Admin)
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password is 'admin123' 
INSERT INTO `users` (`username`, `password`) VALUES
('admin', '$2y$10$wE99qF2FhP9X7vF8t1H1hOpVj/WbHj9Y0/D9G.qI.N/V1v9/b0Z1q');

-- 2. TABEL settings
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hero_title` varchar(100) NOT NULL,
  `hero_highlight` varchar(100) NOT NULL,
  `hero_desc` text NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`hero_title`, `hero_highlight`, `hero_desc`, `profile_image`) VALUES
('Hi, I''m', 'A Passionate Event Organizer', 'A university student with high-level communication skills, public speaking expertise, and extensive experience in coordinating successful events.', 'images/profile.png');

-- 3. TABEL skills
CREATE TABLE `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `proficiency` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `skills` (`name`, `proficiency`) VALUES
('Public Speaking', 95),
('Event Management', 90),
('Communication', 98),
('Team Leadership', 85);

-- 4. TABEL events
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `role` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `events` (`title`, `description`, `role`, `event_date`) VALUES
('PIONIR Permadani 2026', 'Mengurus data staf, memastikan semua kebutuhan administrasi dan koordinasi berjalan lancar untuk acara penyambutan mahasiswa baru.', 'Koordinator Data & Administrasi', '2026-08-15'),
('Tedi Games: Electronic Games', 'Menyusun agenda parade, mengelola turnamen e-sports, dan memastikan rundown acara berjalan sesuai jadwal.', 'Ketua Pelaksana', '2025-11-20'),
('Seminar Nasional Komunikasi', 'Menjadi Master of Ceremony (MC) dan memandu jalannya seminar nasional yang dihadiri oleh 500+ peserta.', 'Master of Ceremony', '2025-05-10');

-- 5. TABEL event_galleries
CREATE TABLE `event_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `event_galleries` (`event_id`, `image_path`) VALUES
(1, 'uploads/pionir_1.jpg'),
(1, 'uploads/pionir_2.jpg'),
(2, 'uploads/tedi_1.jpg'),
(3, 'uploads/seminar_1.jpg');

-- 6. TABEL contact_messages
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_name` varchar(100) DEFAULT NULL,
  `sender_email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `contact_messages` (`sender_name`, `sender_email`, `message`, `submitted_at`) VALUES
('John Doe', 'john@example.com', 'Halo, saya tertarik dengan pengalaman event organizer Anda.', '2026-06-10 10:00:00'),
('Jane Smith', 'jane@example.com', 'Bisakah kita berkolaborasi untuk acara kampus bulan depan?', '2026-06-11 14:30:00');

-- TRIGGERS
DELIMITER $$
-- Trigger 1 & 2: Set tanggal hari ini dan set pengirim menjadi Guest jika kosong
CREATE TRIGGER `before_contact_insert` BEFORE INSERT ON `contact_messages`
FOR EACH ROW
BEGIN
    -- Set tanggal hari ini
    IF NEW.submitted_at IS NULL THEN
        SET NEW.submitted_at = NOW();
    END IF;
    
    -- Set Guest jika kosong
    IF NEW.sender_name IS NULL OR TRIM(NEW.sender_name) = '' THEN
        SET NEW.sender_name = 'Guest';
    END IF;
END
$$
DELIMITER ;

-- VIEWS
-- View 1: v_detail_portofolio_event
CREATE OR REPLACE VIEW `v_detail_portofolio_event` AS
SELECT 
    e.id AS event_id,
    e.title,
    e.description,
    e.role,
    e.event_date,
    g.image_path
FROM `events` e
LEFT JOIN `event_galleries` g ON e.id = g.event_id;

-- View 2: v_pesan_terbaru
CREATE OR REPLACE VIEW `v_pesan_terbaru` AS
SELECT 
    id,
    sender_name,
    sender_email,
    message,
    submitted_at
FROM `contact_messages`
ORDER BY submitted_at DESC
LIMIT 5;

-- FUNCTIONS
DELIMITER $$
-- Function 1: hitung_total_event
CREATE FUNCTION `hitung_total_event`() RETURNS int(11)
DETERMINISTIC
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM `events`;
    RETURN total;
END
$$

-- Function 2: hitung_total_pesan
CREATE FUNCTION `hitung_total_pesan`() RETURNS int(11)
DETERMINISTIC
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM `contact_messages`;
    RETURN total;
END
$$
DELIMITER ;

COMMIT;

/* --------------------------------------------------------
MINIMAL 3 QUERY COMPLEX 

1. JOIN (Menampilkan detail acara beserta foto dokumentasinya)
SELECT e.title, e.role, g.image_path 
FROM events e 
JOIN event_galleries g ON e.id = g.event_id;

2. Subquery (Mencari acara yang paling banyak fotonya)
SELECT title FROM events 
WHERE id = (
    SELECT event_id FROM event_galleries 
    GROUP BY event_id 
    ORDER BY COUNT(*) DESC LIMIT 1
);

3. GROUP BY (Menghitung pesan masuk berdasarkan tanggal)
SELECT DATE(submitted_at) as tanggal, COUNT(*) as jumlah_pesan 
FROM contact_messages 
GROUP BY DATE(submitted_at);
-------------------------------------------------------- */
