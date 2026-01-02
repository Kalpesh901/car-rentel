<?php require 'inc/header.php'; ?>
<section class="hero">
  <h2>Drive Your Dream Car Today 🚗</h2>
</section>

<section class="content">
  <h2>Popular Choices</h2>
  <div class="cards">
    <?php
    require 'inc/db.php';
    $stmt = $pdo->query("SELECT * FROM cars LIMIT 6");
    while($car = $stmt->fetch()):
    ?>
    <div class="card">
      <img src="<?php echo htmlspecialchars($car['Image']); ?>" alt="">
      <h3><?php echo htmlspecialchars($car['Model']); ?></h3>
      <p>₹ <?php echo number_format($car['RentPrice'],2); ?> / day</p>
      <a class="btn" href="cars.php?car=<?php echo $car['CarID']; ?>">View & Book</a>
    </div>
    <?php endwhile; ?>
  </div>
</section>
<?php require 'inc/footer.php'; ?>
