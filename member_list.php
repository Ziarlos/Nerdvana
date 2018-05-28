<?php declare(strict_types=1);

ob_start();
session_start();

require_once 'includes/private_header.php';

echo '<h1>New Members</h1>';
$new_members = '';
$user_list = $Database->query("SELECT * FROM users ORDER BY user_id DESC LIMIT 0, 5", array(), 'fetchAll');
foreach ($user_list as $list) {
    if ($new_members != '') {
        $new_members .= ', ';
    }
    $new_members .= '<a href="private_profile.php?action=view&amp;user_id=' . $list['user_id'] . '">' . $list['user_name'] . '</a>';
}
echo '<p>'.$new_members.'</p>';

echo '<h1>Currently Active</h1>';
$online = $Database->query("SELECT user_id, user_name FROM users WHERE UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_active) < 500 ORDER BY user_id ASC", array(), 'fetchAll');
echo '<ul>';
foreach ($online as $member) {
    echo '<li> <a href="private_profile.php?action=view&amp;user_id=' . $member['user_id'] . '">' . $member['user_name'] . '</a> </li>';
}
echo '</ul>';

require_once 'includes/private_footer.php';
$contents = ob_get_contents();
ob_end_flush();
echo $contents;
