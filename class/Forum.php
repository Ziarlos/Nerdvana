<?php declare(strict_types=1);

namespace Nerdvana;

class Forum
{
    /**
     * @var $Database Database object
     */
    private $Database = null;

    /**
     * @var $User User object
     */
    private $User = null;

    public function __construct(Database $Database, User $User)
    {
        $this->Database = $Database;
        $this->User = $User;
    }

    /**
     * @name countTopics
     * @purpose counts the number of topics in the category: $category_id
     *
     * @param $category_id (int)
     *
     * @access public
     */
    public function countTopics($category_id)
    {
        $this->Database->query("SELECT * FROM forum_topics WHERE topic_category = :category_id", array(':category_id' => $category_id));
        return $this->Database->count();
    }

    /**
     * @name countPosts
     * @purpose count posts in topic
     *
     * @params $category_id (int)
     * @params $topic_id (int)
     *
     * @access public
     */
    public function countPosts($category_id = null, $topic_id = null)
    {
        if ($category_id !== null) {
            $this->Database->query("SELECT forum_posts.post_id FROM forum_posts LEFT JOIN forum_topics ON forum_posts.post_topic = forum_topics.topic_id WHERE forum_topics.topic_category = :category_id", array(':category_id' => $category_id));
        } elseif ($topic_id !== null) {
            $this->Database->query("SELECT post_id FROM forum_posts WHERE post_topic = :topic_id", array(':topic_id' => $topic_id));
        }
        return $this->Database->count();
    }

    /**
     * @name viewCategories
     * @purpose returns an array of the categories for the main forum.
     *
     * @access public
     */
    public function viewCategories()
    {
        $category = $this->Database->query("SELECT category_id, category_name, category_description FROM forum_category ORDER BY category_id ASC", null, "fetchAll");
        return $category;
    }

    /**
     * @name getCategoryInfo
     * @purpose return category id and name
     * @param $category_id (int)
     *
     * @access public
     */
    public function getCategoryInfo($category_id) {
         $category = $this->Database->query("SELECT category_id, category_name FROM forum_category WHERE category_id = :category_id", array(':category_id' => $category_id));

         return $category;
     }

    /**
     * @name getNewestPost
     * @purpose Retrieves date & creator_id from `forum_topics` and `forum_posts`
     *
     * @param $category_id (int)
     *
     * @access public
     */
    public function getNewestPost($category_id)
    {
        $post_info = $this->Database->query("SELECT forum_posts.post_date, forum_posts.creator_id, forum_topics.topic_date, forum_topics.creator_id FROM forum_topics LEFT JOIN forum_posts ON forum_topics.topic_id = forum_posts.post_topic WHERE forum_topics.topic_category = :category_id ORDER BY forum_topics.topic_date DESC LIMIT 1", array(':category_id' => $category_id));
        return $post_info;
    }

    /**
     * @name viewTopics
     * @purpose displays the topics within a category as array
     *
     * @param $category_id (int)
     *
     * @access public
     */
    public function viewTopics($category_id)
    {
        $topic = $this->Database->query("SELECT topic_id, topic_subject, topic_content, topic_date, topic_category, creator_id FROM forum_topics WHERE topic_category = :category_id ORDER BY topic_id DESC", array(':category_id' => $category_id), "fetchAll");
        return $topic;
    }

    /**
     * @name viewPost
     * @purpose Returns the initial post within a topic.
     *
     * @param $topic_id (int)
     *
     * @access public
     */
    public function viewTopic($topic_id)
    {
        $topic = $this->Database->query("SELECT topic_id, topic_subject, topic_content, topic_date, topic_category, creator_id, editor_id, edit_date FROM forum_topics WHERE topic_id = :topic_id", array(':topic_id' => $topic_id));
        return $topic;
    }

    /**
     * @name getAllPosts
     * @purpose returns an array of all reply posts within topic
     *
     * @param $topic_id (int)
     *
     * @access public
     */
    public function getAllPosts($topic_id)
    {
        $post = $this->Database->query("SELECT post_id, post_content, post_date, post_topic, post_category, creator_id, editor_id, edit_date FROM forum_posts WHERE post_topic = :topic_id", array(':topic_id' => $topic_id), "fetchAll");
        return $post;
    }

    /**
     * @name getPostInfo
     * @purpose returns array with info about post
     *
     * @param $post_id (int)
     *
     * @access public
     *
     */
    public function getPostInfo($post_id)
    {
        $info = $this->Database->query("SELECT post_id, post_content, post_date, post_topic, creator_id FROM forum_posts WHERE post_id = :post_id", array(':post_id' => $post_id));
        return $info;
    }

    /**
     * @name createTopic
     * @purpose inserts a new topic into the forum_topics table
     *
     * @param $category_id (int)
     * @param $subject (string)
     * @param $message (string)
     * @param @user_id (int)
     *
     * @access public
     *
     */
    public function createTopic($category_id, $subject = null, $message = null, $user_id)
    {
        $subject = isset($subject) ? $subject : '[No Subject]';
        $message = isset($message) ? $message : '[No Message Body]';
        $this->Database->query("INSERT INTO forum_topics (topic_id, topic_subject, topic_content, topic_date, topic_category, creator_id) VALUES (null, :subject, :message, CURRENT_TIMESTAMP, :category_id, :user_id)", array(':subject' => $subject, ':message' => $message, ':category_id' => $category_id, ':user_id' => $user_id));
    }

    /**
     * @name createPost
     * @purpose inserts a new post into the forum_posts table
     *
     * @param $topic_id (int)
     * @param $message (string)
     * @param $user_id (int)
     *
     * @access public
     *
     */
    public function createPost($topic_id, $message = null, $user_id)
    {
        $message = isset($message) ? $message : '[No Message Body]';
        $this->Database->query("INSERT INTO forum_posts (post_id, post_content, post_date, post_topic, creator_id) VALUES (null, :message, CURRENT_TIMESTAMP, :topic_id, :user_id)", array(':message' => $message, ':topic_id' => $topic_id, ':user_id' => $user_id));
    }

    /**
     * @name editPost
     * @purpose edit a previously inserted post in the forum_posts table
     *
     * @param $post_id (int)
     * @param $message (string)
     * @param $editor_id (int)
     *
     * @access public
     *
     */
    public function editPost($post_id, $message, $editor_id)
    {
        $this->Database->query("UPDATE forum_posts SET post_content = :post_content, editor_id = :editor_id, edit_date = CURRENT_TIMESTAMP WHERE post_id = :post_id", array(':post_content' => $message, ':post_id' => $post_id, ':editor_id' => $editor_id));
    }

    /**
     * @name deletePost
     * @purpose delete a post in the forum_posts table
     *
     * @param $post_id
     *
     * @access public
     */
    public function deletePost($post_id)
    {
        $this->Database->query("DELETE FROM forum_posts WHERE post_id = :post_id", array(':post_id' => $post_id));
    }

    /**
     * @name editTopic
     * @purpose edit a previously inserted topic in the forum_topics table
     *
     * @param int $topic_id
     * @param string $subject
     * @param string $message
     * @param int $editor_id
     *
     * @access public
     *
     */
    public function editTopic($topic_id, $subject, $message, $editor_id)
    {
        $this->Database->query("UPDATE forum_topics SET topic_subject = :subject, topic_content = :message, editor_id = :editor, edit_date = CURRENT_TIMESTAMP WHERE topic_id = :topic_id", array(':subject' => $subject, ':message' => $message, ':topic_id' => $topic_id, ':editor' => $editor_id));
    }

    /**
     * @name deleteTopic
     * @purpose delete topic and topic replies
     *
     * @param int $topic_id
     *
     * @access public
     *
     */
    public function deleteTopic($topic_id)
    {
        $this->Database->query("DELETE FROM forum_topics WHERE topic_id = :topic_id", array(':topic_id' => $topic_id));
        $this->Database->query("DELETE FROM forum_posts WHERE post_topic = :topic_id", array(':topic_id' => $topic_id));
    }

    /**
     * @name destruct
     * @purpose Delink database
     */
    public function __destruct()
    {
        $this->Database = null;
    }
}
