<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Product</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" language="javascript">

            function validateMyForm(form) {
                $("#errorMessage").html('');
                var ret = true;

                if(!form.name.value.trim()) {
                    $('#errorMessage').append('You must enter a name!<br>');
                    ret = false;
                }

                if(!form.description.value.trim()) {
                    $('#errorMessage').append('You must enter a description!<br>');
                    ret = false;
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

                    include('database.php');

                    if($_POST) {
                        $id = $_POST['id'];
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
                                $logoSql = '';
                                if($_FILES['product_image']['tmp_name']) {
                                    $currfile = $_FILES['product_image']['tmp_name'];
                                    $filename = $_FILES['product_image']['name'];
                                    $image_type = $_FILES['product_image']['type'];
                                    $logoSql = " ,`product_image` = ?, `image_type` = ? ";
                                    $bin_data = fopen($currfile, 'rb');
                                }

                                $sql = "update `product` set `name` = ?, `description`= ?, `price`= ? " . $logoSql . " where `id`= ?;";
                                $sth = $DBH->prepare($sql);

                                $sth->bindParam(1,$name, PDO::PARAM_INT);
                                $sth->bindParam(2,$description, PDO::PARAM_INT);
                                $sth->bindParam(3,$price, PDO::PARAM_INT);

                                if($_FILES['product_image']['tmp_name']) {
                                    $sth->bindParam(4,$bin_data, PDO::PARAM_LOB);
                                    $sth->bindParam(5,$image_type, PDO::PARAM_INT);
                                    $sth->bindParam(6,$_GET['id'], PDO::PARAM_INT);
                                } else {
                                    $sth->bindParam(4,$_GET['id'], PDO::PARAM_INT);
                                }
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

                    } else {
                        if(isset($_GET['id'])) {
                            $sql = "select * from product where id = ?";

                            $sth = $DBH->prepare($sql);
                            $sth->bindParam(1,$_GET['id'], PDO::PARAM_INT);
                            $sth->execute();

                            if ($sth->rowCount() > 0) {
                                $rec = $sth->fetch(PDO::FETCH_ASSOC);
                                $id = $rec['id'];
                                $name = $rec['name'];
                                $description = $rec['description'];
                                $price = $rec['price'];

                            }
                        }
                    }
                ?>

                <form enctype="multipart/form-data"  data-ajax="false" action="edit_product.php?id=<?php echo $_GET['id'] ?>"  method="post" onsubmit="return validateMyForm(this);">
                    <label for="id">ID:</label>
                    <input type="text" name="id" id="id" value="<?php echo isset($id) ? $id : '' ?>" readonly>
                    <label for="name">Name:</label>
                    <input type="text" data-clear-btn="true" name="name" id="name" value="<?php echo isset($name) ? $name : '' ?>">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description"><?php echo isset($description) ? $description : '' ?></textarea>
                    <label for="price">Price:</label>
                    <input type="text" data-clear-btn="true" name="price" id="price" value="<?php echo isset($price) ? $price : '' ?>">
                    <?php
                        echo '<center><img src="getProductImage.php?id=' . $id . '"></center>';
                        echo '<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />';
                        echo '<label for="product_image">Image:</label>';
                        echo '<input type="file" name="product_image" accept="image/*">';
                    ?>
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
