<?php
// upload_receipts.php
include 'connection.php'; // optional - for DB tracking

$folder = $_GET['batch'] ?? null;
$batch_id = $_GET['id'] ?? null;

if (!$folder) {
    die("Batch not specified. Go back to <a href='create_batch.php'>Create Batch</a>.");
}

$base = __DIR__ . '/uploads/';
$uploadDir = $base . $folder . '/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$allowed = ['jpg','jpeg','png','pdf','gif'];
$maxFileSize = 5 * 1024 * 1024; // 5MB

$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['receipts'])) {
    foreach ($_FILES['receipts']['tmp_name'] as $idx => $tmpName) {
        if (!is_uploaded_file($tmpName)) continue;

        $origName = $_FILES['receipts']['name'][$idx];
        $size = $_FILES['receipts']['size'][$idx];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $messages[] = "$origName : File type not allowed.";
            continue;
        }
        if ($size > $maxFileSize) {
            $messages[] = "$origName : File too large.";
            continue;
        }

        // sanitize and make unique
        $safe = preg_replace('/[^\w\.-]/', '_', pathinfo($origName, PATHINFO_FILENAME));
        $stored = $safe . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $uploadDir . $stored;

        if (move_uploaded_file($tmpName, $dest)) {
            $messages[] = "$origName uploaded.";

            // Optional DB record
            if (isset($con) && $batch_id) {
                $stmt = $con->prepare("INSERT INTO receipts_files (batch_id, filename_original, filename_stored) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $batch_id, $origName, $stored);
                $stmt->execute();
                $stmt->close();
            }
        } else {
            $messages[] = "$origName failed to upload.";
        }
    }
}

// list files already in folder
$files = array_diff(scandir($uploadDir), ['.','..']);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Upload Receipts - <?php echo htmlspecialchars($folder); ?></title>
<link rel="stylesheet" href="receipts.css">
</head>
<body>
<div class="panel">
  <h2>Upload Receipts — Batch: <?php echo htmlspecialchars($folder); ?></h2>

  <?php if ($messages): ?>
    <div class="messages">
      <?php foreach ($messages as $m): ?><div><?php echo htmlspecialchars($m); ?></div><?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Choose files (jpg, png, pdf). Max 5MB each</label>
    <input type="file" name="receipts[]" single required>
    <div class="btn-row">
      <button type="submit" class="btn btn--accent">Upload</button>
      <a class="btn" href="receipt_listing.php">Back to Receipt Listing</a>
    </div>
  </form>

  <h3>Files in this batch</h3>
  <?php if (count($files) === 0): ?>
    <p>No files yet.</p>
  <?php else: ?>
    <ul class="file-list">
      <?php foreach ($files as $f): ?>
        <li>
          <a href="<?php echo 'uploads/' . rawurlencode($folder) . '/' . rawurlencode($f); ?>" target="_blank"><?php echo htmlspecialchars($f); ?></a>
          &nbsp;
          <a href="download_file.php?batch=<?php echo urlencode($folder); ?>&file=<?php echo urlencode($f); ?>" class="small">Download</a>
          &nbsp;
          <a href="delete_file.php?batch=<?php echo urlencode($folder); ?>&file=<?php echo urlencode($f); ?>" class="small delete" onclick="return confirm('Delete this file?')">Delete</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
</body>
</html>
