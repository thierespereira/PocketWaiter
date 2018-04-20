<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register Company</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
    </head>
    <body>
        <div data-role="page" style="width=100%; margin:0;" data-theme="b">
            <script type="text/javascript" language="javascript">
                function validateMyForm() {
                    var form = document.forms["register_company_form"];

                    $("#errorMessage").html('');
                    var ret = true;
                    var re_mail = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z])+$/;

                    if(!form['name'].value.trim()) {
                        $("#errorMessage").append('You must enter a name!<br>');
                        ret = false;
                    } else {
                        if(form['name'].value.trim().length > 100) {
                            $('#errorMessage').append('The name is too long - Maximum 100 characteres!<br>');
                            ret = false;
                        }
                    }

                    if (!form['email'].value.trim()) {
                        $("#errorMessage").append('You must enter an email!<br>');
                        ret = false;
                    } else {
                        if (!re_mail.test(form['email'].value.trim())) {
                            $("#errorMessage").append('Not a valid email address!<br>');
                            ret = false;
                        } else {
                            if(form['email'].value.trim().length > 100) {
                                $("#errorMessage").append('Email - Maximum 100 characteres!<br>');
                                ret = false;
                            }
                        }
                    }

                    if(!form['phone'].value.trim()) {
                        $('#errorMessage').append('You must enter a phone number!<br>');
                        ret = false;
                    } else {
                        if(form['phone'].value.trim().length > 20) {
                            $('#errorMessage').append('The phone number is too long - Maximum 20 characteres!<br>');
                            ret = false;
                        }
                    }

                    if(!form['address'].value.trim()) {
                         $('#errorMessage').append('You must enter an address!<br>');
                        ret = false;
                    } else {
                        if(form['address'].value.trim().length > 200) {
                            $('#errorMessage').append('The address is too long - Maximum 150 characteres!<br>');
                            ret = false;
                        }
                    }
                    return ret;
                }
            </script>

            <?php
                if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }
            ?>

            <?php
                include('header.php');
            ?>
        <?php
        if($_POST) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $desc = $_POST['desc'];
            $website = $_POST['website'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $error = '';
            $currfile = $_FILES['logo']['tmp_name'];
            $filename = $_FILES['logo']['name'];
            $logo_type = $_FILES['logo']['type'];
            if($filename != '') {
                $bin_data = fopen($currfile, 'rb');
            }

            if($name == '') {
                $error .= 'You must enter a name for the company!<br>';
            }

            if($email == '') {
                 $error .= 'You must enter an email address!<br>';
            } else {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error .= "Not a valid email address!<br>";
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

                        $sql = "select email from company where email = ?";
                        $sth = $DBH->prepare($sql);

                        $sth->bindParam(1,$email, PDO::PARAM_INT);


                        $sth->execute();

                        if ($sth->rowCount() > 0) {
                            $error = 'The email is already registered in the system!<br>';
                            $error .= 'Please enter a different email!';
                        } else {
                            $sql = "insert into `company` (`name`, `email`, `desc`, `website`, `phone`, `address`, `logo`, `logo_type`) values (?, ?, ?, ?, ?, ?, ?, ?);";
                            $sth = $DBH->prepare($sql);

                            $sth->bindParam(1, $name, PDO::PARAM_INT);
                            $sth->bindParam(2, $email, PDO::PARAM_INT);
                            $sth->bindParam(3, $desc, PDO::PARAM_INT);
                            $sth->bindParam(4, $website, PDO::PARAM_INT);
                            $sth->bindParam(5, $phone, PDO::PARAM_INT);
                            $sth->bindParam(6, $address, PDO::PARAM_INT);
                            $sth->bindParam(7, $bin_data, PDO::PARAM_LOB);
                            $sth->bindParam(8, $logo_type, PDO::PARAM_INT);

                            $sth->execute();
                        }
                    } catch(PDOException $e) {
                        echo $e;
                        die;
                    }
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
                                echo '<script>window.location = "administrator.php" </script>';
                            }
                        }
                    ?>

                <form enctype="multipart/form-data"  data-ajax="false" name="register_company_form" action="register_company.php" method="post" onsubmit="return validateMyForm();">
                    <label for="name">Name:</label>
                    <input type="text" data-clear-btn="true" name="name" id="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>">
                    <label for="email">Email:</label>
                    <input type="text" data-clear-btn="true" name="email" id="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
                    <label for="desc">Description:</label>
                    <input type="text" data-clear-btn="true" name="desc" id="desc" value="<?php echo isset($_POST['desc']) ? $_POST['desc'] : '' ?>">
                    <label for="website">Website:</label>
                    <input type="text" data-clear-btn="true" name="website" id="website" value="<?php echo isset($_POST['website']) ? $_POST['website'] : '' ?>">
                    <label for="phone">Phone number:</label>
                    <input type="text" data-clear-btn="true" name="phone" id="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : '' ?>">
                    <label for="address">Address:</label>
                    <textarea name="address" id="address"><?php echo isset($_POST['address']) ? $_POST['address'] : '' ?></textarea>
                    <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />

                    <label for="logo">Logo:</label>
                    <input type="file" name="logo" accept="image/*">

                    <button type="submit" name="insert" id="insert" data-transition="slide" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Register</button>
                    <a href="administrator.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>
                </form>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
