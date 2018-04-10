<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pocket Waiter - No More Queues!</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>
        <script>
            function validateMyForm(form) {
                $("#erroMessage").html('');
                var ret = true;

                if(!form.code.value.trim()) {
                    $('#erroMessage').append('Please enter a table code.');
                    ret = false;
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

            <?php
                if($_POST){
                    $code = $_POST['code'];

                    try{
                        include('database.php');

                        $sql = "select * from comptable where comptable.code = ?;";
                        $sth = $DBH->prepare($sql);

                        $sth->bindParam(1,$code, PDO::PARAM_INT);

                        $sth->execute();

                        if($sth->rowCount() > 0){
                            $row = $sth->fetch(PDO::FETCH_ASSOC);
                            echo '<script>window.location = "menu.php?code=' . $row['code'] . '" </script>';
                            die;
                        }

                    } catch(PDOException $e) {
                        $error .= $e;
                        echo $e;
                    }

                }

            ?>

            <div role="main" class="ui-content">
                <form action="index.php" method="post" onsubmit="return validateMyForm(this);">
                    <center><label for="code">Enter table code</label></center>
                    <input type="text" data-clear-btn="true" name="code" id="code" value="<?php echo isset($_POST['code']) ? $_POST['code'] : '' ?>">
                    <button type="submit" data-transition="slide" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b" >OK</button>
                </form>
                <br>
                <center>Or log in to continue</center>
                <a href="login.php" id="link" data-transition="slide" class="ui-btn ui-btn-b ui-shadow">Login</a>
                <a href="register.php" data-transition="slide" class="ui-btn ui-btn-b ui-shadow">Register</a>
            </div><!-- /content -->

            <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
