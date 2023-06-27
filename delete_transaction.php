<?php
$val = $_POST['deleteBy'];
if($val == 'date'){
  echo '
<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="home.css">
    <title>Admin Page</title>
</head>
<body>
<nav id= "MainNav" class = "navbar navbar-dark navbar-expand-md py-0 fixed-top">
        
        <div class="collapse navbar-collapse" id="navLinks">
            <ul class="navbar-nav">
                <li class="nav-item">
                <a href="home.html" class="nav-link">ADD</a></li>
                <li class="nav-item">
                    <a href="delete.html" class="nav-link" id = "selected">DELETE</a>
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
      <h3 style="font-weight: 100; padding-bottom: 20px;">DELETE A RECORD</h3>
      <form action="delete.php" method="post">
        <div class="form-group row">
          <label for="selectDate" class="col-sm-2 col-form-label">Select Date</label>
          <div class="col-sm-10">
            <select class="form-control" id="selectDate" name="selectedDate">
              <option value="">Select a date</option>
              <!-- Populate the select options dynamically from your file or database -->';
              
              // Read the transactions.txt file to extract unique dates
              $file = fopen('journal.txt', 'r');
              $dates = [];
              while (($line = fgets($file)) !== false) {
                $data = explode('|', $line);
                $date = $data[3];
                if (!in_array($date, $dates)) {
                  $dates[] = $date;
                  echo "<option value=\"$date\">$date</option>";
                }
              }
              fclose($file);
              echo '
            </select>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-10" style="padding-left: 150px;">
            <button type="submit" class="btn btn-primary">Load</button>
          </div>
        </div>
      </form>
    </div>
</body>
</html>
';
}
else {

echo '

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
    <h3 style="font-weight: 100; padding-bottom: 20px;">DELETE A RECORD</h3>
    <form action="deleteAcc.php" method="post">
        <div class="form-group row">
            <label for="selectAccount" class="col-sm-2 col-form-label">Select Account</label>
            <div class="col-sm-10">
                <select class="form-control" id="selectAccount" name="selectedAccount">
                    <option value="">Select an account</option>
                    <!-- Populate the select options dynamically from your file or database -->';
                    // Read the transactions.txt file to extract unique account numbers'
                    $file = fopen('journal.txt', 'r');
                    $accountNumbers = [];
                    while (($line = fgets($file)) !== false) {
                        $data = explode('|', $line);
                        $accountNumber = trim($data[1]);
                        if (!in_array($accountNumber, $accountNumbers)) {
                            $accountNumbers[] = $accountNumber;
                            echo "<option value=\"$accountNumber\">$accountNumber</option>";
                        }
                    }
                    fclose($file);
                    echo '
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10" style="padding-left: 150px;">
                <button type="submit" class="btn btn-primary">Load</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
';
 }
 ?>