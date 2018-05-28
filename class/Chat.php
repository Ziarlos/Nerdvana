<?php declare(strict_types=1);

namespace Nerdvana;

/**
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */
class Chat
{
    private $_Database;

    /**
     * Construct chat database connection
     *
     * @param object $Database Database variable
     *
     * @return void
     */
    public function __construct(Database $Database)
    {
        $this->_Database = $Database;
    }

    /**
     * Get messages
     * pulls an array of messages from chat table
     * joins chat with users table
     *
     * @return array
     */
    public function getMessages()
    {
        $chat_info = $this->_Database->query("SELECT chat.*, users.user_name, users.staff_status FROM chat LEFT JOIN users ON users.user_id = chat.user_id ORDER BY chat_id DESC", null, "fetchAll");
        return $chat_info;
    }

    /**
     * Send message to database
     *
     * @param int    $user_id (user_id of person sending message)
     * @param string $message (message being sent
     *
     * @return void
     */
    public function sendMessage($user_id, $message)
    {
        $this->_Database->query("INSERT INTO chat (chat_id, user_id, message, time_posted) VALUES ('', :user_id, :message, CURRENT_TIMESTAMP)", array(':user_id' => $user_id, ':message' => $message));
    }

    /**
     * Wipe chat table
     *
     * @return void
     */
    public function wipeChat()
    {
        $this->_Database->query("TRUNCATE TABLE chat");
    }

    /**
     * Delete messages if older than 5 minutes (300 secs)
     *
     * @return void
     */
    public function deleteMessages()
    {
        $this->_Database->query("DELETE FROM chat WHERE UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(time_posted) >= 3200");
    }

    /**
     * Closes link to database
     *
     * @return void
     */
    public function __destruct()
    {
        $this->_Database= null;
    }
}
