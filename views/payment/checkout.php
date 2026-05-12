<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout — Eco Tourism</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/css/payment.css">
</head>
<body>
    <?php require_once __DIR__ . "/../partials/nav.php"; ?>

    <main class="page-content checkout-container">
        <div class="checkout-header">
            <h2>Complete Your Booking</h2>
            <p>Secure payment for your upcoming eco-adventure</p>
        </div>

        <div class="checkout-layout">
            <!-- Payment Form -->
            <div class="checkout-form-section">
                <form action="index.php?action=process_checkout" method="POST" class="payment-form" id="checkout-form">
                    <input type="hidden" name="trip_id" value="<?= htmlspecialchars($trip->id ?? '') ?>">
                    <input type="hidden" name="booking_date" value="<?= htmlspecialchars($bookingDate ?? '') ?>">
                    <input type="hidden" name="amount" id="hidden_amount" value="<?= htmlspecialchars($totalPrice ?? 300) ?>">
                    <input type="hidden" name="currency" id="hidden_currency" value="USD">
                    <input type="hidden" name="num_people" id="hidden_num_people" value="<?= htmlspecialchars($numPeople ?? 1) ?>">
                    
                    <h3 class="form-title" style="margin-top: 1.5rem;">
                        <svg class="secure-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                        Optional Extras
                    </h3>
                    <div class="extras-section" style="display: flex; flex-direction: column; gap: 0.8rem; margin-bottom: 2rem;">
                        <?php if (!empty($extraServices)): ?>
                            <?php foreach ($extraServices as $extra): ?>
                                <label class="extra-item" style="display: flex; align-items: center; background: #f8fafc; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                                    <input type="checkbox" name="extras[]" value="<?= $extra->id ?>" class="extra-checkbox" data-name="<?= htmlspecialchars($extra->name) ?>" data-price="<?= $extra->price ?>" style="margin-right: 1rem; width: 1.2rem; height: 1.2rem; accent-color: #2d6a4f;">
                                    <div style="flex-grow: 1;">
                                        <div style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($extra->name) ?></div>
                                        <div style="font-size: 0.85rem; color: #64748b;"><?= htmlspecialchars($extra->description) ?></div>
                                    </div>
                                    <div class="dynamic-price" data-base="<?= $extra->price ?>" style="font-weight: 600; color: #0f172a;">$<?= number_format($extra->price, 2) ?></div>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: #64748b; font-size: 0.9rem;">No optional extras available for this trip.</p>
                        <?php endif; ?>
                    </div>

                    <h3 class="form-title">
                        <svg class="secure-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Pay Securely with Credit Card (Visa)
                    </h3>
                    
                    <div class="form-group">
                        <label for="card_name">Name on Card</label>
                        <input type="text" id="card_name" name="card_name" placeholder="John Doe" required>
                    </div>

                    <div class="form-group">
                        <label for="card_number">Card Number</label>
                        <div class="input-with-icon">
                            <input type="text" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" required>
                            <svg class="visa-icon" viewBox="0 0 36 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="36" height="24" rx="4" fill="#1434CB"/>
                                <path d="M14.9962 17.5147H12.3023L13.9922 6.55078H16.6861L14.9962 17.5147ZM25.5684 6.84078C24.9654 6.61178 24.1673 6.40278 23.1873 6.40278C20.6123 6.40278 18.7903 7.78178 18.7753 9.77178C18.7593 11.242 20.0833 12.062 21.0633 12.542C22.0723 13.032 22.4113 13.342 22.4113 13.782C22.4113 14.442 21.6133 14.732 20.8903 14.732C19.7003 14.732 18.7903 14.412 18.0193 14.042L17.5933 13.842L17.2083 16.272C17.9654 16.622 19.1413 16.922 20.3703 16.942C23.1113 16.942 24.8974 15.582 24.9124 13.482C24.9274 11.452 23.3644 11.192 21.1394 10.152C20.2523 9.72178 19.7183 9.42178 19.7183 8.84178C19.7183 8.23178 20.4073 7.64178 21.4644 7.64178C22.4113 7.62178 23.1364 7.84178 23.7044 8.08178L24.0154 8.22178L25.5684 6.84078ZM32.7485 17.5147H35.1555L32.8595 6.55078H30.6865C29.9885 6.55078 29.4315 6.94078 29.1365 7.60078L24.9274 17.5147H27.6534L28.1965 15.9847H31.5035L31.8155 17.5147H32.7485ZM28.9815 13.8647L30.3425 10.0418L31.1395 13.8647H28.9815ZM11.1932 6.55078H7.99423C7.39123 6.55078 6.90323 6.86078 6.64323 7.42078L0.000228882 17.5147H2.76623L4.13723 13.7347H9.55323L10.4572 17.5147H13.0112L11.1932 6.55078ZM4.89623 11.6618L7.13523 5.48078H7.31123L8.85723 11.6618H4.89623Z" fill="white"/>
                            </svg>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry">Expiry Date</label>
                            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" maxlength="5" required>
                        </div>
                        <div class="form-group">
                            <label for="cvc">CVC</label>
                            <input type="password" id="cvc" name="cvc" placeholder="123" maxlength="4" required>
                        </div>
                    </div>

                    <div class="checkout-actions">
                        <a href="index.php?action=trip_detail&id=<?= htmlspecialchars($trip->id ?? '') ?>" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-pay">
                            <svg class="lock-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 11H5C3.89543 11 3 11.8954 3 13V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V13C21 11.8954 20.1046 11 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 11V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Pay Now (<span class="dynamic-price total-amount" data-base="<?= htmlspecialchars($totalPrice ?? 300) ?>">$<?= number_format($totalPrice ?? 300, 2) ?></span>)
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="checkout-summary-section">
                <div class="summary-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #f0f4f0; padding-bottom: 0.75rem;">
                        <h3 style="margin-bottom: 0; border: none; padding: 0;">Order Summary</h3>
                        <div class="currency-selector">
                            <select id="currency" style="padding: 0.3rem 0.5rem; border-radius: 6px; border: 1px solid #ccc; font-size: 0.85rem; color: #444; background: #fff; cursor: pointer; outline: none;">
                                <option value="USD" selected>USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="GBP">GBP (£)</option>
                                <option value="EGP">EGP (E£)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="summary-item trip-details">
                        <div class="trip-name"><?= htmlspecialchars($trip->title ?? 'Amazon Rainforest Expedition') ?></div>
                        <div class="trip-location">📍 <?= htmlspecialchars($trip->location ?? 'Amazon, Brazil') ?></div>
                    </div>

                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Date</span>
                            <span><?= htmlspecialchars($bookingDate ?? date('M d, Y')) ?></span>
                        </div>
                        <div class="summary-row" style="align-items: center;">
                            <label for="num_people" style="color: #555;">Travelers</label>
                            <input type="number" id="num_people" min="1" max="50" value="<?= htmlspecialchars($numPeople ?? 1) ?>" style="width: 70px; padding: 0.35rem; border: 1px solid #ccc; border-radius: 6px; text-align: center;">
                        </div>
                        <div class="summary-row" id="discount-row" style="display: none; color: #1b4332; font-weight: 500; align-items: center;">
                            <span>Discount Applied</span>
                            <span id="discount-badge" style="background: #e8f5e9; color: #2d6a4f; padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">-20%</span>
                        </div>
                        <div class="summary-row">
                            <span>Price per person</span>
                            <span class="dynamic-price" data-base="<?= htmlspecialchars($trip->price ?? 150) ?>">$<?= number_format($trip->price ?? 150, 2) ?></span>
                        </div>
                        
                        <!-- Dynamic Extras Container -->
                        <div id="summary-extras-container" style="border-top: 1px dashed #cbd5e1; margin-top: 0.75rem; padding-top: 0.75rem; display: none;">
                            <div style="font-size: 0.8rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase;">Add-ons</div>
                            <div id="summary-extras-list"></div>
                        </div>
                    </div>

                    <div class="summary-total">
                        <span>Total Price</span>
                        <span class="total-amount dynamic-price" data-base="<?= htmlspecialchars($totalPrice ?? 300) ?>">$<?= number_format($totalPrice ?? 300, 2) ?></span>
                    </div>
                    
                    <div class="eco-badge">
                        🌱 This booking contributes to local conservation efforts.
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Format Card Number (adds spaces every 4 digits)
        const cardNumberInput = document.getElementById('card_number');
        cardNumberInput.addEventListener('input', function (e) {
            let target = e.target;
            let val = target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formatted = '';
            for (let i = 0; i < val.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += ' ';
                }
                formatted += val[i];
            }
            target.value = formatted;
        });

        // Format Expiry Date (MM/YY)
        const expiryInput = document.getElementById('expiry');
        expiryInput.addEventListener('input', function (e) {
            let target = e.target;
            let val = target.value.replace(/[^0-9]/gi, '');
            if (val.length > 2) {
                target.value = val.substring(0, 2) + '/' + val.substring(2, 4);
            } else {
                target.value = val;
            }
        });

        // Restrict CVC to numbers
        const cvcInput = document.getElementById('cvc');
        cvcInput.addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^0-9]/gi, '');
        });

        // Multi-currency calculation
        const currencySelect = document.getElementById('currency');
        const dynamicPrices = document.querySelectorAll('.dynamic-price');
        
        // Mock exchange rates with USD as base
        const exchangeRates = {
            USD: { rate: 1, symbol: '$' },
            EUR: { rate: 0.85, symbol: '€' },
            GBP: { rate: 0.74, symbol: '£' },
            EGP: { rate: 52.72, symbol: 'E£' }
        };

        // Base trip price from PHP
        const basePricePerPerson = parseFloat(<?= json_encode($trip->price ?? 150) ?>);
        const numPeopleInput = document.getElementById('num_people');
        const hiddenNumPeople = document.getElementById('hidden_num_people');
        const discountRow = document.getElementById('discount-row');
        const discountBadge = document.getElementById('discount-badge');
        const totalAmountSpans = document.querySelectorAll('.total-amount');

        const extraCheckboxes = document.querySelectorAll('.extra-checkbox');
        const summaryExtrasContainer = document.getElementById('summary-extras-container');
        const summaryExtrasList = document.getElementById('summary-extras-list');

        function updatePricing() {
            let numPeople = parseInt(numPeopleInput.value);
            if (isNaN(numPeople) || numPeople < 1) numPeople = 1;
            
            if (hiddenNumPeople) hiddenNumPeople.value = numPeople;

            let discountPercent = 0;
            if (numPeople >= 10) discountPercent = 40;
            else if (numPeople >= 5) discountPercent = 30;
            else if (numPeople >= 3) discountPercent = 20;

            let subtotal = basePricePerPerson * numPeople;
            let discountAmount = subtotal * (discountPercent / 100);
            let finalBasePrice = subtotal - discountAmount;

            if (discountPercent > 0) {
                discountRow.style.display = 'flex';
                discountBadge.textContent = '-' + discountPercent + '% (' + exchangeRates[currencySelect.value].symbol + (discountAmount * exchangeRates[currencySelect.value].rate).toFixed(2) + ')';
            } else {
                discountRow.style.display = 'none';
            }

            // Calculate Extras
            let extrasTotal = 0;
            let extrasHtml = '';
            
            extraCheckboxes.forEach(cb => {
                if (cb.checked) {
                    const price = parseFloat(cb.getAttribute('data-price'));
                    extrasTotal += price;
                    
                    const config = exchangeRates[currencySelect.value];
                    const convertedPrice = (price * config.rate).toFixed(2);
                    
                    extrasHtml += `
                        <div class="summary-row" style="font-size: 0.9rem;">
                            <span>${cb.getAttribute('data-name')}</span>
                            <span>${config.symbol}${convertedPrice}</span>
                        </div>
                    `;
                }
            });
            
            if (extrasTotal > 0) {
                summaryExtrasContainer.style.display = 'block';
                summaryExtrasList.innerHTML = extrasHtml;
            } else {
                summaryExtrasContainer.style.display = 'none';
                summaryExtrasList.innerHTML = '';
            }

            // Grand Total
            let grandTotal = finalBasePrice + extrasTotal;

            // Update the data-base attributes for the total amounts
            totalAmountSpans.forEach(el => {
                el.setAttribute('data-base', grandTotal);
            });

            // Trigger currency conversion to update UI
            currencySelect.dispatchEvent(new Event('change'));
        }

        extraCheckboxes.forEach(cb => {
            cb.addEventListener('change', updatePricing);
        });

        numPeopleInput.addEventListener('input', updatePricing);

        currencySelect.addEventListener('change', function() {
            const currency = this.value;
            const config = exchangeRates[currency];
            
            dynamicPrices.forEach(el => {
                const basePrice = parseFloat(el.getAttribute('data-base'));
                if (!isNaN(basePrice)) {
                    // Calculate and format to 2 decimal places
                    const converted = (basePrice * config.rate).toFixed(2);
                    // Add comma separators for thousands
                    const formatted = converted.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    el.textContent = config.symbol + formatted;
                    
                    if (el.classList.contains('total-amount')) {
                        document.getElementById('hidden_amount').value = converted;
                    }
                }
            });
            document.getElementById('hidden_currency').value = currency;
            
            // Re-run pricing to update the discount badge currency
            let numPeople = parseInt(numPeopleInput.value) || 1;
            let discountPercent = 0;
            if (numPeople >= 10) discountPercent = 40;
            else if (numPeople >= 5) discountPercent = 30;
            else if (numPeople >= 3) discountPercent = 20;
            if (discountPercent > 0) {
                let discountAmount = (basePricePerPerson * numPeople) * (discountPercent / 100);
                discountBadge.textContent = '-' + discountPercent + '% (' + config.symbol + (discountAmount * config.rate).toFixed(2) + ')';
            }
        });
        
        // Initialize
        updatePricing();
    </script>
</body>
</html>
