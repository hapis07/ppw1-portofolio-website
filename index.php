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
    <!-- Bootstrap 5 CSS (Minimal Usage) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar" data-bs-theme="dark">
        <div class="container nav-container">
            <a href="#" class="navbar-brand logo">Portfo<span>lio.</span></a>
            <button class="navbar-toggler border-0 shadow-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <div class="hamburger" id="hamburger">
                    <span></span><span></span><span></span>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center nav-links">
                    <li class="nav-item"><a class="nav-link" href="#hero">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#skills">Skills</a></li>
                    <li class="nav-item"><a class="nav-link" href="#portfolio">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item ms-lg-4 mt-3 mt-lg-0"><a href="admin/login.php" class="btn btn-outline w-100">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 hero-text order-2 order-lg-1">
                    <span class="hero-label">Personal Portfolio</span>
                    <h1><?= htmlspecialchars($settings->hero_title) ?><br><?= htmlspecialchars($settings->hero_highlight) ?></h1>
                    <p><?= htmlspecialchars($settings->hero_desc) ?></p>
                    <div class="hero-actions d-flex flex-wrap gap-3">
                        <a href="#portfolio" class="btn btn-primary px-4 py-3">View My Work</a>
                        <a href="#contact" class="btn btn-outline px-4 py-3">Contact Me</a>
                    </div>
                </div>
                <div class="col-lg-6 hero-image order-1 order-lg-2 text-center text-lg-end">
                    <img src="<?= htmlspecialchars($settings->profile_image) ?>" alt="Profile Picture" class="img-fluid profile-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="skills section">
        <div class="container">
            <h2 class="section-title mb-5">My Expertise</h2>
            <div class="row g-4 skills-container">
                <?php foreach ($skills as $skill): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="skill-row d-flex justify-content-between align-items-center h-100">
                        <span class="skill-name"><?= htmlspecialchars($skill->name) ?></span>
                        <span class="skill-pct"><?= $skill->proficiency ?>%</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section">
        <div class="container">
            <h2 class="section-title mb-5">Featured Events</h2>
            <div class="row g-4 portfolio-grid">
                <?php foreach ($portfolio as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card portfolio-card bg-transparent border-0 h-100 overflow-hidden rounded-4">
                        <div class="portfolio-img position-relative h-100">
                            <?php 
                            $img_src = !empty($item['images']) ? $item['images'][0] : '';
                            if (empty($img_src) || !file_exists($img_src)) {
                                $img_src = "https://picsum.photos/seed/" . $item['id'] . "/800/600";
                            }
                            ?>
                            <img src="<?= htmlspecialchars($img_src) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="card-img-top w-100 h-100 object-fit-cover">
                            
                            <div class="portfolio-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end">
                                <div class="portfolio-info">
                                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                                    <span class="meta-mono"><?= htmlspecialchars($item['role']) ?> &mdash; <?= date('M Y', strtotime($item['event_date'])) ?></span>
                                    <p class="desc"><?= htmlspecialchars($item['description']) ?></p>
                                </div>
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
        <div class="container">
            <div class="row g-5 align-items-center contact-container">
                <div class="col-lg-5 contact-text">
                    <h2>Get In Touch</h2>
                    <p class="text-mono">Let's build something beautiful together.</p>
                </div>
                <div class="col-lg-7">
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
                                <span class="btn-loader spinner-border spinner-border-sm" style="display: none;" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                </div>
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
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
