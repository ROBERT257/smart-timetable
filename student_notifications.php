<?php
include 'db.php';

// In a real system, this should come from session after login
$student_id = 'STU001';

$stmt = $conn->prepare("SELECT * FROM notifications WHERE student_id = ? ORDER BY date_sent DESC");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Notifications</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #e3f2fd;
      margin: 0;
      padding: 40px;
      color: #333;
    }

    h2 {
      text-align: center;
      color: #1565c0;
    }

    .notif-container {
      max-width: 800px;
      margin: 20px auto;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      padding: 30px;
    }

    .notif {
      border-bottom: 1px solid #ddd;
      padding: 15px 0;
    }

    .notif:last-child {
      border-bottom: none;
    }

    .notif-time {
      font-size: 0.9em;
      color: #777;
    }

    .notif-message {
      font-size: 1.1em;
      color: #333;
      margin-top: 5px;
    }
  </style>
</head>
<body>

<h2>📢 My Notifications</h2>

<div class="notif-container">
  <?php
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<div class='notif'>
              <div class='notif-time'>" . date('M d, Y H:i', strtotime($row['date_sent'])) . "</div>
              <div class='notif-message'>{$row['message']}</div>
            </div>";
    }
  } else {
    echo "<p style='text-align:center;'>No notifications found.</p>";
  }
  ?>
</div>

</body>
</html>
