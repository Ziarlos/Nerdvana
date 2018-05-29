<?php declare(strict_types=1);

/**
 * @category Social
 * @package  Social
 * @author   Ziarlos <bruce.wopat@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/Ziarlos
 */

session_start();
ob_start();

require_once '../includes/private_header.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
switch ($action) {

case 'view_board':
    if (isset($_GET['category_id'])) {
        $_GET['category_id'] = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
        $category_info = $Forum->getCategoryInfo($_GET['category_id']);
    }
    ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="forum.php">Forums</a></li>
        <li class="breadcrum-item active"><?php echo htmlspecialchars($category_info['category_name']); ?></li>
    </ol>
    <table border="1" cellspacing="2" cellpadding="2" class="forum_topics" width="100%">
        <tr>
            <td width="50%" class="table-header">Topic</td>
            <td width="25%" class="table-header">Poster</td>
            <td width="25%" class="table-header">Posted On</td>
        </tr>
    <?php
    $topics = $Forum->viewTopics($_GET['category_id']);
    foreach ($topics as $topic) {
        $getName = $User->getUserName($topic['creator_id']);
        ?>
        <tr class="alternate">
            <td> <a href="forum.php?action=view_topic&amp;topic_id=<?php echo $topic['topic_id']; ?>"> <?php echo htmlspecialchars($topic['topic_subject']); ?> </a></td>
            <td> <?php echo htmlspecialchars($getName['user_name']); ?></td>
            <td> <?php echo $topic['topic_date']; ?> </td>
        </tr>
        <?php
    }
    ?>
    </table>
    <form action="forum.php?action=create_topic" method="post" class="form-horizontal">
        <fieldset>
            <legend>
                Create a topic
            </legend>
            <div class="form-group">
                <label for="new_topic_subject" class="control-label col-sm-3">
                    New Subject:
                </label>
                <div class="col-sm-9">
                    <input type="text" name="new_topic_subject" id="new_topic_subject" required class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="new_topic_message" class="control-label col-sm-3">
                    New Message:
                </label>
                <div class="col-sm-9">
                    <textarea rows="8" cols="60" name="new_topic_message" id="new_topic_message" required class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <input type="hidden" name="category_id" id="category_id" value="<?php echo $_GET['category_id']; ?>" />
                    <br />
                    <button type="submit" name="submit_topic" id="submit_topic" class="btn btn-primary">
                        Create Topic
                    </button>
                </div>
            </div>
        </fieldset>
    </form>
    <?php
    break;

case 'view_topic':
    $topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : '';
    $topic = $Forum->viewTopic($topic_id);
    $category = $Forum->getCategoryInfo($topic['topic_category']);
    $getPosterName = $User->getUserName($topic['creator_id']);
    $getEditorName = $User->getUserName($topic['editor_id']);
    echo '<ol class="breadcrumb">';
        echo '<li class="breadcrumb-item"><a href="forum.php">Forum</a></li>';
        echo '<li class="breadcrumb-item"> <a href="forum.php?action=view_board&amp;category_id=' . $category['category_id'] . '">' . $category['category_name'] . '</a>';
        echo '<li class="breadcrumb-item active">' . $topic['topic_subject'] . '</li>';
    echo '</ol>';
    echo '<p>Posted by: ' . $getPosterName['user_name'] . ' on ' . $topic['topic_date'] . '</p>';
    echo '<p>Topic subject: ' . $topic['topic_subject'] . '<br>';
    echo 'Topic message: ' . $topic['topic_content'].'</p>';
    if ($topic['editor_id'] !== null && $topic['edit_date'] !== null) {
        echo '<p>Edited by: ' . $getEditorName['user_name'] . ' on ' . $topic['edit_date'] . '.</p>';
    }
    if ($topic['creator_id'] === $user['user_id'] || $user['staff_status'] === 1) {
        echo '<p><a href="forum.php?action=edit_topic&amp;topic_id=' . $topic['topic_id'] . '">Edit Topic: ' . $topic['topic_subject'] . '</a> || <a href="forum.php?action=delete_topic&amp;topic_id=' . $topic['topic_id'] . '">Delete Topic: ' . $topic['topic_subject'] . '</a></p>';
    }
    ?>
    <hr>
    <?php
    $posts = $Forum->getAllPosts($topic['topic_id']);
    foreach ($posts as $post) {
        $getPosterName = $User->getUserName($post['creator_id']);
        $getEditorName = $User->getUserName($post['editor_id']);
        echo '<p>Posted by ' . $getPosterName['user_name'] . ' on ' . $post['post_date'] . '</p>';
        echo '<p>' . $post['post_content'] . '</p>';
        if ($post['editor_id'] !== null && $post['edit_date'] !== null) {
            echo '<p>Edited by: ' . $getEditorName['user_name'] . ' on ' . $post['edit_date'] . '.</p>';
        }
        if ($post['creator_id'] === $user['user_id'] || $user['staff_status'] === 1) {
            echo '<p><a href="forum.php?action=edit_reply&amp;post_id=' . $post['post_id'] . '">Edit Post</a> || <a href="forum.php?action=delete_reply&amp;post_id=' . $post['post_id'] . '">Delete Post</a></p>';
        }
        echo '<hr />';
    }
    ?>
    <form action="forum.php?action=reply" method="post" class="form-horizontal">
        <fieldset>
            <legend>Reply to: <?php echo $topic['topic_subject']; ?></legend>
            <div class="form-group">
                <label for="reply_message" class="control-label col-sm-3">
                    Message:
                </label>
                <div class="col-sm-9">
                    <textarea rows="8" cols="60" name="reply_message" id="reply_message" required class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <input type="hidden" name="topic_id" id="topic_id" value="<?php echo $topic['topic_id']; ?>" />
                    <br />
                    <button type="submit" name="submit_reply" id="submit_reply" class="btn btn-primary">
                        Reply
                    </button>
                </div>
            </div>
        </fieldset>
    </form>
    <?php
    break;

case 'create_topic':
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    $category = $Forum->getCategoryInfo($category_id);
    $new_topic_subject = isset($_POST['new_topic_subject']) ? $_POST['new_topic_subject'] : '';
    $new_topic_message = isset($_POST['new_topic_message']) ? $_POST['new_topic_message'] : '';
    $Forum->createTopic($category_id, $new_topic_subject, $new_topic_message, $user['user_id']);
    echo '<p>Topic created!</p>';
    echo '<div class="back_button"><a href="forum.php">Back to Main Forums</a> || <a href="forum.php?action=view_board&amp;category_id=' . $category['category_id'] .'">' . $category['category_name'] . '</a></div>';
    break;

case 'reply':
    $topic_id = isset($_POST['topic_id']) ? $_POST['topic_id'] : '';
    $topic = $Forum->viewTopic($topic_id);
    $category = $Forum->getCategoryInfo($topic['topic_category']);
    $reply_message = isset($_POST['reply_message']) ? $_POST['reply_message'] : '';
    $Forum->createPost($topic['topic_id'], $reply_message, $user['user_id']);
    echo '<p>Reply posted!</p>';
    echo '<div class="back_button">';
        echo '<a href="forum.php">Back to Main Forums</a> || <a href="forum.php?action=view_board&amp;category_id=' . $category['category_id'] . '">' . $category['category_name'] . '</a> || <a href="forum.php?action=view_topic&amp;topic_id=' . $topic['topic_id'] . '">Back to ' . $topic['topic_subject'] . '</a>';
    echo '</div>';
    break;

case 'edit_reply':
    if (isset($_GET['post_id'])) {
        $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    }
    $post = $Forum->getPostInfo($post_id);
    $topic = $Forum->viewTopic($post['post_topic']);
    if ($post['creator_id'] === $user['user_id']) {
        if (isset($_POST['edit_post_message'])) {
            $edit_post_message = filter_input(INPUT_POST, 'edit_post_message', FILTER_SANITIZE_STRING);
        }
        if (isset($_POST['edit_post'])) {
            $Forum->editPost($post['post_id'], $edit_post_message, $user['user_id']);
            echo '<p>You have successfully edited this post.</p>';
            echo '<div class="back_button"><a href="forum.php?action=view_topic&amp;topic_id=' . $topic['topic_id'] . '">' . htmlspecialchars($topic['topic_subject']) . '</a></div>';
        }
        ?>
        <form action="forum.php?action=edit_reply&amp;post_id=<?php echo htmlspecialchars($post['post_id']); ?>" method="post" class="form-horizontal">
            <fieldset>
                <legend>
                    Edit Post for Topic: <?php $topic['topic_subject']; ?>
                </legend>
                <div class="form-group">
                    <label for="edit_post_message" class="control-label col-sm-3">
                        Edit Message:
                    </label>
                    <div class="col-sm-9">
                        <textarea rows="8" cols="60" name="edit_post_message" id="edit_post_message" required class="form-control">
                            <?php echo $post['post_content']; ?>
                        </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" name="edit_post" id="edit_post" class="btn btn-primary">
                            Edit Post
                        </button>
                    </div>
                </div>
            </fieldset>
        </form>
        <?php
    } else {
        echo '<div class="alert alert-warning">You do not have permission to edit this post.</div>';
    }
    break;

case 'delete_reply':
    if (isset($_GET['post_id'])) {
        $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    }
    $post = $Forum->getPostInfo($post_id);
    if ($post['creator_id'] === $user['user_id']) {
        $Forum->deletePost($post['post_id']);
        echo '<p>Post deleted!</p>';
    } else {
        echo '<div class="alert alert-warning">You do not have permission to edit this post.</div>';
    }
    break;

case 'edit_topic':
    if (isset($_GET['topic_id'])) {
        $topic_id = filter_input(INPUT_GET, 'topic_id', FILTER_SANITIZE_NUMBER_INT);
    }
    $topic = $Forum->viewTopic($topic_id);
    if ($topic['creator_id'] == $user['user_id']) {
        if (isset($_POST['edit_topic_subject'])) {
            $edit_topic_subject = filter_input(INPUT_POST, 'edit_topic_subject', FILTER_SANITIZE_STRING);
        }
        if (isset($_POST['edit_topic_content'])) {
            $edit_topic_content = filter_input(INPUT_POST, 'edit_topic_content', FILTER_SANITIZE_STRING);
        }

        if (isset($_POST['edit_topic'])) {
            $Forum->editTopic($topic['topic_id'], $edit_topic_subject, $edit_topic_content, $user['user_id']);
            echo '<p>You have successfully edited this topic.</p>';
            echo '<div class="back_button"><a href="forum.php?action=view_topic&amp;topic_id=' . $topic['topic_id'] . '">' . htmlspecialchars($topic['topic_subject']) . '</a></div>';
        }
        ?>
        <form action="forum.php?action=edit_topic&amp;topic_id=<?php echo $topic['topic_id']; ?>" method="post" class="form-horizontal">
            <fieldset>
                <legend>
                    Edit Topic: <?php echo $topic['topic_subject']; ?>
                </legend>
                <div class="form-group">
                    <label for="edit_topic_subject" class="control-label col-sm-3"
                        >New Subject:
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="edit_topic_subject" id="edit_topic_subject" value="<?php echo $topic['topic_subject']; ?>" required class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_topic_message" class="control-label col-sm-3">
                        New Message:
                    </label>
                    <div class="col-sm-9">
                        <textarea rows="8" cols="60" name="edit_topic_content" id="edit_topic_content" required class="form-control">
                            <?php echo $topic['topic_content']; ?>
                        </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" name="edit_topic" id="edit_topic" class="btn btn-primary">
                            Edit Topic: <?php echo $topic['topic_subject']; ?>
                        </button>
                    </div>
                </div>
            </fieldset>
        </form>
        <?php
    } else {
        echo '<div class="alert alert-warning">You do not have permission to edit this post.</div>';
    }
    break;

case 'delete_topic':
    if (isset($_GET['topic_id'])) {
        $topic_id = filter_input(INPUT_GET, 'topic_id', FILTER_SANITIZE_NUMBER_INT);
    }
    $topic = $Forum->viewTopic($topic_id);
    if ($topic['creator_id'] === $user['user_id'] || $user['staff_status'] === 1) {
        $Forum->deleteTopic($topic['topic_id']);
        echo '<p>Topic deleted!</p>';
    } else {
        echo '<div class="alert alert-warning">You do not have permission to edit this post.</div>';
    }
    break;

default:
    ?>
    <ol class="breadcrumb">
        <li>Forum</li>
    </ol>
    <table border="1" cellspacing="2" cellpadding="2" class="forums" width="100%">
    <tr>
        <td width="40%" class="table-header">Category</td>
        <td width="20%" class="table-header">Last Poster</td>
        <td width="20%" class="table-header">Total Topics</td>
        <td width="20%" class="table-header">Total Posts</td>
    </tr>
    <?php
    $categories = $Forum->viewCategories();
    foreach ($categories as $cat) {
        $last_post = $Forum->getNewestPost($cat['category_id']);
        $get_name = $User->getUserName($last_post['creator_id']);
        ?>
        <tr class="alternate">
            <td>
                <a href="forum.php?action=view_board&amp;category_id=<?php echo $cat['category_id']; ?>"> <?php echo $cat['category_name']; ?></a>
                <br />
                <?php echo $cat['category_description']; ?>
            </td>
            <td>
                <?php echo $get_name['user_name']; ?>
            </td>
            <td>
                <?php echo $Forum->countTopics($cat['category_id']); ?>
            </td>
            <td>
                <?php echo $Forum->countPosts($cat['category_id'], null); ?>
            </td>
        </tr>
        <?php
    }
    ?>
    </table>
    <?php
}

require_once ROOT . '/includes/private_footer.php';
$contents = ob_get_contents();
ob_end_flush();
echo $contents;
