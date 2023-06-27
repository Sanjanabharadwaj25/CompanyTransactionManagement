<?php
// Retrieve form data
$accountNumber = $_POST['inputAccountNumber'];
$companyName = $_POST['inputCompanyName'];
$date = $_POST['inputDate'];
$transactionType = $_POST['transactionType'];
$desc = $_POST['inputDesc'];
$checkNo = $_POST['checkNo'];
$amt = $_POST['Amount'];
$recordCount = 0;
  $file = fopen('journal.txt', 'r');
  while (($line = fgets($file)) !== false) {
    $recordCount++;
  }
  fclose($file);
// Format the transaction data
$transactionData =$recordCount.'|'. $accountNumber . '|' . $companyName . '|' . $date . '|'.$desc.'|'.$checkNo.'|' . $transactionType . '|'. $amt."\n";
//echo "<p> $transactionData</p>";
// Append the transaction data to the file
$file = fopen('journal.txt', 'a');
fwrite($file, $transactionData);
fclose($file);

// Redirect back to the form page or display a success message
header('Location: home.html');
exit();
?>
