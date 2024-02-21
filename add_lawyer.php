<?php
session_start(); 

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin-login.php'); 
    exit();
}


include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $area_of_practice = $_POST['area_of_practice'];
    $experience = $_POST['experience'];
    $password = $_POST['password']; 

    $picture_url = null;

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // the directory where I want to store the pictures
        $uploadFile = $uploadDir . basename($_FILES['picture']['name']);
        
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile)) {
            
            $picture_url = $uploadFile;
        } else {

            echo 'File upload failed.';
            exit();
        }
    }

    $user_type = "lawyer";

    $insertSql = "INSERT INTO lawyer_signup (email, name, phone, address, area_of_practice, experience, password, user_type, picture_url)
                  VALUES (:email, :name, :phone, :address, :area_of_practice, :experience, :password, :user_type, :picture_url)";
    
    $stmt = $db->prepare($insertSql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':area_of_practice', $area_of_practice);
    $stmt->bindParam(':experience', $experience);
    $stmt->bindParam(':password', $password); 
    $stmt->bindParam(':user_type', $user_type); 
    $stmt->bindParam(':picture_url', $picture_url);

    if ($stmt->execute()) {
        header('Location: view_lawyers.php');
        exit();
    } else {
        echo 'Insert failed.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Lawyer</title>
</head>
<body>
<header>
    <nav>
        <h3><center>Add Lawyer</center></h3>
        <a href="admin.php?action=dashboard">Dashboard</a>
        <a href="view_clients.php">View Clients</a>
        <a href="view_lawyers.php">View Lawyers</a>
        <a href="add_lawyer.php">Add Lawyer</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="content">
    <h2>Add Lawyer</h2>
    <form action="add_lawyer.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label> 
        <input type="password" id="password" name="password" required><br><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone"><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address"><br><br>

        <label for="area_of_practice">Area of Practice:</label>
        <input type="text" id="area_of_practice" name="area_of_practice" required><br><br>

        <label for="experience">Experience (in years):</label>
        <input type="number" id="experience" name="experience" required><br><br>

        <label for="picture">Picture:</label>
        <input type="file" id="picture" name="picture"><br><br>

        <input type="submit" value="Add Lawyer">
    </form>
</div>

<?php include 'footer.php'; ?> 
</body>
</html>
