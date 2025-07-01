<?php
include 'db.php';
$student_id = 'S123'; // Assume logged in student
?>
<h2>📊 Attendance Summary</h2>
<?php
$summary = $conn->query("SELECT * FROM attendance_stats WHERE student_id = '$student_id'")->fetch_assoc();
echo "✅ Present: {$summary['present']} | ❌ Absent: {$summary['absent']} | ⏰ Late: {$summary['late']}";
?>

<h3>📅 Attendance Records</h3>
<table>
  <tr><th>Date</th><th>Subject</th><th>Status</th></tr>
  <?php
  $logs = $conn->query("SELECT * FROM attendance WHERE student_id = '$student_id'");
  while ($row = $logs->fetch_assoc()) {
    echo "<tr><td>{$row['date']}</td><td>{$row['subject']}</td><td>{$row['status']}</td></tr>";
  }
  ?>
</table>
