<?php

function display_current_ledger() {
    // Read the ledger file
    $ledgerData = file_get_contents('ledger.txt');
    if (!$ledgerData) {
        echo "Failed to read the ledger file.";
        return;
    }

    // Split the ledger data into records
    $records = explode("\n", $ledgerData);
    if (empty($records)) {
        echo "No records found in the ledger.";
        return;
    }

    // Filter records for the desired month
    $filteredRecords = array_filter($records, function($record) {
        $fields = explode('|', $record);
        $date = $fields[2];
        $month = intval(date('m', strtotime($date)));
        return $month === 6; // Change the month here as per your requirement
    });

    // Create a table to display the ledger
    echo "<table>";
    echo "<tr><th>Account Number</th><th>Description</th><th>Date</th><th>Amount</th><th>Previous Balance</th><th>Current Balance</th></tr>";

    // Create an associative array to store records by account number
    $accountRecords = array();

    // Iterate through the filtered records and group them by account number
    foreach ($filteredRecords as $record) {
        $fields = explode('|', $record);
        $accountNumber = $fields[0];

        // Check if the account number already exists in the array
        if (array_key_exists($accountNumber, $accountRecords)) {
            // Append the current record to the existing account number
            $accountRecords[$accountNumber][] = $record;
        } else {
            // Create a new entry for the account number
            $accountRecords[$accountNumber] = array($record);
        }
    }
    $count =0;
    $prev = [120000,4500,76000,2000,300,4000,6000,49999];
    // Iterate through the account records and display them in respective columns
    foreach ($accountRecords as $accountNumber => $accountRecords) {

        $previousBalance = $prev[$count++];
        $currentBalance = $previousBalance;

        // Iterate through the account records and calculate previous and current balances
        foreach ($accountRecords as $record) {
            $fields = explode('|', $record);
            $amount = floatval($fields[6]);
            $transactionType = $fields[5]; // Get the transaction type from the 5th field

            // Calculate the current balance based on the transaction type
            if ($transactionType === 'debit') {
                $currentBalance -= $amount;
            } else {
                $currentBalance += $amount;
            }
        }

        // Print the merged row with previous balance and current balance
        $firstRow = true;
        foreach ($accountRecords as $record) {
            $fields = explode('|', $record);

            echo "<tr>";
            if ($firstRow) {
                echo "<td rowspan=" . count($accountRecords) . ">{$accountNumber}</td>"; // Account Number
                echo "<td>{$fields[1]}</td>"; // Description
                echo "<td>{$fields[2]}</td>"; // Date
                echo "<td>{$fields[6]}</td>"; // Amount
                echo "<td rowspan=" . count($accountRecords) . ">$previousBalance</td>"; // Previous Balance (always zero for the first row)
                echo "<td rowspan=" . count($accountRecords) . ">";

                // Display the current balance with the appropriate sign
                if ($currentBalance < 0) {
                    echo "-" . abs($currentBalance);
                } else {
                    echo "" . $currentBalance;
                }

                echo "</td>"; // Current Balance
                $firstRow = false;
            } else {
                echo "<td>{$fields[1]}</td>"; // Description
                echo "<td>{$fields[2]}</td>"; // Date
                echo "<td>{$fields[6]}</td>"; // Amount
            }

            echo "</tr>";
        }
    }

    echo "</table>";
}

function displayMonthlyLedger() {
    $file = 'ledger.txt'; // Change the file name or the path to your ledger file
    $ledgerData = file_get_contents($file);
    $records = explode("\n", trim($ledgerData));

    // Create an array to store the records by month
    $recordsByMonth = array();

    foreach ($records as $record) {
        $fields = array_map('trim', explode('|', $record));
        list($accountNumber, $accountDescription, $date, $desc, $checkno, $transactionType, $amount) = $fields;

        // Extract the month from the date field
        $month = date('F', strtotime($date));

        // Check if the month exists in the recordsByMonth array
        if (!array_key_exists($month, $recordsByMonth)) {
            $recordsByMonth[$month] = array();
        }

        // Push the record to the corresponding month array
        $recordsByMonth[$month][] = array(
            'accountNumber' => $accountNumber,
            'accountDescription' => $accountDescription,
            'amount' => $amount
        );
    }

    // Sort the months in chronological order
    $months = array(
        'January',
        'February',
        'March',
        'April',
        'May',
        'June'
    );

    // Create the header row
    echo '<tr>';
    echo '<th>Account Number</th>';
    echo '<th>Account Description</th>';
    foreach ($months as $month) {
        echo '<th>' . $month . '</th>';
    }
    echo '</tr>';

    // Create an array to store the summed amounts for each account number and month
    $amountsByAccount = array();

    foreach ($months as $month) {
        foreach ($recordsByMonth[$month] as $record) {
            $accountNumber = $record['accountNumber'];
            $accountDescription = $record['accountDescription'];
            $amount = $record['amount'];

            // Check if the account number exists in the amountsByAccount array
            if (!array_key_exists($accountNumber, $amountsByAccount)) {
                $amountsByAccount[$accountNumber] = array(
                    'accountDescription' => $accountDescription,
                    'amounts' => array_fill_keys($months, 0)
                );
            }

            // Sum up the amounts for the account number and month
            $amountsByAccount[$accountNumber]['amounts'][$month] += $amount;
        }
    }

    // Create rows for each account number and its summed amount for each month
    foreach ($amountsByAccount as $accountNumber => $data) {
        $accountDescription = $data['accountDescription'];
        $amounts = $data['amounts'];

        echo '<tr>';
        echo '<td>' . $accountNumber . '</td>';
        echo '<td>' . $accountDescription . '</td>';

        foreach ($amounts as $amount) {
            echo '<td>' . $amount . '</td>';
        }

        echo '</tr>';
    }
}




?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="home.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Admin Page</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table, th, td {
            border: 1px solid black;
            text-align: center;
        }

        th, td {
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .hidden {
            display: none;
        }
    </style>
    <script>
        function showJournal() {
            event.preventDefault();
            var form = document.getElementById("journalForm");
            form.classList.add("hidden");
            var table = document.getElementById("journalTable");
            var divClass = document.getElementById("journalDiv");
            divClass.classList.remove("hidden");
            table.classList.remove("hidden");
            fetchJournal();
        }

        function showCurrent() {
            event.preventDefault();
            var form = document.getElementById("journalForm");
            form.classList.add("hidden");
            var table = document.getElementById("currentTable");
            table.classList.remove("hidden");
            var divClass = document.getElementById("currentDiv");
            divClass.classList.remove("hidden");
            copyJournalToLedger();
           
        }

        function showMonthly() {
            event.preventDefault();
            var form = document.getElementById("journalForm");
            form.classList.add("hidden");
            var table = document.getElementById("monthlyTable");
            table.classList.remove("hidden");
            var divClass = document.getElementById("monthlyDiv");
            divClass.classList.remove("hidden");
           

        }

        function fetchJournal() {
            fetch('journal.txt')
                .then(response => response.text())
                .then(data => {
                    const tableBody = document.getElementById('journalTableBody');
                    tableBody.innerHTML = '';
                    const records = data.trim().split('\n');
                    records.forEach(record => {
                        const fields = record.split('|').map(field => field.trim());
                        const [index,accountNumber, checkNumber, date, description, checkno, transactionType,amount] = fields;
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${accountNumber}</td>
                            <td>${checkNumber}</td>
                            <td>${date}</td>
                            <td>${description}</td>
                            <td>${amount}</td>
                        `;
                        tableBody.appendChild(newRow);
                    });
                })
                .catch(error => console.log(error));
        }

        function copyJournalToLedger() {
            fetch('copy_journal_to_ledger.php')
                .then(response => {
                    if (response.ok) {
                        console.log("Journal records successfully copied to the ledger.");
                    } else {
                        console.log("Failed to copy journal records to the ledger.");
                    }
                })
                .catch(error => console.log("Error:", error));
        }

       

    </script>
</head>
<body>
    <nav id="MainNav" class="navbar navbar-dark navbar-expand-md py-0 fixed-top">
        <div class="collapse navbar-collapse" id="navLinks">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="home.html" class="nav-link">ADD</a>
                </li>
                <li class="nav-item">
                    <a href="delete.html" class="nav-link">DELETE</a>
                </li>
                <li class="nav-item">
                    <a href="search.php" class="nav-link">SEARCH</a>
                </li>
                <li class="nav-item">
                    <a href="display.php" class="nav-link" id="selected">DISPLAY</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container" id="journalForm" style="width: 500px;">
        <h3 style="font-weight: 100; padding-bottom: 20px;">DISPLAY RECORD</h3>
        <form action="" method="post">
            <div class="form-group row">
                <div class="col-sm-10" style="padding-left: 150px;  padding-top: 10px;">
                    <button type="submit" class="btn btn-primary" onclick="showMonthly()">Display Monthly Ledger</button>
                </div>
                <div class="col-sm-10" style="padding-left: 150px;  padding-top: 10px;">
                    <button type="submit" class="btn btn-primary" onclick="showCurrent()">Display Current Ledger</button>
                </div>
                <div class="col-sm-10" style="padding-left: 150px; padding-top: 10px;">
                    <button type="submit" class="btn btn-primary" onclick="showJournal()">Display Journal</button>
                </div>
            </div>
        </form>
    </div>

    <div id="journalDiv" class="hidden container">
        <h3 style="font-weight: 100; padding-bottom: 20px;">Journal</h3>
        <table id="journalTable" class="hidden">
            <thead>
                <tr>
                    <th>Account Number</th>
                    <th>Check Number</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="journalTableBody">
            </tbody>
        </table>
        <br>
        <button class="btn btn-primary" onclick="printPage()" style="margin-left: 550px;">Print</button>
    </div>
    

    <div id="monthlyDiv" class="hidden container">
        <h3 style="font-weight: 100; padding-bottom: 20px;">Monthly Ledger</h3>
        <table id="monthlyTable" class="hidden">
            <thead>
            </thead>
            <tbody id="monthlyTableBody">
                <?php 
                        displayMonthlyLedger();
                        ?>
            </tbody>
        </table>
        <br>
        <button class="btn btn-primary" onclick="printPage()" style="margin-left: 550px;">Print</button>
    </div>

    <div id="currentDiv" class="hidden container">
        <h3 style="font-weight: 100; padding-bottom: 20px;">Current Ledger</h3>
        <table id="currentTable" class="hidden">
            <thead>
              
            </thead>
            <tbody id="currentTableBody">
                <?php
            display_current_ledger(); ?>
            </tbody>
        </table>
        <br>
        <button class="btn btn-primary" onclick="printPage()" style="margin-left: 550px;">Print</button>
    </div>
</body>
<script>
        function printPage() {
            window.print();
        }
    </script>
</html>

