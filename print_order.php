<?php
    session_start();
    if(!isset($_SESSION['user_type'])) {
        echo '<script>window.location = "index.php"</script>';
        die;
    }

    if($_SESSION['user_type'] != 'delivery') {
        echo '<script>window.location = "index.php"</script>';
        die;
    }

    if(!isset($_GET['id'])) {
        echo '<script>window.location = "index.php"</script>';
        die;
    }
require('fpdf.php');
$html = '';
$headerColour = array( 100, 100, 100 );
$textColour = array( 120, 120, 120 );
$dateCreated = '';
$customer = '';
$address = '';
$phone = '';
class PDF extends FPDF {
	// Page header
function Header() {
    // Logo
    $this->Image('images/b_logo.png',85,10,45);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(40);
    // Line break
    $this->Ln(35);
}

// Load data
function LoadData($file) {
	$data = array();
	try {
        include('database.php');

        $sqlItems = "select * from order_items inner join product on product.id = order_items.product_id where order_items.order_id = " . $_GET['id'] . ';';
        $sthItems = $DBH->prepare($sqlItems);
        $sthItems->execute();
        if($sthItems->rowCount() > 0) {                                
            while($item = $sthItems->fetch(PDO::FETCH_ASSOC)) {
                $line = $item['id'] . ';' . $item['name'] . ';' . $item['price'];
                $data[] = explode(';',trim($line));
            }
        }
    } catch(PDOException $e) {
    	$error .= $e;
    	echo $e;
    }
    return $data;
}

// Colored table
function FancyTable($header, $data) {
    // Colors, line width and bold font
    $this->Ln();
    $this->SetFillColor(120,120,120);
    $this->SetTextColor(255);
    $this->SetDrawColor(255,255,255);
    $this->SetLineWidth(1);
    $this->SetFont('','B');
    // Header
    $w = array(40, 100, 40);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(240,240,240);
    $this->SetTextColor(0);
    $this->SetFont('','I');
    // Data
    $fill = false;
    foreach($data as $row) {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}

try {
        include('database.php');

        $sql = "select * from `order` inner join user on order.user_id = user.id where order.id = ?";
        $sth = $DBH->prepare($sql);
        $sth->bindParam(1,$_GET['id'], PDO::PARAM_INT);
		$sth->execute();
        if($sth->rowCount() > 0) {
        	$rec = $sth->fetch(PDO::FETCH_ASSOC);
        	$dateCreated = $rec['date_time_of_creation'];
			$customer = $rec['email'];
			$address = $rec['address'];
            $total = $rec['total'];
            $phone = $rec['phone_number'];
        }
      } catch(PDOException $e) {

      }

$pdf = new PDF('P', 'mm', 'A4');
// Column headings
$header = array('ID', 'NAME', 'PRICE');
// Data loading
$data = $pdf->LoadData('list.txt');
$pdf->AddPage();
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->SetFont( 'Arial', 'B', 20);
$pdf->Line(10, 65, 210-20, 65);
$pdf->Write( 60, "                               PACKAGING SLIP");
$pdf->Line(10, 85, 210-20, 85);
$pdf->Ln( 20 );
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->SetFont( 'Arial', 'B', 18 );
$pdf->Write( 64, "Order N.: " . $_GET['id'] );
$pdf->Write( 64, "                                                               Total: " . $total);
$pdf->Ln( 20 );
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->SetFont( 'Arial', '', 15 );
$pdf->Write( 46, "Date of Creation: " . $dateCreated);
$pdf->Ln( 6 );
$pdf->Write( 46, "Customer: " . $customer);
$pdf->Ln( 6 );
$pdf->Write( 46, "Phone number: " . $phone);
$pdf->Ln( 6 );
$pdf->Write( 46, "Delivery Address: " . $address);
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->SetFont( 'Arial', '', 15 );
$pdf->FancyTable($header,$data);
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->SetFont( 'Arial', 'B', 15 );
$pdf->Write(6, 'Customer Signature: ');
$pdf->Output('I','Order_' . $_GET['id'] . '.pdf');
?>