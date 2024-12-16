<?php
$query = "SELECT 
COUNT(CASE WHEN status = 'present' THEN 1 END) AS present_count,
COUNT(CASE WHEN status = 'absent' THEN 1 END) AS absent_count
FROM attendance";
$result = mysqli_query($conn, $query);

if (!$result) {
die("Error executing query: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);
$presentCount = $data['present_count'];
$absentCount = $data['absent_count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Report</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="attendance-report-container">
    <h1>Attendance Report</h1>
    <div class="attendance-summary">
      <div class="stat">
        <h3>Total Present Students</h3>
        <p><?php echo $presentCount; ?></p>
      </div>
      <div class="stat">
        <h3>Total Absent Students</h3>
        <p><?php echo $absentCount; ?></p>
      </div>
    </div>
    <!-- You can add charts or graphs here if you want to visually represent the data -->
  </div>
</body>
</html>
