<?php
session_start(); 

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin-login.php'); 
    exit();
}


include 'db_connection.php';

$clientsSql = "SELECT * FROM client_signup";
$clients = $db->query($clientsSql)->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Clients</title>
</head>
<body>
<header>
    <nav>
        <h3><center>View Clients</center></h3>
        <a href="admin.php?action=dashboard">Dashboard</a>
        <a href="view_clients.php">View Clients</a>
        <a href="view_lawyers.php">View Lawyers</a>
        <a href="add_lawyer.php">Add Lawyer</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="content">
    <h2>Client List</h2>
    <table>
        <tr>
            <th>Name&nbsp;&nbsp;</th>
            <th>Email&nbsp;&nbsp;</th>
            <th>Phone&nbsp;&nbsp;</th>
            <th>Address&nbsp;&nbsp;</th>
            <th>Area of Law&nbsp;&nbsp;</th>
            <th>Budget&nbsp;&nbsp;</th>
            <th>User Type&nbsp;&nbsp;</th>
            <th>Actions&nbsp;&nbsp;</th>
        </tr>
        <?php foreach ($clients as $client) : ?>
            <tr>
                <td><?php echo $client['name']; ?></td>
                <td><?php echo $client['email']; ?></td>
                <td><?php echo $client['phone']; ?></td>
                <td><?php echo $client['address']; ?></td>
                <td><?php echo $client['area_of_law']; ?></td>
                <td><?php echo $client['budget']; ?></td>
                <td><?php echo $client['user_type']; ?></td>
                <td>
                    <a href="edit_client.php?id=<?php echo $client['id']; ?>">Edit</a>
                    <a href="delete_client.php?id=<?php echo $client['id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
