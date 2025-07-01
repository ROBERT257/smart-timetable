<!-- admin/admin_login.php -->
<?php
$adminPassword = "J77-1577-2022"; // You can change this password

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $role = $_POST['role'];

  if ($role === "admin") {
    $password = $_POST['password'] ?? '';
    $department = $_POST['department'] ?? '';

    if ($password === $adminPassword) {
      // You can also store department in session if needed
      header("Location: admin_add_timetable.php");
      exit();
    } else {
      $error = "❌ Incorrect Admin Password.";
    }
  } elseif ($role === "lecturer") {
    header("Location: lecture_login.php"); // If you want to build this later
    exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin / Lecturer Login</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      background: #f5f5f5;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    form label {
      display: block;
      margin: 15px 0 5px;
    }

    form input, select {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .btn {
      margin-top: 20px;
      background-color: #1976d2;
      color: white;
      padding: 10px;
      width: 100%;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .btn:hover {
      background-color: #0d47a1;
    }

    .error {
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>👨‍🏫 Admin / Lecturer Login</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
      <label for="role">Select Role:</label>
      <select name="role" id="role" onchange="toggleFields()" required>
        <option value="">-- Choose Role --</option>
        <option value="admin">Admin</option>
        <option value="lecturer">Lecturer</option>
      </select>

      <div id="adminFields" style="display:none;">
        <label for="password">Admin Password:</label>
        <input type="password" name="password" id="password">

        <label for="department">Department:</label>
        <input type="text" name="department" id="department">
      </div>

      <button type="submit" class="btn">Continue</button>
    </form>
  </div>

  <script>
    function toggleFields() {
      const role = document.getElementById('role').value;
      const adminFields = document.getElementById('adminFields');
      adminFields.style.display = (role === 'admin') ? 'block' : 'none';
    }
  </script>
</body>
</html>
