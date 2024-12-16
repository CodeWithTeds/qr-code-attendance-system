<?php


session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: Teacher-login.php");
  exit();
}
?>
<?php
require 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    header("Location: attendance.php");
    exit();
  } else {
    echo "Invalid email or password.";
  }
}
?>

<!doctype html>
<html lang="en">


<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modernize Free</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/image1.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

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
    padding-top: 10%;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .student-container {
    height: 150%;
    width: 90%;
    border-radius: 20px;
    padding: 30px;
    background-color: rgba(255, 255, 255, 0.8);
  }

  .student-container>div {
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    border-radius: 10px;
    padding: 30px;
    height: 100%;
  }

  .title {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  table.dataTable thead>tr>th.sorting,
  table.dataTable thead>tr>th.sorting_asc,
  table.dataTable thead>tr>th.sorting_desc,
  table.dataTable thead>tr>th.sorting_asc_disabled,
  table.dataTable thead>tr>th.sorting_desc_disabled,
  table.dataTable thead>tr>td.sorting,
  table.dataTable thead>tr>td.sorting_asc,
  table.dataTable thead>tr>td.sorting_desc,
  table.dataTable thead>tr>td.sorting_asc_disabled,
  table.dataTable thead>tr>td.sorting_desc_disabled {
    text-align: center;
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
          <a href="./attendance_admin.php" class="text-nowrap logo-img d-flex align-items-center">
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
                  <i class="ti ti-user-plus"></i>
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



        </nav>
        <div class="main">
          <div class="student-container">
            <div class="student-list">
              <div class="title d-flex justify-content-between align-items-center">
                <h4>List of Students</h4>
                <button class="btn btn-dark" data-toggle="modal" data-target="#addStudentModal">Add Student</button>
              </div>
              <hr>

              <!-- Filter Form -->
              <form method="GET" id="filterForm" class="mb-3">
                <div class="form-group d-flex justify-content-between">
                  <div class="col-md-5">
                    <label for="course" class="form-label">Filter by Course</label>
                    <select name="course" id="course" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                      <option value="">Select all</option>
                      <?php
                      include('./conn/conn.php');

                      // Fetch unique courses
                      $stmt = $conn->prepare("SELECT DISTINCT course FROM tbl_student");
                      $stmt->execute();
                      $courses = $stmt->fetchAll();

                      // Populate dropdown with courses
                      foreach ($courses as $course) {
                        $selected = (isset($_GET['course']) && $_GET['course'] == $course['course']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($course['course']) . '" ' . $selected . '>' . htmlspecialchars($course['course']) . '</option>';
                      }
                      ?>
                    </select>
                  </div>

                  <!-- Filter by Section -->
                  <div class="col-md-5">
                    <label for="section" class="form-label">Filter by Section</label>
                    <select name="section" id="section" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                      <option value="">Select all</option>
                      <?php
                      // Fetch unique sections
                      $stmt = $conn->prepare("SELECT DISTINCT section FROM tbl_student");
                      $stmt->execute();
                      $sections = $stmt->fetchAll();

                      // Populate dropdown with sections
                      foreach ($sections as $section) {
                        $selected = (isset($_GET['section']) && $_GET['section'] == $section['section']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($section['section']) . '" ' . $selected . '>' . htmlspecialchars($section['section']) . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <!-- Search Bar -->
                  <div class="col-md-5">
                    <label for="search" class="form-label">Search by Name</label>
                    <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Search by name" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" onkeyup="debounceSearch()" style="width: 150px;">
                  </div>

                  <script>
                    let debounceTimeout;

                    function debounceSearch() {
                      // Clear the previous timeout if it's still running
                      clearTimeout(debounceTimeout);

                      // Set a new timeout to submit the form after typing stops
                      debounceTimeout = setTimeout(function() {
                        document.getElementById('filterForm').submit(); // Submit the form after a delay
                      }, 500); // 500ms delay (can adjust as needed)
                    }
                  </script>


                </div>
              </form>

              <!-- Student Table -->
              <div class="table-container table-responsive">
                <table class="table text-center table-sm" id="studentTable">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">Course</th>
                      <th scope="col">Section</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Pagination settings
                    $recordsPerPage = 10;
                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($currentPage - 1) * $recordsPerPage;

                    // Get filter criteria
                    $selectedCourse = isset($_GET['course']) ? $_GET['course'] : '';
                    $selectedSection = isset($_GET['section']) ? $_GET['section'] : '';
                    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

                    // Count records for pagination with search filter
                    $countQuery = "SELECT COUNT(*) as total FROM tbl_student WHERE (:course = '' OR course = :course) AND (:section = '' OR section = :section) AND (student_name LIKE :searchQuery)";
                    $countStmt = $conn->prepare($countQuery);
                    $countStmt->bindParam(':course', $selectedCourse);
                    $countStmt->bindParam(':section', $selectedSection);
                    $countStmt->bindValue(':searchQuery', '%' . $searchQuery . '%');
                    $countStmt->execute();
                    $totalRecords = $countStmt->fetchColumn();
                    $totalPages = ceil($totalRecords / $recordsPerPage);

                    // Fetch filtered records for the current page, sorted alphabetically by student_name
                    $stmt = $conn->prepare("SELECT * FROM tbl_student WHERE (:course = '' OR course = :course) AND (:section = '' OR section = :section) AND (student_name LIKE :searchQuery) ORDER BY student_name ASC LIMIT :offset, :recordsPerPage");
                    $stmt->bindParam(':course', $selectedCourse);
                    $stmt->bindParam(':section', $selectedSection);
                    $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%');
                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                    $stmt->bindParam(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    foreach ($result as $row) {
                      $studentID = $row["tbl_student_id"];
                      $studentName = $row["student_name"];
                      $studentCourse = $row["course"];
                      $studentSection = $row["section"];
                      $qrCode = $row["generated_code"];
                    ?>
                      <tr>
                        <th scope="row" id="studentID-<?= $studentID ?>"><?= $studentID ?></th>
                        <td id="studentName-<?= $studentID ?>"><?= $studentName ?></td>
                        <td id="studentCourse-<?= $studentID ?>"><?= $studentCourse ?></td>
                        <td id="studentSection-<?= $studentID ?>"><?= $studentSection ?></td>
                        <td>
                          <div class="action-button">
                            <button class="btn btn-success btn-sm" data-toggle="modal"
                              data-target="#qrCodeModal<?= $studentID ?>"><img
                                src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png"
                                alt="" width="16"></button>

                            <!-- QR Modal -->
                            <div class="modal fade" id="qrCodeModal<?= $studentID ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title"><?= $studentName ?>'s QR Code</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body text-center">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $qrCode ?>" alt="" width="300">
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <button class="btn btn-secondary btn-sm" onclick="updateStudent(<?= $studentID ?>)">&#128393;</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteStudent(<?= $studentID ?>)">&#10006;</button>
                          </div>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>

              </div>

              <!-- Pagination Controls -->
              <nav>
                <ul class="pagination justify-content-center">
                  <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                      <a class="page-link" href="?page=<?= $currentPage - 1 ?>&course=<?= $selectedCourse ?>&section=<?= $selectedSection ?>&search=<?= $searchQuery ?>">Previous</a>
                    </li>
                  <?php endif; ?>

                  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                      <a class="page-link" href="?page=<?= $i ?>&course=<?= $selectedCourse ?>&section=<?= $selectedSection ?>&search=<?= $searchQuery ?>"><?= $i ?></a>
                    </li>
                  <?php endfor; ?>

                  <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                      <a class="page-link" href="?page=<?= $currentPage + 1 ?>&course=<?= $selectedCourse ?>&section=<?= $selectedSection ?>&search=<?= $searchQuery ?>">Next</a>
                    </li>
                  <?php endif; ?>
                </ul>
              </nav>
            </div>
          </div>
        </div>

<!-- Add Modal -->
<div class="modal fade" id="addStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
  aria-labelledby="addStudent" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addStudent">Add Student</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="./endpoint/add-student.php" method="POST">
          <div class="form-group">
            <label for="studentId">Student ID:</label>
            <input type="text" class="form-control" id="studentId" name="student_id" required>
          </div>
          <div class="form-group">
            <label for="studentName">Full Name:</label>
            <input type="text" class="form-control" id="studentName" name="student_name" required>
          </div>
          <div class="form-group">
            <label for="studentCourse">Course:</label>
            <input type="text" class="form-control" id="studentCourse" name="course" required>
          </div>
          <div class="form-group">
            <label for="studentSection">Section:</label>
            <input type="text" class="form-control" id="studentSection" name="section" required>
          </div>
          
          <button type="button" class="btn btn-secondary form-control qr-generator" onclick="generateQrCode()">Generate QR Code</button>

          <div class="qr-con text-center" style="display: none;">
            <input type="hidden" class="form-control" id="generatedCode" name="generated_code">
            <p>Take a pic with your QR code.</p>
            <img class="mb-4" src="" id="qrImg" alt="">
          </div>
          <div class="modal-footer modal-close" style="display: none;">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-dark">Add List</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

        <!-- Update Modal -->
        <div class="modal fade" id="updateStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="updateStudent" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="updateStudent">Update Student</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="./endpoint/update-student.php" method="POST">
                  <input type="hidden" class="form-control" id="updateStudentId" name="tbl_student_id">
                  <div class="form-group">
                    <label for="updateStudentName">Full Name:</label>
                    <input type="text" class="form-control" id="updateStudentName" name="student_name">
                  </div>
                  <div class="form-group">
                    <label for="updateStudentCourse">Course:</label>
                    <input type="text" class="form-control" id="updateStudentCourse" name="course">
                  </div>
                  <div class="form-group">
                    <label for="updateStudentSection">Section:</label>
                    <input type="text" class="form-control" id="updateStudentSection" name="section">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>




        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>

        <!-- Data Table -->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

        <script>
          $(document).ready(function() {
            $('#studentTable').DataTable();
          });


          function updateStudent(id) {
            // Show the modal
            $("#updateStudentModal").modal("show");

            // Get the student data from the table (make sure these IDs are set correctly in the table rows)
            let updateStudentId = $("#studentID-" + id).text();
            let updateStudentName = $("#studentName-" + id).text();
            let updateStudentCourse = $("#studentCourse-" + id).text();
            let updateStudentSection = $("#studentSection-" + id).text();

            // Debugging log to check if data is being fetched correctly
            console.log('ID:', updateStudentId);
            console.log('Name:', updateStudentName);
            console.log('Course:', updateStudentCourse);
            console.log('Section:', updateStudentSection);

            // Populate the modal fields
            $("#updateStudentId").val(updateStudentId);
            $("#updateStudentName").val(updateStudentName);
            $("#updateStudentCourse").val(updateStudentCourse);
            $("#updateStudentSection").val(updateStudentSection);
          }


          function deleteStudent(id) {
            if (confirm("Do you want to delete this student?")) {
              window.location = "./endpoint/delete-student.php?student=" + id;
            }
          }

          function generateRandomCode(length) {
            const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            let randomString = '';

            for (let i = 0; i < length; i++) {
              const randomIndex = Math.floor(Math.random() * characters.length);
              randomString += characters.charAt(randomIndex);
            }

            return randomString;
          }

          function generateQrCode() {
            var studentName = document.getElementById('studentName').value;
            var course = document.getElementById('studentCourse').value;
            var section = document.getElementById('studentSection').value;

            // Check if all fields are filled
            if (studentName.trim() === "" || course.trim() === "" || section.trim() === "") {
              alert("Please fill in all fields (Full Name, Course, Section) before generating the QR code.");
              return; // Prevent QR code generation if fields are empty
            }

            // Concatenate the data for the QR code (Full Name, Course, Section)
            var qrData = studentName + " - " + course + " - " + section;

            // Generate the QR code URL
            var qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + encodeURIComponent(qrData);

            // Update the image source with the generated QR code
            document.getElementById('qrImg').src = qrCodeUrl;

            // Store the QR data in the hidden field for later use
            document.getElementById('generatedCode').value = qrData;

            // Show the QR code and the footer
            document.querySelector('.qr-con').style.display = "block";
            document.querySelector('.modal-footer.modal-close').style.display = "block";
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