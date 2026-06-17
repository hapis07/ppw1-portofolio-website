<?php
require_once '../includes/header.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$msg = '';
$msgType = '';

// Handle Delete Event
if ($action == 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    if ($stmt->execute([$id])) {
        $msg = 'Event deleted successfully.';
        $msgType = 'success';
    } else {
        $msg = 'Failed to delete event.';
        $msgType = 'error';
    }
    $action = 'list';
}

// Handle Delete Image
if ($action == 'delete_img' && isset($_GET['img_id']) && $id) {
    $img_id = $_GET['img_id'];
    $stmt = $pdo->prepare("DELETE FROM event_galleries WHERE id = ? AND event_id = ?");
    if ($stmt->execute([$img_id, $id])) {
        $msg = 'Image deleted successfully.';
        $msgType = 'success';
    }
    $action = 'edit';
}

// Handle Form Submission (Add/Edit Event)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit_event'])) {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $role = trim($_POST['role']);
        $event_date = $_POST['event_date'];
        
        if ($action == 'add') {
            $stmt = $pdo->prepare("INSERT INTO events (title, description, role, event_date) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$title, $description, $role, $event_date])) {
                $new_id = $pdo->lastInsertId();
                $msg = 'Event added successfully. You can now add images below.';
                $msgType = 'success';
                $action = 'edit';
                $id = $new_id;
            } else {
                $msg = 'Failed to add event.';
                $msgType = 'error';
            }
        } elseif ($action == 'edit' && $id) {
            $stmt = $pdo->prepare("UPDATE events SET title = ?, description = ?, role = ?, event_date = ? WHERE id = ?");
            if ($stmt->execute([$title, $description, $role, $event_date, $id])) {
                $msg = 'Event updated successfully.';
                $msgType = 'success';
            } else {
                $msg = 'Failed to update event.';
                $msgType = 'error';
            }
        }
    } elseif (isset($_POST['submit_image']) && $id) {
        // Handle Image Upload
        $image = $_FILES['image'] ?? null;
        if ($image && $image['error'] == 0) {
            $allowed_exts = ['jpg', 'jpeg', 'png'];
            $file_info = pathinfo($image['name']);
            $ext = strtolower($file_info['extension']);
            if (in_array($ext, $allowed_exts)) {
                $upload_dir = '../assets/img/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                $new_filename = uniqid('event_', true) . '.' . $ext;
                $destination = $upload_dir . $new_filename;
                
                if (move_uploaded_file($image['tmp_name'], $destination)) {
                    $stmt = $pdo->prepare("INSERT INTO event_galleries (event_id, image_path) VALUES (?, ?)");
                    $image_path_db = 'assets/img/' . $new_filename;
                    $stmt->execute([$id, $image_path_db]);
                    $msg = 'Image uploaded to gallery.';
                    $msgType = 'success';
                }
            } else {
                $msg = 'Invalid file type. Only JPG and PNG are allowed.';
                $msgType = 'error';
            }
        }
        $action = 'edit';
    }
}

// Fetch list
if ($action == 'list') {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
    $events = $stmt->fetchAll();
}
?>

<div style="display:flex; justify-content: space-between; align-items:center;">
    <h3 class="section-heading" style="margin-bottom:0; border:none; width:auto; flex-grow:0;">Manage Events (Portfolio)</h3>
    <?php if($action == 'list'): ?>
        <a href="?action=add" class="btn btn-primary" style="padding: 10px 20px;">+ Add Event</a>
    <?php else: ?>
        <a href="events.php" class="btn btn-outline" style="padding: 10px 20px;">Back to List</a>
    <?php endif; ?>
</div>
<hr style="border: 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin: 25px 0;">

<?php if($msg): ?>
    <div class="alert alert-<?= $msgType ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($action == 'list'): ?>
    <div class="cms-table-wrapper">
        <table class="cms-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Role</th>
                    <th>Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($events as $ev): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($ev->title) ?></strong></td>
                    <td><?= htmlspecialchars($ev->role) ?></td>
                    <td><?= date('d M Y', strtotime($ev->event_date)) ?></td>
                    <td style="text-align: right;">
                        <a href="?action=edit&id=<?= $ev->id ?>" class="cms-action-btn btn-edit">Edit / Gallery</a>
                        <a href="?action=delete&id=<?= $ev->id ?>" class="cms-action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this event and all its images?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($events) == 0): ?>
                <tr><td colspan="4" style="text-align:center;">No events found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?php 
        $ev_title = ''; $ev_desc = ''; $ev_role = ''; $ev_date = '';
        $galleries = [];
        if ($action == 'edit' && $id) {
            $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
            $stmt->execute([$id]);
            $ev = $stmt->fetch();
            if($ev) {
                $ev_title = $ev->title; $ev_desc = $ev->description; $ev_role = $ev->role; $ev_date = $ev->event_date;
                $gstmt = $pdo->prepare("SELECT * FROM event_galleries WHERE event_id = ?");
                $gstmt->execute([$id]);
                $galleries = $gstmt->fetchAll();
            }
        }
    ?>
    <div class="cms-form">
        <form action="?action=<?= $action ?><?= $id ? '&id='.$id : '' ?>" method="POST">
            <input type="hidden" name="submit_event" value="1">
            <div class="form-group">
                <label>Event Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($ev_title) ?>" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <input type="text" name="role" value="<?= htmlspecialchars($ev_role) ?>" required>
            </div>
            <div class="form-group">
                <label>Event Date</label>
                <input type="date" name="event_date" value="<?= htmlspecialchars($ev_date) ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" required><?= htmlspecialchars($ev_desc) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?= $action == 'add' ? 'Add Event' : 'Update Event' ?></button>
        </form>
    </div>

    <?php if ($action == 'edit' && $id): ?>
    <hr style="border: 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin: 40px 0;">
    <h3 class="section-heading">Event Gallery</h3>
    
    <!-- Upload New Image -->
    <div class="cms-form" style="margin-bottom: 30px;">
        <form action="?action=edit&id=<?= $id ?>" method="POST" enctype="multipart/form-data" style="display:flex; gap: 15px; align-items: flex-end;">
            <input type="hidden" name="submit_image" value="1">
            <div class="form-group" style="margin-bottom:0; flex-grow: 1;">
                <label>Upload New Photo (JPG/PNG)</label>
                <input type="file" name="image" accept="image/jpeg, image/png" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- Gallery Grid -->
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
        <?php foreach($galleries as $img): ?>
        <div style="position: relative; border-radius: 10px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
            <img src="../<?= htmlspecialchars($img->image_path) ?>" style="width: 100%; height: 150px; object-fit: cover; display:block;">
            <div style="padding: 10px; background: rgba(0,0,0,0.8); text-align: center;">
                <a href="?action=delete_img&id=<?= $id ?>&img_id=<?= $img->id ?>" class="cms-action-btn btn-delete" style="margin:0;" onclick="return confirm('Delete this image?')">Delete</a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(count($galleries) == 0): ?>
            <p style="color: var(--text-muted);">No images uploaded yet.</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
