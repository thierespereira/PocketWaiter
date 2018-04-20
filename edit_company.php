<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Company</title>
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
                    var form = document.forms['edit_company_form'];
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
                if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }
            ?>

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

                            $sql = "select * from company where id = ?";
                            $sth = $DBH->prepare($sql);

                            $sth->bindParam(1,$user_id, PDO::PARAM_INT);

                            $sth->execute();

                            if ($sth->rowCount() > 0) {
                                $rec = $sth->fetch(PDO::FETCH_ASSOC);
                                $id = $rec['id'];
                                $name = $rec['name'];
                                $email = $rec['email'];
                                $desc = $rec['desc'];
                                $website = $rec['website'];
                                $phone = $rec['phone'];
                                $address = $rec['address'];
                                $logo = $rec['logo'];
                                $logo_type = $rec['logo_type'];
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
                                            $logoSql = '';
                                            if($_FILES['logo']['tmp_name']) {
                                                $currfile = $_FILES['logo']['tmp_name'];
                                                $filename = $_FILES['logo']['name'];
                                                $logo_type = $_FILES['logo']['type'];
                                                $logoSql = " ,`logo` = ?, `logo_type` = ? ";
                                                $bin_data = fopen($currfile, 'rb');
                                            }
                                            $sqlUpdate = "";

                                            $sqlUpdate = "update `company` set `name` = ?, `email` = ?, `desc` = ?, `website` = ?, `phone` = ?, `address` = ? " . $logoSql . " where  `id` = ?;";
                                            $sthUpdate = $DBH->prepare($sqlUpdate);
                                            $sthUpdate->bindParam(1, $_POST['name'], PDO::PARAM_INT);
                                            $sthUpdate->bindParam(2, $_POST['email'], PDO::PARAM_INT);
                                            $sthUpdate->bindParam(3, $_POST['desc'], PDO::PARAM_INT);
                                            $sthUpdate->bindParam(4, $_POST['website'], PDO::PARAM_INT);
                                            $sthUpdate->bindParam(5, $_POST['phone'], PDO::PARAM_INT);
                                            $sthUpdate->bindParam(6, $_POST['address'], PDO::PARAM_INT);
                                            if($_FILES['logo']['tmp_name']) {
                                                $sthUpdate->bindParam(7, $bin_data, PDO::PARAM_LOB);
                                                $sthUpdate->bindParam(8, $logo_type, PDO::PARAM_INT);
                                                $sthUpdate->bindParam(9, $user_id, PDO::PARAM_INT);
                                            } else {
                                                $sthUpdate->bindParam(7, $user_id, PDO::PARAM_INT);
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
                                    echo '<script> window.location ="edit_company.php" </script>';
                                } else {
                                    if($error) {
                                        echo '<div id="message" style="color:red; background-color:#FFE4E4;">';
                                        echo $error;
                                        echo '<br>';
                                        echo '</div><br>';
                                    }
                                    echo '<form enctype="multipart/form-data" name="edit_company_form" data-ajax="false" action="edit_company.php?id=' . $user_id . '' . '" method="post" onsubmit="return validateMyForm(this);">';
                                    echo '<label for="name">Name:</label>';
                                    echo '<input type="text" data-clear-btn="true" name="name" id="name" value="' . $name . '">';
                                    echo '<label for="email">Email:</label>';
                                    echo '<input type="text" data-clear-btn="true" name="email" id="email" value="' . $email . '">';
                                    echo '<label for="desc">Description:</label>';
                                    echo '<input type="text" data-clear-btn="true" name="desc" id="desc" value="' . $desc . '">';
                                    echo '<label for="website">Website:</label>';
                                    echo '<input type="text" data-clear-btn="true" name="website" id="website" value="' . $website . '">';
                                    echo '<label for="phone">Phone number:</label>';
                                    echo '<input type="text" data-clear-btn="true" name="phone" id="phone" value="' . $phone . '">';
                                    echo '<label for="address">Address:</label>';
                                    echo '<textarea name="address" id="address">' . $address . '</textarea>';
                                    echo '<center><img src="getCompanyImage.php?id=' . $id . '" height="30%" width="30%"/></center>';
                                    echo '<br>';
                                    echo '<label for="logo">Logo:</label>';
                                    echo '<input type="file" name="logo" accept="image/*">';
                                }
                                    echo '<button type="submit" data-transition="slide" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Save</button>';
                                    echo '<a href="administrator.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                                    echo '</form>';
                            } else {
                                $error = 'Company not found.';
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
