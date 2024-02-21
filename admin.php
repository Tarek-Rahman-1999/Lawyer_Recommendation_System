<?php
session_start(); 

$admin_username = 'admin';
$admin_password = 'admin123'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_username = $_POST['username'];
    $entered_password = $_POST['password'];

    if ($entered_username === $admin_username && $entered_password === $admin_password) {
        $_SESSION['is_admin'] = true; // Set session variable to indicate admin login
        header('Location: admin.php?action=dashboard');
        exit();
    } else {
        echo '<p style="color:red;">Invalid username or password.</p>';
    }
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
    </head>
    <body>
    <div class="container">
        <h1>Admin Login</h1>
        <form action="admin.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
    </body>
    </html>
    <?php
    exit();
}

include 'db_connection.php';

//the count of clients
$clientCountSql = "SELECT COUNT(*) FROM client_signup";
$clientCount = $db->query($clientCountSql)->fetchColumn();

//the count of lawyers
$lawyerCountSql = "SELECT COUNT(*) FROM lawyer_signup";
$lawyerCount = $db->query($lawyerCountSql)->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
<header>
    <nav>
        <h3><center>Admin Dashboard</center></h3>
        <a href="admin.php">Dashboard</a>
        <a href="view_clients.php">View Clients</a>
        <a href="view_lawyers.php">View Lawyers</a>
        <a href="add_lawyer.php">Add Lawyer</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="content">
    <h2>Welcome, Admin!</h2>
    <p>Total Clients: <?php echo $clientCount; ?></p>
    <p>Total Lawyers: <?php echo $lawyerCount; ?></p>
    
</div>

<?php include 'footer.php'; ?> 
</body>
</html>
