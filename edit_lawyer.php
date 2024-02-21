<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin-login.php');
    exit();
}
include 'db_connection.php';

if (isset($_GET['id'])) {
    $lawyer_Id = $_GET['id'];

    $lawyerSql = "SELECT * FROM lawyer_signup WHERE id = :id";
    $stmt = $db->prepare($lawyerSql);
    $stmt->bindParam(':id', $lawyer_Id);
    $stmt->execute();
    $lawyer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$lawyer) {
        header('Location: view_lawyers.php'); 
        exit();
    }
} else {
    header('Location: view_lawyers.php'); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $area_of_practice = $_POST['area_of_practice'];
    $experience = $_POST['experience'];
    $user_type = $_POST['user_type'];

    $picture_url = $lawyer['picture_url'];

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['picture']['name']);

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile)) {
            $picture_url = $uploadFile;
        } else {
            echo 'File upload failed.';
            exit();
        }
    }

    $updateSql = "UPDATE lawyer_signup 
                  SET name = :name, 
                      email = :email, 
                      phone = :phone, 
                      address = :address, 
                      area_of_practice = :area_of_practice, 
                      experience = :experience, 
                      user_type = :user_type,
                      picture_url = :picture_url
                  WHERE id = :id";
    $stmt = $db->prepare($updateSql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':area_of_practice', $area_of_practice);
    $stmt->bindParam(':experience', $experience);
    $stmt->bindParam(':user_type', $user_type);
    $stmt->bindParam(':picture_url', $picture_url);
    $stmt->bindParam(':id', $lawyer_Id);

    if ($stmt->execute()) {
        header('Location: view_lawyers.php');
        exit();
    } else {
        $error = "Error updating lawyer information. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lawyer</title>
</head>
<body>
<header>
    <nav>
        <h3><center>Edit Lawyer</center></h3>
        <a href="admin.php?action=dashboard">Dashboard</a>
        <a href="view_clients.php">View Clients</a>
        <a href="view_lawyers.php">View Lawyers</a>
        <a href="add_lawyer.php">Add Lawyer</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="content">
    <h2>Edit Lawyer</h2>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $lawyer['name']; ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $lawyer['email']; ?>" required><br><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $lawyer['phone']; ?>" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo $lawyer['address']; ?>" required><br><br>

        <label for="area_of_practice">Area of Practice:</label>
        <input type="text" id="area_of_practice" name="area_of_practice" value="<?php echo $lawyer['area_of_practice']; ?>" required><br><br>

        <label for="experience">Experience:</label>
        <input type="text" id="experience" name="experience" value="<?php echo $lawyer['experience']; ?>" required><br><br>

        <label for="user_type">User Type:</label>
        <input type="text" id="user_type" name="user_type" value="<?php echo $lawyer['user_type']; ?>" required><br><br>

        <label for="picture">Picture:</label>
        <input type="file" id="picture" name="picture"><br><br>

        <button type="submit">Update</button>
    </form>
</div>

<?php include 'footer.php'; ?> 
</body>
</html>
