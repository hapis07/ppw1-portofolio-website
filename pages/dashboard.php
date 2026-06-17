<?php
require_once '../includes/header.php';

// Get Totals using Functions
$stmt = $pdo->query("SELECT hitung_total_event() AS total_events, hitung_total_pesan() AS total_messages");
$totals = $stmt->fetch();

// Get Latest Messages using View
$stmt = $pdo->query("SELECT * FROM v_pesan_terbaru");
$messages = $stmt->fetchAll();
?>

<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <h3 class="card-title">Total Events Managed</h3>
            <div class="number"><?= $totals->total_events ?></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card h-100">
            <h3 class="card-title">Total Inbox Messages</h3>
            <div class="number"><?= $totals->total_messages ?></div>
        </div>
    </div>
</div>

<h3 class="section-heading">Recent Messages</h3>
<div class="cms-table-wrapper">
    <table class="cms-table">
        <thead>
            <tr>
                <th>Date Received</th>
                <th>Sender Info</th>
                <th>Message Content</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($messages as $msg): ?>
            <tr>
                <td style="white-space: nowrap;"><?= date('d M Y, H:i', strtotime($msg->submitted_at)) ?></td>
                <td>
                    <strong><?= htmlspecialchars($msg->sender_name) ?></strong><br>
                    <span style="font-size: 0.85rem; opacity: 0.7;"><?= htmlspecialchars($msg->sender_email) ?></span>
                </td>
                <td style="line-height: 1.4;"><?= nl2br(htmlspecialchars($msg->message)) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($messages) == 0): ?>
            <tr><td colspan="3" style="text-align:center; padding: 40px;">No messages received yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>
