<?php
require 'inc/header.php';
require 'inc/db.php';
if(empty($_SESSION['customer_id'])){
    header("Location: login.php");
    exit;
}
$stmt = $pdo->prepare("
  SELECT b.*, c.Model, c.Image 
  FROM bookings b 
  JOIN cars c ON b.CarID = c.CarID 
  WHERE b.CustomerID = ? 
  ORDER BY b.BookingID DESC
");


$stmt->execute([$_SESSION['customer_id']]);
$bookings = $stmt->fetchAll();
?>
<section class="content">
  <h2>My Bookings</h2>
  <?php if(!$bookings) echo "<p>No bookings yet.</p>"; ?>
  <?php foreach($bookings as $bk): ?>
  <div class="card">
    <?php if (!empty($bk['Image'])): ?>
      <img src="/carrental/<?php echo htmlspecialchars($bk['Image']); ?>" alt="<?php echo htmlspecialchars($bk['Model']); ?>">
    <?php endif; ?>
    <h3><?php echo htmlspecialchars($bk['Model']); ?></h3>
    <p><?php echo htmlspecialchars($bk['StartDate']); ?> — <?php echo htmlspecialchars($bk['EndDate']); ?></p>
    <p>Amount: ₹ <?php echo number_format($bk['Amount'],2); ?></p>
    <p>Booking ID: <?php echo $bk['BookingID']; ?></p>
  </div>
<?php endforeach; ?>


</section>
<?php require 'inc/footer.php'; ?>
