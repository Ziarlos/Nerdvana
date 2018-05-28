<?php

//use Nerdvana\Authenticate;
use Nerdvana\Chat;
use Nerdvana\Database;
use Nerdvana\Forum;
use Nerdvana\User;

define('ROOT', dirname(__DIR__));

require_once ROOT . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DATABASE'])->notEmpty();

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
define('DB_HOSTNAME', getenv('DB_HOSTNAME'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DATABASE', getenv('DATABASE'));

/**
 * Define the timezone: set to America/Los_Angeles (PST) for now.
 */
date_default_timezone_set('America/Los_Angeles');

/**
 * Load classes
 */
$Database = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DATABASE);
/**
 * Have not finished the authentication script so it will be commented out for the moment
 * $Authenticate = new Authenticate($Database);
 */
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
function handleException($exception): void
{
    echo '<p>' . $exception->getMessage() . '</p>';
    echo '<p> Sorry, an error has occurred. Please try again later.</p>';
    error_log($exception->getMessage());
}

set_exception_handler('handleException');
