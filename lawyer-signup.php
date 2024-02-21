<?php
$user_type = "lawyer";
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$area_of_practice = $_POST['area_of_practice'];
$experience = $_POST['experience'];
$password = $_POST['password2'];
$confirm_password = $_POST['confirm_password2'];

if (!preg_match('/^[A-Za-z]+(?: [A-Za-z]+){0,2}$/', $name)) {
  echo '<p style="color:red;">Please enter a valid name.</p>';
  return;
}

if (!preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
  echo '<p style="color:red;">Please enter a valid email address.</p>';
  return;
}

if (!preg_match('/^(?:\+?880|0)\d{9,10}$/', $phone)) {
  echo '<p style="color:red;">Please enter a valid Bangladeshi mobile number.</p>';
  return;
}

if (!preg_match('/^[\w\s.,-]+$/i', $address)) {
  echo '<p style="color:red;">Please enter a valid address.</p>';
  return;
}

if (!preg_match('/^[A-Za-z\s]+$/i', $area_of_practice)) {
  echo '<p style="color:red;">Please enter a valid area of law.</p>';
  return;
}

if (!preg_match('/^\d+$/', $experience)) {
  echo '<p style="color:red;">Please enter your Experience year </p>';
  return;
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{5,20})/', $password)) {
  echo '<p style="color:red;">Please enter a valid password.</p>';
  return;
}

if ($password !== $confirm_password) {
  echo 'Passwords do not match.';
  exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$db = new PDO('mysql:host=localhost;dbname=lawyer_recommender', 'root', '');

$checkEmail = "SELECT email FROM lawyer_signup WHERE LOWER(email) = LOWER('$email')";

$existingEmail = $db->query($checkEmail);

if ($existingEmail->rowCount() > 0) {
    echo '<script>';
    echo 'alert("Email address already exists. Please use a different email address.");';
    echo 'window.location="signup.html";'; 
    echo '</script>';
} else {
    $sql = "INSERT INTO lawyer_signup (name, email, phone, address, area_of_practice, experience, password, user_type) VALUES ('$name', '$email', '$phone', '$address', '$area_of_practice', '$experience', '$hashed_password','$user_type')";
    
    if ($db->exec($sql)) {
        echo '<script>';
        echo 'alert("Registration successful! You can now log in using your credentials.");';
        echo 'window.location="login.html";';
        echo '</script>';
    } else {
        echo '<script>';
        echo 'alert("Registration failed. Please try again.");';
        echo 'window.location="signup.html";';
        echo '</script>';
    }
}


?>