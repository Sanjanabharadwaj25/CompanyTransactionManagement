<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selectedDate = $_POST['selectedDate'];

  // Read the journal.txt file
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

    // Add the transaction to the array if it matches the selected date
    if ($date == $selectedDate) {
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
                <a href="home.html" class="nav-link">ADD</a>
            </li>
            <li class="nav-item">
                <a href="delete.html" class="nav-link" id="selected">DELETE</a>
            </li>
            <li class="nav-item">
                <a href="search.php" class="nav-link">SEARCH</a>
            </li>
            <li class="nav-item">
                <a href="display.html" class="nav-link">DISPLAY</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
  <h3 style="font-weight: 100; padding-bottom: 20px;">DELETE RECORDS BY DATE</h3>
  <form action="delete.php" method="post">
    <div class="form-group row">
      <label for="selectDate" class="col-sm-2 col-form-label">Select Date</label>
      <div class="col-sm-10">
        <input type="date" class="form-control" id="selectDate" name="selectedDate" required>
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
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($transactions as $transaction) : ?>
              <tr>
                <td><?php echo $transaction['accountNumber']; ?></td>
                <td><?php echo $transaction['companyName']; ?></td>
                <td><?php echo $transaction['date']; ?></td>
                <td><?php echo $transaction['transactionType']; ?></td>
                <td>
                  <button type="button" class="btn btn-danger delete-btn" data-index="<?php echo $transaction['index']; ?>">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else : ?>
      <p>No transactions found for the selected date.</p>
    <?php endif; ?>
  <?php endif; ?>
</div>

<script>
$(document).ready(function() {
  $('.delete-btn').click(function() {
    var confirmDelete = confirm("Are you sure you want to delete this record?");
    if (confirmDelete) {
      var index = $(this).data('index');
      $.post('deleteRecord.php', { index: index }, function(data) {
        if (data === 'success') {
          alert("Record deleted successfully.");
          // Refresh the page after deletion
          location.reload();
        } else {
          alert("Failed to delete the record.");
        }
      });
    }
  });
});
</script>

</body>
</html>
