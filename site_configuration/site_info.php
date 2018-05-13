<?php declare(strict_types=1);
/**
 * Global variables and constants will be defined in this page
 * These variables and constants may be used in multiple pages.
 * 
 * PHP version 7+
 * 
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */

require_once 'define_config.php';
require_once 'site_configuration.inc.php';

require_once './class/Database.class.php';
require_once './class/Authenticate.class.php';
require_once './class/User.class.php';
require_once './class/Chat.class.php';
require_once './class/Forum.class.php';


$Database = new Database(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$Authenticate = new Authenticate($Database);
$User = new User($Database);
$Forum = new Forum($Database, $User);
$Chat = new Chat($Database);

if (isset($_SESSION['user_id'])) {
    $user = $User->getUserInfo($_SESSION['user_id']);
    $User->updateLastActiveTime($user['user_id']);
}

/**
 * Error handling function
 * 
 * @param string $exception Exception parameter
 * 
 * @return void
 */
function handleException($exception)
{
    echo '<p>' . $exception->getMessage() . '</p>';
    echo '<p> Sorry, an error has occurred. Please try again later.</p>';
    error_log($exception->getMessage());
}

set_exception_handler('handleException');
?>