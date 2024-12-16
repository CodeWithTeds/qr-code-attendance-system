<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging log to check if POST data is received correctly
    var_dump($_POST);  // This will print all the data coming in the POST request

    // Check if the required POST fields are set
    if (isset($_POST['tbl_student_id'], $_POST['student_name'], $_POST['course'], $_POST['section'])) {
        // Get values from POST
        $studentId = $_POST['tbl_student_id'];
        $studentName = $_POST['student_name'];
        $studentCourse = $_POST['course'];
        $studentSection = $_POST['section'];

        try {
            // Prepare the SQL query to update the student record
            $stmt = $conn->prepare("UPDATE tbl_student SET student_name = :student_name, course = :course, section = :section WHERE tbl_student_id = :tbl_student_id");
            
            // Bind the parameters to the SQL query
            $stmt->bindParam(":tbl_student_id", $studentId, PDO::PARAM_STR); 
            $stmt->bindParam(":student_name", $studentName, PDO::PARAM_STR); 
            $stmt->bindParam(":course", $studentCourse, PDO::PARAM_STR); 
            $stmt->bindParam(":section", $studentSection, PDO::PARAM_STR);

            // Execute the update query
            $stmt->execute();

            // Redirect to the masterlist page after successful update
            header("Location: http://localhost/qr-code-attendance-system/html/masterlist.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        // If required fields are missing, show an alert and redirect
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/qr-code-attendance-system/html/masterlist.php';
            </script>
        ";
    }
}
?>
