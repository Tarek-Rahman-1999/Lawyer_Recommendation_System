<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['id'];

    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    // Validate 

    
    $stmt = $db->prepare("SELECT password FROM client_signup WHERE id = :id");
    $stmt->bindParam(':id', $userID);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($oldPassword, $userData['password'])) {
        
        if ($newPassword === $confirmNewPassword) {
    
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $db->prepare("UPDATE client_signup SET password = :password WHERE id = :id");
            $stmt->bindParam(':password', $hashedNewPassword);
            $stmt->bindParam(':id', $userID);

            if ($stmt->execute()) {
                echo '<script>alert("Password updated successfully.");</script>';
                header('Location: profile.php');
                exit;
            } else {
                echo '<script>alert("Password update failed. Please try again.");</script>';
            }
        } else {
            echo '<script>alert("New password and confirm new password do not match.");</script>';
        }
    } else {
        echo '<script>alert("Old password is incorrect.");</script>';
    }
}
?>

<?php include 'header.php'; ?>

<div class="content">
    <h2>Change Password</h2>
    <form action="updatepass.php" method="post" onsubmit="return confirm('Are you sure you want to change your password?');">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_new_password">Confirm New Password:</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" required><br><br>

        <input type="submit" value="Change Password">
    </form>
</div>

<?php include 'footer.php'; ?>
