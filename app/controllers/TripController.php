<?php

class TripController
{
    private $db;

    public function __construct($databaseConnection)
    {
        $this->db = $databaseConnection;
    }

    // =========================================================================
    // Trip list & detail
    // =========================================================================

    public function showTripList($loggedInUser)
    {
        $statement = $this->db->prepare(
            "SELECT trips.*, users.name AS guide_name
             FROM trips
             JOIN guides ON trips.guide_id = guides.id
             JOIN users  ON guides.user_id = users.id
             ORDER BY trips.created_at DESC"
        );
        $statement->execute();
        $tripRows = $statement->fetchAll();

        $trips = [];
        foreach ($tripRows as $row) {
            $trip                       = new Trip();
            $trip->id                   = $row["id"];
            $trip->guide_id             = $row["guide_id"];
            $trip->title                = $row["title"];
            $trip->description          = $row["description"];
            $trip->location             = $row["location"];
            $trip->price                = $row["price"];
            $trip->capacity             = $row["capacity"];
            $trip->available_from       = $row["available_from"];
            $trip->available_to         = $row["available_to"];
            $trip->sustainability_score = $row["sustainability_score"];
            $trip->created_at           = $row["created_at"];
            $trip->tags                 = $row["tags"] ?? null;
            $trip->equipment_type       = $row["equipment_type"];
            $trip->equipment_total      = $row["equipment_total"];
            $trip->guide_name           = $row["guide_name"];
            $trips[] = $trip;
        }

        require_once __DIR__ . "/../../views/trips/list.php";
    }

    public function showGuideTrips($loggedInUser)
    {
        $guideStatement = $this->db->prepare(
            "SELECT id FROM guides WHERE user_id = ? LIMIT 1"
        );
        $guideStatement->execute([$loggedInUser->id]);
        $guideRow = $guideStatement->fetch();

        if (!$guideRow) {
            header("Location: index.php?action=dashboard&error=" . urlencode("Guide profile not found."));
            exit;
        }

        $guideId = $guideRow["id"];

        $statement = $this->db->prepare(
            "SELECT trips.*, users.name AS guide_name
             FROM trips
             JOIN guides ON trips.guide_id = guides.id
             JOIN users  ON guides.user_id = users.id
             WHERE trips.guide_id = ?
             ORDER BY trips.created_at DESC"
        );
        $statement->execute([$guideId]);
        $tripRows = $statement->fetchAll();

        $trips = [];
        foreach ($tripRows as $row) {
            $trip                       = new Trip();
            $trip->id                   = $row["id"];
            $trip->guide_id             = $row["guide_id"];
            $trip->title                = $row["title"];
            $trip->description          = $row["description"];
            $trip->location             = $row["location"];
            $trip->price                = $row["price"];
            $trip->capacity             = $row["capacity"];
            $trip->available_from       = $row["available_from"];
            $trip->available_to         = $row["available_to"];
            $trip->sustainability_score = $row["sustainability_score"];
            $trip->created_at           = $row["created_at"];
            $trip->tags                 = $row["tags"] ?? null;
            $trip->equipment_type       = $row["equipment_type"];
            $trip->equipment_total      = $row["equipment_total"];
            $trip->guide_name           = $row["guide_name"];
            $trips[] = $trip;
        }

        require_once __DIR__ . "/../../views/guide/trips.php";
    }

    public function showTripDetail($loggedInUser)
    {
        $tripId = (int) ($_GET["id"] ?? 0);

        if ($tripId <= 0) {
            header("Location: index.php?action=trips");
            exit;
        }

        $statement = $this->db->prepare(
            "SELECT trips.*, users.name AS guide_name, guides.bio AS guide_bio,
                    guides.sustainability_score AS guide_sustainability
             FROM trips
             JOIN guides ON trips.guide_id = guides.id
             JOIN users  ON guides.user_id = users.id
             WHERE trips.id = ?
             LIMIT 1"
        );
        $statement->execute([$tripId]);
        $row = $statement->fetch();

        if (!$row) {
            header("Location: index.php?action=trips");
            exit;
        }

        $trip                       = new Trip();
        $trip->id                   = $row["id"];
        $trip->guide_id             = $row["guide_id"];
        $trip->title                = $row["title"];
        $trip->description          = $row["description"];
        $trip->location             = $row["location"];
        $trip->price                = $row["price"];
        $trip->capacity             = $row["capacity"];
        $trip->available_from       = $row["available_from"];
        $trip->available_to         = $row["available_to"];
        $trip->sustainability_score = $row["sustainability_score"];
        $trip->guide_name           = $row["guide_name"];
        $trip->guide_bio            = $row["guide_bio"];
        $trip->tags                 = $row["tags"];
        $trip->equipment_type       = $row["equipment_type"];
        $trip->equipment_total      = $row["equipment_total"];

        $optimizedRoute = $this->getOptimizedRoute($tripId);

        $reviewStatement = $this->db->prepare(
            "SELECT reviews.*, users.name AS reviewer_name
             FROM reviews
             JOIN users ON reviews.user_id = users.id
             WHERE reviews.trip_id = ?
             ORDER BY reviews.created_at DESC"
        );
        $reviewStatement->execute([$tripId]);
        $reviews = $reviewStatement->fetchAll();

        require_once __DIR__ . "/../../views/trips/detail.php";
    }

    // =========================================================================
    // Create trip
    // =========================================================================

    public function showCreateForm($loggedInUser)
    {
        $errorMessage = $_GET["error"] ?? "";
        require_once __DIR__ . "/../../views/trips/create.php";
    }

    public function handleCreate($loggedInUser)
    {
        $title          = trim($_POST["title"] ?? "");
        $description    = trim($_POST["description"] ?? "");
        $location       = trim($_POST["location"] ?? "");
        $price          = $_POST["price"] ?? "";
        $capacity       = $_POST["capacity"] ?? "";
        $availableFrom  = $_POST["available_from"] ?? "";
        $availableTo    = $_POST["available_to"] ?? "";
        $transportType  = trim($_POST["transport_type"] ?? "");
        $type           = trim($_POST["type"] ?? "");
        $equipmentType  = trim($_POST["equipment_type"] ?? "");
        $equipmentTotal = (int) ($_POST["equipment_total"] ?? 0);

        $tags     = $this->generateImpactTags($_POST);
        $tagsJson = json_encode($tags);

        if (
            empty($title) || empty($location) || empty($price)
            || empty($capacity) || empty($availableFrom) || empty($availableTo)
        ) {
            header("Location: index.php?action=trip_create&error=" . urlencode("Please fill in all required fields."));
            exit;
        }

        if ((float) $price <= 0) {
            header("Location: index.php?action=trip_create&error=" . urlencode("Price must be greater than zero."));
            exit;
        }

        $guideStatement = $this->db->prepare(
            "SELECT id FROM guides WHERE user_id = ? LIMIT 1"
        );
        $guideStatement->execute([$loggedInUser->id]);
        $guideRow = $guideStatement->fetch();

        if (!$guideRow && $loggedInUser->role !== "admin") {
            header("Location: index.php?action=dashboard&error=" . urlencode("You do not have a guide profile yet."));
            exit;
        }

        $guideId = $guideRow ? $guideRow["id"] : null;

        // FIX: SQL now includes tags, equipment_type, equipment_total (11 columns = 11 values)
        $statement = $this->db->prepare(
            "INSERT INTO trips
    (
        guide_id,
        title,
        description,
        location,
        price,
        capacity,
        available_from,
        available_to,
        tags,
        equipment_type,
        equipment_total
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $statement->execute([
            $guideId,
            $title,
            $description,
            $location,
            (float) $price,
            (int) $capacity,
            $availableFrom,
            $availableTo,
            $tagsJson,
            $equipmentType,
            (int) $equipmentTotal
        ]);
        $newTripId = (int) $this->db->lastInsertId();
        $this->calculateEcoLeafScore($newTripId);

        header("Location: index.php?action=trips&success=" . urlencode("Trip created successfully!"));
        exit;
    }

    // =========================================================================
    // Translation helper
    // =========================================================================

    public function translationNeeded($tripId, $updatedLanguage)
    {
        // FIX: moved SQL comment to its own line so -- does not eat the last condition
        $updateStatement = $this->db->prepare(
            "UPDATE trip_translations
             SET translation_needed = 1
             WHERE trip_id = ?
             AND language != ?
             -- exclude the language that was just updated
             AND language IS NOT NULL"
        );
        return $updateStatement->execute([$tripId, $updatedLanguage]);
    }

    // =========================================================================
    // Carbon offset
    // =========================================================================

    public function showCarbonOffset($user)
    {
        $tripId = $_GET['trip_id'];

        $result = $this->calculate_carbon_offset($tripId);

        require __DIR__ . '/../../views/trips/carbon_offset.php';
    }
    public function calculate_carbon_offset($tripId)
    {
        $statement = $this->db->prepare(
            "SELECT * FROM trips WHERE id = ? LIMIT 1"
        );
        $statement->execute([$tripId]);
        $tripRow = $statement->fetch();

        if (!$tripRow) {
            return false;
        }

        $transportType = $tripRow["transport_type"] ?? "other";
        $carbonPerKm   = match ($transportType) {
            "plane"   => 0.25,
            "bus"     => 0.30,
            "walking" => 0.02,
            default   => 0.15,
        };

        $distance   = (float) ($tripRow["distance_km"] ?? 100);
        $carbonCost = $distance * $carbonPerKm;

        $statement = $this->db->prepare(
            "SELECT * FROM carbon_offset
             WHERE location = ?
             ORDER BY cost_per_kg ASC
             LIMIT 3"
        );
        $statement->execute([$tripRow["location"]]);
        $carbonRows = $statement->fetchAll();

        return [
            "carbon_cost"     => $carbonCost,
            "carbon_projects" => $carbonRows,
        ];
    }

    // =========================================================================
    // Indigenous consent check
    // =========================================================================

    public function checkConsent($user)
    {
        $tripId = $_GET['trip_id'];

        $this->check_indigenous_consent($tripId);

        require __DIR__ . '/../../views/trips/consent_check.php';
    }
    public function check_indigenous_consent($tripId)
    {
        $statement = $this->db->prepare(
            "SELECT * FROM trips WHERE id = ? LIMIT 1"
        );
        $statement->execute([$tripId]);
        $tripRow = $statement->fetch();

        if (!$tripRow) {
            header("Location: index.php?action=trips&error=" . urlencode("Trip not found."));
            exit;
        }

        if (!$tripRow["is_protected_area"]) {
            return; // Not a protected area — no consent needed
        }

        if ($tripRow["indigenous_consent_status"] !== "approved") {
            header("Location: index.php?action=trip_detail&id=$tripId&error=" . urlencode("This trip requires indigenous community approval."));
            exit;
        }
    }

    // =========================================================================
    // Leave No Trace PDF
    // =========================================================================

    public function downloadLeaveNoTrace($user)
    {
        $tripId = $_GET['trip_id'];

        $file = $this->generate_leave_no_trace_PDF($tripId, $user);

        header("Location: storage/" . $file);
        exit;
    }
    public function generate_leave_no_trace_PDF($tripId, $loggedInUser)
    {
        $statement = $this->db->prepare(
            "SELECT trips.*, users.name AS user_name
             FROM bookings
             JOIN trips ON bookings.trip_id = trips.id
             JOIN users ON bookings.user_id = users.id
             WHERE bookings.trip_id = ?
               AND bookings.user_id = ?
               AND bookings.status != 'cancelled'
             LIMIT 1"
        );
        $statement->execute([$tripId, $loggedInUser->id]);
        $tripRow = $statement->fetch();

        if (!$tripRow) {
            header("Location: index.php?action=trips&error=" . urlencode("You are not allowed to access this document."));
            exit;
        }

        $tripType = $tripRow["type"] ?? "general";

        $rules = match ($tripType) {
            "desert" => [
                "Avoid damaging desert plants",
                "Carry enough water",
                "Do not leave trash behind",
            ],
            "marine" => [
                "Do not touch coral reefs",
                "Avoid plastic in water",
                "Respect marine life",
            ],
            default => [
                "Do not litter in nature areas",
                "Respect wildlife and avoid disturbing animals",
                "Use reusable bottles instead of plastic",
                "Stay on marked paths to protect ecosystems",
                "Support local communities and guides",
            ],
        };

        $content  = "Leave No Trace Guidelines\n\n";
        $content .= "Traveler: " . $tripRow["user_name"] . "\n";
        $content .= "Trip: "     . $tripRow["title"]     . "\n";
        $content .= "Location: " . $tripRow["location"]  . "\n\n";

        foreach ($rules as $rule) {
            $content .= "- $rule\n";
        }

        $fileName = "leave_no_trace_" . $loggedInUser->id . "_" . $tripId . ".pdf";

        // FIX: was bare DIR (undefined constant) — must be __DIR__
        $storagePath = __DIR__ . "/../../storage/" . $fileName;
        file_put_contents($storagePath, $content);

        return $fileName;
    }

    // =========================================================================
    // Eco-Leaf score
    // =========================================================================


    public function showEcoScore($user)
    {
        $tripId = $_GET['trip_id'];

        $score = $this->calculateEcoLeafScore($tripId);

        require __DIR__ . '/../../views/trips/eco_score.php';
    }
    public function calculateEcoLeafScore($tripId)
    {
        $statement = $this->db->prepare(
            "SELECT trips.waste_management_level, guides.local_cred_score
             FROM trips
             JOIN guides ON trips.guide_id = guides.id
             WHERE trips.id = ?
             LIMIT 1"
        );
        $statement->execute([$tripId]);
        $data = $statement->fetch();

        if (!$data) {
            return false;
        }

        // FIX: guard against calculate_carbon_offset returning false
        $carbonData = $this->calculate_carbon_offset($tripId);
        $carbonCost = ($carbonData !== false) ? (float) $carbonData["carbon_cost"] : 25.0;
        $carbonScore = max(0, 100 - ($carbonCost * 2));

        $wasteScore = match ($data["waste_management_level"] ?? "") {
            "zero_waste" => 100,
            "recycling"  => 70,
            "basic"      => 40,
            default      => 10,
        };

        $localCredScore = (float) ($data["local_cred_score"] ?? 0);

        $ecoLeafScore = round(
            ($carbonScore * 0.40) + ($wasteScore * 0.30) + ($localCredScore * 0.30),
            2
        );

        $updateStatement = $this->db->prepare(
            "UPDATE trips SET sustainability_score = ? WHERE id = ?"
        );
        $updateStatement->execute([$ecoLeafScore, $tripId]);

        return $ecoLeafScore;
    }

    // =========================================================================
    // Global sustainability report
    // =========================================================================

    public function showGlobalSustainabilityReport($user)
    {
        $report = $this->getGlobalSustainabilityReport();
        $topEcoTrips = $this->getTopEcoTrips();
        $carbonProjects = $this->getCarbonProjects();

        require __DIR__ . '/../../views/sustainability/report.php';
    }
    public function getGlobalSustainabilityReport()
    {
        $carbonStatement = $this->db->query(
            "SELECT SUM(carbon_score) AS total_co2
             FROM trips
             JOIN bookings ON bookings.trip_id = trips.id
             WHERE bookings.status != 'cancelled'"
        );
        $totalCo2 = (float) ($carbonStatement->fetch()["total_co2"] ?? 0);

        $jobsStatement = $this->db->query(
            "SELECT COUNT(DISTINCT guide_id) AS total_jobs
             FROM trips
             JOIN bookings ON bookings.trip_id = trips.id
             WHERE bookings.status != 'cancelled'"
        );
        $totalLocalJobs = (int) ($jobsStatement->fetch()["total_jobs"] ?? 0);

        $avgStatement = $this->db->query(
            "SELECT AVG(sustainability_score) AS avg_score
             FROM trips
             WHERE sustainability_score IS NOT NULL"
        );
        $avgEcoScore = round((float) ($avgStatement->fetch()["avg_score"] ?? 0), 2);

        return [
            "total_co2_offset_kg" => round($totalCo2, 2),
            "total_local_jobs"    => $totalLocalJobs,
            "avg_eco_leaf_score"  => $avgEcoScore,
        ];
    }

    public function getTopEcoTrips()
    {
        $statement = $this->db->query(
            "SELECT * FROM trips
             WHERE sustainability_score IS NOT NULL
             ORDER BY sustainability_score DESC
             LIMIT 5"
        );
        return $statement->fetchAll();
    }

    public function getCarbonProjects()
    {
        $statement = $this->db->query(
            "SELECT * FROM carbon_offset
             ORDER BY cost_per_kg ASC
             LIMIT 5"
        );
        return $statement->fetchAll();
    }

    // =========================================================================
    // Private helpers
    // =========================================================================


    public function showOptimizedRoute($user)
    {
        $tripId = $_GET['trip_id'];

        $route = $this->getOptimizedRoute($tripId);

        require __DIR__ . '/../../views/trips/route.php';
    }
    private function generateImpactTags($tripData)
    {
        $tags = [];

        if (!empty($tripData["no_plastic"]) && $tripData["no_plastic"] == 1) {
            $tags[] = "Plastic-Free";
        }
        if (!empty($tripData["local_food"]) && $tripData["local_food"] == 1) {
            $tags[] = "Supports Local Community";
        }
        if (!empty($tripData["wildlife_support"]) && $tripData["wildlife_support"] == 1) {
            $tags[] = "Supports Local Wildlife";
        }
        if (
            !empty($tripData["transport_type"])
            && in_array($tripData["transport_type"], ["bike", "walking"])
        ) {
            $tags[] = "Low Carbon Transport";
        }

        return $tags;
    }

    public function getOptimizedRoute($tripId)
    {
        $statement = $this->db->prepare(
            "SELECT name, lat, lng
             FROM trip_locations
             WHERE trip_id = ?
             ORDER BY order_index ASC"
        );
        $statement->execute([$tripId]);
        $locations = $statement->fetchAll();

        return $this->optimizeSustainableRoute($locations);
    }

    private function optimizeSustainableRoute($locations)
    {
        if (empty($locations)) {
            return [];
        }

        $route   = [];
        $current = array_shift($locations);
        $route[] = $current;

        while (!empty($locations)) {
            $nearestIndex    = 0;
            $nearestDistance = PHP_INT_MAX;

            foreach ($locations as $index => $location) {
                $distance = $this->calculateDistance($current, $location);
                if ($distance < $nearestDistance) {
                    $nearestDistance = $distance;
                    $nearestIndex    = $index;
                }
            }

            $current = $locations[$nearestIndex];
            $route[] = $current;
            unset($locations[$nearestIndex]);
            $locations = array_values($locations);
        }

        return $route;
    }

    private function calculateDistance($loc1, $loc2)
    {
        if (!isset($loc1["lat"], $loc1["lng"], $loc2["lat"], $loc2["lng"])) {
            return 0;
        }

        $lat1 = (float) $loc1["lat"];
        $lon1 = (float) $loc1["lng"];
        $lat2 = (float) $loc2["lat"];
        $lon2 = (float) $loc2["lng"];

        $earthRadius = 6371;
        $dLat        = deg2rad($lat2 - $lat1);
        $dLon        = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
