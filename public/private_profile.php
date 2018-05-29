<?php declare(strict_types=1);

use Nerdvana\Authenticate;

ob_start();
session_start();

require_once '../includes/private_header.php';

if (Authenticate::isLoggedIn()) {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    switch ($action) {
    case 'view':
        $viewed = $User->getUserInfo($_GET['user_id']);
        ?>
        <h2> Welcome to Nerdvana, <?= $user['user_name']; ?>!</h2>
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

<h3>In Progress:</h3>
<h4>Events</h4>
    <ul>
        <li>Calendar for events</li>
        <li>Notifications from calendar</li>
    </ul>
<h4>Forums</h4>
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
            <li>
                <ul>
                    <li>Username</li>
                    <li>Location</li>
                    <li>Age</li>
                    <li>Games Played</li>
                    <li>Guilds in Games</li>
                    <li>Occupation</li>
                </ul>
            </li>
        <li>Character Picture Gallery</li>
        <li>Accounts Page</li>
    </ul>

    <h1 style="font-size: 40px; color: orange">PRACTICE MAKES PERFECT! <br>More practice = better skills.</h1>
    <?php
    }
} else {
    Authenticate::notLoggedIn();
}

require_once ROOT . '/includes/private_footer.php';
$contents = ob_get_contents();
echo $contents;
ob_end_flush();
