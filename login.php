<?php declare(strict_types=1);

/**
 * Global variables and constants will be defined in this page
 * These variables and constants may be used in multiple pages.
 * Below we start a database connection.
 * Since PHP in moving to PDO and MySQLi, we no longer use MySQL.
 * PHP version 7+
 *
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */
ob_start();
session_start();
require_once 'site_configuration/site_info.php';

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
            include_once 'site_configuration/site_info.php';
            header('location: private_profile.php');
        } else {
            include_once 'includes/public_header.php';
            echo '<p>That email/password combination does not exist!</p>';
            include_once 'includes/public_footer.php';
        }
    } else {
        include_once 'includes/public_header.php';
        echo '<p>That email/password combination does not exist!</p>';
        include_once 'includes/public_footer.php';
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
?>