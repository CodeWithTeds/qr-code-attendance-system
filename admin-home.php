<?php
include('./conn/conn.php'); // Ensure your database connection

// Query for counts
$totalAttendanceQuery = $conn->query("SELECT COUNT(*) FROM tbl_attendance");
$totalAttendance = $totalAttendanceQuery->fetchColumn();

$totalHolidaysQuery = $conn->query("SELECT COUNT(*) FROM tbl_holidays");
$totalHolidays = $totalHolidaysQuery->fetchColumn();

$totalStudentsQuery = $conn->query("SELECT COUNT(*) FROM tbl_student");
$totalStudents = $totalStudentsQuery->fetchColumn();

$totalTeachersQuery = $conn->query("SELECT COUNT(*) FROM users");
$totalTeachers = $totalTeachersQuery->fetchColumn();
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Add this in the <head> section -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Attendance page</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/image1.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
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
    <h2 class="text-center mb-4" style="font-weight: bold; font-size: 2rem;">📊 Statistics Summary</h2>
    <div class="row text-center">
        <!-- Total Attendance Records -->
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-secondary text-white animate__animated animate__fadeInUp">
                <div class="card-body">
                    <h5 class="card-title">Total Attendance</h5>
                    <h3 class="display-4"><?php echo $totalAttendance; ?></h3>
                </div>
            </div>
        </div>

        <!-- Total Holidays -->
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-warning text-dark animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="card-body">
                    <h5 class="card-title">Total Holidays</h5>
                    <h3 class="display-4"><?php echo $totalHolidays; ?></h3>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-info text-white animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <h3 class="display-4"><?php echo $totalStudents; ?></h3>
                </div>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="col-md-3 mb-4">
            <div class="card stat-card bg-primary text-white animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="card-body">
                    <h5 class="card-title">Total Teachers</h5>
                    <h3 class="display-4"><?php echo $totalTeachers; ?></h3>
                </div>
            </div>
        </div>
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




</body>

</html>