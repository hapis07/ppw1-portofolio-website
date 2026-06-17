<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Portfolio</title>
    <!-- Bootstrap 5 CSS (Minimal Usage) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time() ?>">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap');

        :root {
            --sidebar-width: 250px;
            --bg-main: #0a0a0a;
            --bg-secondary: #131313;
            --text-main: #e8e6e3;
            --text-muted: #6b6b6b;
            --primary: #c9b99a;
            --primary-hover: #b3a488;
            --white: #e8e6e3;
            --border-light: rgba(255, 255, 255, 0.07);
            --transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --font-sans: 'Inter', sans-serif;
            --font-mono: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            min-height: calc(100vh / 0.75);
            display: flex;
            overflow-x: hidden;
            zoom: 0.75;
            font-family: var(--font-sans);
            font-weight: 400;
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-light);
            position: fixed;
            height: calc(100vh / 0.75);
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-header {
            height: 90px;
            display: flex;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid var(--border-light);
        }

        .sidebar-header h2 {
            font-size: 1.4rem;
            color: var(--text-main);
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin: 0;
        }

        .sidebar-header h2 span { color: var(--primary); }

        .sidebar-nav {
            padding: 30px 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 400;
            border-radius: 12px;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .sidebar-nav a:hover {
            background: var(--bg-secondary);
            color: var(--text-main);
            transform: translateX(5px);
        }

        .sidebar-nav a.active {
            background: rgba(201, 185, 154, 0.08);
            color: var(--primary);
            border-left: 3px solid var(--primary);
            font-weight: 500;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid var(--border-light);
        }

        .sidebar-footer .btn {
            width: 100%;
            text-align: center;
            display: block;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            min-height: calc(100vh / 0.75);
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: 90px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
            background: rgba(10, 10, 10, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-light);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .top-navbar .user-info {
            color: var(--text-muted);
            font-weight: 400;
        }

        .content-area {
            padding: 50px;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
        }

        /* Reusable Components */
        .section-heading { 
            font-size: 1.5rem; 
            margin-bottom: 30px; 
            color: var(--text-main); 
            display: flex; 
            align-items: center; 
            gap: 15px;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .section-heading::after { content: ''; flex-grow: 1; height: 1px; background: var(--border-light); }

        /* Stats Grid */
        .stats-grid { 
            display: grid; grid-template-columns: 1fr 1fr; gap: 30px; 
            margin-bottom: 60px; 
        }
        .stat-card { 
            background: var(--bg-secondary);
            padding: 40px; border-radius: 16px; 
            border: 1px solid var(--border-light);
            text-align: left; 
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(201, 185, 154, 0.2);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
            background: var(--primary);
        }
        .stat-card h3 { color: var(--text-muted); font-size: 1rem; margin-bottom: 15px; font-weight: 400; text-transform: uppercase; letter-spacing: 0.05em;}
        .stat-card .number { font-size: 3.5rem; color: var(--text-main); font-weight: 300; line-height: 1; }

        /* Tables */
        .cms-table-wrapper {
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .cms-table { width: 100%; border-collapse: collapse; }
        .cms-table th, .cms-table td { 
            padding: 25px; text-align: left; 
            border-bottom: 1px solid var(--border-light); 
        }
        .cms-table th { 
            background: var(--bg-secondary); color: var(--text-muted); 
            font-weight: 500; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em;
        }
        .cms-table tr:hover td { background: var(--bg-main); }
        .cms-table td { color: var(--text-main); font-size: 0.95rem; }
        .cms-table td strong { font-weight: 500; }
        
        /* Actions */
        .cms-action-btn {
            padding: 8px 18px; border-radius: 40px; text-decoration: none; font-size: 0.8rem; text-transform: uppercase; font-weight: 500; margin-right: 5px;
            display: inline-block; transition: 0.3s; border: 1px solid transparent; letter-spacing: 0.05em;
        }
        .btn-edit { background: var(--bg-secondary); color: var(--text-main); border-color: var(--border-light); }
        .btn-edit:hover { background: var(--text-main); color: var(--bg-main); }
        .btn-delete { background: #fff0f0; color: #e74c3c; border-color: rgba(231, 76, 60, 0.2); }
        .btn-delete:hover { background: #e74c3c; color: #fff; }

        /* Form Styles */
        .cms-form {
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            padding: 50px; border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .form-group { margin-bottom: 30px; }
        .form-group label { display: block; margin-bottom: 12px; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;}
        .form-group input[type="text"], .form-group input[type="number"], .form-group input[type="date"], .form-group input[type="file"], .form-group textarea, .form-group select {
            width: 100%; padding: 16px 20px; border-radius: 12px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-light); color: var(--text-main); font-family: inherit; font-size: 1rem; transition: var(--transition);
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--primary); background: transparent; }
        .alert-success { background: rgba(126, 161, 139, 0.1); color: var(--primary); padding: 18px 25px; border-radius: 12px; border: 1px solid var(--primary); margin-bottom: 30px; font-weight: 400;}
        .alert-error { background: #fff0f0; color: #e74c3c; padding: 18px 25px; border-radius: 12px; border: 1px solid #e74c3c; margin-bottom: 30px; font-weight: 400;}

        /* Button overrides */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 30px; border-radius: 40px; text-decoration: none; font-size: 0.9rem; font-weight: 400; letter-spacing: 0.05em; transition: var(--transition); border: 1px solid transparent; cursor: pointer;
        }
        .btn-primary { background: var(--primary); color: var(--bg-main); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255, 255, 255, 0.1); opacity: 0.9;}
        .btn-outline { background: transparent; border-color: rgba(255,255,255,0.15); color: var(--text-main); }
        .btn-outline:hover { background: transparent; border-color: var(--primary); color: var(--primary); }

        @media (max-width: 992px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-main { margin-left: 0; }
        }
    </style>
</head>
<body>
    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
    
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>Portfo<span>CMS</span></h2>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">Dashboard Overview</a>
            <a href="settings.php" class="<?= $current_page == 'settings.php' ? 'active' : '' ?>">Hero Settings</a>
            <a href="skills.php" class="<?= strpos($current_page, 'skill') !== false ? 'active' : '' ?>">Manage Skills</a>
            <a href="events.php" class="<?= strpos($current_page, 'event') !== false ? 'active' : '' ?>">Featured Events</a>
            <a href="messages.php" class="<?= $current_page == 'messages.php' ? 'active' : '' ?>">Inbox Messages</a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn btn-primary" style="background: var(--error); color: var(--bg-dark); border:none;">Logout</a>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <main class="admin-main flex-grow-1">
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div class="user-info">
                Welcome back, <span style="color: var(--primary);"><?= htmlspecialchars($_SESSION['admin']) ?></span>
            </div>
            <a href="../index.php" class="btn btn-outline" target="_blank" style="padding: 10px 24px;">View Live Site</a>
        </div>
        
        <div class="container-fluid content-area">
