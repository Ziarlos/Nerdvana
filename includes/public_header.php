<?php declare(strict_types=1);

require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Nerdvana - A Social Zone</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/public.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('[data-toggle=offcanvasleft]').on("click", function() {
                $('.row-offcanvas-right').removeClass('active');
                $('.row-offcanvas-left').toggleClass('active');
            });
        });
    </script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <div class="navbar navbar-fixed-top navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <p class="pull-left visible-xs">
                    <button id="offcanvasleft" class="btn btn-xs btn-default" type="button" data-toggle="offcanvasleft"><i class="glyphicon glyphicon-chevron-left"></i> Sign In</button>
                </p>
                <button class="navbar-toggle" type="button" data-target=".navbar-collapse" data-toggle="collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                    <a class="navbar-brand" href="javascript:void(0);">Nerdvana</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="/index.php">Home</a></li>
                    <li><a href="/index.php?action=terms_of_service">Terms of Service</a></li>
                    <li><a href="/index.php?action=register">Register</a></li>
                    <li><a href="/index.php?action=lost-password">Lost Password</a></li>
                    <li><a href="/public_profile.php">Profiles</a></li>
                </ul>
            </div> <!-- /.nav-collapse -->
        </div> <!-- /.container -->
    </div> <!-- /.navbar -->

    <div class="container">
        <div class="row row-offcanvas row-offcanvas-left">
            <div class="col-xs-6 col-sm-3 col-lg-3 sidebar-offcanvas" id="sidebarLeft" role="navigation">
                <div class="well sidebar-nav clearfix">
            <aside class="sign-in">
                  <form action="/login.php?action=login" method="post">
                    <div class="form-group">
                        <label for="login-email" class="control-label">Email</label>
                        <input type="text" name="login-email" id="login-email" placeholder="Email" class="form-control">
                    </div>
                    <div class="form-group">
                    <label for="login-password" class="control-label">Password</label>
                    <input type="password" name="login-password" id="login-password" placeholder="Password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Sign in</button>
                  </form>
            </aside>
            <aside class="users-online">
                <h3>Users Online</h3>
                <?php
                $currently_online = $User->getActiveUsers();
                if (isset($currently_online)) {
                    foreach ($currently_online as $online_user) {
                        echo '<a href="public_profile.php?action=view&amp;user_id=' . $online_user['user_id'] . '">' . $online_user['user_name'] . '</a> (' . $online_user['user_id'] . ')<br>';
                    }
                } else {
                    ?> <p>No users are currently online.</p> <?php
                }
                ?>
            </aside>
         </div><!--/.well -->
            </div><!--/#sidebarLeft col-xs-6 col-sm-4 col-lg-3 sidebar-offcanvas-->

            <div class="col-xs-12 col-sm-9 col-lg-9">
                <div class="well clearfix">
