<?php
$pageTitle = 'Guide Vetting Queue — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";
?>

<main class="page-content">
    <div class="page-header">
        <h2>🧑‍🌾 Guide Vetting Queue</h2>
        <a href="index.php?action=admin_dashboard" class="btn btn-outline">Back to Dashboard</a>
    </div>

    <p class="form-hint">Review pending guide profiles before they are allowed to create trips.</p>

    <?php if (!empty($_GET["success"])): ?>
        <div class="alert alert-success animate-fade-in"><?= htmlspecialchars($_GET["success"]) ?></div>
    <?php endif; ?>

    <?php if (empty($guides)): ?>
        <div class="empty-state animate-fade-in">
            <div class="empty-icon">✅</div>
            <p>All clear! There are no pending guide applications right now.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive animate-fade-in">
            <table class="table">
                <thead>
                    <tr>
                        <th>Guide Information</th>
                        <th>Qualifications</th>
                        <th>Bio</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($guides as $guide): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($guide['name']) ?></strong><br>
                                <span style="font-size: 0.85em; color: #666;">✉️ <?= htmlspecialchars($guide['email']) ?></span><br>
                                <span style="font-size: 0.85em; color: #666;">🏡 <?= htmlspecialchars($guide['years_of_residency'] ?? '0') ?> Years Resident</span>
                            </td>
                            <td>
                                <span class="badge" style="background: var(--primary-color); color: black; padding: 2px 6px; border-radius: 4px; font-size: 0.8em;">
                                    <?= htmlspecialchars($guide['experience_years'] ?? '0') ?> Yrs Exp
                                </span><br>
                                <span style="font-size: 0.85em; color: #666;">🗣️ <?= htmlspecialchars($guide['languages'] ?? 'None specified') ?></span><br>
                                <span style="font-size: 0.85em; color: #666;">🍃 Eco Score: <?= htmlspecialchars($guide['sustainability_score'] ?? '0') ?></span><br>
                                
                                <?php if (!empty($guide['cert_file'])): ?>
                                    <div style="margin-top: 8px;">
                                        <a href="uploads/<?= htmlspecialchars($guide['cert_file']) ?>" target="_blank" class="btn btn-outline btn-sm" style="font-size: 0.75em; padding: 2px 5px;">📄 View Certificate</a>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($guide['proof_file'])): ?>
                                    <div style="margin-top: 4px;">
                                        <a href="uploads/<?= htmlspecialchars($guide['proof_file']) ?>" target="_blank" class="btn btn-outline btn-sm" style="font-size: 0.75em; padding: 2px 5px;">🌐 Language Proof</a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="max-width: 300px;">
                                <details>
                                    <summary style="cursor: pointer; color: var(--primary-color); font-size: 0.9em; font-weight: bold;">Read Full Bio</summary>
                                    <p style="font-size: 0.85em; margin-top: 5px; color: #444; line-height: 1.4;">
                                        <?= nl2br(htmlspecialchars($guide['bio'] ?? 'No bio provided.')) ?>
                                    </p>
                                </details>
                            </td>
                            <td>
                                <a href="index.php?action=admin_guide_approve&id=<?= $guide['id'] ?>" class="btn btn-primary btn-sm">Approve Guide</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>
