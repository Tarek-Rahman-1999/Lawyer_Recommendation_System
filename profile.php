<?php
session_start();
include 'db_connection.php'; 

if (!isset($_SESSION['id'])) {
    header('Location: login.html');
    exit;
}

$userID = $_SESSION['id'];
$stmt = $db->prepare("SELECT * FROM client_signup WHERE id = :id");
$stmt->bindParam(':id', $userID);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?> 
<div class="content">

    <h2>User Profile</h2>
    <p>Name: <?php echo $userData['name']; ?></p>
    <p>Email: <?php echo $userData['email']; ?></p>
    <p>Phone: <?php echo $userData['phone']; ?></p>
    <p>Address: <?php echo $userData['address']; ?></p>
    <p>Area of Law: <?php echo $userData['area_of_law']; ?></p>
    <p>Budget: <?php echo $userData['budget']; ?></p>
    <a href="update_profile.php">Update Profile</a>
</div>
<?php include 'footer.php'; ?> 
