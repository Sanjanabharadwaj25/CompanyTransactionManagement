<?php
// Check if accountNumber is provided
if(isset($_POST['accountNumber'])){
    // Get the accountNumber from the request
    $accountNumber = $_POST['accountNumber'];

    // Read the transactions from the file
    $file = fopen('journal.txt', 'r');
    $transactions = array();
    while($line = fgets($file)){
        $data = explode('|', $line);
        $currentAccountNumber = trim($data[0]);
        if($currentAccountNumber === $accountNumber){
            $transactions[] = trim($line);
        }
    }
    fclose($file);

    // Send the transactions as JSON response
    echo json_encode($transactions);
}
?>
