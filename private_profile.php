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

require_once 'includes/private_header.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
case 'view':
    $viewed = $User->getUserInfo($_GET['user_id']);
    ?>
<<<<<<< HEAD
=======
    <h2> Welcome to Nerdvana, <?php echo $user['user_name']; ?>!</h2>
>>>>>>> ea909f18347467b41b058e6a32fec95405e467dc
    <section class="user-profile-page">
        <dl class="profile_info">
            <dt class="profile_info_label">Username:</dt> <dd class="profile_info_description"> <?php echo $viewed['user_name']; ?></dd>
            <dt class="profile_info_label">Gender:</dt> <dd class="profile_info_description"> <?php echo $viewed['gender'] == "M" ? "Male" : "Female"; ?> </dd>
            <dt class="profile_info_label">Last Active:</dt> <dd class="profile_info_description"> <?php echo date("l F jS Y g:i:s A", strtotime($viewed['last_active'])); ?> </dd>
        </dl>
        <figure class="profile_avatar">
            <img src="/images/user_images/<?php echo $viewed['profile_picture']; ?>" alt="Profile Picture">
            <figcaption>User Quotation</figcaption>
        </figure>
        <br class="clear">
    </section>
    <?php
    break;

default:
?>
<h2> Welcome to Nerdvana, <?php echo $user['user_name']; ?>!</h2>
<section class="user-profile-page">
    <dl class="profile_info">
        <dt class="profile_info_label">Username:</dt> <dd class="profile_info_description"> <?php echo $user['user_name']; ?></dd>
        <dt class="profile_info_label">Gender:</dt> <dd class="profile_info_description"> <?php echo $user['gender'] == "M" ? "Male" : "Female"; ?> </dd>
        <dt class="profile_info_label">Last Active:</dt> <dd class="profile_info_description"> <?php echo date("l F jS Y g:i:s A", strtotime($user['last_active'])); ?> </dd>
    </dl>
    <figure class="profile_avatar">
        <img src="/images/user_images/<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
        <figcaption>User Quotation</figcaption>
    </figure>
    <br class="clear">
</section>

<h3>In Progress:</h4>
<h4>Events</h4>
    <ul>
        <li>Calendar for events</li>
        <li>Notifications from calendar</li>
    </ul>
<h4>Forums</h5>
    <ul>
        <li>Forum Script</li>
        <li>Forum Class</li>
        <li>Forum Unread Message Indicator</li>
        <li>Forum Subscriber</li>
    </ul>
<h4>Users</h4>
    <ul>
        <li>Profile Pictures</li>
        <li>Player Information</li>
            <ul>
                <li>Username</li>
                <li>Location</li>
                <li>Age</li>
                <li>Games Played</li>
                <li>Guilds in Games</li>
                <li>Occupation</li>
            </ul>
        <li>Character Picture Gallery</li>
        <li>Accounts Page</li>
    </ul>

<h1 style="font: 40px; color: orange">PRACTICE MAKES PERFECT! <br>More practice = better skills.</h1>
<?php
if (file_exists(ROOT . '/class/Authenticate.php')) {
    var_dump(ROOT . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Authenticate.php');
    include_once ROOT . '/class/Authenticate.php';
    if (class_exists('Authenticate')) {
        echo 'No such class';
    } else {
        var_dump(new Authenticate);
    }
} else {
    echo 'No such file.';
}

}

require_once 'includes/private_footer.php';
$contents = ob_get_contents();
echo $contents;
<<<<<<< HEAD
ob_end_flush();
=======
ob_end_flush();
?>
>>>>>>> ea909f18347467b41b058e6a32fec95405e467dc
