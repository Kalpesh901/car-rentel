<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $car = $_POST['car'];
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];

    // Connect to database
    $conn = new mysqli("localhost", "root", "", "carrental");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO bookings (name, email, car, pickup_date, dropoff_date)
            VALUES ('$name', '$email', '$car', '$pickup', '$dropoff')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Booking Confirmed! ✅</p>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Booking</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <h1>Book a Car</h1>
</header>

<section class="content">
  <form method="POST" action="booking.php">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Car Model:</label><br>
    <select name="car">
      <option>BMW 5 Series</option>
      <option>Toyota Fortuner</option>
      <option>Ferrari 488</option>
    </select><br><br>
    <label>Pick-up Date:</label><br>
    <input type="date" name="pickup" required><br><br>
    <label>Drop-off Date:</label><br>
    <input type="date" name="dropoff" required><br><br>
    <button type="submit" class="btn">Confirm Booking</button>
  </form>
</section>
</body>
</html>
