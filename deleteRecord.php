<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the index number from the AJAX request
  $index = $_POST['index'];

  // Read the journal.txt file
  $file = fopen('journal.txt', 'r');
  $transactions = [];

  while (($line = fgets($file)) !== false) {
    $transactions[] = trim($line);
  }

  fclose($file);

  // Check if the index is valid
  if ($index >= 0 && $index < count($transactions)) {
    // Remove the transaction at the specified index
    array_splice($transactions, $index, 1);

    // Write the updated transactions back to the file
    $file = fopen('journal.txt', 'w');
    fwrite($file, implode(PHP_EOL, $transactions) . PHP_EOL); // Append newline character at the end
    fclose($file);

    // Return a success response
    echo 'success';
  } else {
    // Return an error response
    echo 'Invalid index';
  }
}
?>
