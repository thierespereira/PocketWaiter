<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Products</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
    </head>
    <body>
        <div data-role="page" style="width=100%; margin:0;" data-theme="b">

            <?php
                include('header.php');
            ?>

            <div role="main" class="ui-content">
                <a href="check_out.php" class="ui-btn ui-icon-shop ui-btn-icon-left ui-btn-b">Checkout</a>
                <div>
                    <b><center>Mains</center></b>
                    <?php
                        try {

                            if(isset($_GET['code'])){
                                $code = $_GET['code'];
                                $_SESSION['code'] = $code;
                            }

                            include('database.php');

                            $sql = "select product.* from product inner join comptable on product.comp_id = comptable.comp_id where comptable.code = ? and product.`type` = 'main';";
                            $sth = $DBH->prepare($sql);

                            $sth->bindParam(1,$code, PDO::PARAM_INT);

                            $sth->execute();

                            if ($sth->rowCount() > 0) {
                                echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search products..." data-inset="true"> ';
                                while ($row = $sth->fetch(PDO::FETCH_ASSOC))  {
                                    echo '<li><a href="view_menu_item.php?id=' . $row['id'] . '&code=' . $code . '" data-transition="slide">' . $row['name'] . ' - €' . $row['price'] . '</a></li>';
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
                </div>

                <div>
                    <b><center>Sides</center></b>
                    <?php
                        try {

                            if(isset($_GET['code'])){
                                $code = $_GET['code'];
                                $_SESSION['code'] = $code;
                            }

                            include('database.php');

                            $sql = "select product.* from product inner join comptable on product.comp_id = comptable.comp_id where comptable.code = ? and product.`type` = 'side';";
                            $sth = $DBH->prepare($sql);

                            $sth->bindParam(1,$code, PDO::PARAM_INT);

                            $sth->execute();

                            if ($sth->rowCount() > 0) {
                                echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search products..." data-inset="true"> ';
                                while ($row = $sth->fetch(PDO::FETCH_ASSOC))  {
                                    echo '<li><a href="view_menu_item.php?id=' . $row['id'] . '&code=' . $code . '" data-transition="slide">' . $row['name'] . ' - €' . $row['price'] . '</a></li>';
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
                </div>

                <div>
                    <b><center>Drinks</center></b>
                    <?php
                        try {

                            if(isset($_GET['code'])){
                                $code = $_GET['code'];
                                $_SESSION['code'] = $code;
                            }

                            include('database.php');

                            $sql = "select product.* from product inner join comptable on product.comp_id = comptable.comp_id where comptable.code = ? and product.`type` = 'drink';";
                            $sth = $DBH->prepare($sql);

                            $sth->bindParam(1,$code, PDO::PARAM_INT);

                            $sth->execute();

                            if ($sth->rowCount() > 0) {
                                echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search products..." data-inset="true"> ';
                                while ($row = $sth->fetch(PDO::FETCH_ASSOC))  {
                                    echo '<li><a href="view_menu_item.php?id=' . $row['id'] . '&code=' . $code . '" data-transition="slide">' . $row['name'] . ' - €' . $row['price'] . '</a></li>';
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
                </div>

                <div>
                    <b><center>Desserts</center></b>
                    <?php
                        try {

                            if(isset($_GET['code'])){
                                $code = $_GET['code'];
                                $_SESSION['code'] = $code;
                            }

                            echo '';

                            include('database.php');

                            $sql = "select product.* from product inner join comptable on product.comp_id = comptable.comp_id where comptable.code = ? and product.`type` = 'dessert';";
                            $sth = $DBH->prepare($sql);

                            $sth->bindParam(1,$code, PDO::PARAM_INT);

                            $sth->execute();

                            if ($sth->rowCount() > 0) {
                                echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search products..." data-inset="true"> ';
                                while ($row = $sth->fetch(PDO::FETCH_ASSOC))  {
                                    echo '<li><a href="view_menu_item.php?id=' . $row['id'] . '&code=' . $code . '" data-transition="slide">' . $row['name'] . ' - €' . $row['price'] . '</a></li>';
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
                </div>

            </div><!-- /content -->
            <div>
                <?php
                if(!isset($_SESSION['user_type'])) {
                    echo '<a href="index.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                } else if($_SESSION['user_type']  == 'customer') {
                    echo '<a href="customer.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                } else {
                    echo '<a href="index.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                }
                ?>
            </div>
            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
