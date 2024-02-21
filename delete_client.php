<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $client_id = $_GET['id'];

    $deleteSql = "DELETE FROM client_signup WHERE id = :id";
    $stmt = $db->prepare($deleteSql);
    $stmt->bindParam(':id', $client_id);
    $stmt->execute();

    header('Location: view_clients.php');
    exit();
} else {
    echo 'Invalid client ID.';
    exit();
}
?>
