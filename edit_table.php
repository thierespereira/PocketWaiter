<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Product</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" language="javascript">

            function validateMyForm(form) {
                $("#errorMessage").html('');
                var ret = true;

                if(!form.name.value.trim()) {
                    $('#errorMessage').append('You must enter a name!<br>');
                    ret = false;
                }

                if(!form.description.value.trim()) {
                    $('#errorMessage').append('You must enter a description!<br>');
                    ret = false;
                }

                if(!form.price.value.trim()) {
                    $('#errorMessage').append('You must enter a price!<br>');
                    ret = false;
                } else {
                    var pr = number(form.price.value);
                    if(pr <= 0) {
                       $('#errorMessage').append('Not a valid price!<br>');
                       ret = false;
                    }
                }

                return ret;
            }
        </script>
    </head>
    <body>
        <div data-role="page" style="width=100%; margin:0;" data-theme="b">

            <?php
                include('header.php');
            ?>

            <div role="main" class="ui-content">
                <div id="errorMessage" style="background-color:#e58585;">
                </div>
                <?php
                    if(!isset($_SESSION['user_type'])) {
                        echo '<script>window.location = "index.php" </script>';
                        die;
                    }


                    if($_SESSION['user_type']  != 'staffadmin') {
                        echo '<script>window.location = "index.php" </script>';
                        die;
                    }

                    include('database.php');

                    if($_POST) {
                        $id = $_POST['id'];
                        $code = $_POST['code'];
                        $status = $_POST['status'];
                        $error = '';

                        if($code == '') {
                            $error .= 'You must enter a code!<br>';
                        }

                        if(!$error) {
                            try {
                                $sql = "update `comptable` set `code` = ?, `status`= ? where id= ? ";
                                $sth = $DBH->prepare($sql);

                                $sth->bindParam(1,$code, PDO::PARAM_INT);
                                $sth->bindParam(2,$status, PDO::PARAM_INT);
                                $sth->bindParam(3,$_GET['id'], PDO::PARAM_INT);

                                $sth->execute();

                            } catch(PDOException $e) {
                                $error .= $e;
                            }
                        }

                        if($error) {
                            echo '<div id="message" style="color:red; background-color:#FFE4E4;">';
                            echo $error;
                            echo '<br>';
                            echo '</div><br>';
                        }

                        if(empty($registered)) {
                            echo '<div id="message" style="background-color:lightgreen;">';
                            echo 'Table edited successfully.<br>';
                            echo 'Please click <a href="manage_table.php" data-transition="slide" >here</a> to view a list of tables.';
                            echo '<br>';
                            echo '</div><br>';
                        }

                    } else {
                        if(isset($_GET['id'])) {
                            $sql = "select * from comptable where id = ?";

                            $sth = $DBH->prepare($sql);
                            $sth->bindParam(1,$_GET['id'], PDO::PARAM_INT);
                            $sth->execute();

                            if ($sth->rowCount() > 0) {
                                $rec = $sth->fetch(PDO::FETCH_ASSOC);
                                $id = $rec['id'];
                                $code = $rec['code'];
                                $status = $rec['status'];

                            }
                        }
                    }
                ?>

                <form enctype="multipart/form-data"  data-ajax="false" action="edit_table.php?id=<?php echo $_GET['id'] ?>"  method="post" onsubmit="return validateMyForm(this);">
                    <label for="id">ID:</label>
                    <input type="text" name="id" id="id" value="<?php echo isset($id) ? $id : '' ?>" readonly>
                    <label for="code">Code:</label>
                    <input type="text" data-clear-btn="true" name="code" id="code" value="<?php echo isset($code) ? $code : '' ?>">

                    <?php
                        if($_SESSION['user_type'] == 'staffadmin') {
                            echo '<div class="ui-field-contain">';
                                echo '<label for="status">Status:</label>';
                                echo '<select id="status" name="status">';
                                    echo '<option value="online"' . ($status == 'online' ? 'selected="selected"' : '' ) .'>Online</option>';
                                    echo '<option value="offline"' . ($status == 'offline' ? 'selected="selected"' : '' ) . '>Offline</option>';
                                echo '</select>';
                            echo '</div>';
                        }
                    ?>
                    <button type="submit" data-transition="slide" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Save</button>
                    <a href="manage_table.php" data-transition="slide" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>
                </form>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
