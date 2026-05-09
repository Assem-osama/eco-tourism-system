<?php

$report = $report ?? [];

$topEcoTrips = $topEcoTrips ?? [];

$carbonProjects = $carbonProjects ?? [];

$totalCarbonOffset = (float) ($report["total_co2_offset_kg"] ?? 0);

$totalLocalJobs = (int) ($report["total_local_jobs"] ?? 0);

$averageEcoLeafScore = (float) ($report["avg_eco_leaf_score"] ?? 0);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Sustainability Report — Eco Tourism
    </title>

    <link
        rel="stylesheet"
        href="assets/style.css">

    <style>
        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin: 1.5rem 0;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
            text-align: center;
        }

        .stat-icon {
            font-size: 2.2rem;
            margin-bottom: .5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2d6a4f;
            margin-bottom: .25rem;
        }

        .stat-label {
            color: #666;
            font-size: .9rem;
        }

        .eco-leaf-bar-wrap {
            background: #e8f5e9;
            border-radius: 8px;
            height: 18px;
            margin-top: .5rem;
            overflow: hidden;
        }

        .eco-leaf-bar {
            background: #2d6a4f;
            height: 100%;
            border-radius: 8px;
            transition: width .6s ease;
        }

        .carbon-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .carbon-table th {
            background: #2d6a4f;
            color: #fff;
            padding: .65rem 1rem;
            text-align: left;
            font-size: .85rem;
        }

        .carbon-table td {
            padding: .65rem 1rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: .9rem;
        }

        .tag-pill {
            display: inline-block;
            padding: .2rem .65rem;
            border-radius: 20px;
            background: #e8f5e9;
            color: #1b4332;
            font-size: .78rem;
            margin: .15rem;
        }
    </style>

</head>

<body>

    <?php require_once __DIR__ . '/../partials/nav.php'; ?>

    <main class="page-content">

        <h2>
            🌍 Global Sustainability Report
        </h2>

        <p class="form-hint">
            Live metrics across all confirmed trips on the platform.
        </p>

        <!-- Statistics -->

        <div class="report-grid">

            <div class="stat-card">

                <div class="stat-icon">
                    💨
                </div>

                <div class="stat-value">
                    <?= number_format($totalCarbonOffset, 1) ?> kg
                </div>

                <div class="stat-label">
                    Total CO₂ offset
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon">
                    👷
                </div>

                <div class="stat-value">
                    <?= number_format($totalLocalJobs) ?>
                </div>

                <div class="stat-label">
                    Local guide jobs supported
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon">
                    🍃
                </div>

                <div class="stat-value">
                    <?= number_format($averageEcoLeafScore, 1) ?>
                </div>

                <div class="stat-label">
                    Avg Eco-Leaf score (0–100)
                </div>

                <div class="eco-leaf-bar-wrap">

                    <div
                        class="eco-leaf-bar"
                        style="width: <?= min(100, $averageEcoLeafScore) ?>%">

                    </div>

                </div>

            </div>

        </div>

        <!-- Top Eco Trips -->

        <?php if (!empty($topEcoTrips)): ?>

            <div
                class="form-card"
                style="margin-bottom:1.5rem">

                <h3>
                    🏆 Top Eco-Friendly Trips
                </h3>

                <table class="carbon-table">

                    <thead>

                        <tr>

                            <th>Trip</th>

                            <th>Location</th>

                            <th>Eco Score</th>

                            <th>Impact Tags</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($topEcoTrips as $ecoTrip): ?>

                            <tr>

                                <td>

                                    <a
                                        href="index.php?action=trip_detail&id=<?= (int) ($ecoTrip["id"] ?? 0) ?>">

                                        <?= htmlspecialchars($ecoTrip["title"] ?? 'Untitled Trip') ?>

                                    </a>

                                </td>

                                <td>
                                    <?= htmlspecialchars($ecoTrip["location"] ?? 'Unknown') ?>
                                </td>

                                <td>

                                    <strong>
                                        <?= number_format((float) ($ecoTrip["sustainability_score"] ?? 0), 1) ?>
                                    </strong>

                                    / 100

                                    <div
                                        class="eco-leaf-bar-wrap"
                                        style="margin-top:.3rem">

                                        <div
                                            class="eco-leaf-bar"
                                            style="width: <?= min(100, (float) ($ecoTrip["sustainability_score"] ?? 0)) ?>%">

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <?php
                                    $tripTags = json_decode($ecoTrip["tags"] ?? "[]", true);

                                    foreach ((array) $tripTags as $tripTag):
                                    ?>

                                        <span class="tag-pill">
                                            <?= htmlspecialchars($tripTag) ?>
                                        </span>

                                    <?php endforeach; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

        <!-- Carbon Projects -->

        <?php if (!empty($carbonProjects)): ?>

            <div class="form-card">

                <h3>
                    🌱 Carbon Offset Projects
                </h3>

                <table class="carbon-table">

                    <thead>

                        <tr>

                            <th>Project</th>

                            <th>Location</th>

                            <th>Cost per kg CO₂</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($carbonProjects as $project): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($project["name"] ?? 'Unnamed Project') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($project["location"] ?? 'Unknown') ?>
                                </td>

                                <td>
                                    $<?= number_format((float) ($project["cost_per_kg"] ?? 0), 3) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </main>

</body>

</html>