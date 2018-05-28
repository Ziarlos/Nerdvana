<?php declare(strict_types=1);

namespace Nerdvana;

class User {

    /**
     * @var $Database Database object
     */
    private $Database;

    public function __construct(Database $Database) {
        $this->Database = $Database;
    }

    /**
     * @name updateLastActiveTime
     * @purpose update the `last_active` field in the database per page load
     * @param $user_id (int)
     *
     * @access public
     */
    public function updateLastActiveTime($user_id) {
        $this->Database->query("UPDATE users SET last_active = NOW() WHERE user_id = :user_id", array(':user_id' => $user_id));
    }

    /**
     * @name getUserInfo
     * @purpose return an array of information related to user
     * @param $user_id (int)
     *
     * @access public
     */
    public function getUserInfo($user_id) {
        $user = $this->Database->query("SELECT * FROM users WHERE user_id = :user_id", array(':user_id' => $user_id));
        if ($this->Database->count() == 0) {
            return 0;
        }
        else {
            return $user;
        }
    }

    /**
     * @name getActiveUsers
     * @purpose return a list of users online within last 5 min
     *
     * @access public
     */
    public function getActiveUsers() {
        $users_online = $this->Database->query("SELECT user_id, user_name, gender, staff_status FROM users WHERE UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_active) < 300 ORDER BY user_id ASC", array(), "fetchAll");
        if ($this->Database->count() == 0) {
            return null;
        }
        else {
            return $users_online;
        }
    }

    /**
     * @name getUserName
     * @purpose return name of user
     * @param $user_id (int)
     *
     * @access public
     */
    public function getUserName($user_id) {
        $info = $this->Database->query("SELECT user_name FROM users WHERE user_id = :user_id", array(':user_id' => $user_id));
        return $info;
    }

    public function __destruct() {
        $this->Database = null;
    }
}
