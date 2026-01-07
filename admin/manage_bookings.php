<?php
require '../inc/db.php';
session_start();

// Protect admin area
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = "";

// Handle Booking Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // delete booking + cascade payments
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE BookingID = ?");
    $stmt->execute([$id]);
    $success = "Booking deleted.";
}

// Handle Payment Status Update
if (isset($_POST['update_payment'])) {
    $paymentId = (int)$_POST['payment_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE payments SET Status=? WHERE PaymentID=?");
    $stmt->execute([$status, $paymentId]);
    $success = "Payment updated.";
}

// Fetch bookings with joins
$sql = "SELECT b.BookingID, b.StartDate, b.EndDate, b.Amount, 
               c.Name AS CustomerName, c.Email, c.LicenseNo,
               car.Model, car.PlateNo,
               p.PaymentID, p.Mode, p.Status AS PaymentStatus
        FROM bookings b
        JOIN customers c ON b.CustomerID = c.CustomerID
        JOIN cars car ON b.CarID = car.CarID
        LEFT JOIN payments p ON b.BookingID = p.BookingID
        ORDER BY b.created_at DESC";
$stmt = $pdo->query($sql);
$bookings = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Bookings - Admin</title>
<link rel="stylesheet" href="/carrental/assets/style.css">
</head>
<body>
<section class="content">
  <h2>Manage Bookings</h2>
  <a href="dashboard.php" class="btn">⬅ Back to Dashboard</a>

  <?php foreach ($errors as $err) echo "<p class='error'>".htmlspecialchars($err)."</p>"; ?>
  <?php if ($success) echo "<p class='success'>$success</p>"; ?>

  <table border="1" cellpadding="8" cellspacing="0" style="margin:auto;">
    <tr>
      <th>ID</th>
      <th>Customer</th>
      <th>Car</th>
      <th>Dates</th>
      <th>Amount</th>
      <th>Payment</th>
      <th>Action</th>
    </tr>
    <?php foreach ($bookings as $bk): ?>
    <tr>
      <td><?php echo $bk['BookingID']; ?></td>
      <td>
        <?php echo htmlspecialchars($bk['CustomerName']); ?><br>
        (<?php echo htmlspecialchars($bk['Email']); ?>)<br>
        License: <?php echo htmlspecialchars($bk['LicenseNo']); ?>
      </td>
      <td>
        <?php echo htmlspecialchars($bk['Model']); ?><br>
        Plate: <?php echo htmlspecialchars($bk['PlateNo']); ?>
      </td>
      <td><?php echo $bk['StartDate']; ?> → <?php echo $bk['EndDate']; ?></td>
      <td>₹ <?php echo number_format($bk['Amount'],2); ?></td>
      <td>
        <?php if ($bk['PaymentID']): ?>
          Mode: <?php echo $bk['Mode']; ?><br>
          Status: <?php echo $bk['PaymentStatus']; ?>
          <form method="post" style="margin-top:5px;">
            <input type="hidden" name="payment_id" value="<?php echo $bk['PaymentID']; ?>">
            <select name="status">
              <option value="Pending" <?php if($bk['PaymentStatus']=='Pending') echo 'selected'; ?>>Pending</option>
              <option value="Completed" <?php if($bk['PaymentStatus']=='Completed') echo 'selected'; ?>>Completed</option>
              <option value="Failed" <?php if($bk['PaymentStatus']=='Failed') echo 'selected'; ?>>Failed</option>
            </select>
            <button class="btn" name="update_payment">Update</button>
          </form>
        <?php else: ?>
          No payment record
        <?php endif; ?>
      </td>
      <td>
        <a class="btn" href="manage_bookings.php?delete=<?php echo $bk['BookingID']; ?>" onclick="return confirm('Delete this booking?');">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</section>
</body>
</html>
