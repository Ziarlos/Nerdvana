<?php declare(strict_types=1);

require_once '../config/config.php';

ob_start();
session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
case 'login':
    if (isset($_POST['login-email'])) {
        $email = filter_input(INPUT_POST, 'login-email', FILTER_SANITIZE_EMAIL);
    }
    if (isset($_POST['login-password'])) {
        $password = filter_input(INPUT_POST, 'login-password', FILTER_SANITIZE_STRING);
    }
    $error = false;

    $pass = HASH("SHA512", $password);

    $user = $Database->query("SELECT user_id, user_name, email, password, gender FROM users WHERE email = :email LIMIT 1", array(':email' => $email));
    $user_exists = $Database->count();

    if ($user_exists == 1) {
        if ($user['password'] === $pass) {
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['login_status'] = true;
            require_once '../config/config.php';
            header('location: private_profile.php');
        } else {
            include_once '../includes/public_header.php';
            echo '<p>That email/password combination does not exist!</p>';
            include_once '../includes/public_footer.php';
        }
    } else {
        include_once '../includes/public_header.php';
        echo '<p>That email/password combination does not exist!</p>';
        include_once '../includes/public_footer.php';
    }
    break;

case 'logout':
    $_SESSION = array();
    session_destroy();
    unset($_SESSION);
    header('location: index.php');
    break;

default:
}

ob_end_flush();
