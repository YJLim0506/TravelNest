<?php
require_once '../config.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Use the function name from your config.php
            $pdo = get_db_connection();
            
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'Username already exists. Please choose a different username.';
            } else {
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'Email already exists. Please use a different email address.';
                } else {
                    // Hash password and insert user
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    
                    if ($stmt->execute([$username, $email, $hashedPassword])) {
                        // Get the new user's ID
                        $new_user_id = $pdo->lastInsertId();
                        
                        // Set session for the new user (auto-login after registration)
                        $_SESSION['user_id'] = $new_user_id;
                        $_SESSION['username'] = $username;
                        
                        // REDIRECT LOGIC - ADD THIS SECTION
                        $redirect_url = '../index.php'; // default redirect
                        
                        // Check for redirect parameter from URL
                        if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
                            $redirect_url = $_GET['redirect'];
                        } 
                        // Check for redirect stored in session
                        elseif (isset($_SESSION['redirect_after_login'])) {
                            $redirect_url = $_SESSION['redirect_after_login'];
                            unset($_SESSION['redirect_after_login']); // Clear it after use
                        }
                        
                        // Security check - only allow relative URLs within same domain
                        if (strpos($redirect_url, '/') === 0 && strpos($redirect_url, '//') !== 0) {
                            header('Location: ' . $redirect_url);
                        } else {
                            header('Location: ../index.php');
                        }
                        exit;
                        
                    } else {
                        $error = 'Registration failed. Please try again.';
                    }
                }
            }
        } catch (Exception $e) {
            $error = 'Database error. Please try again later.';
            // For debugging (remove in production)
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                $error .= ' Debug: ' . $e->getMessage();
            }
        }
    }
}
?>