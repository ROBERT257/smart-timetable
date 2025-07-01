<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Weekly Timetable</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #ffffff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 900px;
      background: white;
      margin: 30px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      color: #1565c0;
      margin-bottom: 20px;
    }
    form label {
      font-weight: bold;
      display: block;
      margin: 15px 0 5px;
    }
    form input,
    form select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background-color: #f9f9f9;
    }
    .day-block {
      border-top: 1px solid #ddd;
      padding-top: 20px;
      margin-top: 30px;
    }
    .lesson-pair {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 15px;
    }
    .lesson-pair > div {
      flex: 1 1 200px;
    }
    button {
      width: 100%;
      margin-top: 30px;
      padding: 14px;
      background-color: #1976d2;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0d47a1;
    }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      text-decoration: none;
      color: #1976d2;
      font-weight: bold;
    }
    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🗓️ Add Weekly Timetable</h2>
    <?php if (isset($_GET['success'])): ?>
      <p style="color: green; font-weight: bold; text-align: center;">✅ Timetable saved successfully!</p>
    <?php endif; ?>

    <form method="post" action="save_timetable.php">
      <label>👨‍🏫 Teacher Name:</label>
      <input type="text" name="teacher_name" required>

      <label>🎓 Course (Optional):</label>
      <input type="text" name="course">

      <?php
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $day):
      ?>
        <div class="day-block">
          <h3><?= $day ?></h3>
          <?php for ($i = 1; $i <= 2; $i++): ?>
            <div class="lesson-pair">
              <div>
                <label>Subject <?= $i ?>:</label>
                <input type="text" name="subject_<?= strtoupper(substr($day, 0, 3)) . $i ?>" placeholder="e.g. Mathematics">
              </div>
              <div>
                <label>Start Time <?= $i ?>:</label>
                <input type="time" name="start_<?= strtoupper(substr($day, 0, 3)) . $i ?>">
              </div>
              <div>
                <label>End Time <?= $i ?>:</label>
                <input type="time" name="end_<?= strtoupper(substr($day, 0, 3)) . $i ?>">
              </div>
              <div>
                <label>Room <?= $i ?>:</label>
                <input type="text" name="room_<?= strtoupper(substr($day, 0, 3)) . $i ?>" placeholder="e.g. P3L4">
              </div>
              <div>
                <label>Live Class Link <?= $i ?> (optional):</label>
                <input type="url" name="link_<?= strtoupper(substr($day, 0, 3)) . $i ?>" placeholder="https://zoom.us/j/...">
              </div>
            </div>
          <?php endfor; ?>
        </div>
      <?php endforeach; ?>

      <button type="submit">💾 Save Timetable</button>
    </form>
    <a class="back-link" href="../index.php">← Back to Home</a>
  </div>
</body>
</html>