<?php
session_start();

if (isset($_SESSION['id'])) {
  header('Location: clhome.php'); 
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];


  $db = new PDO('mysql:host=localhost;dbname=lawyer_recommender', 'root', '');

  $clientSql = "SELECT * FROM client_signup WHERE email = ?";
  $clientStmt = $db->prepare($clientSql);
  $clientStmt->execute([$email]);
  $clientRow = $clientStmt->fetch(PDO::FETCH_ASSOC);

  $lawyerSql = "SELECT * FROM lawyer_signup WHERE email = ?";
  $lawyerStmt = $db->prepare($lawyerSql);
  $lawyerStmt->execute([$email]);
  $lawyerRow = $lawyerStmt->fetch(PDO::FETCH_ASSOC);


  if ($clientRow && password_verify($password, $clientRow['password'])) {
    $_SESSION['id'] = $clientRow['id'];
    $_SESSION['user_type'] = 'client'; 
    header('Location: clhome.php');
    exit();
  } elseif ($lawyerRow && password_verify($password, $lawyerRow['password'])) {
    $_SESSION['id'] = $lawyerRow['id'];
    $_SESSION['user_type'] = 'lawyer';
    header('Location: lyhome.php');
    exit();
  } else {
    echo '<p style="color:red;">Invalid email or password.</p>';
  }
}
?>
