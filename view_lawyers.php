<?php
session_start(); 

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin-login.php');
    exit();
}


include 'db_connection.php';

// Fetch all lawyers
$lawyersSql = "SELECT * FROM lawyer_signup";
$lawyers = $db->query($lawyersSql)->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Lawyers</title>
</head>
<body>
<header>
    <nav>
        <h3><center>View Lawyers</center></h3>
        <a href="admin.php?action=dashboard">Dashboard</a>
        <a href="view_clients.php">View Clients</a>
        <a href="view_lawyers.php">View Lawyers</a>
        <a href="add_lawyer.php">Add Lawyer</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="content">
    <h2>Lawyer List</h2>
    <table>
        <tr>
            <th>Picture&nbsp;&nbsp;</th>
            <th>Name&nbsp;&nbsp;</th>
            <th>Email&nbsp;&nbsp;</th>
            <th>Phone&nbsp;&nbsp;</th>
            <th>Address&nbsp;&nbsp;</th>
            <th>Area of Practice&nbsp;&nbsp;</th>
            <th>Experience&nbsp;&nbsp;</th>
            <th>User Type&nbsp;&nbsp;</th>
            <th>Actions&nbsp;&nbsp;</th>
        </tr>
        <?php foreach ($lawyers as $lawyer) : ?>
            <tr>
            <td><img src="<?php echo $lawyer['picture_url']; ?>" alt="Lawyer Picture" width="100"></td>
                <td><?php echo $lawyer['name']; ?></td>
                <td><?php echo $lawyer['email']; ?></td>
                <td><?php echo $lawyer['phone']; ?></td>
                <td><?php echo $lawyer['address']; ?></td>
                <td><?php echo $lawyer['area_of_practice']; ?></td>
                <td><?php echo $lawyer['experience']; ?></td>
                <td><?php echo $lawyer['user_type']; ?></td>
                <td>
                    <a href="edit_lawyer.php?id=<?php echo $lawyer['id']; ?>">Edit</a>
                    <a href="delete_lawyer.php?id=<?php echo $lawyer['id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this lawyer?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
