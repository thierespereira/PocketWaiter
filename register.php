<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" language="javascript">

            function validateMyForm(form) {
                $("#errorMessage").html('');
                var ret = true;
                var re_mail = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z])+$/;

                if (!form.email.value.trim()) {
                    $("#errorMessage").append('You must enter an email!<br>');
                    ret = false;
                } else {
                    if (!re_mail.test(form.email.value.trim())) {
                        $("#errorMessage").append('Not a valid email address!<br>');
                        ret = false;
                    } else {
                        if(form.email.value.trim().length > 100) {
                            $("#errorMessage").append('Email - Maximum 100 characteres!<br>');
                            ret = false;
                        }
                    }
                }

                if(!form.password.value.trim()) {
                    $('#errorMessage').append('You must enter a password!<br>');
                    ret = false;
                } else {
                    if(form.password.value.trim().length < 6) {
                        $('#errorMessage').append('The password is too short - Minimum 6 characteres!<br>');
                        ret = false;
                    }

                    if(!form.rePassword.value.trim()) {
                        $('#errorMessage').append('You must fill in "re-enter password" field!<br>');
                        ret = false;
                    } else {
                        if(form.password.value.trim() != form.rePassword.value.trim()) {
                            $('#errorMessage').append('The password is not a match!<br>');
                            ret = false;
                        }
                    }
                }

                if(!form.phone.value.trim()) {
                    $('#errorMessage').append('You must enter a phone number!<br>');
                    ret = false;
                } else {
                    if(form.phone.value.trim().length > 20) {
                        $('#errorMessage').append('The phone number is too long - Maximum 20 characteres!<br>');
                        ret = false;
                    }
                }

                if(!form.address.value.trim()) {
                     $('#errorMessage').append('You must enter an address!<br>');
                    ret = false;
                } else {
                    if(form.address.value.trim().length > 200) {
                        $('#errorMessage').append('The address is too long - Maximum 150 characteres!<br>');
                        ret = false;
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
            ?>
        <?php

        if($_POST) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $rePassword = $_POST['rePassword'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $comp_id = '';
            if(isset($_POST['comp_id'])) {
                $comp_id = $_POST['comp_id'];
            }
            if($comp_id == ''){
                $comp_id = null;
            }
            $type = 'customer';
            if(isset($_POST['type'])) {
                $type = $_POST['type'];
            }

            $error = '';

            if($email == '') {
                 $error .= 'You must enter an email address!<br>';
            }
            else {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error .= "Not a valid email address!<br>";
                }
            }

            if($password == '') {
                $error .= 'You must enter a password!<br>';
            }

            if($rePassword == '') {
                $error .= 'You must fill in "re-enter password" field!<br>';
            }

            if(($password != '') && ($rePassword != '')) {
                if($password != $rePassword) {
                    $error .= 'The password is not a match!';
                }
            }

            if($phone == '') {
                $error .= 'You must enter a phone number!<br>';
            }
            if($address == '') {
                $error .= 'You must enter an address!<br>';
            }


            if(!$error) {
                try {
                    include('database.php');

                    $sql = "select email from user where email = ?";
                    $sth = $DBH->prepare($sql);

                    $sth->bindParam(1,$email, PDO::PARAM_INT);


                    $sth->execute();

                    if ($sth->rowCount() > 0) {
                        $error = 'The email is already registered in the system!<br>';
                        $error .= 'Please enter a different email!';
                    } else {
                        $sql = "insert into `user` (`email`, `password`, `salt`, `type`, `phone_number`, `address`, `comp_id`) values (?, ?, ?, ?, ?, ?, ?);";
                        $sth = $DBH->prepare($sql);

                        $salt = 'thisisntasalt';
                        $passwordHashed = md5($password . $salt);

                        $sth->bindParam(1, $email, PDO::PARAM_INT);
                        $sth->bindParam(2, $passwordHashed, PDO::PARAM_INT);
                        $sth->bindParam(3, $salt, PDO::PARAM_INT);
                        $sth->bindParam(4, $type, PDO::PARAM_INT);
                        $sth->bindParam(5, $phone, PDO::PARAM_INT);
                        $sth->bindParam(6, $address, PDO::PARAM_INT);
                        $sth->bindParam(7, $comp_id, PDO::PARAM_INT);

                        $sth->execute();
                    }
                } catch(PDOException $e) {
                    echo $e;
                    die;
                }
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
                                if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
                                    echo '<script>window.location = "administrator.php" </script>';
                                } else {
                                    echo '<script>window.location = "index.php" </script>';
                                }
                            }
                        }
                    ?>

                <form action="register.php" method="post" onsubmit="return validateMyForm(this)">
                    <label for="email">Email:</label>
                    <input type="text" data-clear-btn="true" name="email" id="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
                    <label for="password">Password:</label>
                    <input type="password" data-clear-btn="true" name="password" id="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>" autocomplete="off">
                    <label for="rePassword">Re-enter password:</label>
                    <input type="password" data-clear-btn="true" name="rePassword" id="rePassword" value="<?php echo isset($_POST['rePassword']) ? $_POST['rePassword'] : '' ?>" autocomplete="off">
                    <label for="phone">Phone number:</label>
                    <input type="text" data-clear-btn="true" name="phone" id="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : '' ?>">
                    <label for="textarea">Address:</label>
                    <textarea name="address" id="address"><?php echo isset($_POST['address']) ? $_POST['address'] : '' ?></textarea>

                    <?php
                        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
                            echo '<div class="ui-field-contain">';
                                    echo '<label for="type">Account Type:</label>';
                                    echo '<select id="type" name="type">';
                                    echo '<option value="customer"' . ($type == 'customer' ? 'selected="selected"' : '' ) .'>Customer</option>';
                                    echo '<option value="admin"' . ($type == 'admin' ? 'selected="selected"' : '' ) . '>Administrator</option>';
                                    echo '<option value="adminstaff"' . ($type == 'adminstaff' ? 'selected="selected"' : '' ) . '>Staff Member</option>';
                                    echo '<option value="staff"' . ($type == 'staff' ? 'selected="selected"' : '' ) .'>Staff</option>';
                                echo '</select>';
                            echo '</div>';

                                try {
                                    include('database.php');

                                    $sql = "select id, name from company";
                                    $sth = $DBH->prepare($sql);

                                    $sth->execute();

                                    if ($sth->rowCount() > 0) {
                                        echo '<div class="ui-field-contain">';
                                            echo '<label for="comp_id">Company:</label>';
                                            echo '<select id="comp_id" name="comp_id">';
                                                echo '<option value="">Not Applicable</option>';
                                            while ($row = $sth->fetch(PDO::FETCH_ASSOC))  {
                                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                            }
                                            echo '</select>';
                                        echo '</div>';
                                    } else {
                                        $error = 'No companies registered.';
                                    }
                                } catch(PDOException $e) {
                                    $error .= $e;
                                    echo $e;
                                }
                        }
                     ?>

                    <button type="submit" data-transition="slide" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Register</button>

                    <?php
                        if(!isset($_SESSION['user_type'])) {
                            echo '<a href="index.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                        } else if($_SESSION['user_type']  == 'admin') {
                            echo '<a href="administrator.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                        } else {
                            echo '<a href="index.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                        }
                    ?>
                </form>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
