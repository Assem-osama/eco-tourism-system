<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Bookings — Eco Tourism</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Cancel Modal Overlay */
        .cancel-modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.2s ease;
        }
        .cancel-modal-overlay.active { display: flex; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .cancel-modal {
            background: #fff;
            border-radius: 12px;
            max-width: 360px;
            width: 85%;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .cancel-modal-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .cancel-modal-header h3 { margin: 0; font-size: 0.95rem; }
        .cancel-modal-header .icon { font-size: 1.1rem; }

        .cancel-modal-body { padding: 1rem; }

        .policy-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #888;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .policy-list {
            list-style: none;
            padding: 0;
            margin: 0 0 0.75rem 0;
        }
        .policy-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
            margin-bottom: 0.3rem;
            font-size: 0.78rem;
        }
        .policy-list li:nth-child(1) { background: #e8f5e9; color: #1b5e20; }
        .policy-list li:nth-child(2) { background: #fff3e0; color: #e65100; }
        .policy-list li:nth-child(3) { background: #ffebee; color: #b71c1c; }

        .policy-list li .label { font-weight: 500; }
        .policy-list li .value { font-weight: 700; font-size: 0.8rem; }

        .cancel-divider {
            border: none;
            border-top: 1px dashed #e0e0e0;
            margin: 0.75rem 0;
        }

        .your-booking-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #888;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .booking-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.3rem 0;
            font-size: 0.82rem;
            color: #444;
        }
        .booking-detail-row .detail-label { color: #777; }
        .booking-detail-row .detail-value { font-weight: 600; color: #222; }

        .refund-highlight {
            background: #f0f4f0;
            border-radius: 8px;
            padding: 0.55rem 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .refund-highlight .refund-label { font-weight: 600; color: #1b4332; font-size: 0.82rem; }
        .refund-highlight .refund-value { font-weight: 700; color: #2d6a4f; font-size: 1rem; }

        .cancel-modal-actions {
            display: flex;
            gap: 0.5rem;
            padding: 0.75rem 1rem 1rem;
        }
        .cancel-modal-actions .btn {
            flex: 1;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .cancel-modal-actions .btn-keep {
            background: #f0f4f0;
            color: #333;
        }
        .cancel-modal-actions .btn-keep:hover { background: #e0e4e0; }
        .cancel-modal-actions .btn-confirm-cancel {
            background: #dc3545;
            color: white;
        }
        .cancel-modal-actions .btn-confirm-cancel:hover { background: #b02a37; }
    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../partials/nav.php"; ?>

    <main class="page-content">
        <h2>My Bookings</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <?php if (empty($bookings)): ?>
            <p>You have no bookings yet. <a href="index.php?action=trips">Browse trips →</a></p>
        <?php else: ?>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Trip</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td>
                                <a href="index.php?action=trip_detail&id=<?= $booking->trip_id ?>">
                                    <?= htmlspecialchars($booking->trip_title) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($booking->trip_location) ?></td>
                            <td><?= htmlspecialchars($booking->booking_date) ?></td>
                            <td>$<?= number_format($booking->total_price, 2) ?></td>
                            <td><span class="badge badge-<?= htmlspecialchars($booking->status) ?>"><?= htmlspecialchars($booking->status) ?></span></td>
                            <td>
                                <?php if ($booking->status !== "cancelled"): ?>
                                    <?php 
                                        $feePercent = 100 - $booking->refund_percentage;
                                        $feeAmount = $booking->total_price - $booking->refund_amount;
                                    ?>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="openCancelModal(<?= $booking->id ?>, '<?= htmlspecialchars($booking->trip_title, ENT_QUOTES) ?>', <?= $booking->total_price ?>, <?= $booking->refund_percentage ?>, <?= $booking->refund_amount ?>, <?= $feePercent ?>, <?= $feeAmount ?>)">
                                        Cancel
                                    </button>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <!-- Styled Cancel Modal -->
    <div class="cancel-modal-overlay" id="cancelModal">
        <div class="cancel-modal">
            <div class="cancel-modal-header">
                <span class="icon">⚠️</span>
                <h3>Cancel Booking</h3>
            </div>
            <div class="cancel-modal-body">
                <div class="policy-title">Cancellation Policy</div>
                <ul class="policy-list">
                    <li>
                        <span class="label">7+ days before trip</span>
                        <span class="value">100% refund</span>
                    </li>
                    <li>
                        <span class="label">2–6 days before trip</span>
                        <span class="value">50% refund</span>
                    </li>
                    <li>
                        <span class="label">Less than 48 hours</span>
                        <span class="value">0% refund</span>
                    </li>
                </ul>

                <hr class="cancel-divider">

                <div class="your-booking-title">Your Booking Details</div>
                <div class="booking-detail-row">
                    <span class="detail-label">Trip</span>
                    <span class="detail-value" id="modalTripName"></span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">Total Paid</span>
                    <span class="detail-value" id="modalTotalPaid"></span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">Cancellation Fee</span>
                    <span class="detail-value" style="color: #dc3545;" id="modalFee"></span>
                </div>

                <div class="refund-highlight">
                    <span class="refund-label">Your Refund</span>
                    <span class="refund-value" id="modalRefund"></span>
                </div>
            </div>
            <div class="cancel-modal-actions">
                <button class="btn btn-keep" onclick="closeCancelModal()">Keep Booking</button>
                <form method="POST" action="index.php?action=booking_cancel" id="cancelForm" style="flex:1; display:flex;">
                    <input type="hidden" name="booking_id" id="modalBookingId">
                    <button type="submit" class="btn btn-confirm-cancel" style="width:100%;">Yes, Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCancelModal(bookingId, tripName, totalPaid, refundPct, refundAmt, feePct, feeAmt) {
            document.getElementById('modalBookingId').value = bookingId;
            document.getElementById('modalTripName').textContent = tripName;
            document.getElementById('modalTotalPaid').textContent = '$' + totalPaid.toFixed(2);
            document.getElementById('modalFee').textContent = feePct + '% ($' + feeAmt.toFixed(2) + ')';
            document.getElementById('modalRefund').textContent = refundPct + '% ($' + refundAmt.toFixed(2) + ')';
            document.getElementById('cancelModal').classList.add('active');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.remove('active');
        }

        // Close modal on overlay click
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) closeCancelModal();
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeCancelModal();
        });
    </script>
</body>

</html>