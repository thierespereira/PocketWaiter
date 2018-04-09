<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Order</title>
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


                if($_SESSION['user_type']  != 'kitchen') {
                    echo '<script>window.location = "index.php" </script>';
                    die;
                }

                if(!isset($_GET['id'])) {
                    echo '<script>window.location = "kitchen.php"</script>';
                    die;
                }

                try {
                    include('database.php');

                    if($_POST) {
                        if(isset($_POST['update_status'])) {
                            $sql = 'update `order` set `status`= "Ready" where  `id` = ?;';
                            $sth = $DBH->prepare($sql);
                            $sth->bindParam(1, $_GET['id'], PDO::PARAM_INT);
                            $sth->execute();
                        } else if(isset($_POST['printPDF'])) {

                            echo '<script>window.location = "print_order.php?id=' . $_GET['id'] . '"</script>';
                            die;
                        }
                    }

                    $sql = "select * from `order` where `id` = ?";

                    $sth = $DBH->prepare($sql);
                    $sth->bindParam(1,$_GET['id'], PDO::PARAM_INT);
                    $sth->execute();

                    if ($sth->rowCount() > 0) {
                        $rec = $sth->fetch(PDO::FETCH_ASSOC);
                        $status = $rec['status'];

                        echo '<ul data-role="listview" data-inset="true" data-divider-theme="a">';
                        echo '	<li data-role="list-divider"><h4> Nº: ' . $rec['id'] . ' <br> Created on: ' . $rec['date_time_of_creation'] .' <br> Status: ' . $rec['status']  . '  <li data-role="list-divider">Total: €' . $rec['total'] .'</li></h4></li>';


                        $sqlItems = "select * from order_items inner join product on product.id = order_items.product_id where order_items.order_id = " . $rec['id'] . ';';
                        $sthItems = $DBH->prepare($sqlItems);
                        $sthItems->execute();
                        if($sthItems->rowcount() > 0) {
                            while($item = $sthItems->fetch(PDO::FETCH_ASSOC)) {
                                echo '<li>' . $item['item'] . ' - ' . $item['name'] . ' - €' . $item['price'] .'</li>';
                            }
                        }
                        echo '</ul>';
                    }
                } catch(PDOException $e) {
                    $error .= $e;
                }

                echo '<form action="edit_orders_on.php?id=' . $_GET['id'] . '" method="post">';
                if($status == 'Being Prepared') {
                    echo '<center><button id="update_status" name="update_status" class="ui-btn ui-icon-tag ui-btn-icon-left ui-btn-b">Mark as "Ready!"</button></center>';
                    echo '<a href="print_order.php?id=' . $_GET['id'] . '" target="_blank" class="ui-btn ui-icon-action ui-btn-icon-left ui-btn-b">Print Receipt</a>';
                } else {
                    echo '<a href="print_order.php?id=' . $_GET['id'] . '" target="_blank" class="ui-btn ui-icon-action ui-btn-icon-left ui-btn-b">Print Receipt</a>';
                }
                echo '<a href="kitchen.php" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b">Return</a>';
                echo '</form>';
            ?>
        </div>
    </body>
 </html>
