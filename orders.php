<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>View Orders</title>
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

                echo '<div role="main" class="ui-content">';

                try {
                    include('database.php');

                    $sql = 'select * from `order` where `user_id` = ? order by `date_time_of_creation` desc';
                    $sth = $DBH->prepare($sql);
                    $sth->bindparam(1, $_SESSION['user_id'], PDO::PARAM_INT);

                    $sth->execute();

                    if($sth->rowcount() > 0) {
                        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                            echo '  <h4> Nº: ' . $row['id'] . ' <br> Created on: ' . $row['date_time_of_creation'] .' <br> Status: ' . $row['status'] . ' <br> Total: €' . $row['total'] .'</h4>';
                            echo '  <table data-role="table" id="movie-table-custom" data-mode="reflow" class="movie-list ui-responsive table-stripe">';
                            echo '<thead style = "background:#575757;">';
                            echo '<tr>';
                            echo '<th data-priority="1"><center>Name</center></th>';
                            echo '<th style="width:70%; background-color: dimgray;"><center>Description</center></th>';
                            echo '<th data-priority="2"><center>Price</center></th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody style = "background:#6a6a6a;">';

                            $sqlItems = "select * from `order_items` inner join `product` on product.id = order_items.product_id where order_items.order_id = " . $row['id'] . ' order by `item` asc;';
                            $sthItems = $DBH->prepare($sqlItems);
                            $sthItems->execute();

                            if($sthItems->rowcount() > 0) {
                                while($item = $sthItems->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<tr>';
                                    echo '<th><center>' . $item['name'] . '</center></th>';
                                    echo '<td><center>' . $item['description'] . '</center></td>';
                                    echo '<td><center>' . $item['price'] .'</center></td>';
                                    echo '</tr>';
                                }
                            }

                            echo '</tbody>';
                            echo '</table>';
                        }
                    }

                } catch(PDOException $e) {

                }
            ?>

                <div>
                    <a href="customer.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b ui-shadow">Return</a>
                </div>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
