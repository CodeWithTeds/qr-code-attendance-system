<div class="main">
  <div class="student-container">
    <div class="student-list">
      <div class="title d-flex justify-content-between align-items-center">
        <h4>List of Instructors</h4>
        <button class="btn btn-dark" data-toggle="modal" data-target="#addStudentModal">Add Instructor</button>
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

          <div class="col-md-5">
            <label for="search" class="form-label">Search by Name</label>
            <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Search by name" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" onkeyup="debounceSearch()" style="width: 150px;">
          </div>
        </div>
      </form>

      <!-- Instructor Table -->
      <div class="table-container table-responsive">
        <table class="table text-center table-sm" id="instructorTable">
          <thead class="thead-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
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
            $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

            // Count records for pagination with search filter
            $countQuery = "SELECT COUNT(*) as total FROM users WHERE role = 'instructor' AND (name LIKE :searchQuery)";
            $countStmt = $conn->prepare($countQuery);
            $countStmt->bindValue(':searchQuery', '%' . $searchQuery . '%');
            $countStmt->execute();
            $totalRecords = $countStmt->fetchColumn();
            $totalPages = ceil($totalRecords / $recordsPerPage);

            // Fetch filtered records for the current page, sorted alphabetically by name
            $stmt = $conn->prepare("SELECT * FROM users WHERE role = 'instructor' AND (name LIKE :searchQuery) ORDER BY name ASC LIMIT :offset, :recordsPerPage");
            $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%');
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
            $stmt->execute();
            $instructors = $stmt->fetchAll();

            foreach ($instructors as $instructor) {
              $instructorID = $instructor["id"];
              $instructorName = $instructor["name"];
              $instructorEmail = $instructor["email"];
            ?>
              <tr>
                <th scope="row" id="instructorID-<?= $instructorID ?>"><?= $instructorID ?></th>
                <td id="instructorName-<?= $instructorID ?>"><?= $instructorName ?></td>
                <td id="instructorEmail-<?= $instructorID ?>"><?= $instructorEmail ?></td>
                <td>
                  <button class="btn btn-secondary btn-sm" onclick="updateInstructor(<?= $instructorID ?>)">&#128393;</button>
                  <button class="btn btn-danger btn-sm" onclick="deleteInstructor(<?= $instructorID ?>)">&#10006;</button>
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
              <a class="page-link" href="?page=<?= $currentPage - 1 ?>&search=<?= $searchQuery ?>">Previous</a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>&search=<?= $searchQuery ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $currentPage + 1 ?>&search=<?= $searchQuery ?>">Next</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>
