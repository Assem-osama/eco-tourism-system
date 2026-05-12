<?php

class GuideController
{
    private $db;

    public function __construct($databaseConnection)
    {
        $this->db = $databaseConnection;
    }
    public function showGuidePanel($loggedInUser)
    {
        $statement = $this->db->prepare(
            "SELECT * FROM guides WHERE user_id = ? LIMIT 1"
        );
        $statement->execute([$loggedInUser->id]);
        $guideData = $statement->fetch();

        if (!$guideData) {
            header("Location: index.php?action=dashboard&error=" . urlencode("Guide profile not found."));
            exit;
        }

        $guideId = $guideData["id"];

        // Get total trips
        $tripCountStmt = $this->db->prepare("SELECT COUNT(*) FROM trips WHERE guide_id = ?");
        $tripCountStmt->execute([$guideId]);
        $totalTrips = $tripCountStmt->fetchColumn();

        // Get total bookings for guide's trips
        $bookingCountStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM bookings 
             JOIN trips ON bookings.trip_id = trips.id 
             WHERE trips.guide_id = ?"
        );
        $bookingCountStmt->execute([$guideId]);
        $totalBookings = $bookingCountStmt->fetchColumn();

        // Get pending shadow requests
        $shadowReqStmt = $this->db->prepare(
            "SELECT ts.*, t.title as trip_title, u.name as trainee_name 
             FROM trip_shadows ts
             JOIN trips t ON ts.trip_id = t.id
             JOIN guides g ON ts.trainee_guide_id = g.id
             JOIN users u ON g.user_id = u.id
             WHERE t.guide_id = ? AND ts.status = 'pending'"
        );
        $shadowReqStmt->execute([$guideId]);
        $pendingShadowRequests = $shadowReqStmt->fetchAll();

        require_once __DIR__ . '/../../views/guide/panel.php';
    }
    // Certificate upload / renewal

    public function upload_or_renew_certificate($loggedInUser)
    {
        $guideId  = (int) ($_POST["guide_id"] ?? 0);
        $fileName = "";
        if (!empty($_FILES["certificate_file"]["name"])) {
            $fileName = time() . "_" . basename($_FILES["certificate_file"]["name"]);
            $targetPath = __DIR__ . "/../../public/uploads/" . $fileName;
            move_uploaded_file($_FILES["certificate_file"]["tmp_name"], $targetPath);
        }

        if ($guideId <= 0 || empty($fileName)) {
            header("Location: index.php?action=dashboard&error=" . urlencode("Invalid certificate data."));
            exit;
        }

        // Confirm the guide row belongs to the logged-in user
        $ownerStatement = $this->db->prepare(
            "SELECT id FROM guides WHERE id = ? AND user_id = ? LIMIT 1"
        );
        $ownerStatement->execute([$guideId, $loggedInUser->id]);

        if (!$ownerStatement->fetch()) {
            header("Location: index.php?action=dashboard&error=" . urlencode("Not allowed."));
            exit;
        }

        $existingStatement = $this->db->prepare(
            "SELECT * FROM certificates WHERE guide_id = ? LIMIT 1"
        );
        $existingStatement->execute([$guideId]);
        $existingCert = $existingStatement->fetch();

        $translationFlag = 1;

        if ($existingCert) {
            $newVersion = $existingCert["version"] + 1;

            // FIX: SQL comment was on the same line as a value, causing MySQL
            // to treat the rest of the query as a comment. Moved to its own line.
            $updateStatement = $this->db->prepare(
                "UPDATE certificates
                 SET certificate_file   = ?,
                     version            = ?,
                     status             = 'active',
                     translation_needed = ?,
                     -- action is set to 'renew' when replacing an existing certificate
                     action             = 'renew'
                 WHERE id = ?"
            );
            $updateStatement->execute([
                $fileName,
                $newVersion,
                $translationFlag,
                $existingCert["id"],
            ]);
        } else {
            $insertStatement = $this->db->prepare(
                "INSERT INTO certificates
                    (guide_id, certificate_file, status, version, translation_needed, action)
                 VALUES (?, ?, 'active', 1, ?, 'upload')"
            );
            $insertStatement->execute([$guideId, $fileName, $translationFlag]);
        }

        // Flag all certificates for this guide as needing translation/review
        $translationStatement = $this->db->prepare(
            "UPDATE certificates
             SET translation_needed = 1
             WHERE guide_id = ?"
        );
        $translationStatement->execute([$guideId]);

        header("Location: index.php?action=guide_profile&success=" . urlencode("Certificate saved successfully!"));
        exit;
    }

    public function handleLanguageVerification($loggedInUser)
    {
        $language           = trim($_POST["language"] ?? "");
        $verificationMethod = trim($_POST["verification_method"] ?? "certificate");
        
        $proofFile = "";
        if ($verificationMethod === "certificate" && !empty($_FILES["proof_file"]["name"])) {
            $proofFile = time() . "_" . basename($_FILES["proof_file"]["name"]);
            $targetPath = __DIR__ . "/../../public/uploads/" . $proofFile;
            move_uploaded_file($_FILES["proof_file"]["tmp_name"], $targetPath);
        }

        if (empty($language) || empty($verificationMethod)) {
            header("Location: index.php?action=guide_profile&error=" . urlencode("Please fill in all language verification fields."));
            exit;
        }

        if ($verificationMethod === "certificate" && empty($proofFile)) {
            header("Location: index.php?action=guide_profile&error=" . urlencode("Please upload your certificate proof."));
            exit;
        }

        $guideStatement = $this->db->prepare(
            "SELECT id FROM guides WHERE user_id = ? LIMIT 1"
        );
        $guideStatement->execute([$loggedInUser->id]);
        $guideRow = $guideStatement->fetch();

        if (!$guideRow) {
            header("Location: index.php?action=guide_profile&error=" . urlencode("Guide profile not found."));
            exit;
        }

        // Prevent duplicate submissions for the same language
        $duplicateStatement = $this->db->prepare(
            "SELECT id FROM guide_languages
             WHERE guide_id = ? AND language = ? AND status != 'rejected'
             LIMIT 1"
        );
        $duplicateStatement->execute([$guideRow["id"], $language]);

        if ($duplicateStatement->fetch()) {
            header("Location: index.php?action=guide_profile&error=" . urlencode("Language already submitted."));
            exit;
        }

        $insertStatement = $this->db->prepare(
            "INSERT INTO guide_languages
                (guide_id, language, verification_method, proof_file, status)
             VALUES (?, ?, ?, ?, 'pending')"
        );
        $insertStatement->execute([$guideRow["id"], $language, $verificationMethod, $proofFile]);

        header("Location: index.php?action=guide_profile&success=" . urlencode("Language verification requested successfully."));
        exit;
    }

    public function calculateLocalCredScore($guideId)
    {
        $guideStatement = $this->db->prepare(
            "SELECT years_of_residency, community_score FROM guides WHERE id = ?"
        );
        $guideStatement->execute([$guideId]);
        $guideRow = $guideStatement->fetch();

        if (!$guideRow) {
            return false;
        }

        $yearsOfResidency = (int) ($guideRow["years_of_residency"] ?? 0);
        $communityScore   = (int) ($guideRow["community_score"] ?? 0);

        $rawScore       = ($yearsOfResidency * 5) + ($communityScore * 10);
        $localCredScore = min(100.0, round($rawScore, 2));

        $updateStatement = $this->db->prepare(
            "UPDATE guides SET local_cred_score = ? WHERE id = ?"
        );
        $updateStatement->execute([$localCredScore, $guideId]);

        return $localCredScore;
    }

    public function handleTraineeShadowing($loggedInUser)
    {
        $tripId         = (int) ($_POST["trip_id"] ?? 0);
        $traineeGuideId = (int) ($_POST["trainee_guide_id"] ?? 0);

        if ($tripId <= 0 || $traineeGuideId <= 0) {
            header("Location: index.php?action=dashboard&error=" . urlencode("Invalid trip or trainee."));
            exit;
        }

        $seniorGuideStatement = $this->db->prepare(
            "SELECT id FROM guides WHERE user_id = ? LIMIT 1"
        );
        $seniorGuideStatement->execute([$loggedInUser->id]);
        $seniorGuideRow = $seniorGuideStatement->fetch();

        if (!$seniorGuideRow && $loggedInUser->role !== "admin") {
            header("Location: index.php?action=dashboard&error=" . urlencode("Unauthorized action."));
            exit;
        }

        $seniorGuideId = $seniorGuideRow ? $seniorGuideRow["id"] : null;

        $pendingStatement = $this->db->prepare(
            "SELECT id FROM trip_shadows
             WHERE trip_id = ? AND trainee_guide_id = ? AND status = 'pending'
             LIMIT 1"
        );
        $pendingStatement->execute([$tripId, $traineeGuideId]);
        $shadowRow = $pendingStatement->fetch();

        if (!$shadowRow) {
            header("Location: index.php?action=dashboard&error=" . urlencode("No pending request found."));
            exit;
        }

        $updateStatement = $this->db->prepare(
            "UPDATE trip_shadows SET status = 'active', senior_guide_id = ? WHERE id = ?"
        );
        $updateStatement->execute([$seniorGuideId, $shadowRow["id"]]);

        header("Location: index.php?action=guide_panel&success=" . urlencode("Trainee approved successfully."));
        exit;
    }

    public function handleFieldReport($loggedInUser)
    {
        $tripId     = (int) ($_POST["trip_id"] ?? 0);
        $reportText = trim($_POST["report_text"] ?? "");
        $hasPhoto   = !empty($_FILES["field_photo"]["name"]);

        if ($tripId <= 0) {
            header("Location: index.php?action=dashboard&error=" . urlencode("Please choose a valid trip."));
            exit;
        }

        if (empty($reportText) && !$hasPhoto) {
            header("Location: index.php?action=field_report&error=" . urlencode("Please provide a report text or upload a photo."));
            exit;
        }

        $guideStatement = $this->db->prepare(
            "SELECT id FROM guides WHERE user_id = ? LIMIT 1"
        );
        $guideStatement->execute([$loggedInUser->id]);
        $guideId = $guideStatement->fetchColumn();

        if (!$guideId) {
            header("Location: index.php?action=dashboard&error=" . urlencode("You are not a registered guide."));
            exit;
        }

        $photoPath = null;

        if ($hasPhoto) {
            $safeFileName = time() . "_" . basename($_FILES["field_photo"]["name"]);
            $uploadPath   = __DIR__ . "/../../uploads/" . $safeFileName;

            if (move_uploaded_file($_FILES["field_photo"]["tmp_name"], $uploadPath)) {
                $photoPath = "uploads/" . $safeFileName;
            } else {
                header("Location: index.php?action=field_report&error=" . urlencode("Failed to upload the photo."));
                exit;
            }
        }

        $insertStatement = $this->db->prepare(
            "INSERT INTO field_reports (guide_id, trip_id, report_text, photo_url, posted_at)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $insertStatement->execute([$guideId, $tripId, $reportText, $photoPath]);

        header("Location: index.php?action=trip_detail&id=$tripId&success=" . urlencode("Report submitted successfully!"));
        exit;
    }
    public function showFieldReportForm($loggedInUser)
    {
        $statement = $this->db->prepare(
            "SELECT *
         FROM trips
         WHERE guide_id IN (
             SELECT id FROM guides WHERE user_id = ?
         )
         ORDER BY created_at DESC"
        );

        $statement->execute([$loggedInUser->id]);

        $activeTrips = $statement->fetchAll();

        require_once __DIR__ . "/../../views/guide/field_report.php";
    }

    public function showGuideProfile($loggedInUser)
    {
        $guideStatement = $this->db->prepare(
            "SELECT * FROM guides WHERE user_id = ? LIMIT 1"
        );

        $guideStatement->execute([$loggedInUser->id]);

        $guide = $guideStatement->fetch(PDO::FETCH_OBJ);

        $pendingShadowRequests = [];

        require_once __DIR__ . "/../../views/guide/profile.php";
    }

    public function issueStrike($loggedInUser)
    {
        $userId = (int) ($_GET["id"] ?? 0);
        if ($userId <= 0) {
            header("Location: index.php?action=admin_dashboard&error=" . urlencode("Invalid user ID."));
            exit;
        }

        // Increment strike
        $statement = $this->db->prepare("UPDATE users SET strikes_count = strikes_count + 1 WHERE id = ?");
        $statement->execute([$userId]);

        // Check if >= 3 and blacklist
        $checkStmt = $this->db->prepare("SELECT strikes_count, name FROM users WHERE id = ?");
        $checkStmt->execute([$userId]);
        $userRow = $checkStmt->fetch();

        if ($userRow && $userRow['strikes_count'] >= 3) {
            $blacklistStmt = $this->db->prepare("UPDATE users SET account_status = 'blacklisted' WHERE id = ?");
            $blacklistStmt->execute([$userId]);
            createLog($this->db, $loggedInUser->id, "Guide Blacklisted", "User {$userRow['name']} reached 3 strikes and was blacklisted.", "users", $userId);
            header("Location: index.php?action=admin_dashboard&success=" . urlencode("User has been issued a strike and is now blacklisted."));
            exit;
        }

        createLog($this->db, $loggedInUser->id, "Issued Strike", "Issued a strike to User {$userRow['name']}. Total strikes: {$userRow['strikes_count']}", "users", $userId);
        header("Location: index.php?action=admin_dashboard&success=" . urlencode("Strike successfully issued to the user."));
        exit;
    }

    public function resetStrikes($loggedInUser)
    {
        $userId = (int) ($_GET["id"] ?? 0);
        if ($userId > 0) {
            $statement = $this->db->prepare("UPDATE users SET strikes_count = 0, account_status = 'active' WHERE id = ?");
            $statement->execute([$userId]);

            $checkStmt = $this->db->prepare("SELECT name FROM users WHERE id = ?");
            $checkStmt->execute([$userId]);
            $userRow = $checkStmt->fetch();
            
            createLog($this->db, $loggedInUser->id, "Reset Strikes", "Reset strikes for User {$userRow['name']}.", "users", $userId);
        }
        header("Location: index.php?action=admin_dashboard&success=" . urlencode("Strikes reset successfully."));
        exit;
    }

    public function showAdminGuidesVetting($loggedInUser)
    {
        $statement = $this->db->prepare(
            "SELECT guides.*, users.name, users.email,
             (SELECT certificate_file FROM certificates WHERE guide_id = guides.id ORDER BY created_at DESC LIMIT 1) AS cert_file,
             (SELECT proof_file FROM guide_languages WHERE guide_id = guides.id ORDER BY id DESC LIMIT 1) AS proof_file
             FROM guides 
             JOIN users ON guides.user_id = users.id 
             WHERE guides.status = 'pending'"
        );
        $statement->execute();
        $guides = $statement->fetchAll();

        require_once __DIR__ . "/../../views/admin/guide_vetting.php";
    }

    public function approveGuide($loggedInUser)
    {
        $guideId = (int) ($_GET["id"] ?? 0);
        if ($guideId > 0) {
            $statement = $this->db->prepare("UPDATE guides SET status = 'approved' WHERE id = ?");
            $statement->execute([$guideId]);
            createLog($this->db, $loggedInUser->id, "Approved Guide", "Approved guide profile with ID: $guideId", "guides", $guideId);
        }
        header("Location: index.php?action=admin_guides_vetting&success=" . urlencode("Guide approved successfully."));
        exit;
    }
}
