<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Teacher Timetable</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e1f5fe, #ffffff);
      margin: 0;
      padding: 40px;
      color: #333;
    }

    h2 {
      text-align: center;
      color: #1565c0;
      margin-bottom: 30px;
    }

    .teacher-name-heading {
      text-align: center;
      color: #2e7d32;
      font-size: 22px;
      font-weight: bold;
      margin-top: 40px;
    }

    form {
      max-width: 500px;
      margin: auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    form label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }

    form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
    }

    form button {
      width: 100%;
      padding: 12px;
      background-color: #1976d2;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    form button:hover {
      background-color: #0d47a1;
    }

    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    table th, table td {
      padding: 14px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    table th {
      background-color: #1976d2;
      color: white;
    }

    table tr:hover {
      background-color: #f1f1f1;
    }

    .no-schedule {
      text-align: center;
      color: #d32f2f;
      margin-top: 20px;
      font-weight: bold;
    }

    .print-btn {
      display: block;
      width: fit-content;
      margin: 20px auto;
      background-color: #43a047;
      color: white;
      padding: 12px 20px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .print-btn:hover {
      background-color: #2e7d32;
    }

    /* ✅ Hide header and form when printing */
    @media print {
      h2, form, .print-btn {
        display: none !important;
      }
    }
  </style>
</head>
<body>
  <h2>📅 View My Schedule</h2>
  <form method="get">
    <label>Enter Teacher Name:</label>
    <input type="text" name="teacher_name" required>
    <button type="submit">View Timetable</button>
  </form>

  <?php
  if (isset($_GET['teacher_name'])) {
    $name = htmlspecialchars($_GET['teacher_name']);
    $stmt = $conn->prepare("SELECT * FROM timetable WHERE teacher_name = ? ORDER BY FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday'), start_time");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      echo "<div class='teacher-name-heading'>Timetable for: $name</div>";
      echo "<table>
              <tr><th>Day</th><th>Subject</th><th>Start</th><th>End</th><th>Room</th></tr>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['day_of_week']}</td>
                <td>{$row['subject']}</td>
                <td>{$row['start_time']}</td>
                <td>{$row['end_time']}</td>
                <td>{$row['room']}</td>
              </tr>";
      }
      echo "</table><button class='print-btn' onclick='window.print()'>🖨️ Print Timetable</button>";
    } else {
      echo "<p class='no-schedule'>❌ No schedule found for <strong>$name</strong>.</p>";
    }
  }
  ?>
</body>
</html>
