<div data-role="header">
    <center>
        <p><img src="images/pw_logo.png"></p>
    </center>
    <div style="text-align:right; padding-right: 1em;">
        <?php
            if(isset($_POST['hLogout'])) {
                session_destroy();
                echo '<script>window.location = "index.php"</script>';
            }

            if(isset($_SESSION['user_type'])) {
                if($_SESSION['user_type'] == 'customer') {
                    $numItems = 0;
                    if(isset($_SESSION['cart'])) {
                        $cart = $_SESSION['cart'];
                        $numItems = count($cart);
                    }
                }
            }
        ?>
    </div>
</div>
<!-- /header -->
