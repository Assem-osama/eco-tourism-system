<?php
$pageTitle = 'Admin Vetting Dashboard — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";
?>

<main class="page-content">
    <div class="page-header">
        <h2>Admin Vetting Dashboard</h2>
        <p class="subtitle">Review and approve new trips submitted by guides.</p>
    </div>

    <?php if (!empty($_GET["success"])): ?>
        <div class="alert alert-success animate-fade-in"><?= htmlspecialchars($_GET["success"]) ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET["error"])): ?>
        <div class="alert alert-danger animate-fade-in"><?= htmlspecialchars($_GET["error"]) ?></div>
    <?php endif; ?>

    <?php if (empty($trips)): ?>
        <div class="empty-state animate-fade-in">
            <div class="empty-icon">✅</div>
            <p>No trips are currently awaiting approval. Great job!</p>
        </div>
    <?php else: ?>
        <div class="table-responsive animate-fade-in">
            <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">Trip Title</th>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">Guide Name</th>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">Location</th>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">Price</th>
                        <th style="text-align: left; padding: 12px; border-bottom: 2px solid #eaeaea;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trips as $trip): ?>
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea;">
                                <strong><?= htmlspecialchars($trip->title) ?></strong>
                                <br>
                                <a href="index.php?action=trip_detail&id=<?= $trip->id ?>" target="_blank" style="font-size: 0.85em; color: var(--primary-color);">View Details</a>
                            </td>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea;"><?= htmlspecialchars($trip->guide_name) ?></td>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea;"><?= htmlspecialchars($trip->location) ?></td>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea;">$<?= number_format($trip->price, 2) ?></td>
                            <td style="padding: 12px; border-bottom: 1px solid #eaeaea; display: flex; gap: 8px;">
                                <a href="index.php?action=admin_trip_approve&id=<?= $trip->id ?>" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to approve this trip?');" style="background-color: #28a745; border-color: #28a745;">Approve</a>
                                <a href="index.php?action=admin_trip_reject&id=<?= $trip->id ?>" class="btn btn-sm btn-outline" onclick="return confirm('Are you sure you want to reject this trip?');" style="color: #dc3545; border-color: #dc3545;">Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>
