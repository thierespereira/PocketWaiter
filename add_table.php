<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add Table</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/jquery.mobile-1.4.5.min.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js"></script>

        <script type="text/javascript" language="javascript">

            function validateMyForm(form) {
                $("#errorMessage").html('');
                var ret = true;

                if(!form.table_code.value.trim()) {
                    $('#errorMessage').append('You must enter a code!<br>');
                    ret = false;
                } else {
                    if(form.table_code.value.trim().length > 50) {
                        $('#errorMessage').append('The code is too long. Max 50 characteres!<br>');
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
                    if(!isset($_SESSION['user_type']) || ($_SESSION['user_type']  != 'staffadmin')) {
                        echo '<script>window.location = "index.php" </script>';
                        die;
                    }

                    if($_POST) {
                   		$table_code = $_POST['table_code'];
                   		$comp_id = $_SESSION['comp_id'];
	               		$error = '';
	               		$registered = '';
                   		if($table_code == '') {
                            $error .= 'You must enter a table code!<br>';
                        }


                        if(!$error) {
                        	try {
                                include('database.php');

                                $sql = "select id from comptable where code = ?";
			                    $sth = $DBH->prepare($sql);

			                    $sth->bindParam(1,$table_code, PDO::PARAM_INT);

			                    $sth->execute();

			                    if ($sth->rowCount() > 0) {
			                        $error = 'The code is already registered in the system!<br>';
			                        $error .= 'Please enter a different code!';
			                    } else {
	                                $sql = "insert into `comptable` (`code`, `comp_id`) values (?,?);";
	                                $sth = $DBH->prepare($sql);

	                                $sth->bindParam(1,$table_code, PDO::PARAM_INT);
	                                $sth->bindParam(2,$comp_id, PDO::PARAM_INT);
	                                $sth->execute();
                            	}
                            } catch(PDOException $e) {
                                $error .= $e;
                            }
                        }

                        if($error) {
                            echo '<div id="message" style="color:red; background-color:#FFE4E4;">';
                            echo $error;
                            echo '<br>';
                            echo '</div><br>';
                        } else {
                            echo '<div id="message" style="background-color:lightgreen;">';
                            echo 'Table added successfully.<br>';
                            echo 'Please click <a href="manage_table.php" data-transition="slide" >here</a> to view a list of tables.';
                            echo '<br>';
                            echo '</div><br>';
                        }
                    }
                ?>

                <form action="add_table.php" method="post" onsubmit="return validateMyForm(this);">
                    <label for="table_code">Code:</label>
                    <input type="text" data-clear-btn="true" name="table_code" id="table_code" value="<?php echo (isset($_POST['table_code']) && !$registered) ? $_POST['table_code'] : '' ?>">
                    <button type="submit" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b">Save</button>
                    <a href="manage_table.php" class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-btn-b" >Return</a>
                </form>
            </div><!-- /content -->

             <div data-role="footer">
                <center><h5 style="color:#B0B0B0;">This web application was developed by PVP.</h5></center>
            </div><!-- /footer -->

        </div><!-- /page -->
    </body>
</html>
