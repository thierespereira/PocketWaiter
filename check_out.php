<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Checkout</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
    </head>
    <body>
        <div data-role="page" style="width=100%; margin:0;" data-theme="b">

            <?php
                include('header.php');

                if(!isset($_SESSION['user_type'])) {
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }


                if($_SESSION['user_type']  != 'customer') {
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }
            ?>

            <div role="main" class="ui-content">
                <?php
                    include('database.php');
                    if(isset($_SESSION['cart'])) {
                        $cart = $_SESSION['cart'];

                        if($_POST) {
                            if(isset($_POST['delete'])) {
                                $ind = $_POST['itemNum'];
                                echo $_POST['itemNum'];
                                unset($cart[$ind - 1]);
                                $cart = array_values($cart);
                                $_SESSION['cart'] = $cart;
                            } else {
                                if(isset($_POST['confirm'])) {
                                    try {
                                        $sql = "insert into `pocketwaiter`.`order` (`user_id`, `total`, `date_time_of_creation`, `status`) values ('" . $_SESSION['user_id'] . "', '0.00', Now(), 'Open');";

                                        $sth = $DBH->prepare($sql);

                                        $sth->execute();


                                        $sql = "SELECT LAST_INSERT_ID();";
                                        $sth = $DBH->prepare($sql);
                                        $sth->execute();
                                        $rec = $sth->fetch(PDO::FETCH_ASSOC);
                                        $OrderId = $rec['LAST_INSERT_ID()'];
                                        $total = 0;
                                        $item = 1;
                                        foreach ($cart as $value) {
                                            $sql = "select price from product where id = " . $value;
                                            $sth = $DBH->prepare($sql);

                                            $sth->execute();

                                            if ($sth->rowCount() > 0) {
                                                $rec = $sth->fetch(PDO::FETCH_ASSOC);
                                                $total = $total + $rec['price'];
                                                $sql2 = "insert into `pocketwaiter`.`order_items` (`order_id`, `item`, `product_id`) values ('" . $OrderId . "', '" .  $item . "', '" . $value . "');";
                                                $sth2 = $DBH->prepare($sql2);
                                                $sth2->execute();
                                                $item = $item + 1;
                                            }
                                        }

                                        $sql = "update `pocketwaiter`.`order` set `total`='" . $total ."' where  `id`=" . $OrderId . ";";

                                        $sth = $DBH->prepare($sql);
                                        $sth->execute();

                                        $cart = array();
                                        $_SESSION['cart'] = $cart;

                                        echo 'Order was placed succesfully. ';
                                        echo '<a href="orders.php">View your Orders</a>';
                                        die;
                                    } catch(PDOException $e) {
                                        echo $e;
                                        die;
                                    }
                                }
                            }
                        }

                        if(count($cart) > 0) {
                            $total = 0;
                            $item = 1;
                            echo '<table data-role="table" id="movie-table-custom" data-mode="reflow" class="movie-list ui-responsive table-stripe">';
                            echo '<thead>';
                            echo '    <tr">';
                            echo '      <th data-priority="1">Item</th>';
                            echo '      <th style="width:40%">Product</th>';
                            echo '      <th data-priority="2">Price</th>';
                            echo '      <th data-priority="3">Delete</th>';
                            echo '    </tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            foreach ($cart as $value)  {
                                $sql = "select * from product where id = " . $value;
                                $sth = $DBH->prepare($sql);

                                $sth->execute();

                                if ($sth->rowCount() > 0) {

                                    $rec = $sth->fetch(PDO::FETCH_ASSOC);
                                    $total = $total + $rec['price'];
                                    echo '<tr>';
                                    echo '<th>' . $item . '</th>';
                                    echo '<td>' . $rec['name']. '</td>';
                                    echo '<td>€' . $rec['price'] . '</td>';
                                    echo '<td><form action="check_out.php" method="post"><input type="hidden" name="itemNum" value="' . $item . '"/><button type="submit" name="delete" id="delete" class="ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-notext"></button></form></td>';
                                    echo '</tr>';
                                    $item = $item + 1;
                                }
                            }
                            echo '<tr>';
                                echo '<th></th>';
                                echo '<td><strong>Total</strong></td>';
                                echo '<td><strong>€' . $total . '</strong></td>';
                                echo '<td></td>';
                                echo '</tr>';
                            echo '</tbody>';
                            echo '</table>';
                            echo '<form action="check_out.php" method="post"><button type="submit" data-transition="slide"  id="confirm" name="confirm" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Confirm Order</button></form>';
                        } else {
                            echo '<center>Your cart is empty!</center>';
                        }
                    } else {
                        echo '<center>Your cart is empty!</center>';
                    }
                ?>

            </div><!-- /content -->
            <div>
                <a href="customer.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b ui-shadow">Return</a>
            </div>
            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
