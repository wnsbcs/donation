<?php
include 'connection.php';

// Check if ID is passed
if (!isset($_GET['id'])) {
    echo "<script>alert('No campaign selected!'); window.location.href='donation_campaign.php';</script>";
    exit();
}

$id = $_GET['id'];

// Fetch campaign data
$stmt = $con->prepare("SELECT * FROM donation_campaign WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Campaign not found!'); window.location.href='donation_campaign.php';</script>";
    exit();
}

$campaign = $result->fetch_assoc();
$stmt->close();

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $Purpose = $_POST['Purpose'] ?? null;
    $Start_Date = $_POST['Start_Date'] ?? null;
    $Due_Date = $_POST['Due_Date'] ?? null;

    if ($Purpose && $Start_Date && $Due_Date) {
        $update_stmt = $con->prepare("UPDATE donation_campaign SET Purpose=?, Start_Date=?, Due_Date=? WHERE id=?");
        $update_stmt->bind_param("sssi", $Purpose, $Start_Date, $Due_Date, $id);
        $update_stmt->execute();
        $update_stmt->close();

        echo "<script>alert('Campaign updated successfully!'); window.location.href='donation_campaign.php';</script>";
        exit();
    } else {
        echo "<script>alert('Please fill out all fields!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Campaign Alert</title>
    <link rel="stylesheet" href="donation_campaign.css">
</head>
<body>
    <div class="top-buttons">
        <button onclick="window.location.href='donationlisting.php'">Donation Listing</button>
        <button onclick="window.location.href='donation_campaign.php'">Donation Campaign Alert</button>
    </div>

    <div class="container">
        <h2>Edit Donation Campaign</h2>

        <form method="POST" id="editForm">
            <label>Purpose of Donation:</label>
            <input type="text" name="Purpose" id="Pupose" value="<?php echo htmlspecialchars($campaign['Purpose']); ?>" required>

            <label>Start Date:</label>
            <input type="date" name="Start_Date" id="Start_Date" value="<?php echo htmlspecialchars($campaign['Start_Date']); ?>" required>

            <label>Due Date:</label>
            <input type="date" name="Due_Date" id="Due_Date" value="<?php echo htmlspecialchars($campaign['Due_Date']); ?>" required>

            <div class="btn-group">
                <button type="submit" name="update" class="save-btn">Save Changes</button>
                <button type="button" onclick="window.location.href='donation_campaign.php'" class="clear-btn">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
