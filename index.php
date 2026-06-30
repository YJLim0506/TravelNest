<?php
// Main index.php file that includes all sections
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelNest - Embark in Comfort.</title>
    <link rel="stylesheet" href="/Assignment/styles/styles.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php
        include 'Include/header.php'; 
    ?>
    <?php if (isset($_GET['feedback']) && $_GET['feedback'] === 'sent'): ?>
    <div class="alert alert-success text-center alert-banner">Thanks! Your feedback was sent.</div>
    <?php elseif (isset($_GET['feedback']) && $_GET['feedback'] === 'failed'): ?>
    <div class="alert alert-error text-center alert-banner">Couldn’t send feedback. Please try again.</div>
    <?php endif; ?>

    <?php if (isset($_GET['payment']) && $_GET['payment'] === 'success'): ?>
    <div class="alert alert-success text-center alert-banner">
        Thank you! Your payment was successful.
    </div>
    <?php elseif (isset($_GET['payment']) && $_GET['payment'] === 'failed'): ?>
    <div class="alert alert-error text-center alert-banner">
        Oops! Something went wrong with your payment.
    </div>
    <?php endif; ?>

    <!-- Success message for login/logout -->
    <?php if (isset($_SESSION['login_success'])): ?>
        <div class="message message-success" style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 2000; max-width: 500px;">
            <?php echo htmlspecialchars($_SESSION['login_success']); unset($_SESSION['login_success']); ?>
        </div>
        <script>
            // Auto-hide success message after 4 seconds
            setTimeout(function() {
                const successMsg = document.querySelector('.message-success');
                if (successMsg) {
                    successMsg.style.opacity = '0';
                    successMsg.style.transform = 'translate(-50%, -20px)';
                    setTimeout(() => successMsg.remove(), 300);
                }
            }, 4000);
        </script>
    <?php endif; ?>

    <?php if (isset($_SESSION['logout_success'])): ?>
        <div class="message message-success" style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 2000; max-width: 500px;">
            <?php echo htmlspecialchars($_SESSION['logout_success']); unset($_SESSION['logout_success']); ?>
        </div>
        <script>
            // Auto-hide logout success message after 4 seconds
            setTimeout(function() {
                const successMsg = document.querySelector('.message-success');
                if (successMsg) {
                    successMsg.style.opacity = '0';
                    successMsg.style.transform = 'translate(-50%, -20px)';
                    setTimeout(() => successMsg.remove(), 300);
                }
            }, 4000);
        </script>
    <?php endif; ?>
 

    <!-- Include Hero Section -->
    <?php include 'Home/hero.php'; ?>

    <!-- Include Packages Section -->
    <?php include 'Home/packages.php'; ?>

    <!-- Include Destinations Section -->
    <?php include 'Home/destinations.php'; ?>

    <!-- Include Services Section -->
    <?php include 'Home/services.php'; ?>

    <!-- Include Contact Section -->
    <?php include 'Home/contact.php'; ?>

    <!-- Include Footer -->
    <?php include 'Include/footer.php'; ?>

    <script src="script.js"></script>
    
    <script>
        // Mobile hamburger menu functionality
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');

        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });

        // Close menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(n => n.addEventListener('click', () => {
            navMenu.classList.remove('active');
        }));
    </script>
</body>
</html>