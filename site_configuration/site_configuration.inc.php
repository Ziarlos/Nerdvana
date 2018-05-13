<?php declare(strict_types=1);
/**
 * Create a connection to the database using the PDO extension
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

try {
    $driver_options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );
    $dbx = new PDO('mysql:host=' . HOSTNAME . ';dbname=' . DATABASE . ';charset=UTF8', USERNAME, PASSWORD, $driver_options);
}
catch (PDOException $ex) {
    echo '<p>Could not connect using PDO!</p>';
    echo $ex->getMessage();
}
