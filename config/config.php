<?php
/**
 * Global variables and constants will be defined in this page
 * These variables and constants may be used in multiple pages.
 * Below we start a database connection.
 * Since PHP in moving to PDO and MySQLi, we no longer use MySQL.
 *
 * PHP version 7+
 *
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */
require_once './vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOSTNAME', 'DB_USERNAME', 'DB_PASSWORD', 'DATABASE'])->notEmpty();

define('DB_HOSTNAME', getenv('DB_HOSTNAME'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DATABASE', getenv('DATABASE'));

/**
* Define the timezone: set to America/Los_Angeles (PST) for now.
*/

date_default_timezone_set('America/Los_Angeles');

/**
 * Attempt to connect to the database
 */

try {
    $driver_options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );
    $dbx = new PDO('mysql:host=' . DB_HOSTNAME . ';dbname=' . DATABASE . ';charset=UTF8', DB_USERNAME, DB_PASSWORD, $driver_options);
}
catch (PDOException $ex) {
    echo '<p>Could not connect using PDO!</p>';
    echo $ex->getMessage();
}

/**
 * Load classes
 */

require_once './class/Database.class.php';
require_once './class/Authenticate.class.php';
require_once './class/User.class.php';
require_once './class/Chat.class.php';
require_once './class/Forum.class.php';

$Database = new Database(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DATABASE);
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
