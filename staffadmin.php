<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Administrator</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
    </head>
    <body>
        <div data-role="page" style="width=100%; margin:0;" data-theme="b">

            <?php
                include('header.php');

                if(!isset($_SESSION['user_type']) || ($_SESSION['user_type']  != 'staffadmin')) {
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }
            ?>

            <div role="main" class="ui-content">
                <a href="register.php" data-transition="slide" class="ui-btn ui-icon-plus ui-btn-icon-left ui-btn-b ui-shadow">Create New User</a>
                <a href="manage_product.php" data-transition="slide" class="ui-btn ui-icon-gear ui-btn-icon-left ui-btn-b ui-shadow">Manage Products</a>
                <a href="manage_table.php" data-transition="slide" class="ui-btn ui-icon-gear ui-btn-icon-left ui-btn-b">Manage Tables</a>
                <a href="edit_user.php" data-transition="slide" class="ui-btn ui-icon-gear ui-btn-icon-left ui-btn-b">Edit My Profile</a>

                <form action="index.php" method="post"><button type="submit" id="hLogout" name="hLogout" data-transition="slide" class="ui-btn ui-icon-power ui-btn-icon-left ui-btn-b">Log out</button></form>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
