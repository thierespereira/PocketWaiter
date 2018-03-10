<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>

        <style>
            .row:hover {
                background-color: lightgray;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div data-role="page" style="width=100%; margin:0;" data-theme="b">

            <?php
                include('header.php');
            ?>

            <div role="main" class="ui-content">
                <?php
                    if(!isset($_SESSION['user_type'])) {
                        echo '<script>window.location = "index.php" </script>';
                        die;
                    }


                    if($_SESSION['user_type']  != 'admin') {
                        echo '<script>window.location = "index.php" </script>';
                        die;
                    }


                    // DB
                    try {
                        include('database.php');

                        $sql = "select * from user";
                        $sth = $DBH->prepare($sql);

                        $sth->execute();

                        if ($sth->rowCount() > 0) {
                            echo '<table data-role="table" id="movie-table-custom" data-mode="reflow" class="movie-list ui-responsive table-stripe">';
                            echo '<thead>';
                            echo '    <tr">';
                            echo '      <th data-priority="1">ID</th>';
                            echo '      <th style="width:40%">Email</th>';
                            echo '      <th data-priority="2">Password</th>';
                            echo '      <th data-priority="3">Type</th>';
                            echo '    </tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr class="row" onclick="location.href=\'edit_user.php?id=' . $row['id'] . '\'">';
                                echo '<th>' . $row['id'] . '</th>';
                                echo '<td>' . $row['email']. '</td>';
                                echo '<td>' . $row['password'] . '</td>';
                                echo '<td>' . $row['type'] . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                            echo '<form action="index.php" method="post"><button type="submit" id="hLogout" name="hLogout" class="ui-btn ui-icon-power ui-btn-icon-left ui-btn-b">Log out</button></form>';
                        } else {
                            $error = 'No users.';
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
