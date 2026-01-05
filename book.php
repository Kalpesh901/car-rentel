<?php
require 'inc/db.php';
session_start();

if(empty($_SESSION['customer_id'])){
    header("Location: login.php");
    exit;
}

$carId = isset($_GET['car']) ? (int)$_GET['car'] : null;
if(!$carId){
    die("No car selected.");
}

$stmt = $pdo->prepare("SELECT * FROM cars WHERE CarID = ?");
$stmt->execute([$carId]);
$car = $stmt->fetch();
if(!$car) die("Car not found.");

$errors = [];
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    if(!$start || !$end) $errors[] = "Select pick-up and drop-off dates.";
    elseif($end < $start) $errors[] = "Drop-off must be after pick-up.";

    if(empty($errors)){
        // check overlapping bookings for that car
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE CarID = ? AND NOT (EndDate < ? OR StartDate > ?)");
        $stmt->execute([$carId, $start, $end]);
        if($stmt->rowCount() > 0){
            $errors[] = "Car is already booked for selected dates.";
        } else {
            // calculate amount: assume price per day
            $days = (strtotime($end) - strtotime($start)) / (60*60*24) + 1;
            if($days < 1) $days = 1;
            $amount = $days * $car['RentPrice'];

            $insert = $pdo->prepare("INSERT INTO bookings (CustomerID, CarID, StartDate, EndDate, Amount) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$_SESSION['customer_id'], $carId, $start, $end, $amount]);

            $bookingId = $pdo->lastInsertId();

            // Optional: mark car status as Booked (simple approach)
            $u = $pdo->prepare("UPDATE cars SET Status='Booked' WHERE CarID = ?");
            $u->execute([$carId]);

            // (You may want to create a payment flow here; for demo, mark payment pending)
            $p = $pdo->prepare("INSERT INTO payments (BookingID, Mode, Status) VALUES (?, ?, ?)");
            $p->execute([$bookingId, 'Card', 'Pending']);

            $success = "Booking successful! Booking ID: $bookingId. Amount: ₹".number_format($amount,2);
        }
    }
}

require 'inc/header.php';
?>

<section class="content">
  <h2>Book: <?php echo htmlspecialchars($car['Model']); ?></h2>

  <?php foreach($errors as $e) echo "<p class='error'>".htmlspecialchars($e)."</p>"; ?>
  <?php if($success) echo "<p class='success'>".htmlspecialchars($success)."</p>"; ?>

  <form method="post">
    <label>Pick-up Date</label><br>
    <input type="date" name="start_date" required><br><br>
    <label>Drop-off Date</label><br>
    <input type="date" name="end_date" required><br><br>
    <button class="btn">Confirm Booking</button>
  </form>
</section>

<?php require 'inc/footer.php'; ?>
