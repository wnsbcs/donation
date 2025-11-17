<?php
include 'connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $donor_name = $_POST['donor_name'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $payment_method = $_POST['payment_method'] ?? null;
    $chapter = $_POST['chapter'] ?? null;
    $date_of_payment = $_POST['date_of_payment'] ?? null;

    if ($donor_name && $amount && $payment_method && $chapter && $date_of_payment) {
        $stmt = $con->prepare("INSERT INTO donationlisting (Donor_Name, Amount, Payment_Method, Chapter, Date_of_Payment) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsss", $donor_name, $amount, $payment_method, $chapter, $date_of_payment);
        $stmt->execute();
        $stmt->close();
        header("Location: DonationListing.php");
        exit;
    } else {
        echo "<script>alert('Please fill out all fields correctly.');</script>";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $con->query("DELETE FROM donationlisting WHERE id = $id");
    header("Location: DonationListing.php");
    exit;
}

// Fetch all donations
$result = $con->query("SELECT * FROM donationlisting ORDER BY Date_of_Payment DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Donation Listing</title>
  <link rel="stylesheet" href="donationlisting.css">
</head>
<body>
    <!-- button donation listing and campaign alert -->
    <div class="top-buttons">
        <button onclick="window.location.href='donation_campaign.php'">Donation Campaign Alert</button>
        <button onclick="window.location.href='donationlisting.php'">Donation Listing</button>
        <button onclick="window.location.href='upload_receipts.php'">Upload Receipts</button>
    </div>

    <div class="container">
      <h3>Donation Form</h3>
      <form method="POST" id="donationForm">
        <input type="hidden" name="action" value="add">

        <label>Donor Name:</label>
        <input type="text" name="donor_name" placeholder="Enter donor name" required>

        <label>Amount:</label>
        <input type="number" name="amount" placeholder="Enter amount" required>

        <label>Payment Method:</label>
        <select name="payment_method" required>
          <option value="" disabled selected>Select Payment Method</option>
          <option value="Gcash">Gcash</option>
          <option value="Cash">Cash</option>
        </select>

        <label>Chapter:</label>
        <select name="chapter" required>
          <option value="" disabled selected>Select Chapter</option>
          <option value="Ilocos">TEAM ILOCOS</option>
          <option value="Laguna">TEAM LAGUNA</option>
          <option value="Malabon">TEAM MALABON</option>
          <option value="Mandaluyong/ San Juan">TEAM MANDALUYONG/ SAN JUAN</option>
          <option value="Manila">TEAM MANILA</option>
          <option value="Marikina">TEAM MARIKINA</option>
          <option value="Metro South">TEAM METRO SOUTH</option>
          <option value="Montalban">TEAM MONTALBAN</option>
          <option value="North Caloocan">TEAM NORTH CALOOCAN</option>
          <option value="Pampanga">TEAM PAMPANGA</option>
          <option value="Pangasinan">TEAM PANGASINAN</option>
          <option value="Pasay">TEAM PASAY</option>
          <option value="Pasig">TEAM PASIG</option>
          <option value="Quezon City">TEAM QUEZON CITY</option>
          <option value="Rizal">TEAM RIZAL</option>
          <option value="San Mateo">TEAM SAN MATEO</option>
          <option value="SJDM">TEAM SAN JOSE DEL MONTE BULACAN</option>
          <option value="Sta Maria Bulacan">TEAM STA MARIA BULACAN</option>
          <option value="Taguig/Makati">TEAM TAGUIG/ MAKATI</option>
          <option value="Valenzuela">TEAM VALENZUELA</option>
        </select>

        <label>Date of Payment:</label>
        <input type="date" name="date_of_payment" required>

        <button type="submit" class="donate-btn">DONATE</button>
      </form>
    </div>

    <div class="container">
      <h3>Donation Listing</h3>
      <input type="text" id="searchBox" placeholder="🔍 Search donor, method, or chapter..." onkeyup="filterTable()">

      <table id="donationTable">
        <thead>
          <tr>
            <th onclick="sortTable(1)">Donor Name </th>
            <th onclick="sortTable(2)">Amount </th>
            <th onclick="sortTable(3)">Payment Method </th>
            <th onclick="sortTable(4)">Chapter </th>
            <th onclick="sortTable(5)">Date of Payment </th>
            <th>Action</th> <!-- moved to right -->
          </tr>
        </thead>
        <tbody>
          <?php 
          $totalQuery = $con->query("SELECT SUM(Amount) AS total_amount FROM donationlisting");
          $total = $totalQuery->fetch_assoc()['total_amount'] ?? 0;
          while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['Donor_Name']); ?></td>
            <td>₱<?php echo number_format($row['Amount'], 2); ?></td>
            <td><?php echo htmlspecialchars($row['Payment_Method']); ?></td>
            <td><?php echo htmlspecialchars($row['Chapter']); ?></td>
            <td><?php echo date('M d, Y', strtotime($row['Date_of_Payment'])); ?></td>
            <td class="action-cell">
              <a href="DonationListingEdit.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
              <a href="DonationListing.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this donation?');">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>

        <tfoot>
  <tr>
    <th colspan="1" style="text-align:right; color:#ffcc00;">GRAND TOTAL:</th>
    <th colspan="5" style="text-align:left; color:#ffcc00;">
      ₱<?php echo number_format($total, 2); ?>
    </th>
  </tr>
</tfoot>
      </table>
    </div>
<script src="donationlisting.js"></script>
</body>
</html>
