<?php
  $id = $_GET['id'];

  include('database.php');
  // do some validation here to ensure id is safe
  $sql = "SELECT product_image, image_type FROM product WHERE id=" . $id;
  $sth = $DBH->prepare($sql);
  $sth->execute();
  if ($sth->rowCount() > 0) {
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if($row['product_image']) {
      header("Content-type: " . $row['image_type']);
      echo $row['product_image'];
    } else {
        $filename = "images/your-product-here.png";
        $fp = fopen($filename, "r");
        $data = fread($fp, filesize($filename));
        fclose($fp);      
        header("Content-type: image/png");
        echo $data;
    }
  }
?>