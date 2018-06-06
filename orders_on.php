<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>View Orders</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
    </head>
    <body>
        <div data-role="page" style="width=100%; margin:0;" data-theme="b">
            <div role="main" class="ui-content">
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

                    try {
                        include('database.php');

                        $sql = 'select * from `order` where status = "Being Prepared" order by date_time_of_creation;';
                        $sth = $DBH->prepare($sql);
                        $sth->execute();

                        if($sth->rowcount() > 0) {
                            echo '<ul data-role="listview" data-inset="true">';
                            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

                                echo '  <li><a href="edit_orders_on.php?id=' . $row['id'] . '" data-transition="slide"> Nº: ' . $row['id'] . ' <br> Created on: ' . $row['date_time_of_creation'] .' <br> Status: ' . $row['status'] . ' <br> Table No.: ' . $row['table_id'] . '</a></li>';

                            }
                            echo '</ul>';
                        } else {
                            echo '<center>There are no orders being prepared.</center>';
                        }

                    } catch(PDOException $e) {

                    }
                echo '<a href="kitchen.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>';
                ?>

            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
