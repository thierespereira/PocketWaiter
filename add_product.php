<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add Product</title>
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
                    var form = document.forms['add_product_form'];
                    $("#errorMessage").html('');
                    var ret = true;

                    if(!form['name'].value.trim()) {
                        $('#errorMessage').append('You must enter a name!<br>');
                        ret = false;
                    } else {
                        if(form['name'].value.trim().length > 70) {
                            $('#errorMessage').append('The name is too long. Max 70 characteres!<br>');
                            ret = false;
                        }
                    }

                    if(!form['description'].value.trim()) {
                        $('#errorMessage').append('You must enter a description!<br>');
                        ret = false;
                    } else {
                        if(form['description'].value.trim().length > 250) {
                            $('#errorMessage').append('The description is too long - Max 250 characteres!<br>');
                            ret = false;
                        }
                    }

                    if(!form['price'].value.trim()) {
                        $('#errorMessage').append('You must enter a price!<br>');
                        ret = false;
                    } else {
                        var pr = number(form['price'].value);
                        if(pr <= 0) {
                           $('#errorMessage').append('Not a valid price!<br>');
                           ret = false;
                        }
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
                        $type = $_POST['type'];
                        $comp_id = $_SESSION['comp_id'];
                        $registered = '';
                        $error = '';
                        $currfile = $_FILES['product_image']['tmp_name'];
                        $filename = $_FILES['product_image']['name'];
                        $image_type = $_FILES['product_image']['type'];
                        if($filename != '') {
                            $bin_data = fopen($currfile, 'rb');
                        }

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

                                $sql = "insert into `product` (`name`, `description`, `price`, `comp_id`, `type`, `product_image`, `image_type`) values (?,?,?,?,?,?,?);";
                                $sth = $DBH->prepare($sql);

                                $sth->bindParam(1,$name, PDO::PARAM_INT);
                                $sth->bindParam(2,$description, PDO::PARAM_INT);
                                $sth->bindParam(3,$price, PDO::PARAM_INT);
                                $sth->bindParam(4,$comp_id, PDO::PARAM_INT);
                                $sth->bindParam(5,$type, PDO::PARAM_INT);
                                $sth->bindParam(6,$bin_data, PDO::PARAM_LOB);
                                $sth->bindParam(7,$image_type, PDO::PARAM_INT);
                                $sth->execute();

                                $registered = true;

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
                            echo 'Please click <a href="manage_product.php" data-transition="slide" >here</a> to view a list of products.';
                            echo '<br>';
                            echo '</div><br>';
                        }
                    }
                ?>

                <form enctype="multipart/form-data"  name="add_product_form" data-ajax="false" action="add_product.php" method="post" onsubmit="return validateMyForm();">
                    <label for="name">Name:</label>
                    <input type="text" data-clear-btn="true" name="name" id="name" value="<?php echo (isset($_POST['name']) && !$registered) ? $_POST['name'] : '' ?>">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description"><?php echo (isset($_POST['description']) && !$registered) ? $_POST['description'] : '' ?></textarea>
                    <label for="price">Price:</label>
                    <input type="text" data-clear-btn="true" name="price" id="price" value="<?php echo (isset($_POST['price']) && !$registered) ? $_POST['price'] : '' ?>">
                    <?php
                    echo '<div class="ui-field-contain">';
                            echo '<label for="type">Type:</label>';
                            echo '<select id="type" name="type">';
                            echo '<option value="main"' . ($type == 'main' ? 'selected="selected"' : '' ) .'>Mains</option>';
                            echo '<option value="side"' . ($type == 'side' ? 'selected="selected"' : '' ) . '>Sides</option>';
                            echo '<option value="dessert"' . ($type == 'dessert' ? 'selected="selected"' : '' ) . '>Desserts</option>';
                            echo '<option value="drink"' . ($type == 'drink' ? 'selected="selected"' : '' ) .'>Drinks</option>';
                        echo '</select>';
                    echo '</div>';
                    ?>
                    <br>
                    <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                    <label for="product_image">Image:</label>
                    <input type="file" name="product_image" accept="image/*">
                    <button type="submit" data-transition="slide" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Save</button>
                    <a href="manage_product.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>
                </form>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
