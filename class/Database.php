<?php declare(strict_types=1);

namespace Nerdvana;

use PDO;
use PDOException;

/**
 * PDO database connection class
 * PHP version 7+
 *
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */
class Database
{
    /**
     * Reference & handle for PDO instance.
     *
     * @object    $_pdo
     * @access private
     */
    private $_pdo = null;
    /**
     * Reference to host
     *
     * @var    string $_hostname host
     * @access private
     */
    private $_hostname;
    /**
     * Reference to database name
     *
     * @var    string $_username Username for database
     * @access private
     */
    private $_username;
    /**
     * Database password
     *
     * @var    string $_password password for database
     * @access private
     */
    private $_password;
    /**
     * Database
     *
     * @var    string $_database the database to use
     * @access private
     */
    private $_database;
    /**
     * Binding statement
     *
     * @var    string $statement variable for query, binding, & execution
     * @access public
     */
    public $statement = null;
    /**
     * PDO configuration
     *
     * @var    array $options PDO configuration
     * @access private
     */
    private $_options = array(
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    /**
     * Database constructor
     * Create a new object with database connection.
     *
     * @param string $hostname Hostname
     * @param string $username User connecting
     * @param string $password Password for DB
     * @param string $database Database to use
     */
    public function __construct($hostname, $username, $password, $database)
    {
        $this->_hostname = $hostname;
        $this->_database = $database;
        $this->_username = $username;
        $this->_password = $password;
        try {
            $this->_pdo = new PDO('mysql:host=' . $this->_hostname . ';dbname=' . $this->_database . ';charset=UTF8',
                $this->_username, $this->_password, $this->_options);
            // $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo '<p>My apologies, I could not connect to the database using the PDO accessor.<br />';
            echo 'Error: ' . $e->getMessage() . '</p>';
        }
    }

    /**
     * Query Method creates a new prepared query
     *
     * @param string                $query SQL query to execute
     * @param array|string|int|bool $bind  variables to pass in
     * @param string                $fetch query type
     *
     * @return array
     */
    public function query($query, $bind = null, $fetch = 'FETCH_ASSOC')
    {
        // Prepare statement
        $this->statement = $this->_pdo->prepare($query);
        // bind values
        if ($bind !== null) {
            foreach ($bind as $select => $value) {
                // for each value type give appropriate PDO::PARAM
                if (is_int($value)) {
                    $param = PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $param = PDO::PARAM_BOOL;
                } elseif (is_null($value)) {
                    $param = PDO::PARAM_NULL;
                } elseif (is_string($value)) {
                    $param = PDO::PARAM_STR;
                } else {
                    $param = false;
                }

                // bind value
                if (isset($bind) && isset($param)) {
                    $this->statement->bindValue($select, $value, $param);
                }
            } // foreach
        }

        // Execute query
        if (!$this->statement->execute()) {
            $result = array(1 => 'false', 2 => 'SQL Error');

            return $result;
        }

        // return contents
        switch ($fetch) {
            case 'FETCH_ASSOC':
                $result = $this->statement->fetch(PDO::FETCH_ASSOC);
                break;
            case 'FETCH_BOTH':
                $result = $this->statement->fetch(PDO::FETCH_BOTH);
                break;
            case 'FETCH_LAZY':
                $result = $this->statement->fetch(PDO::FETCH_LAZY);
                break;
            case 'FETCH_OBJ':
                $result = $this->statement->fetch(PDO::FETCH_OBJ);
                break;
            case 'fetchAll':
                $result = $this->statement->fetchAll();
                break;
        }

        return $result;
    } // query method

    /**
     * Count returns the rowCount method
     *
     * @access public
     * @return int
     */
    public function count()
    {
        $result = $this->statement->rowCount();

        return $result;
    }

    /**
     * Last inserted id
     *
     * @return int
     * @access public
     */
    public function lastInsertId()
    {
        $result = $this->_pdo->lastInsertId();

        return $result;
    }

    /**
     * Destroy DB connection
     *
     * @return void
     */
    public function __destruct()
    {
        $this->_pdo = null;
    }
}
