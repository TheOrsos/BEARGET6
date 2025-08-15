<?php
require_once 'db_connect.php';

echo "Starting migration...<br>";

// SQL to add the new column
$sql = "ALTER TABLE shared_funds ADD COLUMN last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";

if ($conn->query($sql) === TRUE) {
    echo "SUCCESS: Column 'last_updated' added to 'shared_funds' table successfully.<br>";
} else {
    // Check if the error is "Duplicate column name" which means it already exists
    if ($conn->errno == 1060) {
        echo "INFO: Column 'last_updated' already exists in 'shared_funds' table.<br>";
    } else {
        echo "ERROR: Could not alter table. " . $conn->error . "<br>";
    }
}

$conn->close();
echo "Migration finished.<br>";
?>
