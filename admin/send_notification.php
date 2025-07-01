<?php
session_start();
include '../db.php';

if (!isset($_SESSION['lecturer'])) {
  header("Location: lecture_login.php");
  exit();
}

$lecturer = $_SESSION['lecturer'];
$lecturerName = $lecturer['full_name'] ?? 'Unknown';
$lecturerEmail = $lecturer['email'] ?? '';
$unit = $lecturer['unit'] ?? 'Not Set';

$departments = $conn->query("SELECT DISTINCT course FROM students");

$students = [];
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['department']) && !isset($_POST['send_all'])) {
  $selectedDept = $_POST['department'];
  $stmt = $conn->prepare("SELECT student_id, full_name FROM students WHERE course = ?");
  $stmt->bind_param("s", $selectedDept);
  $stmt->execute();
  $students = $stmt->get_result();
} else {
  $students = $conn->query("SELECT student_id, full_name FROM students");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['message'], $_POST['app_password'])) {
  $message = $_POST['message'];
  $appPassword = escapeshellarg($_POST['app_password']);
  $senderEmail = escapeshellarg($lecturerEmail);
  $success = false;
  $formattedMessage = "📘 $unit\n\n$message";

  if (isset($_POST['send_all'])) {
    $result = $conn->query("SELECT student_id, email FROM students");
    while ($row = $result->fetch_assoc()) {
      if (!empty($row['email'])) {
        $check = $conn->prepare("SELECT 1 FROM students WHERE student_id = ?");
        $check->bind_param("s", $row['student_id']);
        $check->execute();
        $checkResult = $check->get_result();
        if ($checkResult->num_rows > 0) {
          $stmt = $conn->prepare("INSERT INTO notifications (student_id, message, unit) VALUES (?, ?, ?)");
          $stmt->bind_param("sss", $row['student_id'], $message, $unit);
          $stmt->execute();

          $recipientEmail = escapeshellarg($row['email']);
          $subject = escapeshellarg("Class Notification from $lecturerName");
          $emailMessage = escapeshellarg($formattedMessage);

          $output = shell_exec("python ../python/send_email.py $senderEmail $appPassword $recipientEmail $subject $emailMessage 2>&1");
          file_put_contents('email_log.txt', $output, FILE_APPEND);
        }
      }
    }
    $success = true;
  } else {
    $student_id = $_POST['student_id'];
    $check = $conn->prepare("SELECT 1 FROM students WHERE student_id = ?");
    $check->bind_param("s", $student_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
      $stmt = $conn->prepare("INSERT INTO notifications (student_id, message, unit) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $student_id, $message, $unit);
      $success = $stmt->execute();

      $stmtEmail = $conn->prepare("SELECT email FROM students WHERE student_id = ?");
      $stmtEmail->bind_param("s", $student_id);
      $stmtEmail->execute();
      $emailResult = $stmtEmail->get_result();
      if ($emailRow = $emailResult->fetch_assoc()) {
        if (!empty($emailRow['email'])) {
          $recipientEmail = escapeshellarg($emailRow['email']);
          $subject = escapeshellarg("Class Notification from $lecturerName");
          $emailMessage = escapeshellarg($formattedMessage);
          shell_exec("python ../python/send_email.py $senderEmail $appPassword $recipientEmail $subject $emailMessage");
        }
      }
    } else {
      $success = false;
      $feedback = "❌ Cannot send. Student ID '$student_id' not found.";
    }
  }

  $feedback = $success ? "✅ Notification sent successfully." : ($feedback ?? "❌ Failed to send notification.");
}
?>

<!-- Rest of the HTML remains unchanged -->
<!DOCTYPE html>
<html>
<head>
  <title>Send Notification</title>
  <script src="../js/smart_notification.js"></script>

  <link rel="stylesheet" href="../css/style.css">
  <style>
    
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #bbdefb, #e3f2fd);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .notification-box {
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
    }
    h2 {
      text-align: center;
      color: #0d47a1;
      margin-bottom: 25px;
      font-size: 26px;
    }
    p {
      font-weight: bold;
      font-size: 15px;
      text-align: center;
      margin-bottom: 20px;
      color: #444;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      color: #333;
    }
    select, textarea, input[type="password"] {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 1px solid #ccc;
      margin-top: 5px;
      font-size: 15px;
      box-sizing: border-box;
    }
    textarea {
      resize: vertical;
      height: 100px;
    }
    .checkbox {
      margin-top: 10px;
      font-weight: normal;
    }
    .btn {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: #1976d2;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .btn:hover {
      background-color: #0d47a1;
    }
    .msg {
      text-align: center;
      margin-top: 15px;
      font-weight: bold;
    }
    .success { color: #2e7d32; }
    .error { color: #c62828; }
  </style>
</head>
<body>
  <div class="notification-box">
    <h2>📢 Send Notification</h2>
    <?php if (!empty($feedback)) echo "<p class='msg " . ($success ? "success" : "error") . "'>$feedback</p>"; ?>
    <form method="post" onsubmit="return validateForm() && showLoader();">

      <p>👨‍🏫 <strong><?= htmlspecialchars($lecturerName) ?></strong> | 📘 <strong><?= htmlspecialchars($unit) ?></strong></p>

      <label for="department">🎓 Select Department:</label>
      <select name="department" onchange="this.form.submit()">
        <option value="">-- Choose Department --</option>
        <?php while ($dept = $departments->fetch_assoc()): ?>
          <option value="<?= $dept['course'] ?>" <?= (isset($_POST['department']) && $_POST['department'] == $dept['course']) ? 'selected' : '' ?> >
            <?= $dept['course'] ?> | Lecturer: <?= htmlspecialchars($lecturerName) ?> | Unit: <?= htmlspecialchars($unit) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <div class="checkbox">
        <label><input type="checkbox" name="send_all" onchange="this.form.submit()" <?= isset($_POST['send_all']) ? 'checked' : '' ?>> 📨 Send to All Students</label>
      </div>

      <?php if (!isset($_POST['send_all'])): ?>
        <label for="student_id">👤 Select Student:</label>
        <select name="student_id" required>
          <option value="">-- Choose Student --</option>
          <?php while ($student = $students->fetch_assoc()): ?>
            <option value="<?= $student['student_id'] ?>"><?= $student['full_name'] ?> (<?= $student['student_id'] ?>)</option>
          <?php endwhile; ?>
        </select>
      <?php endif; ?>

      <label for="app_password">🔐 Your Gmail App Password:</label>
      <input type="password" name="app_password" placeholder="Enter your Gmail App Password" required>

      <label for="message">📝 Notification Message:</label>
      <textarea name="message" placeholder="e.g. Today's class is postponed due to an emergency." required></textarea>

      <?php if (isset($_POST['department'])): ?>
        <input type="hidden" name="department" value="<?= htmlspecialchars($_POST['department']) ?>">
      <?php endif; ?>
      <?php if (isset($_POST['send_all'])): ?>
        <input type="checkbox" name="send_all" onchange="confirmAllStudents(this); this.form.submit();" <?= isset($_POST['send_all']) ? 'checked' : '' ?>>

      <?php endif; ?>

      <button type="submit" class="btn">📤 Send Notification</button>
    </form>
  </div>
</body>
</html>  


