<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.html');
    exit;
}

$lawyerID = $_SESSION['id'];

$stmt = $db->prepare("
    SELECT c.name AS client_name, c.email AS client_email, c.phone AS client_phone, c.address AS client_address
    FROM client_signup c
    JOIN client_lawyer_relationship clr ON c.id = clr.client_id
    WHERE clr.lawyer_id = :lawyer_id
");
$stmt->bindParam(':lawyer_id', $lawyerID);
$stmt->execute();
$hiredClients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT name FROM lawyer_signup WHERE id = :id");
$stmt->bindParam(':id', $lawyerID);
$stmt->execute();
$lawyerData = $stmt->fetch(PDO::FETCH_ASSOC);
$lawyerName = $lawyerData['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Lawyer Home</title>
</head>
<body>
    <center><h3>Lawyer Recommendation System</h3></center>
<a href="lyhome.php">Home</a>&nbsp;&nbsp;&nbsp;
<a href="logout.php">Logout</a>&nbsp;&nbsp;&nbsp;<br><br>

<div class="content">
    <header>
        <div class="user-info">
            <div class="user-name">
                Welcome, <?php echo $lawyerName; ?>
            </div>
        </div>
    </header>

    <h3>Clients Who Have Hired You</h3>
    <ul>
        <?php foreach ($hiredClients as $client) { ?>
            <li>
                <h3><?php echo $client['client_name']; ?></h3>
                <p>Email: <?php echo $client['client_email']; ?></p>
                <p>Phone: <?php echo $client['client_phone']; ?></p>
                <p>Address: <?php echo $client['client_address']; ?></p>
            </li>
        <?php } ?>
    </ul>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
