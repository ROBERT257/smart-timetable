<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Smart School System - Home</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f3f9ff;
      margin: 0;
      padding: 0;
    }

    .main-header {
      background: #1976d2;
      color: white;
      padding: 20px;
      text-align: center;
    }

    nav a {
      margin: 0 10px;
      text-decoration: none;
      color: #ffffff;
      font-weight: bold;
      background-color: #1565c0;
      padding: 8px 16px;
      border-radius: 6px;
    }

    nav a:hover {
      background-color: #0d47a1;
    }

    .hero {
      text-align: center;
      padding: 50px 20px;
      background: linear-gradient(to right, #e3f2fd, #fff);
    }

    .cta-btn {
      display: inline-block;
      padding: 10px 20px;
      margin-top: 20px;
      background-color: #1976d2;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: background 0.3s ease;
      cursor: pointer;
    }

    .cta-btn:hover {
      background-color: #0d47a1;
    }

    footer {
      background: #1976d2;
      color: white;
      text-align: center;
      padding: 15px;
      margin-top: 40px;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 30px;
      border-radius: 10px;
      text-align: center;
      width: 300px;
    }

    .modal-content h3 {
      margin-bottom: 20px;
    }

    .role-btn {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 12px;
      background-color: #1976d2;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .role-btn:hover {
      background-color: #0d47a1;
    }

    .close-btn {
      background: #ccc;
      color: black;
    }
  </style>
</head>
<body class="home-page">
  <header class="main-header">
    <h1>📚 Smart School System</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="timetable.php">View Timetable</a>
       <a href="student_notifications.php">📢 Notifications</a>
     


    </nav>
  </header>

  <section class="hero">
    <h2>Welcome to the Smart School System</h2>
    <p>Your one-stop solution for managing classes, attendance, and timetables efficiently.</p>
    <button class="cta-btn" onclick="openModal()">Get Started</button>
  </section>

  <!-- Modal -->
  <div class="modal" id="loginModal">
    <div class="modal-content">
      <h3>Who are you?</h3>
      <form action="student_schedule.php" method="get">
        <button type="submit" class="role-btn">🎓 Student</button>
      </form>
      <form action="admin/admin_login.php" method="get">
        <button type="submit" class="role-btn">👨‍🏫 Lecture</button>
      </form>
      <button class="role-btn close-btn" onclick="closeModal()">Cancel</button>
    </div>
  </div>

  <footer>
    <p>&copy; <?php echo date("Y"); ?> Smart School System. All rights reserved.</p>
  </footer>

  <script>
    function openModal() {
      document.getElementById('loginModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('loginModal').style.display = 'none';
    }

    window.onclick = function(event) {
      const modal = document.getElementById('loginModal');
      if (event.target === modal) {
        closeModal();
      }
    }
  </script>
</body>
</html>
