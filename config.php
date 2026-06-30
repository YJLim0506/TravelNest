<?php
// Session Configuration (must be before session_start)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start session
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'wanderlust_travel');

// Site Configuration
define('SITE_NAME', 'Wanderlust Travel');
define('SITE_URL', 'http://localhost/Assignment');
define('SITE_EMAIL', 'info@wanderlust.com');
define('SITE_PHONE', '+1 (555) 123-4567');
define('SITE_ADDRESS', '123 Travel Street, Adventure City, AC 12345');

// Social Media Links
define('FACEBOOK_URL', '#');
define('TWITTER_URL', '#');
define('INSTAGRAM_URL', '#');
define('LINKEDIN_URL', '#');

// Contact Form Settings
define('CONTACT_EMAIL', 'info@wanderlust.com');
define('CONTACT_SUBJECT_PREFIX', 'Wanderlust Travel - ');

// Error Reporting (set to false in production)
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('UTC');

// Helper Functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function get_page_title($page_name = '') {
    if (empty($page_name)) {
        return SITE_NAME . ' - Your Journey Begins Here';
    }
    return $page_name . ' - ' . SITE_NAME;
}

function get_current_page() {
    return basename($_SERVER['PHP_SELF'], '.php');
}

function is_current_page($page_name) {
    return get_current_page() === $page_name;
}

// Database Connection Function (for future use)
function get_db_connection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            die("Connection failed: " . $e->getMessage());
        } else {
            die("Database connection failed. Please try again later.");
        }
    }
}
?>