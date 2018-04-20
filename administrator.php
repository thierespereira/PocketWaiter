<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Administrator</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
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

                    echo '<a href="register.php" data-transition="slide" class="ui-btn ui-btn-b ui-shadow">Create a New User</a>';
                    echo '<a href="register_company.php" data-transition="slide" class="ui-btn ui-btn-b ui-shadow">Create a New Company</a>';

                    // DB
                    try {
                        include('database.php');

                        echo '<br>';
                        echo '<br>';

                        $sql = "select * from user";
                        $sth = $DBH->prepare($sql);

                        $sth->execute();

                        if ($sth->rowCount() > 0) {
                            echo '<div><center><b>Users</b></center></div>';
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
                        } else {
                            $error = 'No users registered.';
                        }

                        echo '<br>';
                        echo '<br>';
                        echo '<br>';

                        $sql = "select * from company";
                        $sth = $DBH->prepare($sql);

                        $sth->execute();

                        if ($sth->rowCount() > 0) {
                            echo '<div><center><b>Companies</b></center></div>';
                            echo '<table data-role="table" id="movie-table-custom" data-mode="reflow" class="movie-list ui-responsive table-stripe">';
                            echo '<thead>';
                            echo '    <tr">';
                            echo '      <th data-priority="1">ID</th>';
                            echo '      <th data-priority="2">Name</th>';
                            echo '      <th style="width:40%">Description</th>';
                            echo '      <th data-priority="3">Phone Number</th>';
                            echo '    </tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr class="row" onclick="location.href=\'edit_company.php?id=' . $row['id'] . '\'">';
                                echo '<th>' . $row['id'] . '</th>';
                                echo '<td>' . $row['name']. '</td>';
                                echo '<td>' . $row['desc'] . '</td>';
                                echo '<td>' . $row['phone'] . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            $error = 'No companies registered.';
                        }

                    } catch(PDOException $e) {
                        $error .= $e;
                        echo $e;
                    }
                    echo '<form action="index.php" method="post"><button type="submit" data-transition="slide"  id="hLogout" name="hLogout" class="ui-btn ui-icon-power ui-btn-icon-left ui-btn-b">Log out</button></form>';


                ?>

            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
