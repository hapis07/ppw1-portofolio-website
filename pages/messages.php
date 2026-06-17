<?php
require_once '../includes/header.php';

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
    <div class="alert alert-<?= $msgType ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
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
                    <button type="button" class="cms-action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#msgModal<?= $m->id ?>">View</button>
                    <a href="?action=delete&id=<?= $m->id ?>" class="cms-action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
                </td>
            </tr>

            <!-- View Message Modal -->
            <div class="modal fade" id="msgModal<?= $m->id ?>" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background: var(--bg-secondary); border: 1px solid var(--border-light);">
                  <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title" style="color: var(--text-main);">Message Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                        <strong style="color: var(--primary);">From:</strong> <?= htmlspecialchars($m->sender_name) ?> <br>
                        <strong style="color: var(--primary);">Email:</strong> <?= htmlspecialchars($m->sender_email) ?> <br>
                        <strong style="color: var(--primary);">Date:</strong> <?= date('d M Y, H:i', strtotime($m->submitted_at)) ?>
                    </div>
                    <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); color: var(--text-main); font-family: var(--font-sans); line-height: 1.6;">
                        <?= nl2br(htmlspecialchars($m->message)) ?>
                    </div>
                  </div>
                  <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <?php endforeach; ?>
            <?php if(count($messages) == 0): ?>
            <tr><td colspan="4" style="text-align:center;">No messages found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>
