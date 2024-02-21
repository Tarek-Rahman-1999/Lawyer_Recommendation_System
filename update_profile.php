<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['id'];

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $area_of_law = $_POST['area_of_law'];
    $budget = $_POST['budget'];

   //Validation

    $stmt = $db->prepare("UPDATE client_signup SET name = :name, phone = :phone, address = :address, area_of_law = :area_of_law, budget = :budget WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':area_of_law', $area_of_law);
    $stmt->bindParam(':budget', $budget);
    $stmt->bindParam(':id', $userID);

    if ($stmt->execute()) {
        header('Location: profile.php');
        exit;
    } else {
        echo '<script>alert("Update failed. Please try again.");</script>';
    }
}

$userID = $_SESSION['id'];
$stmt = $db->prepare("SELECT * FROM client_signup WHERE id = :id");
$stmt->bindParam(':id', $userID);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<div class="content">
    <h2>Update Profile</h2>
    <form action="update_profile.php" method="post" onsubmit="return confirm('Are you sure you want to update your profile?');">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $userData['name']; ?>" required><br><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $userData['phone']; ?>" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $userData['address']; ?>" required><br><br>

        <label for="area_of_law">Area of Law:</label>
        <input type="text" id="area_of_law" name="area_of_law" value="<?php echo $userData['area_of_law']; ?>" required><br><br>

        <label for="budget">Budget:</label>
        <input type="text" id="budget" name="budget" value="<?php echo $userData['budget']; ?>" required><br><br>

        <input type="submit" value="Update">
    </form>
</div>

<?php include 'footer.php'; ?>
