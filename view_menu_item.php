<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>View Product</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
        <style>
            .orange {
                background-color: #f7931e;
            }
        </style>
    </head>
    <body>
        <div data-role="page" style="width=100%; margin:0;" data-theme="b">
            <?php
                if($_POST) {

                    $cart = array();
                    if(isset($_SESSION['cart'])) {
                        $cart = $_SESSION['cart'];
                    }

                    $cart[] = $_GET['id'];
                    $_SESSION['cart'] = $cart;
                }

                include('header.php');
            ?>

            <div role="main" class="ui-content">
                <div id="product" data-theme="b">
                <?php
                    try {
                        $productId = $_GET['id'];
                        $code = $_SESSION['code'];

                        include('database.php');

                        $sql = 'select * from product where id = ?';
                        $sth = $DBH->prepare($sql);
                        $sth->bindparam(1, $productId, PDO::PARAM_INT);

                        $sth->execute();

                        if($sth->rowcount() > 0) {
                            $rec = $sth->fetch(PDO::FETCH_ASSOC);
                            echo '<center><p>';
                            echo '<strong>' . $rec['name'] . ' - €' . $rec['price'] . '</strong><br>';
                            echo $rec['description'] . '</center></p>';
                            echo '<p></p>';
                            echo '<form action="view_menu_item.php?id=' . $productId .'" method="post">';
                            echo '<center><button type="submit" id="purchase" name="purchase" data-transition="slide" class="ui-btn ui-icon-shop ui-btn-icon-left ui-shadow orange">Add to Cart</button></center>';
                            echo '</form>';
                        }
                    }
                    catch(PDOException $e) {
                        $error = $e;
                        echo $e;
                    }
                ?>
                </div>
            </div><!-- /content -->
            <div>
                <?php
                    echo '<a href="menu.php?code=' . $code . '" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b ui-shadow">Return</a>';
                ?>
            </div>
            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
