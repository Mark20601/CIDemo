<?php
include 'connection.php';

// Fetch the existing data for this student
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student - Student Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .edit-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header h3 {
            color: #667eea;
            font-size: 20px;
            font-weight: 500;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            display: inline-block;
        }

        .student-info {
            background: #f8f9ff;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            border: 1px solid #e0e7ff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .student-info-icon {
            font-size: 24px;
            background: #667eea;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .student-info-text {
            flex: 1;
        }

        .student-info-text p {
            color: #4a5568;
            font-size: 14px;
        }

        .student-info-text strong {
            color: #333;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            color: #a0aec0;
            font-size: 16px;
        }

        input, select {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e0e7ff;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        select {
            padding: 12px 15px 12px 45px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23a0aec0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input::placeholder {
            color: #a0aec0;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .btn-save {
            background: #667eea;
            color: white;
        }

        .btn-save:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-cancel {
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e0e7ff;
        }

        .btn-cancel:hover {
            background: #edf2f7;
            transform: translateY(-2px);
        }

        .required-field::after {
            content: " *";
            color: #e53e3e;
        }

        .field-hint {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 5px;
            margin-left: 45px;
        }

        /* Loading state for button */
        .btn-save:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .edit-container {
                padding: 25px;
            }
            
            .button-group {
                flex-direction: column;
            }
        }

        /* Gender icons styling */
        .gender-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>

<div class="edit-container">
    <div class="header">
        <h2>📝 Edit Student</h2>
        <h3>Update Information</h3>
    </div>

    <div class="student-info">
        <div class="student-info-icon">
            <?php
            $icon = $student['gender'] == 'Male' ? '👨' : ($student['gender'] == 'Female' ? '👩' : '👤');
            echo $icon;
            ?>
        </div>
        <div class="student-info-text">
            <p>Currently editing:</p>
            <strong><?= htmlspecialchars($student['full_name']) ?></strong>
            <p>ID: <?= htmlspecialchars($student['student_id']) ?></p>
        </div>
    </div>

    <form action="process.php" method="POST">
        <input type="hidden" name="id" value="<?= $student['id'] ?>">
        
        <div class="form-group">
            <label class="required-field">Student ID</label>
            <div class="input-wrapper">
                <span class="input-icon">🆔</span>
                <input type="text" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>" required placeholder="e.g., 2024-0001">
            </div>
        </div>
        
        <div class="form-group">
            <label class="required-field">Full Name</label>
            <div class="input-wrapper">
                <span class="input-icon">👤</span>
                <input type="text" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" required placeholder="Enter full name">
            </div>
        </div>
        
        <div class="form-group">
            <label>Age</label>
            <div class="input-wrapper">
                <span class="input-icon">🎂</span>
                <input type="number" name="age" value="<?= htmlspecialchars($student['age']) ?>" placeholder="Enter age">
            </div>
        </div>
        
        <div class="form-group">
            <label>Program</label>
            <div class="input-wrapper">
                <span class="input-icon">📚</span>
                <input type="text" name="program" value="<?= htmlspecialchars($student['program']) ?>" placeholder="e.g., Computer Science">
            </div>
        </div>
        
        <div class="form-group">
            <label>Year & Section</label>
            <div class="input-wrapper">
                <span class="input-icon">📌</span>
                <input type="text" name="year_section" value="<?= htmlspecialchars($student['year_section']) ?>" placeholder="e.g., 3A">
            </div>
        </div>
        
        <div class="form-group">
            <label>Gender</label>
            <div class="input-wrapper">
                <span class="input-icon">
                    <?php
                    $genderIcon = $student['gender'] == 'Male' ? '👨' : ($student['gender'] == 'Female' ? '👩' : '⚪');
                    echo $genderIcon;
                    ?>
                </span>
                <select name="gender">
                    <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>👨 Male</option>
                    <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>👩 Female</option>
                    <option value="Other" <?= $student['gender'] == 'Other' ? 'selected' : '' ?>>⚪ Other</option>
                </select>
            </div>
        </div>
        
        <div class="field-hint">
            <span style="color: #e53e3e;">*</span> Required fields
        </div>
        
        <div class="button-group">
            <button type="submit" name="update" class="btn btn-save">💾 Save Changes</button>
            <a href="index.php" class="btn btn-cancel">✖️ Cancel</a>
        </div>
    </form>
</div>

</body>
</html>