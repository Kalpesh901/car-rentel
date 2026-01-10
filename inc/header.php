<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Car Rental System</title>
<link rel="stylesheet" href="/car-rentel/assets/style.css">
</head>
<body>
<header>
  <h1><a href="/car-rentel/index.php">Car Rental</a></h1>
  <nav>
  <a href="/car-rentel/index.php">Home</a>
  <a href="/car-rentel/cars.php">Cars</a>

  <?php if (!empty($_SESSION['customer_id'])): ?>
    <a href="/car-rentel/my_bookings.php">My Bookings</a>
    <a href="/car-rentel/logout.php">Logout</a>
  <?php else: ?>
    <a href="/car-rentel/register.php">Register</a>
    <a href="/car-rentel/login.php">Login</a>
    <a href="/car-rentel/admin/login.php">Admin</a>
  <?php endif; ?>
  </nav>

</header>
<main>
