<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" language="javascript">

            function validateMyForm(form) {
                $("#erroMessage").html('');
                var ret = true;
                var re_mail = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z])+$/;

                if (!form.email.value.trim()) {
                    $("#erroMessage").append('Please enter an email.<br>');
                    ret = false;
                } else {
                    if (!re_mail.test(email.value.trim())) {
                        $("#erroMessage").append('Please enter a valid email.<br>');
                        ret = false;
                    }
                }

                if(!form.password.value.trim()) {
                    $('#erroMessage').append('Please enter a password.');
                    ret = false;
                }
                return ret;
            }
        </script>
        <style>
        #divider {
                border-left: 1px dashed;
            }
        </style>
    </head>
    <body>
        <?php

            if($_POST) {

                $code = $_POST['code'];
                $error = '';
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

        <div data-role="page" style="width=100%; margin:0;" data-theme="b">

            <?php
                include('header.php');
            ?>

            <div role="main" class="ui-content">
                <div id="erroMessage" style="color:red; background-color:#FFE4E4;">
                </div>
                <?php
                    if($_POST) {
                        if($error) {
                            echo '<div id="message" style="color:red; background-color:#FFE4E4;">';
                            echo '<center><b>' . $error . '</b></center';
                            echo '</div>';
                            echo '<br>';
                        }
                    }
                ?>
                <form action="menu.php" method="post" onsubmit="return validateMyForm(this);">
                    <center><label for="code">Enter the table code below:</label></center>
                    <input type="text" data-clear-btn="true" name="code" id="code" value="<?php echo isset($_POST['code']) ? $_POST['code'] : '' ?>">
                    <button type="submit" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b" >OK</button>
                    <a href="customer.php" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>
                </form>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
