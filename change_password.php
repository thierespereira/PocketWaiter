<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Change Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" language="javascript">

            function validateMyForm(form) {
                $("#errorMessage").html('');
                var ret = true;
                var re_mail = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z])+$/;

                if (!form.currentPassword.value.trim()) {
                    $("#errorMessage").append('Enter your current password.<br>');
                    ret = false;
                }

                if(!form.password.value.trim()) {
                    $('#errorMessage').append('Enter a password.<br>');
                    ret = false;
                } else {
                    if(form.password.value.trim().length < 6) {
                        $('#errorMessage').append('The password is too short - Minimum 6 characteres!<br>');
                        ret = false;
                    }

                    if(!form.rePassword.value.trim()) {
                        $('#errorMessage').append('You must fill in the "re-enter password" field.<br>');
                        ret = false;
                    } else {
                        if(form.password.value.trim() != form.rePassword.value.trim()) {
                            $('#errorMessage').append('The password is not a match.<br>');
                            ret = false;
                        }
                    }
                }
                return ret;
            }
        </script>
    </head>
    <body>

        <div data-role="page" style="width=100%; margin:0;" data-theme="b">
            <?php
                include('header.php');

                if($_POST) {

                    if(!isset($_SESSION['user_id'])) {
                        echo '<script>window.location = "login.php"</script>';
                        die;
                    }

                    try {
                        $user_id = $_SESSION['user_id'];
                        $updated = false;

                        //Create DB connection
                        include('database.php');

                        $sql = "select * from user where id = ?";
                        $sth = $DBH->prepare($sql);
                        $sth->bindParam(1,$user_id, PDO::PARAM_INT);
                        $sth->execute();

                        if ($sth->rowCount() > 0) {
                            $rec = $sth->fetch(PDO::FETCH_ASSOC);
                            $password = $rec['password'];

                            $_currentPassword = $_POST['currentPassword'];
                            $salt = 'thisisntasalt';
                            $passwordHashed = md5($_currentPassword . $salt);
                            $_password = $_POST['password'];
                            $_rePassword = $_POST['rePassword'];
                            $error = '';

                            if($passwordHashed != $password) {
                                $error = 'Current Password does not match your password';
                            } else {
                                if($_password == '') {
                                    $error = 'Enter a password.<br>';
                                }

                                if($_rePassword == '') {
                                    $error .= 'Enter the re-enter password.<br>';
                                }

                                if(($_password != '') && ($_rePassword != '')) {
                                    if($_password != $_rePassword) {
                                        $error .= 'The password is not a match!';
                                    }
                                }

                                if(!$error) {
                                    $salt = 'thisisntasalt';
                                    $passwordHashed = md5($_password . $salt);

                                    $sql = "update `user` set `password` = ?, `salt` = ? where  `id` = ?;";
                                    $sth = $DBH->prepare($sql);

                                    $sth->bindParam(1,$passwordHashed, PDO::PARAM_INT);
                                    $sth->bindParam(2,$salt, PDO::PARAM_INT);
                                    $sth->bindParam(3,$user_id, PDO::PARAM_INT);

                                    $sth->execute();

                                    session_destroy();
                                    $updated = true;
                                }
                            }
                        }
                        else {
                            $error .= 'User not found';
                        }
                    }
                    catch(PDOException $e) {
                        $error .= $e;
                    }
                }
            ?>

            <div role="main" class="ui-content">
                <div id="errorMessage" style="color:red; background-color:#FFE4E4;">
                </div>
                <?php
                    if($_POST) {
                        if($error) {
                            echo '<div id="message" style="color:red; background-color:#FFE4E4;">';
                            echo $error;
                            echo '<br>';
                            echo '</div><br>';
                        } else {
                            if(!empty($updated)) {
                                echo '<script>window.location="login.php"</script>';
                            }
                        }
                    }
                ?>
                <form action="change_password.php" method="post" onsubmit="return validateMyForm(this);">
                    <label for="currentPassword">Current Password:</label>
                    <input type="Password" data-clear-btn="true" name="currentPassword" id="currentPassword" value="" autocomplete="off">
                    <label for="password">Password:</label>
                    <input type="password" data-clear-btn="true" name="password" id="password" value="" autocomplete="off">
                    <label for="rePassword">Re-enter Password:</label>
                    <input type="password" data-clear-btn="true" name="rePassword" id="rePassword" value="" autocomplete="off">
                    <button type="submit" data-transition="slide" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Save</button>
                </form>
            </div>

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->
        </div>

    </body>
</html>
