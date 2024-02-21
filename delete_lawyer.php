<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $lawyer_id = $_GET['id'];

    $deleteSql = "DELETE FROM lawyer_signup WHERE id = :id";
    $stmt = $db->prepare($deleteSql);
    $stmt->bindParam(':id', $lawyer_id);
    $stmt->execute();

    header('Location: view_lawyers.php');
    exit();
} else {
    
    echo 'Invalid client ID.';
    exit();
}
?>
