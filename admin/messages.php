<?php
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$msg = '';
$msgType = '';

// Handle Delete
if ($action == 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
    if ($stmt->execute([$id])) {
        $msg = 'Message deleted successfully.';
        $msgType = 'success';
    } else {
        $msg = 'Failed to delete message.';
        $msgType = 'error';
    }
}

// Fetch list
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
$messages = $stmt->fetchAll();
?>

<div style="display:flex; justify-content: space-between; align-items:center;">
    <h3 class="section-heading" style="margin-bottom:0; border:none; width:auto; flex-grow:0;">Inbox Messages</h3>
</div>
<hr style="border: 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin: 25px 0;">

<?php if($msg): ?>
    <div class="alert-<?= $msgType ?>"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="cms-table-wrapper">
    <table class="cms-table">
        <thead>
            <tr>
                <th width="15%">Date Received</th>
                <th width="25%">Sender Info</th>
                <th width="45%">Message Content</th>
                <th width="15%" style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($messages as $m): ?>
            <tr>
                <td style="white-space: nowrap;"><?= date('d M Y, H:i', strtotime($m->submitted_at)) ?></td>
                <td>
                    <strong><?= htmlspecialchars($m->sender_name) ?></strong><br>
                    <span style="font-size: 0.85rem; opacity: 0.7;"><a href="mailto:<?= htmlspecialchars($m->sender_email) ?>" style="color:var(--primary); text-decoration:none;"><?= htmlspecialchars($m->sender_email) ?></a></span>
                </td>
                <td style="line-height: 1.4;"><?= nl2br(htmlspecialchars($m->message)) ?></td>
                <td style="text-align: right;">
                    <a href="?action=delete&id=<?= $m->id ?>" class="cms-action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($messages) == 0): ?>
            <tr><td colspan="4" style="text-align:center;">No messages found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
