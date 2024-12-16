<?php
include('./conn/conn.php'); // Ensure your database connection

// Set limit and page for pagination
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;



// Count total instructors
$instructorQuery = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'instructor'");
$totalInstructors = $instructorQuery->fetchColumn();

// Count total admins
$adminQuery = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
$totalAdmins = $adminQuery->fetchColumn();

// Fetch instructors with pagination
$instructorsQuery = $conn->prepare("
    SELECT id, name, email, password 
    FROM users 
    WHERE role = 'instructor' 
    LIMIT :limit OFFSET :offset
");
$instructorsQuery->bindParam(':limit', $limit, PDO::PARAM_INT);
$instructorsQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
$instructorsQuery->execute();
$instructors = $instructorsQuery->fetchAll(PDO::FETCH_ASSOC);

// Fetch admins with pagination
$adminsQuery = $conn->prepare("
    SELECT id, name, email, password 
    FROM users 
    WHERE role = 'admin' 
    LIMIT :limit OFFSET :offset
");
$adminsQuery->bindParam(':limit', $limit, PDO::PARAM_INT);
$adminsQuery->bindParam(':offset', $offset, PDO::PARAM_INT);
$adminsQuery->execute();
$admins = $adminsQuery->fetchAll(PDO::FETCH_ASSOC);

// Calculate total pages for both instructors and admins
$totalInstructorPages = ceil($totalInstructors / $limit);
$totalAdminPages = ceil($totalAdmins / $limit);

// Query to get students with pagination
$studentsPerPage = 10;
$currentStudentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($currentStudentPage - 1) * $studentsPerPage;
$studentQuery = $conn->prepare("SELECT tbl_student_id, student_name FROM tbl_student LIMIT :start, :limit");
$studentQuery->bindParam(':start', $start, PDO::PARAM_INT);
$studentQuery->bindParam(':limit', $studentsPerPage, PDO::PARAM_INT);
$studentQuery->execute();
$students = $studentQuery->fetchAll(PDO::FETCH_ASSOC);

// Query to get the total number of students
$totalStudentsQuery = $conn->query("SELECT COUNT(*) FROM tbl_student");
$totalStudents = $totalStudentsQuery->fetchColumn();

// Calculate total pages for students
$totalStudentPages = ceil($totalStudents / $studentsPerPage);

// Query to get student distribution by course
$studentDistributionQuery = $conn->query("SELECT course, COUNT(*) AS count FROM tbl_student GROUP BY course");
$studentDistribution = [];
while ($row = $studentDistributionQuery->fetch(PDO::FETCH_ASSOC)) {
    $studentDistribution[$row['course']] = $row['count'];
}

// Query to fetch holidays and other data
$holidayQuery = $conn->query("SELECT holiday_id, holiday_date, holiday_name, description, created_by, created_at FROM tbl_holidays");
$holidays = $holidayQuery->fetchAll(PDO::FETCH_ASSOC);

// Query for counts
$totalAttendanceQuery = $conn->query("SELECT COUNT(*) FROM tbl_attendance");
$totalAttendance = $totalAttendanceQuery->fetchColumn();

$totalHolidaysQuery = $conn->query("SELECT COUNT(*) FROM tbl_holidays");
$totalHolidays = $totalHolidaysQuery->fetchColumn();

$totalTeachersQuery = $conn->query("SELECT COUNT(*) FROM users");
$totalTeachers = $totalTeachersQuery->fetchColumn();

// Query to fetch instructors distribution
$instructorDistributionQuery = $conn->query("SELECT name, COUNT(*) AS count FROM users WHERE role = 'instructor' GROUP BY name");
$instructorDistribution = [];
while ($row = $instructorDistributionQuery->fetch(PDO::FETCH_ASSOC)) {
    $instructorDistribution[$row['name']] = $row['count'];
}

// Query to fetch admins distribution
$adminDistributionQuery = $conn->query("SELECT name, COUNT(*) AS count FROM users WHERE role = 'admin' GROUP BY name");
$adminDistribution = [];
while ($row = $adminDistributionQuery->fetch(PDO::FETCH_ASSOC)) {
    $adminDistribution[$row['name']] = $row['count'];
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Add this in the <head> section -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<!-- Add these in your header if not already present -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Attendance page</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/image1.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
  <style>
    
  </style>
  <script>
    window.onload = function() {
      <?php if (!empty($errorMessage)): ?>
        swal({
          title: 'Error!',
          text: '<?= $errorMessage ?>',
          type: 'error',
          confirmButtonText: 'OK'
        });
      <?php endif; ?>

      <?php if (!empty($successMessage)): ?>
        swal({
          title: 'Success!',
          text: '<?= $successMessage ?>',
          type: 'success',
          confirmButtonText: 'OK'
        });
      <?php endif; ?>
    };
  </script>

</head>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');


  .pagination {
    display: flex;
    gap: 8px;
    justify-content: center;
    padding: 0;
    list-style: none;
  }

  .page-item {
    display: inline-block;
  }

  .page-link {
    color: #007bff;
    font-weight: bold;
    padding: 6px 12px;
    border: none;
    background: none;
  }

  .page-link:hover {
    color: #0056b3;
    text-decoration: underline;
  }

  .page-item.active .page-link {
    color: white;
    background-color: #007bff;
    border-radius: 5px;
  }


  .navbar-custom {
    background-color: #5D87FF;
    /* Your desired color */
    border-radius: 20px;
  }

  .navbar-custom .nav-link {
    color: white;
    /* Change this to your desired text color */
  }

  .navbar-custom .navbar-brand {
    color: white;
    /* Change this to your desired brand color */
  }

  * {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
  }

  body {
    background-image: url('image/granby.jpg');
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-size: cover;
  }


  .main {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 95vh;
  }

  .attendance-container {
    height: 120%;
    width: 95%;
    border-radius: 20px;
    padding: 40px;
    background-color: rgba(255, 255, 255, 0.8);
  }

  .attendance-container>div {
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    border-radius: 10px;
    padding: 30px;
  }

  .attendance-container>div:last-child {
    width: 64%;
    margin-left: auto;
  }
</style>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./manage_attendance.php" class="text-nowrap logo-img d-flex align-items-center">
            <img src="../assets/images/logos/image1.png" width="50" alt="" />
            <span class="ms-2">Granby College</span>
          </a>
          <div class="close-btn d-xl-none d-block sidebartogglezr cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <!-- <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li> -->

            <li class="nav-small-cap">
  <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
  <span class="hide-menu">FEATURES</span>
</li>
<!-- New section for Attendance Report -->
<li class="sidebar-item">
  <a class="sidebar-link" href="./admin-home.php" aria-expanded="false">
    <span>
      <i class="ti ti-chart-line"></i>
    </span>
    <span class="hide-menu">Dashboard</span>
  </a>
</li>
<li class="sidebar-item">
  <a class="sidebar-link" href="./manage_attendance.php" aria-expanded="false">
    <span>
      <i class="ti ti-article"></i>
    </span>
    <span class="hide-menu">Manage Attendance</span>
  </a>
</li>
<li class="sidebar-item">
  <a class="sidebar-link" href="./attendance_admin.php" aria-expanded="false">
    <span>
      <i class="ti ti-user"></i>
    </span>
    <span class="hide-menu">Manage Student</span>
  </a>
</li>



<li class="nav-small-cap">
  <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
  <span class="hide-menu"></span>
</li>

            </li>
            <!-- <li class="sidebar-item">
                            <a class="sidebar-link" href="Teacher-login.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-login"></i>
                                </span>
                                <span class="hide-menu">Login</span>
                            </a>
                        </li> -->
            <li class="sidebar-item">
              <a class="sidebar-link" href="logout.php" aria-expanded="false">
                <span>
                  <i class="ti ti-logout"></i>
                </span>
                <span class="hide-menu">Logout</span>
              </a>
            </li>
          </ul>

        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
            <li style="display: flex;align-items: center;padding-top: 12px;font-size: 20px;margin-left: 20px;" class="nav-item">
              <p>Admin</p>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35"
                    class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                  aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)"
                      class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="javascript:void(0)"
                      class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-mail fs-6"></i>
                      <p class="mb-0 fs-3">My Account</p>
                    </a>
                    <a href="javascript:void(0)"
                      class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-list-check fs-6"></i>
                      <p class="mb-0 fs-3">My Task</p>
                    </a>
                    <a href="./authentication-login.html"
                      class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">
        <!--  Row 1 -->
        <div class="row">

        </div>

      </div>
      </nav>
      <div class="container my-5">
    <h2 class="text-center mb-4" style="font-weight: bold; font-size: 2rem;">📊 Statistical Report</h2>
    <div class="row text-center">
        <!-- Total Attendance Records -->
        <div class="col-md-3 mb-4">
    <a href="attendance_graph.php" style="text-decoration: none;">
        <div class="card stat-card bg-secondary text-white animate__animated animate__fadeInUp">
            <div class="card-body">
                <h5 class="card-title">Total Attendance</h5>
                <h3 class="display-4"><?php echo $totalAttendance; ?></h3>
            </div>
        </div>
    </a>
</div>
<!-- Button to Open Modal for Holidays -->
<div class="col-md-3 mb-4">
    <a href="#" data-bs-toggle="modal" data-bs-target="#holidaysModal" style="text-decoration: none;">
        <div class="card stat-card bg-warning text-dark animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <div class="card-body">
                <h5 class="card-title">Total Holidays</h5>
                <h3 class="display-4"><?php echo $totalHolidays; ?></h3>
            </div>
        </div>
    </a>
</div>

<!-- Modal for Displaying Holidays -->
<div class="modal fade" id="holidaysModal" tabindex="-1" aria-labelledby="holidaysModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background-color: #f49131; color: #fff; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="holidaysModalLabel">Holiday List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 2rem;">
                <div class="container">
                    <div class="row">
                        <?php foreach ($holidays as $holiday): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow-lg bg-white" style="border-radius: 15px; transition: all 0.3s ease;">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 1.25rem; font-weight: bold;"><?php echo htmlspecialchars($holiday['holiday_name']); ?></h5>
                                        <p class="card-text" style="font-size: 1rem; color: #555;">
                                            <strong>Date:</strong> <?php echo date('F j, Y', strtotime($holiday['holiday_date'])); ?><br>
                                
                                            <strong>Created At:</strong> <?php echo date('F j, Y, g:i A', strtotime($holiday['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Students -->
<div class="col-md-3 mb-4">
    <a href="#" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#studentsGraph">
        <div class="card stat-card bg-info text-white animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="card-body">
                <h5 class="card-title">Total Students</h5>
                <h3 class="display-4"><?php echo $totalStudents; ?></h3>
            </div>
        </div>
    </a>
</div>

<!-- Total Instructors -->
<div class="col-md-3 mb-4">
    <a href="#" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#instructorsGraph">
        <div class="card stat-card bg-success text-white animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="card-body">
                <h5 class="card-title">Total Instructors</h5>
                <h3 class="display-4"><?php echo $totalInstructors; ?></h3>
            </div>
        </div>
    </a>
</div>

<!-- Total Admins -->
<div class="col-md-3 mb-4">
    <a href="#" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#adminsGraph">
        <div class="card stat-card bg-warning text-white animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="card-body">
                <h5 class="card-title">Total Admins</h5>
                <h3 class="display-4"><?php echo $totalAdmins; ?></h3>
            </div>
        </div>
    </a>
</div>

<!-- Students Graph Modal -->
<div class="modal fade" id="studentsGraph" tabindex="-1" aria-labelledby="studentsGraphLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background-color: #17a2b8; color: #fff; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="studentsGraphLabel">Students Graph</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 2rem;">
                <h5 class="text-center mb-4">Student Distribution</h5>
                <canvas id="studentsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Instructors Graph Modal -->
<div class="modal fade" id="instructorsGraph" tabindex="-1" aria-labelledby="instructorsGraphLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background-color: #28a745; color: #fff; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="instructorsGraphLabel">Instructors Graph</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 2rem;">
                <h5 class="text-center mb-4">Instructor Distribution</h5>
                <canvas id="instructorsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Admins Graph Modal -->
<div class="modal fade" id="adminsGraph" tabindex="-1" aria-labelledby="adminsGraphLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header" style="background-color: #ffc107; color: #fff; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="adminsGraphLabel">Admins Graph</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 2rem;">
                <h5 class="text-center mb-4">Admin Distribution</h5>
                <canvas id="adminsChart"></canvas>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data
var studentData = <?php echo json_encode($studentDistribution); ?>;
var instructorData = <?php echo json_encode($instructorDistribution); ?>;
var adminData = <?php echo json_encode($adminDistribution); ?>;

// Shared chart options
const options = {
    responsive: true,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        x: {
            grid: { display: false },
            ticks: { color: '#94a3b8' }
        },
        y: {
            grid: { color: '#f1f5f9' },
            ticks: { color: '#94a3b8' }
        }
    }
};

// Create charts
new Chart(document.getElementById('studentsChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: Object.keys(studentData),
        datasets: [{
            data: Object.values(studentData),
            backgroundColor: '#4f46e5',
            borderRadius: 4,
            maxBarThickness: 35
        }]
    },
    options: options
});

new Chart(document.getElementById('instructorsChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: Object.keys(instructorData),
        datasets: [{
            data: Object.values(instructorData),
            backgroundColor: '#0ea5e9',
            borderRadius: 4,
            maxBarThickness: 35
        }]
    },
    options: options
});

new Chart(document.getElementById('adminsChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: Object.keys(adminData),
        datasets: [{
            data: Object.values(adminData),
            backgroundColor: '#06b6d4',
            borderRadius: 4,
            maxBarThickness: 35
        }]
    },
    options: options
});
</script>

<!-- Script to handle modals -->
<script>
$(document).ready(function() {
    // Show appropriate modal based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');
    
    if(type === 'instructor') {
        $('#instructorModal').modal('show');
    } else if(type === 'admin') {
        $('#adminModal').modal('show');
    }
    
    // Handle modal state for pagination
    $('.pagination .page-link').click(function(e) {
        localStorage.setItem('modalOpen', 'true');
        localStorage.setItem('modalType', $(this).closest('.modal').attr('id'));
    });
    
    // Check for modal state on page load
    if(localStorage.getItem('modalOpen') === 'true') {
        const modalType = localStorage.getItem('modalType');
        $('#' + modalType).modal('show');
        localStorage.setItem('modalOpen', 'false');
    }
});
</script>
<!-- Add this script at the bottom -->
<script>
$(document).ready(function() {
    // If there's a #teacherModal in the URL, show the modal
    if(window.location.hash == '#teacherModal') {
        $('#teacherModal').modal('show');
    }
});
</script>
    </div>
  </div>
</div>

<!-- Automatically Show Modal -->
<script>
  $(document).ready(function() {
    $('#teacherModal').modal('show'); // Show modal when the page loads
  });
</script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
  /* Custom styles for the modal */
  .modal-content {
    border-radius: 10px;
  }

  .modal-header {
    background-color: #28a745;
    color: white;
  }

  .modal-title {
    font-size: 1.5rem;
    font-weight: bold;
  }

  .table thead {
    background-color: #28a745;
    color: white;
  }

  .table-striped tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
  }

  .close {
    color: white;
  }

  /* Styling for pagination */
  .pagination .page-item.disabled .page-link {
    pointer-events: none;
    background-color: #e9ecef;
    border-color: #e9ecef;
  }

  .pagination .page-item.active .page-link {
    background-color: #28a745;
    border-color: #28a745;
  }

  .pagination .page-link {
    color: #28a745;
  }

  /* Styling for the card (Total Teachers) */
  .stat-card {
    border-radius: 10px;
    cursor: pointer;
  }

  .stat-card:hover {
    background-color: #218838;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .stat-card .card-body {
    padding: 20px;
  }

  .card-title {
    font-size: 1.2rem;
  }

  .display-4 {
    font-size: 2.5rem;
    font-weight: bold;
  }
</style>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
  /* Custom styles for the modal */
  .modal-content {
    border-radius: 10px;
  }

  .modal-header {
    background-color: #28a745;
    color: white;
  }

  .modal-title {
    font-size: 1.5rem;
    font-weight: bold;
  }

  .table thead {
    background-color: #28a745;
    color: white;
  }

  .table-striped tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
  }

  .close {
    color: white;
  }

  /* Styling for the card (Total Teachers) */
  .stat-card {
    border-radius: 10px;
    cursor: pointer;
  }

  .stat-card:hover {
    background-color: #218838;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .stat-card .card-body {
    padding: 20px;
  }

  .card-title {
    font-size: 1.2rem;
  }

  .display-4 {
    font-size: 2.5rem;
    font-weight: bold;
  }
</style>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare data for the monthly registration graph
    const months = <?php echo json_encode(array_map(function($month) {
        return $month['month'] . '-' . $month['year']; 
    }, $monthlyTeachers)); ?>;
    const teacherCounts = <?php echo json_encode(array_map(function($month) {
        return $month['teacher_count']; 
    }, $monthlyTeachers)); ?>;

    // Create the graph
    const ctx = document.getElementById('teachersGraph').getContext('2d');
    const teachersGraph = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Teachers Registered',
                data: teacherCounts,
                backgroundColor: 'rgba(40, 167, 69, 0.6)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month-Year'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Teachers'
                    }
                }
            }
        }
    });
</script>

    </div>
</div>
<style>
    /* General container styling */
.stat-card {
    border-radius: 15px;
    transition: transform 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.stat-card:hover {
    transform: translateY(-10px);
}

h2 {
    font-family: 'Poppins', sans-serif;
    color: #333;
}

.card-title {
    font-size: 1.2rem;
}

.display-4 {
    font-size: 2.5rem;
    font-weight: bold;
}

</style>
       </div>
                  
        </div>
      </div>
    </div>


    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>

</html>



<style>
  
</style>
</body>

</html>