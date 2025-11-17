<?php
include 'connection.php';

// Check if an ID is provided
if (!isset($_GET['id'])) {
    echo "<script>alert('No donation selected!'); window.location.href='DonationListing.php';</script>";
    exit;
}

$id = intval($_GET['id']);

// Fetch existing donation record
$stmt = $con->prepare("SELECT * FROM donationlisting WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$donation = $result->fetch_assoc();

if (!$donation) {
    echo "<script>alert('Donation not found!'); window.location.href='DonationListing.php';</script>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_name = $_POST['donor_name'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $chapter = $_POST['chapter'];
    $date_of_payment = $_POST['date_of_payment'];

    $update = $con->prepare("UPDATE donationlisting SET donor_name=?, amount=?, payment_method=?, chapter=?, date_of_payment=? WHERE id=?");
    $update->bind_param("sdsssi", $donor_name, $amount, $payment_method, $chapter, $date_of_payment, $id);

    if ($update->execute()) {
        echo "<script>alert('Donation updated successfully!'); window.location.href='DonationListing.php';</script>";
    } else {
        echo "<script>alert('Error updating donation.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Donation</title>
  <link rel="stylesheet" href="donationlistingedit.css">
</head>
<body>
  <div class="container">
      <h2>Edit Donation Record</h2>
      <form method="POST">
        <label>Donor Name:</label>
        <input type="text" name="donor_name" value="<?php echo htmlspecialchars($donation['Donor_Name']); ?>" required>

        <label>Amount:</label>
        <input type="number" name="amount" value="<?php echo htmlspecialchars($donation['Amount']); ?>" required>

        <label>Payment Method:</label>
        <select name="payment_method" required>
          <option value="Gcash" <?php if ($donation['Payment_Method'] == 'Gcash') echo 'selected'; ?>>Gcash</option>
          <option value="Cash" <?php if ($donation['Payment_Method'] == 'Cash') echo 'selected'; ?>>Cash</option>
        </select>

        <label>Chapter:</label>
        <select name="chapter" required>
          <?php
          $chapters = [
            "Ilocos","Laguna","Malabon","Mandaluyong/ San Juan","Manila","Marikina",
            "Metro South","Montalban","North Caloocan","Pampanga","Pangasinan","Pasay",
            "Pasig","Quezon City","Rizal","San Mateo","SJDM","Sta Maria Bulacan","Taguig/Makati","Valenzuela"
          ];
          foreach ($chapters as $ch) {
            $selected = ($donation['Chapter'] == $ch) ? 'selected' : '';
            echo "<option value='$ch' $selected>TEAM $ch</option>";
          }
          ?>
        </select>

        <label>Date of Payment:</label>
        <input type="date" name="date_of_payment" value="<?php echo htmlspecialchars($donation['Date_of_Payment']); ?>" required>

        <button type="submit">UPDATE DONATION</button>
        <a href="donationlisting.php" class="back-link">Back to Listing</a>
      </form>
  </div>
</body>
</html>
