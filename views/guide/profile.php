<?php
$pageTitle = 'Guide Profile — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";
?>

<main class="page-content">
    <div class="header-section">
        <h2>My Guide Profile</h2>
        <p class="form-hint">Manage your certifications, language skills, and trainee requests.</p>
    </div>

    <?php if (!empty($_GET["error"])): ?>
        <div class="alert alert-error animate-fade-in"><?= htmlspecialchars($_GET["error"]) ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET["success"])): ?>
        <div class="alert alert-success animate-fade-in"><?= htmlspecialchars($_GET["success"]) ?></div>
    <?php endif; ?>

    <div class="profile-grid">
        <!-- ── Certificate upload ──────────────────────────────── -->
        <div class="form-card animate-fade-in">
            <div class="card-header">
                <h3>📄 Guide Certificate</h3>
            </div>
            <div class="card-body">
                <p class="form-hint">Upload or renew your official eco-tourism guide certificate.</p>
                <form method="POST" action="index.php?action=certificate_submit" enctype="multipart/form-data">
                    <input type="hidden" name="guide_id" value="<?= (int) ($guide->id ?? 0) ?>">
                    <div class="form-group">
                        <label>Certificate File *</label>
                        <input type="file" name="certificate_file" accept=".pdf,.jpg,.jpeg,.png" required>
                        <p class="form-hint">Uploading a new version will flag existing translations for update.</p>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <?= isset($guide->has_certificate) && $guide->has_certificate ? 'Renew Certificate' : 'Upload Certificate' ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- ── Language verification ───────────────────────────── -->
        <div class="form-card animate-fade-in">
            <div class="card-header">
                <h3>🌐 Language Verification</h3>
            </div>
            <div class="card-body">
                <p class="form-hint">Submit languages for review to display them on your public profile.</p>
                <form method="POST" action="index.php?action=language_verification_submit" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Language *</label>
                        <input type="text" name="language" required placeholder="e.g. Arabic, French, Spanish">
                    </div>
                    <div class="form-group">
                        <label>Verification Method *</label>
                        <select name="verification_method" id="verificationMethod" onchange="toggleProofField(this.value)">
                            <option value="certificate">Certificate</option>
                            <option value="native_speaker">Native speaker</option>
                            <option value="test">Language test</option>
                        </select>
                    </div>
                    <div id="proofFileSection" class="form-group">
                        <label>Proof File *</label>
                        <input type="file" name="proof_file" id="proofFileInput" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit for Review</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ── Trainee shadowing requests ─────────────────────── -->
    <?php if (!empty($pendingShadowRequests)): ?>
        <div class="form-card animate-fade-in" style="margin-top:1.5rem">
            <div class="card-header">
                <h3>👥 Pending Trainee Requests</h3>
            </div>
            <div class="card-body">
                <?php foreach ($pendingShadowRequests as $request): ?>
                    <div class="shadow-request-item">
                        <div class="request-info">
                            <strong><?= htmlspecialchars($request["trainee_name"]) ?></strong>
                            <p class="form-hint">Wants to shadow: <em><?= htmlspecialchars($request["trip_title"]) ?></em></p>
                        </div>
                        <form method="POST" action="index.php?action=trainee_shadow_approve">
                            <input type="hidden" name="trip_id" value="<?= (int) $request["trip_id"] ?>">
                            <input type="hidden" name="trainee_guide_id" value="<?= (int) $request["trainee_guide_id"] ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<script>
    function toggleProofField(method) {
        const proofSection = document.getElementById("proofFileSection");
        const proofInput = document.getElementById("proofFileInput");
        const needsProof = method === "certificate";
        proofSection.style.display = needsProof ? "block" : "none";
        proofInput.required = needsProof;
    }
    toggleProofField(document.getElementById("verificationMethod").value);
</script>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>