<?php declare(strict_types=1);

use Nerdvana\Authenticate;

ob_start();
session_start();

require_once 'includes/private_header.php';

if (Authenticate::isLoggedIn()) {
    $action = isset($_GET['action']) ? $_GET['action'] : null;

    switch ($action) {

    case 'change-username':

        echo '<p>In progress</p>';
        if (isset($_POST['change_username_confirmed']) && $_POST['change_username_confirmed'] == "Y") {
            $current_username = isset($_POST['current_username']) ? $_POST['current_username'] : null;
            $enter_new_username = isset($_POST['enter_new_username']) ? $_POST['enter_new_username'] : null;
            $confirm_new_username = isset($_POST['confirm_new_username']) ? $_POST['confirm_new_username'] : null;
            $error = false;
            if (!preg_match("/[a-zA-Z0-9]/", $current_username)) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>Your current username should only have upper- or lower-case letters and the numbers 0 - 9.</p>';
                echo '</div>';
            }
            if (!preg_match("/[a-zA-Z0-9]/", $enter_new_username)) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>Your new username should only have upper- or lower-case letters and the numbers 0 - 9.</p>';
                echo '</div>';
            }
            if (!preg_match("/[a-zA-Z0-9]/", $enter_new_username)) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>The confirmation username should only have upper- or lower-case letters and the numbers 0 - 9.</p>';
                echo '</div>';
            }
            if ($current_username !== $user['user_name']) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>That is not your current username! Your current username: ' . $user['user_name'] . '.</p>';
                echo '</div>';
            }
            if ($enter_new_username !== $confirm_new_username) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>Your new username does not match the confirmation username!</p>';
                echo '</div>';
            }
            if ($error === false) {
                $Database->query("UPDATE users SET user_name = :user_name WHERE user_id = :user_id LIMIT 1", array(":user_name" => $enter_new_username, ":user_id" => $_SESSION['user_id']));
                $_SESSION['user_name'] = $enter_new_username;
                echo '<div class="alert alert-success">';
                    echo '<p>You have updated your username from ' . $current_username . ' to ' . $enter_new_username . '.</p>';
                echo '</div>';
            }
        } else {
        ?>
            <form action="account.php?action=change-username" method="post" class="form-horizontal">
                <fieldset>
                    <legend>Change Your Username</legend>
                    <div class="form-group">
                        <label for="current_username" class="col-sm-3 control-label">
                            Current Username:
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="current_username" id="current_username" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="enter_new_username" class="col-sm-3 control-label">
                            Enter New Username:
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="enter_new_username" id="enter_new_username" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_new_username" class="col-sm-3 control-label">
                            Confirm New Username:
                        </label>
                        <div class="col-sm-9">
                            <input type="text" name="confirm_new_username" id="confirm_new_username" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary" name="change_username_confirmed" value="Y">
                                Change Username
                            </button>
                            <button type="reset" class="btn btn-warning">
                                Reset
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        <?php
        }
        echo '<div class="back_button"><a href="account.php">Account Settings</a></div>';
        break;

    case 'change-email':
        if (isset($_POST['change_email_confirmed']) && $_POST['change_email_confirmed'] == "Y") {
            $current_email = isset($_POST['current_email']) ? $_POST['current_email'] : null;
            $enter_new_email = isset($_POST['enter_new_email']) ? $_POST['enter_new_email'] : null;
            $confirm_new_email = isset($_POST['confirm_new_email']) ? $_POST['confirm_new_email'] : null;
            $error = false;
            if (!filter_var($current_email, FILTER_VALIDATE_EMAIL)) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>Current email address is not a valid email format.</p>';
                echo '</div>';
            }
            if (!filter_var($enter_new_email, FILTER_VALIDATE_EMAIL)) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>New email address is not a valid email format.</p>';
                echo '</div>';
            }
            if (!filter_var($confirm_new_email, FILTER_VALIDATE_EMAIL)) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>New email confirmation is not a valid email format.</p>';
                echo '</div>';
            }
            if ($_SESSION['email'] !== $current_email) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>You did not enter your current email address!</p>';
                echo '</div>';
            }
            if ($enter_new_email !== $confirm_new_email) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>Your new email address must match in both fields!</p>';
                echo '</div>';
            }
            $email_check = $Database->query("SELECT email FROM users WHERE email = :email LIMIT 1", array(":email" => $enter_new_email));
            if ($email_check == 1) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>That email address is already in use!</p>';
                echo '</div>';
            }
            if ($error === false) {
                $Database->query("UPDATE users SET email = :email WHERE user_id = :user_id LIMIT 1", array(":user_id" => $_SESSION['user_id'], ":email" => $enter_new_email));
                $_SESSION['email'] = $enter_new_email;
                echo '<div class="alert alert-success">';
                    echo '<p>Your email address has been updated!</p>';
                echo '</div>';
            }
        } else {
        ?>
            <form action="account.php?action=change-email" method="post" class="form-horizontal">
                <fieldset>
                    <legend>Change Email</legend>
                    <div class="form-group">
                        <label for="current_email" class="control-label col-sm-3">
                            Current Email:
                        </label>
                        <div class="col-sm-9">
                            <input type="email" name="current_email" id="current_email" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="enter_new_email" class="control-label col-sm-3">
                            Enter New Email:
                        </label>
                        <div class="col-sm-9">
                            <input type="email" name="enter_new_email" id="enter_new_email" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_new_email" class="control-label col-sm-3">
                            Confirm New Email:
                        </label>
                        <div class="col-sm-9">
                            <input type="email" name="confirm_new_email" id="confirm_new_email" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" name="change_email_confirmed" value="Y" class="btn btn-primary">
                                Change Email
                            </button>
                            <button type="reset" class="btn btn-warning">
                                Reset
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        <?php
        }
        echo '<div class="back_button"><a href="account.php">Account Settings</a></div>';

        break;

    case 'change-password':
        if (isset($_POST['change_password_confirmed']) && $_POST['change_password_confirmed'] === "Y") {
            if (isset($_POST['enter_current_password'])) {
                $current_password = filter_input(INPUT_POST, 'enter_current_password', FILTER_SANITIZE_STRING);
            }
            if (isset($_POST['enter_new_password'])) {
                $new_password = filter_input(INPUT_POST, 'enter_new_password', FILTER_SANITIZE_STRING);
            }
            if (isset($_POST['confirm_new_password'])) {
                $confirm_password = filter_input(INPUT_POST, 'confirm_new_password', FILTER_SANITIZE_STRING);
            }
            $error = false;
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>You have left fields blank.</p>';
                echo '</div>';
            }
            if (HASH("SHA512", $current_password) !== $user['password']) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>You did not enter your current password!</p>';
                echo '</div>';
            }
            if (HASH("SHA512", $new_password) !== HASH("SHA512", $confirm_password)) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>Your new password must match the confirmation password field!</p>';
                echo '</div>';
            }
            if (HASH("SHA512", $current_password) === HASH("SHA512", $new_password) || HASH("SHA512", $current_password) === HASH("SHA512", $confirm_password)) {
                $error = true;
                echo '<div class="alert alert-danger">';
                    echo '<p>Your new password and confirmation password must not be the same as your old password!</p>';
                echo '</div>';
            }
            if ($error === false) {
                $update_password = HASH("SHA512", $new_password);
                $Database->query("UPDATE users` SET password = :password WHERE email = :email LIMIT 1", array(':email' => $user['email'], ':password' => $update_password));
                    echo '<div class="alert alert-success">';
                        echo '<p>You have updated your password!</p>';
                    echo '</div>';
            }
        } else {
            ?>
                <form action="account.php?action=change-password" method="post" class="form-horizontal">
                    <fieldset>
                        <legend>Change Password</legend>
                        <div class="form-group">
                            <label for="enter_current_password" class="control-label col-sm-3">Current Password:</label>
                            <div class="col-sm-9">
                                <input type="password" name="enter_current_password" id="enter_current_password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="enter_new_password" class="control-label col-sm-3">New Password:</label>
                            <div class="col-sm-9">
                                <input type="password" name="enter_new_password" id="enter_new_password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_new_password" class="control-label col-sm-3">
                                Confirm Password:
                            </label>
                            <div class="col-sm-9">
                                <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" name="change_password_confirmed" id="change_password_confirmed" value="Y" class="btn btn-primary">
                                            Change Password
                                </button>
                                <button type="reset" class="btn btn-warning">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            <?php
        }
        echo '<div class="back_button">';
            echo '<a href="account.php">Account Settings</a>';
        echo '</div>';
        break;

    case 'manage-images':
        if (isset($_GET['sub-action']) && $_GET['sub-action'] == "delete_image") {
            if (isset($_GET['image_name'])) {
                $image_name = $_GET['image_name'];
                $image_owner = explode('_', $image_name);
                if ($image_owner[0] == $user['user_id']) {
                    unlink("images/user_images/{$image_name}");
                    echo '<div class="alert alert-info">';
                        echo '<p>Image deleted.</p>';
                    echo '</div>';
                    header("location: account.php?action=manage-images");
                } else {
                    echo '<div class="alert alert-danger">';
                        echo '<p>This is not your image to delete.</p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="alert alert-danger">';
                    echo '<p>You did not select an image to delete!</p>';
                echo '</div>';
            }
        }
        if (isset($_GET['sub-action']) && $_GET['sub-action'] == "set_avatar") {
            if (isset($_GET['image_name'])) {
                $image_name = $_GET['image_name'];
                $image_owner = explode('_', $image_name);
                if ($image_owner[0] == $user['user_id']) {
                    echo '<div class="alert alert-success">';
                        echo '<p>You have successfully changed your picture!</p>';
                    echo '</div>';
                    $Database->query("UPDATE users SET profile_picture = :profile_picture WHERE user_id = :user_id", array(':profile_picture' => $image_name, ':user_id' => $user['user_id']));
                }
            }
        }
        $directory = scandir("images/user_images/");
        $image_count = count($directory);
        if ($image_count > 0) {
            echo '<dl class="uploaded_images">';
            for ($i = 0; $i < $image_count; $i++) {
                $id = explode('_', $directory[$i]);
                if ($id[0] == $user['user_id']) {
                    echo '<dt> <img src="/images/user_images/' . $directory[$i] . '" alt="Uploaded Image: ' . $directory[$i] . '" width="40%" height="40%"> </dt> <dd> <a href="account.php?action=manage-images&amp;sub-action=delete_image&amp;image_name=' . $directory[$i] . '">Delete Image: ' . $directory[$i] . '</a></dd> <dd><a href="account.php?action=manage-images&amp;sub-action=set_avatar&amp;image_name=' . $directory[$i] . '">Set Image: ' . $directory[$i] . ' as Avatar Picture</a></dd>';
                }
            }
            echo '</dl>';
        } else {
            echo '<div class="alert alert-info">';
                echo '<p>You have not uploaded any images.</p>';
            echo '</div>';
        }
        echo '<div class="back_button"><a href="account.php">Account Settings</a></div>';
        break;

    case 'upload-images':
        echo '<div class="alert alert-warning">';
            echo '<p>This is still in development.</p>';
        echo '</div>';

        if (isset($_POST['upload_images']) && $_POST['upload_images'] == "Y") {
            echo '<p>You submitted the images upload form! The coder is researching methods of uploading pictures.</p>';


            if (isset($_FILES['images']) && count($_FILES['images']['name']) == 1) {
                if (isset($_FILES['images'])) {
                    //  $image = $_FILES['images']['name'];
                    //  move_uploaded_file($_FILES['images']['tmp_name'][0], "images/user_images/" . $user['user_id'] . "_" . $_FILES['images']['name'][0]);
                    //  echo "<img src='images/user_images/" . $user['user_id'] . "_" . $_FILES['images']['name'][0] . "'>";
                    //  echo 'Uploaded Image: ' . $_FILES['images']['name'][0] . ' <br>';
                    echo '<div class="alert alert-info">';
                        echo '<p>You want to upload one picture.</p>';
                    echo '</div>';
                }
            } elseif (isset($_FILES['images']) && count($_FILES['images']) > 1) {
                if ($user['user_id'] == 1) {
                    if (isset($_FILES['images'])) {
                        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                            //  move_uploaded_file($tmp_name, "images/user_images/" . $user['user_id'] . '_' . $_FILES['images']['name'][$key]);
                            //  echo "<img src='images/user_images/" . $user['user_id'] . "_" . $_FILES['images']['name'][$key] . "' style='width: 25%; height: 25%;'>";
                            //  echo 'Uploaded Image: ' . $_FILES['images']['name'][$key] . ' <br>';
                        }
                        echo '<div class="alert alert-info">';
                            echo '<p>You want to upload multiple pictures.</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="alert alert-warning">';
                        echo '<p>This is still in development.</p>';
                    echo '</div>';
                }
            }
        }
        ?>
            <form action="account.php?action=upload-images" enctype="multipart/form-data" method="post" class="form-horizontal">
                <fieldset>
                    <legend>Upload Images</legend>
                    <div class="form-group">
                        <label for="images" class="col-sm-2 control-label">
                            Upload Images:
                        </label>
                        <div class="col-sm-10">
                            <input type="file" name="images[]" id="images" accept="image/png, image/jpg, image/jpeg, image/gif" multiple class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gallery" class="col-sm-2 control-label">
                            Gallery:
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="gallery" id="gallery" list="gallery-list" class="form-control" />
                            <datalist id="gallery-list">
                                <option value="Profile Pictures">
                                    Profile Pictures
                                </option>
                                <option value="Game Pictures">
                                    Game Pictures
                                </option>
                            </datalist>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="upload_images"
                                value="Y" class="btn btn-default">
                                Upload Images
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        <?php
        echo '<div class="back_button"><a href="account.php">Account Settings</a></div>';
        break;

    default:
    ?>
        <ul class="list-group">
            <li class="list-group-item">
                <a href="account.php?action=change-username">Change Username</a>
            </li>
            <li class="list-group-item">
                <a href="account.php?action=change-email">Change Email</a>
            </li>
            <li class="list-group-item">
                <a href="account.php?action=change-password">Change Password</a>
            </li>
            <li class="list-group-item">
                <a href="account.php?action=upload-images">Upload Images</a>
            </li>
            <li class="list-group-item">
                <a href="account.php?action=manage-images">Manage Images</a>
            </li>
        </ul>
    <?php
    }
} else {
    Authenticate::notLoggedIn();
}

require_once 'includes/private_footer.php';

$contents = ob_get_contents();
ob_end_flush();
echo $contents;
