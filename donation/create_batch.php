<?php
// create_batch.php
// Optional: include your DB connection if you want to store batch entries
include 'connection.php'; // comment out if you don't have DB

function sanitize_folder($s){
    $s = preg_replace('/[^\w\s-]/', '', $s);
    $s = preg_replace('/\s+/', '_', trim($s));
    return $s;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event = trim($_POST['event'] ?? '');
    $date  = trim($_POST['date'] ?? '');

    if ($event === '' || $date === '') {
        $errors[] = "Please fill event name and date.";
    } else {
        // Build folder name: YYYY-MM-DD_EventName
        $folder = date('Y-m-d', strtotime($date)) . '_' . sanitize_folder($event);
        $uploadPath = __DIR__ . '/uploads/' . $folder;

        if (!is_dir(__DIR__ . '/uploads')) {
            mkdir(__DIR__ . '/uploads', 0755, true);
        }

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Optional: insert into DB receipts_batches
        if (isset($con)) {
            $stmt = $con->prepare("INSERT INTO receipts_batches (batch_folder, event_name, batch_date) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $folder, $event, $date);
            $stmt->execute();
            $batch_id = $stmt->insert_id;
            $stmt->close();
        } else {
            $batch_id = null;
        }

        // Redirect to uploader for this batch
        header('Location: upload_receipts.php?batch=' . urlencode($folder) . ($batch_id ? '&id='.$batch_id : ''));
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Create Batch - Upload Receipts</title>
<link rel="stylesheet" href="receipts.css">
</head>
<body>
<div class="panel">
  <h2>Create Batch (Upload Receipts)</h2>
  <?php if ($errors): ?>
    <div class="errors"><?= implode('<br>', $errors) ?></div>
  <?php endif; ?>
  <form method="post">
    <label>Event / Batch name (example: Sponsor)</label>
    <input type="text" name="event" required placeholder="Event - Sponsor">

    <label>Batch Date</label>
    <input type="date" name="date" required>

    <div class="btn-row">
      <button type="submit" class="btn btn--accent">Create & Upload</button>
      <a href="receipt_listing.php" class="btn">View Receipt Listing</a>
    </div>
  </form>
</div>
</body>
</html>
