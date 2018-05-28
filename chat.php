<?php declare(strict_types=1);

/**
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */

ob_start();
session_start();

require_once 'config/config.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

switch ($action) {

case 'send_message':
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    if (!isset($message)) {
        $message = "";
    }

    if ($message != "") {
        if (substr($message, 0, 5) == "/wipe" && $user['user_id'] == 1) {
            $Chat->wipeChat();
        } else {
            $Chat->sendMessage($user['user_id'], $message);
        }
    }
    break;

default:
    $chats = $Chat->getMessages();
    foreach ($chats as $chat) {
        echo '<div class="chatlines" id="chat_id' . $chat['chat_id'] . '" title="' . $chat['time_posted'] . '"><a href="javascript:Chat.click(\'' . $chat['user_name'] . '\', ' . $chat['user_id'] . ');"><span class="user-staff-rank' . $chat['staff_status'] . '">' . $chat['user_name'] . '</span></a>: ' . $chat['message'] . '</div>';
    }
    $Chat->deleteMessages();
}

ob_end_flush();
