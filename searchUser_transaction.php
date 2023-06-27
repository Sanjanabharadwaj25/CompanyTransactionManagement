<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selectedAccount = $_POST['selectedAccount'];

  // Read the transactions.txt file
  $file = fopen('journal.txt', 'r');
  $transactions = [];

  while (($line = fgets($file)) !== false) {
    $line = trim($line); // Trim the newline character
    $data = explode('|', $line);
    $index = $data[0]; // Extract the first field and name it as "index"
    $accountNumber = $data[1];
    $companyName = $data[2];
    $date = $data[3];
    $transactionType = $data[4];

    // Add the transaction to the array if it matches the selected account
    if ($accountNumber == $selectedAccount) {
      $transactions[] = [
        'index' => $index,
        'accountNumber' => $accountNumber,
        'companyName' => $companyName,
        'date' => $date,
        'transactionType' => $transactionType
      ];
    }
  }

  fclose($file);
}
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="home.css">
    <title>Admin Page</title>
</head>
<body>
<nav id="MainNav" class="navbar navbar-dark navbar-expand-md py-0 fixed-top">
    <div class="collapse navbar-collapse" id="navLinks">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="company.html" class="nav-link">ADD</a>
            </li>
            <li class="nav-item">
                <a href="searchUser.php" class="nav-link"  id="selected">SEARCH</a>
            </li>
            <li class="nav-item">
                <a href="displayUser.html" class="nav-link">DISPLAY</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
  <h3 style="font-weight: 100; padding-bottom: 20px;">SEARCH RECORDS</h3>
  <form action="searchUser_transaction.php" method="post">
    <div class="form-group row">
      <label for="selectAccount" class="col-sm-2 col-form-label">Select Account</label>
      <div class="col-sm-10">
        <select class="form-control" id="selectAccount" name="selectedAccount">
          <option value="">Select an account</option>
          <!-- Populate the select options dynamically from your file or database -->
          <?php
          // Read the transactions.txt file to extract unique account numbers
          $file = fopen('journal.txt', 'r');
          $accountNumbers = [];
          while (($line = fgets($file)) !== false) {
            $data = explode('|', $line);
            $accountNumber = $data[1];
            if (!in_array($accountNumber, $accountNumbers)) {
              $accountNumbers[] = $accountNumber;
              echo "<option value=\"$accountNumber\">$accountNumber</option>";
            }
          }
          fclose($file);
          ?>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-10" style="padding-left: 150px;">
        <button type="submit" class="btn btn-primary">Load</button>
      </div>
    </div>
  </form>

  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
    <?php if (!empty($transactions)) : ?>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Account Number</th>
              <th scope="col">Company Name</th>
              <th scope="col">Date</th>
              <th scope="col">Transaction Type</th>
             
            </tr>
          </thead>
          <tbody>
            <?php foreach ($transactions as $transaction) : ?>
              <tr>
                <td><?php echo $transaction['accountNumber']; ?></td>
                <td><?php echo $transaction['companyName']; ?></td>
                <td><?php echo $transaction['date']; ?></td>
                <td><?php echo $transaction['transactionType']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else : ?>
      <p>No transactions found for the selected account.</p>
    <?php endif; ?>
  <?php endif; ?>
</div>

</body>
</html>
