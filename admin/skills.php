<?php
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$msg = '';
$msgType = '';

// Handle Delete
if ($action == 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
    if ($stmt->execute([$id])) {
        $msg = 'Skill deleted successfully.';
        $msgType = 'success';
    } else {
        $msg = 'Failed to delete skill.';
        $msgType = 'error';
    }
    $action = 'list';
}

// Handle Form Submission (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $proficiency = (int)$_POST['proficiency'];
    
    if ($action == 'add') {
        $stmt = $pdo->prepare("INSERT INTO skills (name, proficiency) VALUES (?, ?)");
        if ($stmt->execute([$name, $proficiency])) {
            $msg = 'Skill added successfully.';
            $msgType = 'success';
            $action = 'list';
        } else {
            $msg = 'Failed to add skill.';
            $msgType = 'error';
        }
    } elseif ($action == 'edit' && $id) {
        $stmt = $pdo->prepare("UPDATE skills SET name = ?, proficiency = ? WHERE id = ?");
        if ($stmt->execute([$name, $proficiency, $id])) {
            $msg = 'Skill updated successfully.';
            $msgType = 'success';
            $action = 'list';
        } else {
            $msg = 'Failed to update skill.';
            $msgType = 'error';
        }
    }
}

// Fetch list for 'list' view
if ($action == 'list') {
    $stmt = $pdo->query("SELECT * FROM skills ORDER BY proficiency DESC");
    $skills = $stmt->fetchAll();
}
?>

<div style="display:flex; justify-content: space-between; align-items:center;">
    <h3 class="section-heading" style="margin-bottom:0; border:none; width:auto; flex-grow:0;">Manage Skills</h3>
    <?php if($action == 'list'): ?>
        <a href="?action=add" class="btn btn-primary" style="padding: 10px 20px;">+ Add Skill</a>
    <?php else: ?>
        <a href="skills.php" class="btn btn-outline" style="padding: 10px 20px;">Back to List</a>
    <?php endif; ?>
</div>
<hr style="border: 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin: 25px 0;">

<?php if($msg): ?>
    <div class="alert-<?= $msgType ?>"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<?php if ($action == 'list'): ?>
    <div class="cms-table-wrapper">
        <table class="cms-table">
            <thead>
                <tr>
                    <th>Skill Name</th>
                    <th>Proficiency (%)</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($skills as $skill): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($skill->name) ?></strong></td>
                    <td>
                        <?= $skill->proficiency ?>%
                    </td>
                    <td style="text-align: right;">
                        <a href="?action=edit&id=<?= $skill->id ?>" class="cms-action-btn btn-edit">Edit</a>
                        <a href="?action=delete&id=<?= $skill->id ?>" class="cms-action-btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($skills) == 0): ?>
                <tr><td colspan="3" style="text-align:center;">No skills found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?php 
        $edit_name = '';
        $edit_prof = 50;
        if ($action == 'edit' && $id) {
            $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
            $stmt->execute([$id]);
            $s = $stmt->fetch();
            if($s) {
                $edit_name = $s->name;
                $edit_prof = $s->proficiency;
            }
        }
    ?>
    <div class="cms-form">
        <form action="?action=<?= $action ?><?= $id ? '&id='.$id : '' ?>" method="POST">
            <div class="form-group">
                <label>Skill Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($edit_name) ?>" required>
            </div>
            <div class="form-group">
                <label>Proficiency (0-100)</label>
                <input type="number" name="proficiency" min="0" max="100" value="<?= htmlspecialchars($edit_prof) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?= $action == 'add' ? 'Add' : 'Save' ?> Skill</button>
        </form>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
