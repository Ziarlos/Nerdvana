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
class Authenticate
{
    
    /**
     * Handle for database connection
     * 
     * @var $Database handle for database connection
     */
    private $Database = null;
    
    /**
     * @var $status_message array handling messages
     */
    private $status_message = array();
    
    public function __construct(Database $Database)
    {
        $this->Database = $Database;
    }
    
    public function registration($user_name, $email, $password, $gender)
    {
        try {
            $this->Database->query("INSERT INTO users (user_name, email, password, gender) VALUES (:user_name, :email, :password, :gender)", array(':user_name' => $user_name, ':email' => $email, ':password' => $password, ':gender' => $gender));
            return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }
    
    public function verification()
    {
    
    }
    public function login()
    {
    
    }
    
    public function logout()
    {
    
    }
    
    public function changePassword()
    {
    
    }
    
    public function lostPassword()
    {
        
    }
    
    public function resetPassword()
    {
    
    }
    
    public function authenticateSession()
    {
    
    }
    
    public static function isLoggedIn()
    {
        if (isset($_SESSION['login_status']) && $_SESSION['login_status'] === true) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function notLoggedIn()
    {
        ?>
        <h1 class="error">You must be logged in to view this page.</h1>
        <?php
    }
    
    public static function invalidAuthorization()
    {
        ?>
        <h1 class="error">You do not have the proper authorization.</h1>
        <div class="back_button"><a href="javascript:history.back();">Go to previous page.</a></div>
        <?php
    }
    
    public function support()
    {
    
    }
    
    public function __destruct()
    {
    
    }

}