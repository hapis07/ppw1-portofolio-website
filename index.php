<?php
require_once 'config/database.php';

// Fetch Settings
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch();
if (!$settings) {
    // Fallback if table is empty
    $settings = (object)[
        'hero_title' => 'Hi, I\'m',
        'hero_highlight' => 'A Passionate Event Organizer',
        'hero_desc' => 'A university student with high-level communication skills, public speaking expertise, and extensive experience in coordinating successful events.',
        'profile_image' => 'images/profile.png'
    ];
}

// Fetch Skills
$stmt = $pdo->query("SELECT * FROM skills ORDER BY proficiency DESC");
$skills = $stmt->fetchAll();

// Fetch Events using View
$stmt = $pdo->query("SELECT * FROM v_detail_portofolio_event ORDER BY event_date DESC");
$events = $stmt->fetchAll();

// Group events by ID to handle multiple galleries
$portfolio = [];
foreach ($events as $row) {
    if (!isset($portfolio[$row->event_id])) {
        $portfolio[$row->event_id] = [
            'id' => $row->event_id,
            'title' => $row->title,
            'description' => $row->description,
            'role' => $row->role,
            'event_date' => $row->event_date,
            'images' => []
        ];
    }
    if ($row->image_path) {
        $portfolio[$row->event_id]['images'][] = $row->image_path;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Portfolio - Event Organizer</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Custom Cursor -->

    <!-- Loading Animation -->
    <div id="loader" class="loader-wrapper">
        <div class="spinner"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="#" class="logo">Portfo<span>lio.</span></a>
            <ul class="nav-links">
                <li><a href="#hero">About</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#portfolio">Portfolio</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="admin/login.php" class="btn btn-outline">Admin</a></li>
            </ul>
            <div class="hamburger" id="hamburger">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="hero">
        <div class="container hero-grid">
            <div class="hero-text">
                <span class="hero-label">Personal Portfolio</span>
                <h1><?= htmlspecialchars($settings->hero_title) ?><br><?= htmlspecialchars($settings->hero_highlight) ?></h1>
                <p><?= htmlspecialchars($settings->hero_desc) ?></p>
                <div class="hero-actions">
                    <a href="#portfolio" class="btn btn-primary">View My Work</a>
                    <a href="#contact" class="btn btn-outline">Contact Me</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="<?= htmlspecialchars($settings->profile_image) ?>" alt="Profile Picture">
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="skills section">
        <div class="container">
            <h2 class="section-title">My Expertise</h2>
            <div class="skills-container">
                <?php foreach ($skills as $skill): ?>
                <div class="skill-row">
                    <span class="skill-name"><?= htmlspecialchars($skill->name) ?></span>
                    <span class="skill-pct"><?= $skill->proficiency ?>%</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section bg-light">
        <div class="container">
            <h2 class="section-title">Featured Events</h2>
            <div class="portfolio-grid">
                <?php foreach ($portfolio as $item): ?>
                <div class="portfolio-card">
                    <div class="portfolio-img">
                        <?php 
                        $img_src = !empty($item['images']) ? $item['images'][0] : '';
                        if (empty($img_src) || !file_exists($img_src)) {
                            $img_src = "https://picsum.photos/seed/" . $item['id'] . "/800/600";
                        }
                        ?>
                        <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        
                        <div class="portfolio-overlay">
                            <div class="portfolio-info">
                                <h3><?= htmlspecialchars($item['title']) ?></h3>
                                <span class="meta-mono"><?= htmlspecialchars($item['role']) ?> &mdash; <?= date('M Y', strtotime($item['event_date'])) ?></span>
                                <p class="desc"><?= htmlspecialchars($item['description']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section">
        <div class="container contact-container">
            <div class="contact-text">
                <h2>Get In Touch</h2>
                <p class="text-mono">Let's build something beautiful together.</p>
            </div>
            <div class="contact-wrapper">
                <form id="contactForm" class="contact-form">
                    <div class="form-group">
                        <label for="name">Name (Optional)</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Your Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Your Email">
                        <small class="error-msg" id="emailError"></small>
                    </div>
                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" rows="5" class="form-control" placeholder="Your Message"></textarea>
                        <small class="error-msg" id="messageError"></small>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">Send Message</span>
                        <span class="btn-loader" style="display: none;">Wait...</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Portfolio. All rights reserved.</p>
        </div>
    </footer>

    <!-- Snackbar -->
    <div id="snackbar">Message sent successfully!</div>

    <script src="js/script.js"></script>
</body>
</html>
