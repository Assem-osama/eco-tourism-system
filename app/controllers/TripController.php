<?php

class TripController
{

    private $db;

    public function __construct($databaseConnection)
    {
        $this->db = $databaseConnection;
    }

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

        // Map each row into a Trip object
        $trips = [];
        foreach ($tripRows as $row) {
            $trip                    = new Trip();
            $trip->id                = $row["id"];
            $trip->guide_id          = $row["guide_id"];
            $trip->title             = $row["title"];
            $trip->description       = $row["description"];
            $trip->location          = $row["location"];
            $trip->price             = $row["price"];
            $trip->capacity          = $row["capacity"];
            $trip->available_from    = $row["available_from"];
            $trip->available_to      = $row["available_to"];
            $trip->sustainability_score = $row["sustainability_score"];
            $trip->created_at        = $row["created_at"];
            // Extra field from the JOIN
            $trip->guide_name        = $row["guide_name"];
            $trips[] = $trip;
        }

        require_once __DIR__ . "/../../views/trips/list.php";
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

        // Fetch reviews for this trip
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


    public function showCreateForm($loggedInUser)
    {
        $errorMessage = $_GET["error"] ?? "";
        require_once __DIR__ . "/../../views/trips/create.php";
    }

    public function handleCreate($loggedInUser)
    {

        $title           = trim($_POST["title"] ?? "");
        $description     = trim($_POST["description"] ?? "");
        $location        = trim($_POST["location"] ?? "");
        $price           = $_POST["price"] ?? "";
        $capacity        = $_POST["capacity"] ?? "";
        $availableFrom   = $_POST["available_from"] ?? "";
        $availableTo     = $_POST["available_to"] ?? "";

        if (
            empty($title) || empty($location) || empty($price) || empty($capacity)
            || empty($availableFrom) || empty($availableTo)
        ) {
            header("Location: index.php?action=trip_create&error=" . urlencode("Please fill in all required fields."));
            exit;
        }

        if ((float) $price <= 0) {
            header("Location: index.php?action=trip_create&error=" . urlencode("Price must be greater than zero."));
            exit;
        }

        // Find the guide record that belongs to this user
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

        $statement = $this->db->prepare(
            "INSERT INTO trips
                (guide_id, title, description, location, price, capacity, available_from, available_to)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $statement->execute([
            $guideId,
            $title,
            $description,
            $location,
            (float) $price,
            (int) $capacity,
            $availableFrom,
            $availableTo
        ]);

        header("Location: index.php?action=trips&success=" . urlencode("Trip created successfully!"));
        exit;
    }
}
