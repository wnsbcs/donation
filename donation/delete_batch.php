<?php
// delete_batch.php

$batch = $_GET['batch'] ?? null;

if (!$batch) {
    die("No batch specified.");
}

$baseDir = __DIR__ . "/uploads/";
$folder = $baseDir . $batch;

// Check if folder exists
if (!is_dir($folder)) {
    die("Batch folder not found.");
}

// 1. DELETE ALL FILES INSIDE THE FOLDER
$files = array_diff(scandir($folder), ['.', '..']);

foreach ($files as $file) {
    $filePath = $folder . '/' . $file;
    if (is_file($filePath)) {
        unlink($filePath); // delete file
    }
}

// 2. DELETE THE FOLDER ITSELF
rmdir($folder);

// OPTIONAL: DELETE DB RECORDS (if you track in database)
// include 'connection.php';
// $stmt = $con->prepare("DELETE FROM receipts_files WHERE batch_folder = ?");
// $stmt->bind_param("s", $batch);
// $stmt->execute();
// $stmt->close();


// Redirect back
header("Location: receipt_listing.php?msg=Batch deleted");
exit;
?>
