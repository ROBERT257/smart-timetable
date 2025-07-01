<?php
session_start();
include '../db.php'; // Ensure this path is correct

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM lecturers WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $lecturer = $result->fetch_assoc();

    // 🛡️ Optional: replace this with password_verify() if you store hashed passwords
    if ($password === $lecturer['password']) {
      // ✅ Save all lecturer data in session (including email)
      $_SESSION['lecturer'] = $lecturer;

      header("Location: send_notification.php");
      exit();
    } else {
      $error = "❌ Incorrect password.";
    }
  } else {
    $error = "❌ Lecturer not found.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Lecturer Login</title>
  <style>
    body {
      background: #f4faff;
      font-family: Arial, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      width: 400px;
    }
    .login-box h2 {
      text-align: center;
      color: #1565c0;
    }
    .login-box input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    .login-box button {
      background: #1976d2;
      color: white;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
    .login-box .error {
      color: red;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>👨‍🏫 Lecturer Login</h2>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">🔐 Login</button>
    </form>
  </div>
</body>
</html>
