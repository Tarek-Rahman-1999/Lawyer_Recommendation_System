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

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

$lawyersPerPage = 6;

$offset = $offset * $lawyersPerPage;


$stmt = $db->prepare("SELECT id, name, email, address, area_of_practice, picture_url FROM lawyer_signup LIMIT :limit OFFSET :offset");
$stmt->bindParam(':limit', $lawyersPerPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recommendedLawyers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['hire_lawyer'])) {
    $lawyerID = $_POST['lawyer_id'];

    $checkStmt = $db->prepare("SELECT * FROM client_lawyer_relationship WHERE client_id = :client_id AND lawyer_id = :lawyer_id");
    $checkStmt->bindParam(':client_id', $userID);
    $checkStmt->bindParam(':lawyer_id', $lawyerID);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() === 0) {
        $insertStmt = $db->prepare("INSERT INTO client_lawyer_relationship (client_id, lawyer_id) VALUES (:client_id, :lawyer_id)");
        $insertStmt->bindParam(':client_id', $userID);
        $insertStmt->bindParam(':lawyer_id', $lawyerID);
        $insertStmt->execute();
    }
    
    header('Location: clhome.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Lawyer Recommendation</title>
</head>
<body>
<?php include 'header.php'; ?> 

<style>
    .recommended-lawyers {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .lawyer-item {
        width: 30%;
        margin-bottom: 10px;
        box-sizing: border-box;
        padding: 10px;
        border: 2px solid #ccc;
        text-align: center;
    }

    .lawyer-picture {
        max-width: 50%;
        height: 50%;
    }

    .lawyer-name {
        font-weight: bold;
        margin: 10px 0;
    }

    .load-more-button {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
</style>
<div class="content">
    <header>
        <div class="user-info">
            <div class="user-name">
                Welcome, <?php echo $userName; ?>
            </div>
        </div>
    </header>

    <div class="recommended-lawyers">
        <h3>Recommended Lawyers</h3>
        <?php foreach ($recommendedLawyers as $lawyer) { ?>
            <li class="lawyer-item">
                <img src="<?php echo $lawyer['picture_url']; ?>" alt="<?php echo $lawyer['name']; ?>">
                <h3><?php echo $lawyer['name']; ?></h3>
                <p>Email: <?php echo $lawyer['email']; ?></p>
                <p>Address: <?php echo $lawyer['address']; ?></p>
                <p>Area of Practice: <?php echo $lawyer['area_of_practice']; ?></p>
                <form method="POST" action="clhome.php">
                    <input type="hidden" name="lawyer_id" value="<?php echo $lawyer['id']; ?>">
                    <input type="submit" name="hire_lawyer" value="Hire & Consult">
                </form>
            </li>
        <?php } ?>
    </div>

    <!-- Load More Button -->
    <div class="load-more-button">
        <form method="get" action="clhome.php">
            <input type="hidden" name="offset" value="<?php echo $offset + 1; ?>">
            <input type="submit" value="Load More">
        </form>
    </div>
</div>
<?php include 'footer.php'; ?> 
</body>
</html>
