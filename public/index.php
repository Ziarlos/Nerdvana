<?php declare(strict_types=1);

ob_start();
session_start();

require_once '../includes/public_header.php';

$action = isset($_GET['action']) ? $_GET['action'] : null;

switch ($action) {
case 'lost-password':
    if (isset($_POST['confirm_reset_password']) && $_POST['confirm_reset_password'] == "Y") {
        $lost_password_email = isset($_POST['lost_password_email']) ? $_POST['lost_password_email'] : null;
        $error = false;

        if (!filter_var($lost_password_email, FILTER_VALIDATE_EMAIL)) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>Please enter a valid email.</p>';
            echo '</div>';
        }

        if ($error === false) {
            $token = hash("SHA256", md5(uniqid('', true)));
            $url = 'http://www.ashesrpg.com/index.php?action=reset-password&email=' . $lost_password_email . '&token=' . $token;
            echo '<div class="alert alert-info">';
                echo '<p>You have been sent a message with a link to change your password!</p>';
            echo '</div>';
            $to = $lost_password_email;
            $subject = '(Website) Password Reset';
            $message = 'You have requested to reset your password on (Website)!<br> To reset your password, click or copy and paste the link below.<br><a href="' . $url . '">' . $url . '</a>';
            $headers = 'From: Bruce<bruce@vps.fromdusktillcon.com>' . "\r\n";
            $headers .= 'Content-type: text/html; charset="UTF-8"' . "\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);

            $Database->query("INSERT INTO users_password_reset (email, token, requested_timestamp) VALUES (:email, :token, NOW())", array(':email' => $lost_password_email, ':token' => $token));
        }

    } else {
        ?>
            <p>This form will send you a link to reset your password.</p>
            <form action="index.php?action=lost-password" method="post" id="external_password_reset_entry_form">
                <fieldset>
                    <legend>Lost Password</legend>
                    <div class="form-group">
                        <label for="lost_password_email" class="control-label col-sm-3">Email</label>
                        <div class="col-sm-9">
                            <input type="email" name="lost_password_email" id="lost_password_email" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" name="confirm_reset_password" id="confirm_reset_password" value="Y" class="btn btn-primary">Reset</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        <?php
    }
    break;

case 'reset-password':
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : null;
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (isset($_POST['confirmed_password_reset']) && $_POST['confirmed_password_reset'] == "Y") {
        $reset_email = isset($_POST['reset_password_email']) ? $_POST['reset_password_email'] : null;
        $reset_token = isset($_POST['reset_password_token']) ? $_POST['reset_password_token'] : null;
        $reset_password_initial = isset($_POST['reset_password_initial']) ? $_POST['reset_password_initial'] : null;
        $reset_password_confirm = isset($_POST['reset_password_confirm']) ? $_POST['reset_password_confirm'] : null;
        $error = false;
        $check = $Database->query("SELECT email, token, requested_timestamp FROM users_password_reset WHERE email = :email OR token = :token", array(':email' => $reset_email, ':token' => $reset_token));

        if ($Database->count() == 0) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>Sorry, that email and token do not exist in the database.</p>';
            echo '</div>';
        }

        if (time() - strtotime($check['requested_timestamp']) > 86400) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>Sorry, that token has expired. You have 24 hours to reset your password from your request time.</p>';
            echo '</div>';
        }

        if ($reset_password_initial !== $reset_password_confirm) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>Your passwords must be the same!</p>';
            echo '</div>';
        }

        if ($error === false) {
            $password = HASH("SHA512", $reset_password_initial);
            try {
                $Database->query("UPDATE users SET password = :password WHERE email = :reset_email LIMIT 1", array(':reset_email' => $reset_email, ':password' => $password));
                $Database->query("DELETE FROM users_password_reset WHERE email = :reset_email AND token = :reset_token LIMIT 1", array(':reset_email' => $reset_email, ':reset_token' => $reset_token));
                echo '<div class="alert alert-success">';
                    echo '<p>You have updated your password!</p>';
                echo '</div>';
            }
            catch (PDOException $e) {
                echo '<div class="alert alert-danger">';
                    echo '<p>Could not change password.</p>';
                echo '</div>';
            }
        }
    } else {
    ?>
    <p>Reset your password here</p>
    <form action="index.php?action=reset-password" method="post" id="external_password_reset_form" class="form-horizontal">
        <fieldset>Reset Password</fieldset>
        <div class="form-group">
            <label for="reset_password_email" class="control-label col-sm-3">
                Email:
            </label>
            <div class="col-sm-9">
                <input type="email" name="reset_password_email" id="reset_password_email" value="<?php echo $email; ?>" readonly class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="reset_password_token" class="control-label col-sm-3">
                Token:
            </label>
            <div class="col-sm-9">
                <input type="text" name="reset_password_token" id="reset_password_token" value="<?php echo $token; ?>" readonly class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="reset_password_initial" class="control-label col-sm-3">
                Enter Password:
            </label>
            <div class="col-sm-9">
                <input type="password" name="reset_password_initial" id="reset_password_initial" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="reset_password_confirm" class="control-label col-sm-3">
                Confirm Password:
            </label>
            <div class="col-sm-9">
                <input type="password" name="reset_password_confirm" id="reset_password_confirm" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" name="confirmed_password_reset" id="confirmed_password_reset" value="Y" class="btn btn-primary">
                    Reset Password
                </button>
            </div>
        </div>
    </form>
    <?php
    }
    break;

case 'register':
    if (isset($_GET['confirm_register']) && $_GET['confirm_register'] == "Y") {

        $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
        $register_email = isset($_POST['register-email']) ? $_POST['register-email'] : '';
        $register_password = isset($_POST['register-password']) ? $_POST['register-password'] : '';
        $confirm_password = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';
        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
        $error = false;

        if (!empty($user_name) && (strlen($user_name) < 4 || strlen($user_name) > 16)) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>Your name must be between 4 and 16 characters. You put in '.strlen($user_name).' characters.</p>';
            echo '</div>';
        }

        if (empty($user_name)) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>You must enter a name!</p>';
            echo '</div>';
        }

        if (!preg_match("/[a-zA-Z0-9]/", $user_name)) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>Your name cannot have anything other than alphabetical or numerical characters in it.</p>';
            echo '</div>';
        }

        if (!filter_var($register_email, FILTER_VALIDATE_EMAIL)) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>You must enter a valid email address.</p>';
            echo '</div>';
        }

        if (empty($register_password)) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>You need to enter a password!</p>';
            echo '</div>';
        }

        if (empty($confirm_password)) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>Please re-enter your password to confirm it.</p>';
            echo '</div>';
        }

        if ($register_password != $confirm_password) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>Your passwords do not match!</p>';
            echo '</div>';
        }

        if (!preg_match("/(M|F)/", $gender)) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>You either did not choose one of the gender choices provided or you did not choose a gender.</p>';
            echo '</div>';
        }

        $check = $Database->prepare("SELECT * FROM users WHERE email = :email OR user_name = :user_name");
        $check->execute(array(':email' => $register_email, ':user_name' => $user_name));

        if ($check->rowCount() == 1) {
            $error = true;
            echo '<div class="alert alert-warning">';
                echo '<p>That name or email address is already in use.</p>';
            echo '</div>';
        }

        if ($error === false) {
            $password = HASH("SHA512", $register_password);

            $add = $Database->prepare("INSERT INTO users (user_id, user_name, email, password, gender) values ('', :user_name, :email, :password, :gender)");
            $add->execute(array(':user_name' => $user_name, ':email' => $register_email, ':password' => $password, ':gender' => $gender));

            echo '<div class="alert alert-success">';
                echo '<p>You have successfully registered!</p>';
            echo '</div>';

            $to = $register_email;
            $subject = 'Welcome to (Website)!';
            $message = 'You have successfully registered your user name '.$user_name.' on (Website)!';

            mail($to, $subject, $message);
        }
    } else {
    ?>
    <form action="index.php?action=register&amp;confirm_register=Y" method="post" id="registration_form" class="form-horizontal">
        <fieldset>
            <legend>
                Register
            </legend>
            <div class="form-group">
                <label for="user_name" class="control-label col-sm-4">
                    Name:
                </label>
                <div class="col-sm-8">
                    <input type="text" name="user_name" id="user_name" required class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="register-email" class="control-label col-sm-4">
                    Email:
                </label>
                <div class="col-sm-8">
                    <input type="email" name="register-email" id="register-email" required class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="register-password" class="control-label col-sm-4">
                    Password:
                </label>
                <div class="col-sm-8">
                    <input type="password" name="register-password" id="register-password" required class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="confirm-password" class="control-label col-sm-4">
                    Confirm Password:
                </label>
                <div class="col-sm-8">
                    <input type="password" name="confirm-password" id="confirm-password" required class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="gender" class="control-label col-sm-4">
                    Gender:
                </label>
                <div class="col-sm-8">
                    <select name="gender" id="gender" required class="form-control">
                        <option value="">Choose Gender</option>
                        <option value="F">Female</option>
                        <option value="M">Male</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="submit" class="btn btn-primary">
                        Register
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
    break;

case "terms_of_service":
    echo "<p>Terms of service goes here!</p>";
    break;

default:
?>
<div class="jumbotron">
<h2> Welcome to the Gaming Forum!</h2>
<h3>In Progress:</h3>
<dl>
    <dt>Events</dt>
        <dd>Calendar for Events</dd>
        <dd>Notifications from Calendar</dd>
    <dt>Forums</dt>
        <dd>Forum Script</dd>
        <dd>Forum Class</dd>
        <dd>Forum Formatting</dd>
        <dd>Forum Unread Message Indicator</dd>
        <dd>Forum Subscriber Feature</dd>
    <dt>Users</dt>
        <dd>Profile Pictures</dd>
        <dd>Player Information</dd>
        <dd>User Location</dd>
        <dd>User Age</dd>
        <dd>Games Played</dd>
        <dd>Social Groups in games</dd>
        <dd>Occupation</dd>
        <dd>Picture/Video Gallery</dd>
        <dd>Accounts Page</dd>
</dl>
<p>Account Options in progress.</p>
<p style="color: rgba(255, 0, 0, 1.0);"><span style="font-size: 20px; color: rgba(255, 0, 0, 1.0)">This website is for training/education purposes until further notice.</span></p>
</div>
<?php
}

require_once ROOT . '/includes/public_footer.php';
ob_end_flush();
