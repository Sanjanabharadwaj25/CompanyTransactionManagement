<?php
function copy_journal_to_ledger() {
    // Read the journal file
    $journalData = file_get_contents('journal.txt');
    if (!$journalData) {
        echo "Failed to read the journal file.";
        return;
    }

    // Split the journal data into records
    $records = explode("\n", $journalData);
    if (empty($records)) {
        echo "No records found in the journal.";
        return;
    }

    // Create an empty array to store the modified ledger records
    $ledgerRecords = array();

    // Iterate through the records and exclude the first field
    foreach ($records as $record) {
        $fields = explode('|', $record);
        if (count($fields) > 1) {
            // Exclude the first field (account number)
            $ledgerRecord = implode('|', array_slice($fields, 1));

            // Check if the modified ledger record already exists
            if (!is_record_exists($ledgerRecord)) {
                $ledgerRecords[] = $ledgerRecord;
            }
        }
    }

    // Join the modified ledger records and create a ledger string
    $ledgerData = implode("\n", $ledgerRecords);

    // Write the ledger data to the ledger file
    $result = file_put_contents('ledger.txt', $ledgerData, FILE_APPEND);
    if ($result === false) {
        echo "Failed to copy journal records to the ledger.";
    } else {
        echo "Journal records successfully copied to the ledger.";
    }
}

// Check if the given record already exists in the ledger
function is_record_exists($record) {
    // Read the ledger file
    $ledgerData = file_get_contents('ledger.txt');
    if (!$ledgerData) {
        return false;
    }

    // Split the ledger data into records
    $ledgerRecords = explode("\n", $ledgerData);

    // Check if the given record exists in the ledger
    foreach ($ledgerRecords as $ledgerRecord) {
        if ($ledgerRecord === $record) {
            return true;
        }
    }

    return false;
}

// Call the function to copy journal records to the ledger
copy_journal_to_ledger();
?>