<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage Table</title>
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
                <a href="add_table.php" class="ui-btn ui-icon-gear ui-btn-icon-left ui-btn-b">Add Table</a>
                <a href="manage_table.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>
                <?php
                    try {
                        //Create db connection
                        include('database.php');
                        
                        $comp_id = $_SESSION['comp_id'];

                        $sql = "select * from comptable where comp_id = ?";
                        $sth = $DBH->prepare($sql);

                        $sth->bindParam(1,$comp_id, PDO::PARAM_INT);

                        $sth->execute();

                        if ($sth->rowCount() > 0) {
                            echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search tables..." data-inset="true"> ';
                            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                                echo '<li data-icon="edit"><a href="edit_table.php?id=' . $row['id'] . '">' .  $row['code'] . '</a></li>';
                            }
                            echo '</ul>';
                        } else {
                            $error = 'No tables.';
                        }
                    } catch(PDOException $e) {
                        $error .= $e;
                        echo $e;
                    }
                ?>
                <form action="index.php" method="post"><button type="submit" id="hLogout" name="hLogout" class="ui-btn ui-icon-power ui-btn-icon-left ui-btn-b">Log out</button></form>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
