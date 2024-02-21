<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.html');
    exit;
}

$userID = $_SESSION['id'];
$stmt = $db->prepare("SELECT name FROM client_signup WHERE id = :id");
$stmt->bindParam(':id', $userID);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
$userName = $userData['name'];

$stmt = $db->prepare("
    SELECT l.name, l.email, l.address, l.area_of_practice, l.picture_url
    FROM lawyer_signup l
    JOIN client_lawyer_relationship clr ON l.id = clr.lawyer_id
    WHERE clr.client_id = :client_id
");
$stmt->bindParam(':client_id', $userID);
$stmt->execute();
$hiredLawyers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$updatePriorityStmt = $db->prepare("UPDATE lawyer_signup SET priority_index = priority_index + 1 WHERE id = :lawyer_id");
$updatePriorityStmt->bindParam(':lawyer_id', $lawyerID);
if ($updatePriorityStmt->execute()) {
    
} else {
    echo 'Failed to update priority index: ' . $updatePriorityStmt->errorInfo()[2];
}

?>

<?php include 'header.php'; ?>
<div class="content">
    <header>
        <div class="user-info">
            <div class="user-name">
                Welcome, <?php echo $userName; ?>
            </div>
        </div>
    </header>

    <h2>Your Hired Lawyers</h2>
    <ul>
        <?php foreach ($hiredLawyers as $lawyer) { ?>
            <li>
                <img src="<?php echo $lawyer['picture_url']; ?>" alt="<?php echo $lawyer['name']; ?>">
                <h3><?php echo $lawyer['name']; ?></h3>
                <p>Email: <?php echo $lawyer['email']; ?></p>
                <p>Address: <?php echo $lawyer['address']; ?></p>
                <p>Area of Practice: <?php echo $lawyer['area_of_practice']; ?></p>
            </li>
        <?php } ?>
    </ul>
</div>
<?php include 'footer.php'; ?>
