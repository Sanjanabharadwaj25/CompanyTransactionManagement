<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="home.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <title>Admin Page</title>
    
</head>
<body>
    <nav id= "MainNav" class = "navbar navbar-dark navbar-expand-md py-0 fixed-top">
        
        <div class="collapse navbar-collapse" id="navLinks">
            <ul class="navbar-nav">
                <li class="nav-item">
                <a href="home.html" class="nav-link">ADD</a></li>
                <li class="nav-item">
                    <a href="delete.html" class="nav-link" >DELETE</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" id = "selected">SEARCH</a>
                </li>
                <li class="nav-item">
                    <a href="display.php" class="nav-link">DISPLAY</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container" style="width: 500px;">
      <h3 style="font-weight: 100; padding-bottom: 20px;">SEARCH FOR A RECORD</h3>

      <form action="search_transaction.php" method="post">
        
          <div class="form-group row">
            <label for="accNo" class="col-sm-4 col-form-label"
              >Account Number</label
            >
            <div class="col-sm-6">
              <select class="form-control" id="selectAccount" name="selectedAccount">
                  <option value="">Select an account</option>
                  <!-- Populate the select options dynamically from your file or database -->
                  <?php
                  // Read the transactions.txt file to extract unique account numbers';
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
            <button type="submit" class="btn btn-primary" onclick="showAlert()">Load</button>
          </div>
        </div>
    </div>
    
</body>
</html>
