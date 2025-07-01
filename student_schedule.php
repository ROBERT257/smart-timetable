<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>📅 Student Schedule</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f8ff;
      padding: 30px;
    }
    h2 {
      text-align: center;
      color: #1565c0;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    form {
      text-align: center;
      margin-bottom: 30px;
    }
    input[type="text"] {
      padding: 10px;
      width: 60%;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      padding: 10px 20px;
      background-color: #1976d2;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }
    th {
      background-color: #1976d2;
      color: white;
    }
    .notifications {
      background: #fff3cd;
      padding: 15px;
      margin-top: 20px;
      border: 1px solid #ffeeba;
      border-radius: 6px;
    }
    .error {
      color: red;
      text-align: center;
    }
    .section-title {
      margin-top: 30px;
      color: #0d47a1;
      text-align: center;
    }
    .live-btn {
      background-color: #43a047;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📚 Student Timetable & Notifications</h2>

    <form method="get">
      <input type="text" name="student_id" placeholder="Enter Your Student ID" required>
      <button type="submit">View My Schedule</button>
    </form>

    <?php
    if (isset($_GET['student_id'])) {
      $student_id = htmlspecialchars(trim($_GET['student_id']));

      // Get Timetable
      echo "<h3 class='section-title'>📅 Weekly Timetable</h3>";
      $stmt = $conn->prepare("SELECT * FROM student_schedule WHERE student_id = ? ORDER BY 
        FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday'), start_time");
      $stmt->bind_param("s", $student_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        echo "<table>
                <tr><th>Day</th><th>Subject</th><th>Start</th><th>End</th><th>Room</th><th>Live Class</th></tr>";
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>" . htmlspecialchars($row['day_of_week']) . "</td>
                  <td>" . htmlspecialchars($row['subject']) . "</td>
                  <td>" . htmlspecialchars($row['start_time']) . "</td>
                  <td>" . htmlspecialchars($row['end_time']) . "</td>
                  <td>" . (!empty($row['room']) ? htmlspecialchars($row['room']) : "N/A") . "</td>
                  <td>";
          if (!empty($row['live_link'])) {
            echo "<a href='" . htmlspecialchars($row['live_link']) . "' target='_blank' class='live-btn'>Join Class</a>";
          } else {
            echo "N/A";
          }
          echo "</td></tr>";
        }
        echo "</table>";
      } else {
        echo "<p class='error'>No schedule found for Student ID: <strong>$student_id</strong>.</p>";
      }

      // Get Notifications
      echo "<h3 class='section-title'>🔔 Notifications</h3>";
      $notif = $conn->prepare("SELECT * FROM notifications WHERE student_id = ? ORDER BY date_sent DESC");
      $notif->bind_param("s", $student_id);
      $notif->execute();
      $notif_result = $notif->get_result();

      if ($notif_result->num_rows > 0) {
        echo "<div class='notifications'>";
        while ($row = $notif_result->fetch_assoc()) {
          echo "<p><strong>[{$row['date_sent']}]</strong> - 📘 <em>{$row['unit']}</em><br>📩 {$row['message']}</p><hr>";
        }
        echo "</div>";
      } else {
        echo "<p style='text-align:center;'>No new notifications.</p>";
      }
    }
    ?>
  </div>
</body>
</html>
