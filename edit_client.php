<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin-login.php');
    exit();
}

include 'db_connection.php';

// Check 'id' parameter is set
if (isset($_GET['id'])) {
    $client_id = $_GET['id'];

    $clientSql = "SELECT * FROM client_signup WHERE id = :id";
    $stmt = $db->prepare($clientSql);
    $stmt->bindParam(':id', $client_id);
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        echo 'Client not found.';
        exit();
    }
} else {

    echo 'Invalid client ID.';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $area_of_law = $_POST['area_of_law'];
    $budget = $_POST['budget'];
    $user_type = $_POST['user_type'];

    $updateSql = "UPDATE client_signup SET name = :name, email = :email, phone = :phone, 
                  address = :address, area_of_law = :area_of_law, budget = :budget, user_type = :user_type 
                  WHERE id = :id";
    $stmt = $db->prepare($updateSql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':area_of_law', $area_of_law);
    $stmt->bindParam(':budget', $budget);
    $stmt->bindParam(':user_type', $user_type);
    $stmt->bindParam(':id', $client_id);

    if ($stmt->execute()) {
        header('Location: view_clients.php');
        exit();
    } else {
        echo 'Update failed.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Client</title>
</head>
<body>
<header>
    <nav>
        <h3><center>Edit Client</center></h3>
        <a href="admin.php?action=dashboard">Dashboard</a>
        <a href="view_clients.php">View Clients</a>
        <a href="view_lawyers.php">View Lawyers</a>
        <a href="add_lawyer.php">Add Lawyer</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="content">
    <h2>Edit Client</h2>
    <form action="edit_client.php?id=<?php echo $client['id']; ?>" method="post">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $client['name']; ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $client['email']; ?>" required><br><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $client['phone']; ?>" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $client['address']; ?>" required><br><br>

        <label for="area_of_law">Area of Law:</label>
        <input type="text" id="area_of_law" name="area_of_law" value="<?php echo $client['area_of_law']; ?>" required><br><br>

        <label for="budget">Budget:</label>
        <input type="text" id="budget" name="budget" value="<?php echo $client['budget']; ?>" required><br><br>

        <label for="user_type">User Type:</label>
        <input type="text" id="user_type" name="user_type" value="<?php echo $client['user_type']; ?>" required><br><br>

        <input type="submit" value="Update">
    </form>
</div>

<?php include 'footer.php'; ?> 
</body>
</html>
