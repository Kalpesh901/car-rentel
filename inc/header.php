<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Car Rental System</title>
<link rel="stylesheet" href="/carrental/assets/style.css">
</head>
<body>
<header>
  <h1><a href="/carrental/index.php">Car Rental</a></h1>
  <nav>
  <a href="/carrental/index.php">Home</a>
  <a href="/carrental/cars.php">Cars</a>

  <?php if (!empty($_SESSION['customer_id'])): ?>
    <a href="/carrental/my_bookings.php">My Bookings</a>
    <a href="/carrental/logout.php">Logout</a>
  <?php else: ?>
    <a href="/carrental/register.php">Register</a>
    <a href="/carrental/login.php">Login</a>
    <a href="/carrental/admin/login.php">Admin</a>
  <?php endif; ?>
  </nav>

</header>
<main>
