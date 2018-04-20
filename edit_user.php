<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit User</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
    </head>
    <body>

        <div data-role="page" style="width=100%; margin:0;" data-theme="b">

            <script type="text/javascript" language="javascript">

                function validateMyForm() {
                    var form = document.forms['edit_user_form'];
                    $("#errorMessage").html('');
                    var ret = true;
                    var re_mail = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z])+$/;

                    if (!form['email'].value.trim()) {
                        $("#errorMessage").append('You must enter an email!<br>');
                        ret = false;
                    } else {
                        if (!re_mail.test(form['email'].value.trim())) {
                            $("#errorMessage").append('Not a valid email address!<br>');
                            ret = false;
                        }
                    }

                    if(!form['phone'].value.trim()) {
                        $('#errorMessage').append('You must enter a phone number!<br>');
                        ret = false;
                    }

                    if(!form['address'].value.trim()) {
                         $('#errorMessage').append('You must enter an address!<br>');
                        ret = false;
                    }
                    return ret;
                }

            </script>

            <?php
                include('header.php');
            ?>

            <div role="main" class="ui-content">
                <div id="errorMessage" style="background-color:#e58585;">
                </div>
                <?php
                    $success = false;
                    $error = '';
                    if(!isset($_SESSION['user_type'])) {
                        echo '<script>window.location = "index.php" </script>';
                        die;
                    }

                    if($_SESSION['user_type'] == 'customer' || $_SESSION['user_type'] == 'staffadmin' ) {
                        $user_id = $_SESSION['user_id'];
                    } else {
                        if($_SESSION['user_type']  == 'admin') {
                            if(isset($_GET['id'])) {
                                $user_id = $_GET['id'];
                            } else {
                                echo '<script>window.location = "administrator.php" </script>';
                                die;
                            }
                        } else {
                            echo '<script>window.location = "index.php" </script>';
                            die;
                        }
                    }

                    if(isset($user_id)) {
                        try {
                            //Create DB connection
                            include('database.php');

                            $sql = "select * from user where id = ?";
                            $sth = $DBH->prepare($sql);

                            $sth->bindParam(1,$user_id, PDO::PARAM_INT);

                            $sth->execute();

                            if ($sth->rowCount() > 0) {
                                $rec = $sth->fetch(PDO::FETCH_ASSOC);
                                $email = $rec['email'];
                                $password = $rec['password'];
                                $rePassword = $rec['password'];
                                $phone = $rec['phone_number'];
                                $address = $rec['address'];
                                $type = $rec['type'];
                                if($_POST) {
                                    if($_POST['email'] == '') {
                                         $error .= 'You must enter an email!<br>';
                                    } else {
                                        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))  {
                                            $error .= "Not a valid email address!<br>";
                                        }
                                    }

                                    if($_POST['phone'] == '') {
                                        $error .= 'You must enter a phone number!<br>';
                                    }
                                    if($_POST['address'] == '') {
                                        $error .= 'You must enter an address!<br>';
                                    }

                                    if(!$error) {
                                        try {
                                            $sqlUpdate = "";

                                            if($_SESSION['user_type'] == 'admin') {
                                                if(isset($_POST['reset_password'])) {
                                                    $sqlUpdate = "update `user` set `email` = ?, `password` = ?, `salt` = ?, `type` = ?, `phone_number` = ?, `address` = ? where  `id` = ?;";
                                                    $sthUpdate = $DBH->prepare($sqlUpdate);

                                                    $pass = '12345678';
                                                    $salt = 'thisisntasalt';
                                                    $passwordHashed = md5($pass . $salt);

                                                    $sthUpdate->bindParam(1, $_POST['email'], PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(2,$passwordHashed, PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(3,$salt, PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(4, $_POST['user_type'], PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(5, $_POST['phone'], PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(6, $_POST['address'], PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(7, $user_id, PDO::PARAM_INT);
                                                } else {
                                                    $sqlUpdate = "update `user` set `email` = ?, `type` = ?, `phone_number` = ?, `address` = ? where  `id` = ?;";
                                                    $sthUpdate = $DBH->prepare($sqlUpdate);

                                                    $sthUpdate->bindParam(1, $_POST['email'], PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(2, $_POST['user_type'], PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(3, $_POST['phone'], PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(4, $_POST['address'], PDO::PARAM_INT);
                                                    $sthUpdate->bindParam(5, $user_id, PDO::PARAM_INT);
                                                }
                                            } else {
                                                $sqlUpdate = "update `user` set `email` = ?, `phone_number` = ?, `address` = ? where  `id` = ?;";
                                                $sthUpdate = $DBH->prepare($sqlUpdate);

                                                $sthUpdate->bindParam(1, $_POST['email'], PDO::PARAM_INT);
                                                $sthUpdate->bindParam(2, $_POST['phone'], PDO::PARAM_INT);
                                                $sthUpdate->bindParam(3, $_POST['address'], PDO::PARAM_INT);
                                                $sthUpdate->bindParam(4, $user_id, PDO::PARAM_INT);
                                            }
                                            $sthUpdate->execute();
                                            $success = true;
                                        } catch(PDOException $e) {
                                            $error .= $e;
                                            $success = false;
                                        }
                                    }
                                }

                                if(!empty($success) and (!$error)) {
                                    echo '<script> window.location ="edit_user.php" </script>';
                                } else {

                                    if($error) {
                                        echo '<div id="message" style="color:red; background-color:#FFE4E4;">';
                                        echo $error;
                                        echo '<br>';
                                        echo '</div><br>';
                                    }
                                    echo '<form action="edit_user.php?id=' . $user_id . '' . '" method="post" name="edit_user_form" onsubmit="return validateMyForm();">';
                                    echo '<label for="email">Email:</label>';
                                    echo '<input type="text" data-clear-btn="true" name="email" id="email" value="' . $email . '">';
                                    echo '<label for="phone">Phone number:</label>';
                                    echo '<input type="text" data-clear-btn="true" name="phone" id="phone" value="' . $phone . '">';
                                    echo '<label for="address">Address:</label>';
                                    echo '<textarea name="address" id="address">' . $address . '</textarea>';

                                    if($_SESSION['user_type'] == 'admin') {
                                        echo '<div class="ui-field-contain">';
                                            echo '<label for="user_type">Account Type:</label>';
                                            echo '<select id="user_type" name="user_type">';
                                                echo '<option value="customer"' . ($type == 'customer' ? 'selected="selected"' : '' ) .'>Customer</option>';
                                                echo '<option value="admin"' . ($type == 'admin' ? 'selected="selected"' : '' ) . '>Administrator</option>';
                                                echo '<option value="staff"' . ($type == 'staff' ? 'selected="selected"' : '' ) . '>Staff Member</option>';
                                                echo '<option value="staff"' . ($type == 'staff' ? 'selected="selected"' : '' ) .'>staff Department</option>';
                                            echo '</select>';
                                        echo '</div>';

                                        include('database.php');

                                        $sql = "select id, name from company";
                                        $sth = $DBH->prepare($sql);

                                        $sth->execute();

                                        if ($sth->rowCount() > 0) {
                                            echo '<div class="ui-field-contain">';
                                                echo '<label for="company_id">Company:</label>';
                                                echo '<select id="company_id" name="company_id">';
                                                    echo '<option value="">Not Applicable</option>';
                                                    while ($row = $sth->fetch(PDO::FETCH_ASSOC))  {
                                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                                    }
                                                echo '</select>';
                                            echo '</div>';
                                        } else {
                                            $error = 'No companies registered.';
                                        }
                                    }

                                    if($_SESSION['user_type'] == 'admin') {
                                        echo '<fieldset data-role="controlgroup">';
                                        echo '<label>fieldset</label>';
                                        echo '<button data-transition="slide" class="ui-btn ui-icon-refresh ui-btn-icon-left ui-btn-b" name="reset_password" id="reset_password">Reset Password</button>';
                                        echo '</fieldset>';
                                    } else {
                                        echo '<a href="change_password.php" data-transition="slide" class="ui-btn ui-icon-edit ui-btn-icon-left ui-btn-b">Change Password</a>';
                                    }
                                    echo '<button type="submit" data-transition="slide" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Save</button>';

                                    if($_SESSION['user_type']  == 'admin') {
                                        echo '<a href="administrator.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                                    } else if($_SESSION['user_type']  == 'staffadmin') {
                                        echo '<a href="staffadmin.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                                    } else {
                                        echo '<a href="customer.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                                    }
                                    echo '</form>';
                                }
                            } else {
                                $error = 'User not found.';
                            }
                        } catch(PDOException $e) {
                            $error .= $e;
                            echo $e;
                        }
                    } else {
                        echo '<script>window.location = "index.php" </script>';
                        die;
                    }

                ?>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
