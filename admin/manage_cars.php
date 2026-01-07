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

// Handle Add Car
if (isset($_POST['add_car'])) {
    $model = trim($_POST['model']);
    $plate = trim($_POST['plate']);
    $price = (float)$_POST['price'];
    $status = $_POST['status'];
    $image = trim($_POST['image']); // just URL or relative path

    if (!$model || !$plate || !$price) {
        $errors[] = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO cars (Model, PlateNo, Status, RentPrice, Image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$model, $plate, $status, $price, $image]);
            $success = "Car added successfully.";
        } catch (PDOException $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    }
}

// Handle Delete Car
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM cars WHERE CarID = ?");
    $stmt->execute([$id]);
    header("Location: manage_cars.php");
    exit;
}

// Fetch cars
$stmt = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC");
$cars = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Cars - Admin</title>
<link rel="stylesheet" href="/carrental/assets/style.css">
</head>
<body>
<section class="content">
  <h2>Manage Cars</h2>
  <a href="dashboard.php" class="btn">⬅ Back to Dashboard</a>

  <?php foreach ($errors as $err) echo "<p class='error'>".htmlspecialchars($err)."</p>"; ?>
  <?php if ($success) echo "<p class='success'>$success</p>"; ?>

  <h3>Add New Car</h3>
  <form method="post">
    <label>Model</label><br>
    <input name="model" required><br><br>

    <label>Plate No</label><br>
    <input name="plate" required><br><br>

    <label>Price per day (₹)</label><br>
    <input name="price" type="number" step="0.01" required><br><br>

    <label>Status</label><br>
    <select name="status">
      <option value="Available">Available</option>
      <option value="Maintenance">Maintenance</option>
    </select><br><br>

    <label>Image Path (optional)</label><br>
    <input name="image" placeholder="assets/images/car.jpg"><br><br>

    <button type="submit" name="add_car" class="btn">Add Car</button>
  </form>

  <h3>Car List</h3>
  <table border="1" cellpadding="8" cellspacing="0" style="margin:auto;">
    <tr>
      <th>ID</th>
      <th>Model</th>
      <th>Plate</th>
      <th>Price/day</th>
      <th>Status</th>
      <th>Image</th>
      <th>Action</th>
    </tr>
    <?php foreach ($cars as $car): ?>
    <tr>
      <td><?php echo $car['CarID']; ?></td>
      <td><?php echo htmlspecialchars($car['Model']); ?></td>
      <td><?php echo htmlspecialchars($car['PlateNo']); ?></td>
      <td>₹ <?php echo number_format($car['RentPrice'],2); ?></td>
      <td><?php echo $car['Status']; ?></td>
      <td>
        <?php if ($car['Image']): ?>
          <img src="/carrental/<?php echo htmlspecialchars($car['Image']); ?>" width="100">
        <?php endif; ?>
      </td>
      <td>
        <a class="btn" href="manage_cars.php?delete=<?php echo $car['CarID']; ?>" onclick="return confirm('Delete this car?');">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</section>
</body>
</html>
