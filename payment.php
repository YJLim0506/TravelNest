<?php
// Handle payment form submission (only if logged in)
require_once 'config.php';
$success = '';
$error = '';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    try {
        $pdo = get_db_connection();

        // Keep only relevant fields
        $userId = $_SESSION['user_id'];
        $packageName = $_POST['package_name'];
        $departureDate = !empty($_POST['departure_date']) ? $_POST['departure_date'] : null;
        $totalPrice = floatval($_POST['total_price']);
        $paymentMethod = $_POST['payment_method'];
        $specialRequests = !empty($_POST['special_requests']) ? $_POST['special_requests'] : null;

        // Insert into simplified travel_payments table
        $stmt = $pdo->prepare("
            INSERT INTO travel_payments 
                (user_id, package_name, departure_date, total_price, payment_method, payment_status, special_requests)
            VALUES 
                (:user_id, :package_name, :departure_date, :total_price, :payment_method, 'completed', :special_requests)
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':package_name' => $packageName,
            ':departure_date' => $departureDate,
            ':total_price' => $totalPrice,
            ':payment_method' => $paymentMethod,
            ':special_requests' => $specialRequests
        ]);

        // Redirect to home with success flag
        header("Location: index.php?payment=success");
        exit;

    } catch (Exception $e) {
        // On error, redirect with failure flag
        header("Location: index.php?payment=failed");
        exit;
    }
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | TravelNest</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Payment page specific styles */
        body {
            background: #f8f9fa;
        }

        .payment-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .payment-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }

        .booking-summary {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .payment-form {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .booking-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }

        .booking-detail:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: #555;
        }

        .detail-value {
            font-weight: 600;
            color: #333;
        }

        .total-amount {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .total-amount .detail-value {
            font-size: 1.25rem;
            color: #007bff;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .payment-method {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-method:hover {
            border-color: #007bff;
        }

        .payment-method.selected {
            border-color: #007bff;
            background: #f8f9ff;
        }

        .payment-method img {
            width: 40px;
            height: 40px;
            margin-bottom: 0.5rem;
        }

        .payment-method span {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #333;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin: 1.5rem 0;
        }

        .terms-checkbox input[type="checkbox"] {
            width: auto;
            margin-top: 0.25rem;
        }

        .terms-checkbox label {
            font-size: 14px;
            color: #555;
            cursor: pointer;
            line-height: 1.4;
        }
        
        .terms-checkbox label a {
            position: relative;
            top: 2px;
            color: #007bff;
            text-decoration: none;
            display: inline-block;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        .btn-pay {
            width: 100%;
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,123,255,0.3);
        }

        .btn-pay:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .back-to-package {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .back-to-package:hover {
            text-decoration: underline;
        }

        .message {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 2rem;
        }

        .message-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Login required alert styles */
        .login-required-alert {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: center;
        }

        .login-required-alert h2 {
            margin-bottom: 1rem;
            color: #721c24;
        }

        .login-required-alert p {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
            transform: translateY(-2px);
        }

        /* Modal styles remain the same */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            overflow-y: auto;
        }

        .modal {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            animation: modalAppear 0.3s ease;
            position: relative;
            margin: 2rem 0;
        }

        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-wrapper {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
            }

            .auth-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'Include/header.php'; ?>
    <div class="payment-container">
        <a href="javascript:history.back()" class="back-to-package" style="margin-top:100px;">
            <i class="fas fa-arrow-left"></i> Back to Package
        </a>

        <h1 style="text-align: center; color: #333; margin-bottom: 2rem;">Complete Your Booking</h1>

        <?php if ($success): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('success-modal').style.display = 'flex';
                });
            </script>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message message-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- Not logged in - Show login required alert -->
            <div class="login-required-alert">
                <h2><i class="fas fa-lock"></i> Login Required</h2>
                <p>You must be logged in to complete your booking. Please log in to your account or create a new one to continue with your travel package purchase.</p>
                <div class="auth-buttons">
                    <a href="Home\login.php?>" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login to Continue
                    </a>
                    <a href="Home/register.php?>" class="btn btn-secondary">
                        <i class="fas fa-user-plus"></i> Create New Account
                    </a>
                </div>
                <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                    Don't worry, your package selection will be saved when you return!
                </p>
            </div>

        <?php else: ?>
            <!-- Logged in - Show payment form -->
            <div class="payment-wrapper">
                <div class="booking-summary">
                    <h2 class="section-title">Booking Summary</h2>
                    <div id="booking-details">
                        <div class="booking-detail">
                            <span class="detail-label">Package:</span>
                            <span class="detail-value" id="package-name">Loading...</span>
                        </div>
                        <div class="booking-detail">
                            <span class="detail-label">Departure Date:</span>
                            <span class="detail-value" id="departure-date">Loading...</span>
                        </div>
                        <div class="booking-detail">
                            <span class="detail-label">Room Type:</span>
                            <span class="detail-value" id="room-type">Loading...</span>
                        </div>
                        <div class="booking-detail">
                            <span class="detail-label">Adults:</span>
                            <span class="detail-value" id="adults-count">Loading...</span>
                        </div>
                        <div class="booking-detail">
                            <span class="detail-label">Children:</span>
                            <span class="detail-value" id="children-count">Loading...</span>
                        </div>
                        <div class="booking-detail">
                            <span class="detail-label">Special Requests:</span>
                            <span class="detail-value" id="special-requests">Loading...</span>
                        </div>
                    </div>
                    
                    <div class="total-amount">
                        <div class="booking-detail">
                            <span class="detail-label">Total Amount:</span>
                            <span class="detail-value" id="total-price">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="payment-form">
                    <h2 class="section-title">Payment Details</h2>
                    
                    <form id="payment-form" method="POST">
                        <!-- Hidden fields for booking data -->
                        <input type="hidden" id="package_name" name="package_name">
                        <input type="hidden" id="package_file" name="package_file">
                        <input type="hidden" id="departure_date" name="departure_date">
                        <input type="hidden" id="room_type" name="room_type">
                        <input type="hidden" id="room_price" name="room_price">
                        <input type="hidden" id="adults" name="adults">
                        <input type="hidden" id="children" name="children">
                        <input type="hidden" id="base_price" name="base_price">
                        <input type="hidden" id="total_price_input" name="total_price">
                        <input type="hidden" id="special_requests_input" name="special_requests">
                        
                        <div class="form-group">
                            <label>Select Payment Method</label>
                            <div class="payment-methods">
                                <div class="payment-method" data-method="tng">
                                    <i class="fas fa-mobile-alt" style="font-size: 40px; color: #00b4d8; margin-bottom: 0.5rem;"></i>
                                    <span>Touch 'n Go</span>
                                </div>
                                <div class="payment-method" data-method="visa">
                                    <i class="fab fa-cc-visa" style="font-size: 40px; color: #1a1f71; margin-bottom: 0.5rem;"></i>
                                    <span>Visa</span>
                                </div>
                                <div class="payment-method" data-method="mastercard">
                                    <i class="fab fa-cc-mastercard" style="font-size: 40px; color: #eb001b; margin-bottom: 0.5rem;"></i>
                                    <span>Mastercard</span>
                                </div>
                                <div class="payment-method" data-method="amex">
                                    <i class="fab fa-cc-amex" style="font-size: 40px; color: #006fcf; margin-bottom: 0.5rem;"></i>
                                    <span>Amex</span>
                                </div>
                            </div>
                            <input type="hidden" id="payment_method" name="payment_method" required>
                        </div>

                        <div id="card-details" style="display: none;">
                            <div class="form-group">
                                <label>Cardholder Name</label>
                                <input type="text" id="cardholder-name" name="cardholder_name" placeholder="Full name as shown on card">
                            </div>
                            <div class="form-group">
                                <label>Card Number</label>
                                <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Expiry Date</label>
                                    <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label>CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4">
                                </div>
                            </div>
                        </div>

                        <div id="tng-details" style="display: none;">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" id="tng-phone" name="tng_phone" placeholder="+60 12-345 6789">
                            </div>
                        </div>

                        <h3 style="margin: 2rem 0 1rem 0; color: #333;">Billing Information</h3>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" id="email" name="billing_email" placeholder="your@email.com" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" id="first-name" name="billing_first_name" placeholder="John" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" id="last-name" name="billing_last_name" placeholder="Doe" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" id="phone" name="billing_phone" placeholder="+60 12-345 6789" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" id="address" name="billing_address" placeholder="123 Main Street" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" id="city" name="billing_city" placeholder="Kuala Lumpur" required>
                            </div>
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" id="postal-code" name="billing_postal_code" placeholder="50000" required>
                            </div>
                        </div>

                        <div class="terms-checkbox">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">
                                I agree to the <a href="#" id="terms-link">Terms and Conditions</a> and 
                                <a href="#" id="privacy-link">Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" class="btn-pay" id="pay-button" disabled>
                            <i class="fas fa-credit-card"></i> Complete Payment
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal-overlay" id="success-modal">
        <div class="modal">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Payment Successful!</h2>
            <p>Thank you for your booking. Your payment has been processed successfully. You will receive a confirmation email shortly with your booking details and travel information.</p>
            <a href="index.php" class="btn">Return to Home</a>
        </div>
    </div>

    <script>
        // Only run payment-related scripts if user is logged in
        <?php if (isset($_SESSION['user_id'])): ?>
        
        // Load booking data from sessionStorage
        function loadBookingData() {
            const bookingData = sessionStorage.getItem('bookingData');
            
            if (!bookingData) {
                alert('No booking data found. Please go back and select your package.');
                window.location.href = 'index.php';
                return;
            }

            const data = JSON.parse(bookingData);
            
            // Populate display fields
            document.getElementById('package-name').textContent = data.packageName;
            document.getElementById('departure-date').textContent = new Date(data.departureDate).toLocaleDateString();
            document.getElementById('adults-count').textContent = data.adults;
            document.getElementById('children-count').textContent = data.children;
            document.getElementById('special-requests').textContent = data.specialRequests || 'None';
            document.getElementById('total-price').textContent = data.totalPrice;
            document.getElementById('room-type').textContent = data.roomTypeDisplay || data.roomType;
            
            // Populate hidden form fields
            document.getElementById('package_name').value = data.packageName;
            document.getElementById('package_file').value = data.packageFile || '';
            document.getElementById('departure_date').value = data.departureDate;
            document.getElementById('room_type').value = data.roomType;
            document.getElementById('room_price').value = data.roomPrice || 0;
            document.getElementById('adults').value = data.adults;
            document.getElementById('children').value = data.children;
            document.getElementById('base_price').value = data.basePrice || 0;
            document.getElementById('total_price_input').value = data.totalPriceNumeric || data.totalPrice.replace(/[^\d.]/g, '');
            document.getElementById('special_requests_input').value = data.specialRequests || '';
        }

        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', () => {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                method.classList.add('selected');
                
                const selectedMethod = method.getAttribute('data-method');
                document.getElementById('payment_method').value = selectedMethod;
                
                const cardDetails = document.getElementById('card-details');
                const tngDetails = document.getElementById('tng-details');
                
                if (selectedMethod === 'tng') {
                    cardDetails.style.display = 'none';
                    tngDetails.style.display = 'block';
                } else {
                    cardDetails.style.display = 'block';
                    tngDetails.style.display = 'none';
                }
                
                validateForm();
            });
        });

        // Form validation
        function validateForm() {
            const selectedPaymentMethod = document.querySelector('.payment-method.selected');
            const termsAccepted = document.getElementById('terms').checked;
            const email = document.getElementById('email').value;
            const firstName = document.getElementById('first-name').value;
            const lastName = document.getElementById('last-name').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;
            const city = document.getElementById('city').value;
            const postalCode = document.getElementById('postal-code').value;
            
            let isValid = selectedPaymentMethod && termsAccepted && email && firstName && lastName && phone && address && city && postalCode;
            
            if (selectedPaymentMethod) {
                const method = selectedPaymentMethod.getAttribute('data-method');
                if (method === 'tng') {
                    const tngPhone = document.getElementById('tng-phone').value;
                    isValid = isValid && tngPhone;
                } else {
                    const cardholderName = document.getElementById('cardholder-name').value;
                    const cardNumber = document.getElementById('card-number').value;
                    const expiryDate = document.getElementById('expiry-date').value;
                    const cvv = document.getElementById('cvv').value;
                    isValid = isValid && cardholderName && cardNumber && expiryDate && cvv;
                }
            }
            
            document.getElementById('pay-button').disabled = !isValid;
        }

        // Add event listeners for form validation
        document.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('input', validateForm);
            input.addEventListener('change', validateForm);
        });

        // Card number formatting
        document.getElementById('card-number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            if (formattedValue.length > 19) formattedValue = formattedValue.substring(0, 19);
            e.target.value = formattedValue;
        });

        // Expiry date formatting
        document.getElementById('expiry-date').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        // CVV validation
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/gi, '');
        });

        // Initialize page
        loadBookingData();
        
        <?php endif; ?>
    </script>

    <?php include 'Include/footer.php'; ?>
</body>
</html>