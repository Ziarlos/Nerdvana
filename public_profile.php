<?php declare(strict_types=1);

/**
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */

ob_start();

require_once 'includes/public_header.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
case 'view':
    $user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : null;
    if (is_numeric($user_id)) {
        $viewed = $User->getUserInfo($_GET['user_id']);
        ?>
        <dl class="profile_info">
            <dt class="profile_info_label">Username:</dt>
            <dd class="profile_info_description"> <?php echo $viewed['user_name']; ?></dd>
            <dt class="profile_info_label">Gender:</dt>
            <dd class="profile_info_description"> <?php echo $viewed['gender'] == "M" ? "Male" : "Female"; ?> </dd>
            <dt class="profile_info_label">Last Active:</dt>
            <dd class="profile_info_description"> <?php echo date("l F jS Y g:i:s A", strtotime($viewed['last_active'])); ?> </dd>
            <dt class="clear"></dt>
        </dl>
        <div class="profile_avatar">
            <img src="/images/user_images/<?php echo $viewed['profile_picture']; ?>" alt="Profile Picture">
        </div>
        <?php
    } else {
        echo '<div class="alert alert-warning">You must enter a numeric value for user id.</div>';
    }
    break;

default:
    echo '<h3>Current Members</h3>';
    $members = '';
    $user_list = $Database->query("SELECT * FROM users ORDER BY user_id DESC", array(), 'fetchAll');
    foreach ($user_list as $list) {
        if ($members != '') {
            $members .= ', ';
        }
        $members .= '<a href="public_profile.php?action=view&amp;user_id=' . $list['user_id'] . '">' . $list['user_name'] . '</a>';
    }
    echo '<p>'.$members.'</p>';
}

require_once 'includes/public_footer.php';
$contents = ob_get_contents();
// echo $contents;
ob_end_flush();
