<?php
// receipt_listing.php

$base = __DIR__ . '/uploads/';

// Check if uploads folder exists
if (!is_dir($base)) {
    die("Uploads folder not found.");
}

// Scan all folders inside uploads/
$folders = array_filter(glob($base . '*'), 'is_dir');

// Sort folders by date (latest first)
usort($folders, function($a, $b) {
    return filemtime($b) - filemtime($a);
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt Listing</title>
    <style>
        body { font-family: Arial; }

        .date-block {
            background: #f3f3f3;
            padding: 12px;
            margin-top: 25px;
            font-size: 22px;
            font-weight: bold;
            border-left: 5px solid #0088ff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .thumb {
            width: 120px;
            height: auto;
            margin: 5px;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .file-box {
            display: inline-block;
            text-align: center;
            margin: 10px;
        }

        .delete-btn {
            display: inline-block;
            padding: 5px 10px;
            background: red;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }

        .delete-btn:hover {
            background: darkred;
        }

        .delete-batch {
            background: #ff4444;
            padding: 7px 12px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<h1>Receipt Listing</h1>

<?php
if (empty($folders)) {
    echo "<p>No receipt batches found.</p>";
} else {

    foreach ($folders as $folderPath) {

        $folderName = basename($folderPath); // example "2025-02-17"

        // HEADER with date + delete batch button
        echo "
        <div class='date-block'>
            <span>Date: $folderName</span>
            <a href='delete_batch.php?batch=" . urlencode($folderName) . "' 
               class='delete-batch'
               onclick=\"return confirm('Delete ALL receipts for this date?')\">
               DELETE BATCH
            </a>
        </div>";

        // pull files inside folder
        $files = array_diff(scandir($folderPath), ['.','..']);

        if (empty($files)) {
            echo "<p>No files for this date.</p>";
            continue;
        }

        foreach ($files as $file) {

            $fileUrl = "uploads/" . urlencode($folderName) . "/" . urlencode($file);
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            echo "<div class='file-box'>";

            // Show image thumbnail
            if (in_array($ext, ['jpg','jpeg','png','gif'])) {
                echo "
                <a href='$fileUrl' target='_blank'>
                    <img src='$fileUrl' class='thumb'>
                </a><br>
                <a href='$fileUrl' target='_blank'>$file</a><br>
                ";
            } else {
                // PDF or others
                echo "
                <a href='$fileUrl' target='_blank'>$file</a><br>
                ";
            }

            // Delete button for single files
            echo "
            <a class='delete-btn' 
               href='delete_file.php?batch=" . urlencode($folderName) . "&file=" . urlencode($file) . "'
               onclick=\"return confirm('Delete this file?')\">
               Delete
            </a>";

            echo "</div>";
        }
    }
}
?>

</body>
</html>
