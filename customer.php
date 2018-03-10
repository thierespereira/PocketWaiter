<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Customer</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
    </head>
    <body>

        <div data-role="page" style="width=100%; margin:0;" data-theme="b">
            <?php
                include('header.php');
            ?>

            <?php
                if($_POST) {
                    session_destroy();
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }



                if(!isset($_SESSION['user_type'])) {
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }


                if($_SESSION['user_type']  != 'customer') {
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }
            ?>

            <div role="main" class="ui-content">
                 <form action="customer.php" method="post">
                    <?php echo '<a href="check_out.php" class="ui-btn ui-icon-shop ui-btn-icon-left ui-btn-b">Checkout</a>' ?>
                    <a href="edit_user.php" class="ui-btn ui-icon-gear ui-btn-icon-left ui-btn-b">Edit My Profile</a>
                    <a href="products.php" class="ui-btn ui-icon-grid ui-btn-icon-left ui-btn-b" >View Products</a>
                    <a href="orders.php" class="ui-btn ui-icon-bullets ui-btn-icon-left ui-btn-b" >My Orders</a>
                    <form action="index.php" method="post"><button type="submit" id="hLogout" name="hLogout" class="ui-btn ui-icon-power ui-btn-icon-left ui-btn-b">Log out</button></form>
                </form>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
