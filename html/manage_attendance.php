<?php


session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: Teacher-login.php");
  exit();
}


include('./conn/conn.php');
date_default_timezone_set('Asia/Manila');

$errorMessage = "";
$successMessage = "";

// Check for session messages
if (isset($_SESSION['error'])) {
  $errorMessage = addslashes($_SESSION['error']);
  unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
  $successMessage = addslashes($_SESSION['success']);
  unset($_SESSION['success']);
}
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

      <div class="main">
        <div class="attendance-container row">
          <!-- <div class="qr-container col-4">
            <div class="scanner-con">
              <h5 class="text-center">Scan your QR Code here for your attendance</h5>
              <video id="interactive" class="viewport" width="100%"></video>
            </div>

            <div class="qr-detected-container" style="display: none;">
              <form action="./endpoint/add-attendance.php" method="POST">
                <h4 class="text-center">Student QR Detected!</h4>
                <input type="hidden" id="detected-qr-code" name="qr_code">
                <button type="submit" class="btn btn-dark form-control">Submit
                  Attendance</button>
              </form>
            </div>
          </div> -->


          <div class="attendance-list">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h4 class="mb-0">List of Present Students</h4>
              <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#filterModal">Filter Attendance</button>
              <button class="btn btn-success btn-sm" onclick="exportToExcel()">Export to Excel</button>

            </div>
            <div class="table-container table-responsive">
              <div class="table-container table-responsive">
                <div class="table-container table-responsive">
                  <table class="table text-center table-sm" id="attendanceTable">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Section</th>
                        <th>Attendance Status</th>
                        <th>Time In</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      date_default_timezone_set('Asia/Manila');
                      include('./conn/conn.php');

                      // Filters
                      $course = !empty($_GET['course']) ? $_GET['course'] : '';
                      $section = !empty($_GET['section']) ? $_GET['section'] : '';
                      $attendanceDate = !empty($_GET['date']) ? $_GET['date'] : '';

                      // Pagination variables
                      $recordsPerPage = 8;
                      $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                      $offset = ($currentPage - 1) * $recordsPerPage;

                      // Base query to retrieve students and attendance information for filtered date, course, and section
                      $query = "
            SELECT s.tbl_student_id, s.student_name, s.course, s.section, a.time_in 
            FROM tbl_student s
            LEFT JOIN tbl_attendance a ON s.tbl_student_id = a.tbl_student_id 
            WHERE 1=1
        ";

                      // Add filtering for course, section, and date if provided
                      if (!empty($course)) {
                        $query .= " AND s.course = :course";
                      }
                      if (!empty($section)) {
                        $query .= " AND s.section = :section";
                      }
                      if (!empty($attendanceDate)) {
                        $query .= " AND DATE(a.time_in) = :attendance_date";
                      }

                      // Pagination
                      $query .= " ORDER BY s.student_name ASC LIMIT :offset, :recordsPerPage";

                      $stmt = $conn->prepare($query);
                      // Bind parameters for the filter conditions
                      if (!empty($course)) {
                        $stmt->bindValue(':course', $course);
                      }
                      if (!empty($section)) {
                        $stmt->bindValue(':section', $section);
                      }
                      if (!empty($attendanceDate)) {
                        $stmt->bindValue(':attendance_date', $attendanceDate);
                      }
                      // Bind pagination parameters
                      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                      $stmt->bindValue(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
                      $stmt->execute();
                      $students = $stmt->fetchAll();

                      // Total count for pagination
                      $totalCountQuery = "
            SELECT COUNT(*) 
            FROM tbl_student s
            LEFT JOIN tbl_attendance a ON s.tbl_student_id = a.tbl_student_id 
            WHERE 1=1
        ";

                      // Add filter for total count
                      if (!empty($course)) {
                        $totalCountQuery .= " AND s.course = :course";
                      }
                      if (!empty($section)) {
                        $totalCountQuery .= " AND s.section = :section";
                      }
                      if (!empty($attendanceDate)) {
                        $totalCountQuery .= " AND DATE(a.time_in) = :attendance_date";
                      }

                      $totalCountStmt = $conn->prepare($totalCountQuery);
                      // Bind parameters for the filter conditions
                      if (!empty($course)) {
                        $totalCountStmt->bindValue(':course', $course);
                      }
                      if (!empty($section)) {
                        $totalCountStmt->bindValue(':section', $section);
                      }
                      if (!empty($attendanceDate)) {
                        $totalCountStmt->bindValue(':attendance_date', $attendanceDate);
                      }
                      $totalCountStmt->execute();
                      $totalRecords = $totalCountStmt->fetchColumn();
                      $totalPages = ceil($totalRecords / $recordsPerPage);

                      if (empty($students)) {
                        echo "<tr><td colspan='6'>No students found.</td></tr>";
                      } else {
                        foreach ($students as $student) {
                          $studentID = $student["tbl_student_id"];
                          $studentName = htmlspecialchars($student["student_name"]);
                          $course = htmlspecialchars($student["course"]);
                          $section = htmlspecialchars($student["section"]);
                          $timeIn = $student["time_in"];

                          // Determine attendance status and format time in if present
                          $isPresent = !empty($timeIn);
                          $formattedTimeIn = $isPresent ? (new DateTime($timeIn))->format("M d, Y g:i A") : "N/A";
                      ?>
                          <tr id="student-<?= $studentID ?>">
                            <th scope="row"><?= $studentID ?></th>
                            <td><?= $studentName ?></td>
                            <td><?= $course ?></td>
                            <td><?= $section ?></td>
                            <td class="attendance-status">
                              <?php if ($isPresent): ?>
                                <span class="text-success">&#10004;</span>
                              <?php else: ?>
                                <span class="text-danger">Absent</span>
                              <?php endif; ?>
                            </td>
                            <td><?= $formattedTimeIn ?></td>
                          </tr>
                      <?php
                        }
                      }
                      ?>
                    </tbody>
                  </table>

                </div>
                <div class="d-flex justify-content-end mb-3">
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#holidayModal">Manage Holidays</button>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                  <ul class="pagination justify-content-center">
                    <?php
                    // Build the query string based on active filters
                    $queryParams = [];
                    if (!empty($_GET['course'])) {
                      $queryParams['course'] = $_GET['course'];
                    }
                    if (!empty($_GET['section'])) {
                      $queryParams['section'] = $_GET['section'];
                    }
                    if (!empty($_GET['date'])) {
                      $queryParams['date'] = $_GET['date'];
                    }

                    // Function to build URL
                    function buildUrl($page, $params = [])
                    {
                      $params['page'] = $page;
                      return '?' . http_build_query($params);
                    }
                    ?>

                    <?php if ($currentPage > 1): ?>
                      <li class="page-item">
                        <a class="page-link" href="<?= buildUrl($currentPage - 1, $queryParams) ?>" aria-label="Previous">
                          <span aria-hidden="true">&laquo;</span>
                        </a>
                      </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                      <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="<?= buildUrl($i, $queryParams) ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                      <li class="page-item">
                        <a class="page-link" href="<?= buildUrl($currentPage + 1, $queryParams) ?>" aria-label="Next">
                          <span aria-hidden="true">&raquo;</span>
                        </a>
                      </li>
                    <?php endif; ?>
                  </ul>
                </nav>




                <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="holidayModalLabel">Manage Holidays</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form id="holidayForm" action="./endpoint/add-holiday.php" method="POST">
                          <div class="mb-3">
                            <label for="holiday_date" class="form-label">Holiday Date</label>
                            <input type="date" class="form-control" id="holiday_date" name="holiday_date" required>
                          </div>
                          <div class="mb-3">
                            <label for="holiday_name" class="form-label">Holiday Name</label>
                            <input type="text" class="form-control" id="holiday_name" name="holiday_name" required>
                          </div>
                          <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                          </div>
                          <button type="submit" class="btn btn-primary">Add Holiday</button>
                        </form>

                        <div class="mt-4">
                          <h6>Current Holidays</h6>
                          <div class="table-responsive">
                            <table class="table table-sm">
                              <thead>
                                <tr>
                                  <th>Date</th>
                                  <th>Name</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody id="holidayList">
                                <?php
                                $holidayQuery = $conn->query("SELECT * FROM tbl_holidays ORDER BY holiday_date DESC");
                                while ($holiday = $holidayQuery->fetch(PDO::FETCH_ASSOC)) {
                                  echo '<tr>';
                                  echo '<td>' . date('M d, Y', strtotime($holiday['holiday_date'])) . '</td>';
                                  echo '<td>' . htmlspecialchars($holiday['holiday_name']) . '</td>';
                                  echo '<td><button class="btn btn-danger btn-sm" onclick="deleteHoliday(' . $holiday['holiday_id'] . ')">Delete</button></td>';
                                  echo '</tr>';
                                }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Add this modal for holiday management -->
                <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="holidayModalLabel">Manage Holidays</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form id="holidayForm" action="./endpoint/add-holiday.php" method="POST">
                          <div class="mb-3">
                            <label for="holiday_date" class="form-label">Holiday Date</label>
                            <input type="date" class="form-control" id="holiday_date" name="holiday_date" required>
                          </div>
                          <div class="mb-3">
                            <label for="holiday_name" class="form-label">Holiday Name</label>
                            <input type="text" class="form-control" id="holiday_name" name="holiday_name" required>
                          </div>
                          <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                          </div>
                          <button type="submit" class="btn btn-primary">Add Holiday</button>
                        </form>

                        <div class="mt-4">
                          <h6>Current Holidays</h6>
                          <div class="table-responsive">
                            <table class="table table-sm">
                              <thead>
                                <tr>
                                  <th>Date</th>
                                  <th>Name</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody id="holidayList">
                                <?php
                                $holidayQuery = $conn->query("SELECT * FROM tbl_holidays ORDER BY holiday_date DESC");
                                while ($holiday = $holidayQuery->fetch(PDO::FETCH_ASSOC)) {
                                  echo '<tr>';
                                  echo '<td>' . date('M d, Y', strtotime($holiday['holiday_date'])) . '</td>';
                                  echo '<td>' . htmlspecialchars($holiday['holiday_name']) . '</td>';
                                  echo '<td><button class="btn btn-danger btn-sm" onclick="deleteHoliday(' . $holiday['holiday_id'] . ')">Delete</button></td>';
                                  echo '</tr>';
                                }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <?php
                // Modify your existing attendance query to check for holidays
                if (!empty($attendanceDate)) {
                  // Check if the selected date is a holiday
                  $holidayCheck = $conn->prepare("SELECT * FROM tbl_holidays WHERE holiday_date = :date");
                  $holidayCheck->bindValue(':date', $attendanceDate);
                  $holidayCheck->execute();
                  $holiday = $holidayCheck->fetch(PDO::FETCH_ASSOC);

                  if ($holiday) {
                    echo '<div class="alert alert-warning text-center">
                <strong>' . htmlspecialchars($holiday['holiday_name']) . '</strong><br>
                This date was marked as a holiday.<br>
                ' . htmlspecialchars($holiday['description']) . '
              </div>';
                  }
                }
                ?>

                <script>
                  function deleteHoliday(holidayId) {
                    if (confirm('Are you sure you want to delete this holiday?')) {
                      fetch('./endpoint/delete-holiday.php', {
                          method: 'POST',
                          headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                          },
                          body: 'holiday_id=' + holidayId
                        })
                        .then(response => response.json())
                        .then(data => {
                          if (data.success) {
                            location.reload();
                          } else {
                            alert('Error deleting holiday');
                          }
                        });
                    }
                  }
                </script>
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Attendance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form id="filterForm" action="" method="get">
                          <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <select class="form-select" id="course" name="course">
                              <option value="">Select Course</option>
                              <?php
                              $courseQuery = $conn->query("SELECT DISTINCT course FROM tbl_student ORDER BY course ASC");
                              while ($course = $courseQuery->fetch(PDO::FETCH_ASSOC)) {
                                $selected = (isset($_GET['course']) && $_GET['course'] == $course['course']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($course['course']) . '" ' . $selected . '>'
                                  . htmlspecialchars($course['course']) . '</option>';
                              }
                              ?>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="section" class="form-label">Section</label>
                            <select class="form-select" id="section" name="section">
                              <option value="">Select Section</option>
                              <?php
                              $sectionQuery = $conn->query("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC");
                              while ($section = $sectionQuery->fetch(PDO::FETCH_ASSOC)) {
                                $selected = (isset($_GET['section']) && $_GET['section'] == $section['section']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($section['section']) . '" ' . $selected . '>'
                                  . htmlspecialchars($section['section']) . '</option>';
                              }
                              ?>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                              value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
                            <div id="holidayInfo" class="mt-2"></div>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="applyFilter()">Apply Filter</button>
                      </div>
                    </div>
                  </div>
                </div>


                <script>
                  function checkHoliday(date) {
                    if (!date) return;

                    fetch(`check_holiday.php?date=${date}`)
                      .then(response => response.json())
                      .then(data => {
                        const holidayInfoDiv = document.getElementById('holidayInfo');
                        if (data.isHoliday) {
                          holidayInfoDiv.innerHTML = `
                        <div class="alert alert-warning mb-0">
                            <strong>${data.holiday_name}</strong><br>
                            This date is marked as a holiday
                        </div>`;
                        } else {
                          holidayInfoDiv.innerHTML = '';
                        }
                      });
                  }

                  function applyFilter() {
                    const form = document.getElementById('filterForm');
                    const course = document.getElementById('course').value;
                    const section = document.getElementById('section').value;
                    const date = document.getElementById('date').value;

                    // Construct the URL with filter parameters
                    let url = new URL(window.location.href);
                    let params = new URLSearchParams(url.search);

                    // Update or remove course parameter
                    if (course) {
                      params.set('course', course);
                    } else {
                      params.delete('course');
                    }

                    // Update or remove section parameter
                    if (section) {
                      params.set('section', section);
                    } else {
                      params.delete('section');
                    }

                    // Update or remove date parameter
                    if (date) {
                      params.set('date', date);
                    } else {
                      params.delete('date');
                    }

                    // Reset to page 1 when applying new filters
                    params.set('page', '1');

                    // Redirect with new parameters
                    window.location.href = `${window.location.pathname}?${params.toString()}`;
                  }

                  // Set the current filter values and check for holiday when the modal opens
                  document.addEventListener('DOMContentLoaded', function() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const dateInput = document.getElementById('date');

                    if (urlParams.has('course')) {
                      document.getElementById('course').value = urlParams.get('course');
                    }

                    if (urlParams.has('section')) {
                      document.getElementById('section').value = urlParams.get('section');
                    }

                    if (urlParams.has('date')) {
                      dateInput.value = urlParams.get('date');
                      checkHoliday(dateInput.value);
                    }

                    // Add event listener to date input
                    dateInput.addEventListener('change', function() {
                      checkHoliday(this.value);
                    });
                  });
                </script>


                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


              </div>
            </div>
          </div>
          <script>
            function exportToExcel() {
              // Get filter values from the form inputs
              const course = document.getElementById('course').value;
              const section = document.getElementById('section').value;
              const date = document.getElementById('date').value;

              // Build the export URL with the filter values
              window.location.href = `export-attendance.php?course=${course}&section=${section}&date=${date}`;
            }
          </script>


          <!-- Bootstrap JS -->
          <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

          <!-- instascan Js -->
          <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

          <script>
            let scanner;

            function startScanner() {
              scanner = new Instascan.Scanner({
                video: document.getElementById('interactive')
              });

              scanner.addListener('scan', function(content) {
                $("#detected-qr-code").val(content);
                console.log(content);
                scanner.stop();
                document.querySelector(".qr-detected-container").style.display = '';
                document.querySelector(".scanner-con").style.display = 'none';
              });

              Instascan.Camera.getCameras()
                .then(function(cameras) {
                  if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                  } else {
                    console.error('No cameras found.');
                    alert('No cameras found.');
                  }
                })
                .catch(function(err) {
                  console.error('Camera access error:', err);
                  alert('Camera access error: ' + err);
                });
            }

            // document.addEventListener('DOMContentLoaded', startScanner);

            function deleteAttendance(id) {
              if (confirm("Do you want to remove this attendance?")) {
                window.location = "./endpoint/delete-attendance.php?attendance=" + id;
              }
            }
          </script>
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