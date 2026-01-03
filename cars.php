<?php
require 'inc/header.php';
require 'inc/db.php';

if(isset($_GET['car'])){
    $carId = (int)$_GET['car'];
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE CarID = ?");
    $stmt->execute([$carId]);
    $car = $stmt->fetch();
    if(!$car) {
        echo "<p>Car not found.</p>";
        require 'inc/footer.php';
        exit;
    }
    // show detail + booking link
    ?>
    <section class="content">
      <h2><?php echo htmlspecialchars($car['Model']); ?></h2>
      <div class="card">
        <img src="<?php echo htmlspecialchars($car['Image']); ?>" alt="">
        <p>Plate: <?php echo htmlspecialchars($car['PlateNo']); ?></p>
        <p>Price: ₹ <?php echo number_format($car['RentPrice'],2); ?> / day</p>
        <p>Status: <?php echo htmlspecialchars($car['Status']); ?></p>
        <a class="btn" href="book.php?car=<?php echo $car['CarID']; ?>">Book Now</a>
      </div>
    </section>
    <?php
} else {
    // list all
    ?>
    <section class="content">
      <h2>Available Cars</h2>
      <div class="cards">
      <?php
      $stmt = $pdo->query("SELECT * FROM cars");
      while($car = $stmt->fetch()):
      ?>
        <div class="card">
          <img src="<?php echo htmlspecialchars($car['Image']); ?>" alt="">
          <h3><?php echo htmlspecialchars($car['Model']); ?></h3>
          <p>₹ <?php echo number_format($car['RentPrice'],2); ?> / day</p>
          <a class="btn" href="cars.php?car=<?php echo $car['CarID']; ?>">View</a>
        </div>
      <?php endwhile; ?>
      </div>
    </section>
    <?php
}
require 'inc/footer.php';
