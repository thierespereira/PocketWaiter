<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage Products</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
    </head>
    <body>
        <div data-role="page" style="width=100%; margin:0;" data-theme="b">

            <?php
                include('header.php');

                if(!isset($_SESSION['user_type']) || ($_SESSION['user_type']  != 'staffadmin')) {
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }
            ?>

            <div role="main" class="ui-content">
                <a href="add_product.php" id="link" data-transition="slide" class="ui-btn ui-btn-b ui-shadow">Add a Product</a>
                <a href="staffadmin.php" id="link" data-transition="slide" class="ui-btn ui-btn-b ui-shadow">Return</a>

                <?php
                    try {
                        //Create db connection
                        include('database.php');

                        $sql = "select * from product where comp_id = ?";

                        $comp_id = $_SESSION['comp_id'];

                        $sth = $DBH->prepare($sql);
                        $sth->bindParam(1,$comp_id, PDO::PARAM_INT);
                        $sth->execute();

                        if ($sth->rowCount() > 0) {
                            echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search products..." data-inset="true"> ';
                            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                                echo '<li data-icon="edit"><a href="edit_product.php?id=' . $row['id'] . '">' .  $row['id'] . ' - ' . $row['name'] . '</a></li>';
                            }
                            echo '</ul>';
                        } else {
                            $error = 'No products.';
                        }
                    } catch(PDOException $e) {
                        $error .= $e;
                        echo $e;
                    }
                ?>

            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
