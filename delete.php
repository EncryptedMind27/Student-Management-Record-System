<?php
include "db-connect.php";

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $studentID = mysqli_real_escape_string($connect, $_GET["id"]);

    if (is_numeric($studentID)) {
        // Delete the student record
        $sql = "DELETE FROM `student_table` WHERE `StudentID` = $studentID";
        $result = mysqli_query($connect, $sql);

        if ($result) {
            header("Location: app.php?msg=Data deleted successfully");
            exit(); 
        } else {
            echo "Failed to delete record: " . mysqli_error($connect);
        }
    } else {
        echo "Invalid student ID.";
    }
} else {
    echo "Student ID not provided.";
}
?>