<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $teacher_name = $_POST['teacher_name'];
  $course = $_POST['course']; // optional if you want to store it
  $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

  $stmt = $conn->prepare("INSERT INTO timetable (teacher_name, subject, day_of_week, start_time, end_time, room) VALUES (?, ?, ?, ?, ?, ?)");

  foreach ($days as $day) {
    for ($i = 1; $i <= 2; $i++) {
      $subjectKey = "subject_" . strtoupper(substr($day, 0, 3)) . $i;
      $startKey = "start_" . strtoupper(substr($day, 0, 3)) . $i;
      $endKey = "end_" . strtoupper(substr($day, 0, 3)) . $i;
      $roomKey = "room_" . strtoupper(substr($day, 0, 3)) . $i;

      $subject = $_POST[$subjectKey] ?? '';
      $start = $_POST[$startKey] ?? '';
      $end = $_POST[$endKey] ?? '';
      $room = $_POST[$roomKey] ?? '';

      // Skip empty subjects
      if (!empty($subject) && !empty($start) && !empty($end)) {
        $stmt->bind_param("ssssss", $teacher_name, $subject, $day, $start, $end, $room);
        $stmt->execute();
      }
    }
  }

  header("Location: admin_add_timetable.php?success=1");
  exit();
}
?>
