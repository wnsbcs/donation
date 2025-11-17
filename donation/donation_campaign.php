<?php
include 'connection.php';

// Handle Save
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save'])) {
    $Purpose = $_POST['Purpose'] ?? null;
    $Start_Date = $_POST['Start_Date'] ?? null;
    $Due_Date = $_POST['Due_Date'] ?? null;

    if ($Purpose && $Start_Date && $Due_Date) {
        $stmt = $con->prepare("INSERT INTO donation_campaign (Purpose, Start_Date, Due_Date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $Purpose, $Start_Date, $Due_Date);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Donation campaign saved successfully!'); window.location.href='donation_campaign.php';</script>";
    } else {
        echo "<script>alert('Please fill out all fields before saving!');</script>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $con->prepare("DELETE FROM donation_campaign WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Campaign deleted successfully!'); window.location.href='donation_campaign.php';</script>";
}

// Fetch campaigns
$result = $con->query("SELECT * FROM donation_campaign ORDER BY start_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Campaign Alert</title>
    <link rel="stylesheet" href="donation_campaign.css">
</head>
<body>
    <h2>DONATION MANAGEMENT</h2>
    <div class="top-buttons">
        <button onclick="window.location.href='donation_campaign.php'">Donation Campaign Alert</button>
        <button onclick="window.location.href='donationlisting.php'">Donation Listing</button>
    </div>

    <div class="container">
        <h2>Donation Campaign Alert</h2>

        <form method="POST" id="campaignForm">
            <label>Purpose of Donation:</label>
            <input type="text" name="Purpose" id="Purpose" placeholder="Enter purpose..." required>

            <label>Start Date:</label>
            <input type="date" name="Start_Date" id="Start_Date" required>

            <label>Due Date:</label>
            <input type="date" name="Due_Date" id="Due_Date" required>

            <div class="btn-group">
                <button type="submit" name="save" class="save-btn">Save</button>
                <button type="button" onclick="clearCampaign()" class="clear-btn">Clear</button>
            </div>
        </form>
    </div>

    <div class="container">
        <h3>Existing Campaigns</h3>
        <table>
            <thead>
                <tr>
                    <th>Purpose</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Purpose']); ?></td>
                    <td><?php echo date('m/d/Y', strtotime($row['Start_Date'])); ?></td>
                    <td><?php echo date('m/d/Y', strtotime($row['Due_Date'])); ?></td>
                    <td>
                        <a href="campaign_edit.php?id=<?php echo $row['id']; ?>" class="edit-btn">EDIT</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this campaign?');">DELETE</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<script src="donation_campaign.js"></script>
</body>
</html>
