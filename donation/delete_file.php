<?php
include 'connection.php';

$batch = $_GET['batch'] ?? null;
$file  = $_GET['file']  ?? null;

if (!$batch || !$file) {
    die("Missing batch or file.");
}

$folder = __DIR__ . "/uploads/" . $batch;
$filePath = $folder . "/" . $file;

// Check folder exists
if (!is_dir($folder)) {
    die("Batch folder does not exist.");
}

// Check file exists
if (!file_exists($filePath)) {
    die("File does not exist.");
}

// Delete file
unlink($filePath);

// Redirect back
header("Location: upload_receipts.php?batch={$batch}");
exit;
?>
