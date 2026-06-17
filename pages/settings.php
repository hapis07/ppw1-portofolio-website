<?php
require_once '../includes/header.php';

// Fetch current settings
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch();

if (!$settings) {
    // Failsafe
    $pdo->exec("INSERT INTO `settings` (`hero_title`, `hero_highlight`, `hero_desc`, `profile_image`) VALUES ('Hi', 'Organizer', 'Desc', 'images/profile.png')");
    $settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();
}

$msg = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hero_title = trim($_POST['hero_title']);
    $hero_highlight = trim($_POST['hero_highlight']);
    $hero_desc = trim($_POST['hero_desc']);
    
    $profile_image = $settings->profile_image;

    // Handle File Upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../images/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $file_name = time() . '_' . basename($_FILES['profile_image']['name']);
        $target_file = $upload_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $profile_image = 'images/' . $file_name;
            } else {
                $msg = 'Failed to upload image.';
                $msgType = 'error';
            }
        } else {
            $msg = 'Only JPG, JPEG, and PNG files are allowed.';
            $msgType = 'error';
        }
    }

    if ($msgType !== 'error') {
        $updateStmt = $pdo->prepare("UPDATE settings SET hero_title = ?, hero_highlight = ?, hero_desc = ?, profile_image = ? WHERE id = ?");
        if ($updateStmt->execute([$hero_title, $hero_highlight, $hero_desc, $profile_image, $settings->id])) {
            $msg = 'Settings updated successfully.';
            $msgType = 'success';
            // Refresh settings variable
            $settings->hero_title = $hero_title;
            $settings->hero_highlight = $hero_highlight;
            $settings->hero_desc = $hero_desc;
            $settings->profile_image = $profile_image;
        } else {
            $msg = 'Failed to update settings in database.';
            $msgType = 'error';
        }
    }
}
?>

<h3 class="section-heading">Manage Hero Settings</h3>

<?php if($msg): ?>
    <div class="alert alert-<?= $msgType ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="cms-form">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Hero Title (Line 1)</label>
            <input type="text" name="hero_title" value="<?= htmlspecialchars($settings->hero_title) ?>" required>
        </div>
        <div class="form-group">
            <label>Hero Title (Line 2)</label>
            <input type="text" name="hero_highlight" value="<?= htmlspecialchars($settings->hero_highlight) ?>" required>
        </div>
        <div class="form-group">
            <label>Hero Description</label>
            <textarea name="hero_desc" rows="4" required><?= htmlspecialchars($settings->hero_desc) ?></textarea>
        </div>
        <div class="form-group">
            <label>Profile Image (Leave blank to keep current)</label>
            <div style="margin-bottom: 10px;">
                <img src="../<?= htmlspecialchars($settings->profile_image) ?>" alt="Current Profile" style="height: 100px; border-radius: 10px;">
            </div>
            <input type="file" name="profile_image" accept="image/jpeg, image/png">
        </div>
        
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
