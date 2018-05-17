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

require_once '/config/config.php';
require_once 'includes/private_header.php';

if (Authenticate::isLoggedIn()) {

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
    
} else {
    Authenticate::notLoggedIn();
}
require_once 'includes/private_footer.php';
$contents = ob_get_contents();
ob_end_flush();
echo $contents;
?>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            