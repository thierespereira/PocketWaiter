<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add Product</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" language="javascript">

            function validateMyForm(form) {
                $("#errorMessage").html('');
                var ret = true;

                if(!form.name.value.trim()) {
                    $('#errorMessage').append('You must enter a name!<br>');
                    ret = false;
                } else {
                    if(form.name.value.trim().length > 70) {
                        $('#errorMessage').append('The name is too long. Max 70 characteres!<br>');
                        ret = false;
                    }
                }

                if(!form.description.value.trim()) {
                    $('#errorMessage').append('You must enter a description!<br>');
                    ret = false;
                } else {
                    if(form.description.value.trim().length > 250) {
                        $('#errorMessage').append('The description is too long - Max 250 characteres!<br>');
                        ret = false;
                    }
                }

                if(!form.price.value.trim()) {
                    $('#errorMessage').append('You must enter a price!<br>');
                    ret = false;
                } else {
                    var pr = number(form.price.value);
                    if(pr <= 0) {
                       $('#errorMessage').append('Not a valid price!<br>');
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

            <div role="main" class="ui-content">
                <div id="errorMessage" style="background-color:#e58585;">
                </div>
                <?php
                    if(!isset($_SESSION['user_type'])) {
                        echo '<script>window.location = "index.php" </script>';
                        die;
                    }


                    if($_SESSION['user_type']  != 'staffadmin') {
                        echo '<script>window.location = "index.php" </script>';
                        die;
                    }

                    if($_POST) {
                        $name = $_POST['name'];
                        $description = $_POST['description'];
                        $price = $_POST['price'];
                        $error = '';

                        if($name == '') {
                            $error .= 'You must enter a name!<br>';
                        }

                        if($description == '') {
                            $error .= 'You must enter a description!<br>';
                        }

                        if($price == '') {
                            $error .= 'You must enter a price!<br>';
                        } else {
                            if(!is_numeric($price)) {
                                $error .= 'Not a valid price!<br>';
                            } else {
                                if($price <= 0) {
                                    $error .= 'Not a valid price!<br>';
                                }
                            }
                        }

                        if(!$error) {
                            try {
                                include('database.php');

                                $sql = "insert into `product` (`name`, `description`, `price`) values (?,?,?);";
                                $sth = $DBH->prepare($sql);

                                $sth->bindParam(1,$name, PDO::PARAM_INT);
                                $sth->bindParam(2,$description, PDO::PARAM_INT);
                                $sth->bindParam(3,$price, PDO::PARAM_INT);
                                $sth->execute();

                            } catch(PDOException $e) {
                                $error .= $e;
                            }
                        }

                        if($error) {
                            echo '<div id="message" style="color:red; background-color:#FFE4E4;">';
                            echo $error;
                            echo '<br>';
                            echo '</div><br>';
                        }

                        if(empty($registered)) {
                            echo '<div id="message" style="background-color:lightgreen;">';
                            echo 'Product added successfully.<br>';
                            echo 'Please click <a href="staffadmin.php">here</a> to view a list of products.';
                            echo '<br>';
                            echo '</div><br>';
                        }
                    }
                ?>

                <form action="add_product.php" method="post" onsubmit="return validateMyForm(this);">
                    <label for="name">Name:</label>
                    <input type="text" data-clear-btn="true" name="name" id="name" value="<?php echo (isset($_POST['name']) && !$registered) ? $_POST['name'] : '' ?>">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description"><?php echo (isset($_POST['description']) && !$registered) ? $_POST['description'] : '' ?></textarea>
                    <label for="price">Price:</label>
                    <input type="text" data-clear-btn="true" name="price" id="price" value="<?php echo (isset($_POST['price']) && !$registered) ? $_POST['price'] : '' ?>">
                    <button type="submit" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Save</button>
                    <a href="staffadmin.php" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>
                </form>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
