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


define('HOSTNAME', 'localhost');
define('USERNAME', 'a user name');
define('PASSWORD', 'a password');
define('DATABASE', 'a database');


/**
* Define the timezone: set to America/Los_Angeles (PST) for now.
*/

date_default_timezone_set('America/Los_Angeles');
?>