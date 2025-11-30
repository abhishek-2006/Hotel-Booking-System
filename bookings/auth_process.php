<?php
session_start();
$PROJECT_ROOT = '/Hotel%20Management%20system'; 
include('../includes/config.php'); 
error_reporting(E_ALL);

// Ensure a POST request was made and the action is defined
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    $_SESSION['error_message'] = "Invalid request method.";
    header("Location: {$PROJECT_ROOT}/index.php");
    exit;
}

$action = $_POST['action'];

// --- Helper Function for Auditing (activity_logs table) ---
function log_activity($conn, $user_id, $action_description) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $action_description, $ip_address);
    $stmt->execute();
    $stmt->close();
}

// --- 1. REGISTRATION LOGIC ---
if ($action === 'register') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($full_name) || empty($email) || empty($phone) || empty($password)) {
        $_SESSION['error_message'] = "All fields are required for registration.";
        header("Location: {$PROJECT_ROOT}/auth/register.php");
        exit;
    }

    // AUTH-R2: Check for Email Uniqueness
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "This email address is already registered.";
        $stmt->close();
        header("Location: {$PROJECT_ROOT}/auth/register.php");
        exit;
    }
    $stmt->close();

    $role = 'user';

    // AUTH-R4: Database Insertion
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $email, $phone, $password, $role);

    if ($stmt->execute()) {
        $new_user_id = $stmt->insert_id;
        log_activity($conn, $new_user_id, "User registered successfully.");
        $_SESSION['success_message'] = "Registration successful! Please log in.";
        
        // AUTH-R5: Redirect to Login
        header("Location: {$PROJECT_ROOT}/auth/login.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Registration failed due to a server error.";
        error_log("Registration DB Error: " . $stmt->error);
        header("Location: {$PROJECT_ROOT}/auth/register.php");
        exit;
    }
    $stmt->close();
}

// --- 2. LOGIN LOGIC ---
elseif ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Email and password are required.";
        header("Location: {$PROJECT_ROOT}/auth/login.php");
        exit;
    }

    // AUTH-L1: Retrieve User Record
    $stmt = $conn->prepare("SELECT user_id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // AUTH-L2: Verify Password
        if ($password === $user['password']) {
            // AUTH-L3: Session Creation
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            // AUTH-L4: Logging
            log_activity($conn, $user['user_id'], "User logged in successfully.");

            // AUTH-L4: Redirection based on role
            if ($user['role'] === 'admin') {
                header("Location: {$PROJECT_ROOT}/admin/dashboard.php");
                exit;
            } else {
                // Default to user dashboard
                header("Location: {$PROJECT_ROOT}/user/dashboard.php");
                exit;
            }
        }
    }

    // AUTH-L5: Failure Response (User not found or password mismatch)
    $_SESSION['error_message'] = "Invalid credentials. Please try again.";
    header("Location: {$PROJECT_ROOT}/auth/login.php");
    exit;
}

// --- 3. LOGOUT LOGIC (Often handled by a separate logout.php, but defined here for completeness) ---
elseif ($action === 'logout') {
    // Logging the logout action (user ID is needed, if available)
    $user_id = $_SESSION['user_id'] ?? null;
    if ($user_id) {
        log_activity($conn, $user_id, "User logged out.");
    }

    // Terminate session
    session_unset();
    session_destroy();
    
    // Redirect to homepage
    header("Location: {$PROJECT_ROOT}/index.php");
    exit;
}
else {
    // If action is unrecognized
    $_SESSION['error_message'] = "Unrecognized form action.";
    header("Location: {$PROJECT_ROOT}/index.php");
    exit;
}
?>