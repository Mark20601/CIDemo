<?php
include 'connection.php';

// Handle CREATE
if (isset($_POST['add'])) {
    $student_id = $_POST['student_id'];

    // 1. SELECT query to check for existence
    $check_sql = "SELECT COUNT(*) FROM students WHERE student_id = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$student_id]);
    $count = $check_stmt->fetchColumn();

    if ($count > 0) {
        header("Location: index.php?error= Student ID already Exists!");
    } else {
        // 3. If ID is unique, proceed with the INSERT
        $sql = "INSERT INTO students (student_id, full_name, age, program, year_section, gender) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $student_id, 
            $_POST['full_name'], 
            $_POST['age'], 
            $_POST['program'], 
            $_POST['year_section'], 
            $_POST['gender']
        ]);
        
        header("Location: index.php?success= Student Saved in the Database!");
        exit();
    }
}


// Handle UPDATE
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $program = $_POST['program'];
    $year_section = $_POST['year_section'];
    $gender = $_POST['gender'];

    $sql = "UPDATE students SET 
            student_id = ?, 
            full_name = ?, 
            age = ?, 
            program = ?, 
            year_section = ?, 
            gender = ? 
            WHERE id = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$student_id, $full_name, $age, $program, $year_section, $gender, $id]);
    
    header("Location: index.php");
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$id]);
    header("Location: index.php");
}
?>