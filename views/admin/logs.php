<?php
$pageTitle = 'System Audit Logs — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";
?>

<main class="page-content">
    <div class="page-header">
        <h2>System Audit Logs</h2>
        <p class="subtitle">Review all critical actions performed by Guides and Admins.</p>
    </div>

    <?php if (empty($logs)): ?>
        <div class="empty-state animate-fade-in">
            <div class="empty-icon">📝</div>
            <p>No audit logs available yet.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive animate-fade-in">
            <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">Timestamp</th>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">User</th>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">Action</th>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">Description</th>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">TableAffected</th>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">Record ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea; font-size: 0.9em; color: #666;"><?= htmlspecialchars($log['created_at']) ?></td>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea;">
                                <strong><?= htmlspecialchars($log['user_name'] ?? 'Unknown User') ?></strong>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea;">
                                <span class="badge" style="background: var(--primary-color); color: black; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;">
                                    <?= htmlspecialchars($log['action']) ?>
                                </span>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea;"><?= htmlspecialchars($log['description']) ?></td>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea;"><code><?= htmlspecialchars($log['table_name']) ?></code></td>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea;">#<?= htmlspecialchars($log['record_id']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>
