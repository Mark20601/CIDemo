<?php include 'connection.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
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
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 30px;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            display: inline-block;
        }

        /* Form Styles */
        .form-container {
            background: #f8f9ff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #e0e7ff;
        }

        .form-container h3 {
            color: #4a5568;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .form-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        input, select {
            padding: 12px 15px;
            border: 2px solid #e0e7ff;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input::placeholder {
            color: #a0aec0;
        }

        .btn {
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            width: auto;
            min-width: 150px;
        }

        .btn:hover {
            background: #5a67d8;
        }

        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
            border-radius: 15px;
            border: 1px solid #e0e7ff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #f8f9ff;
            color: #4a5568;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #e0e7ff;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e0e7ff;
            color: #4a5568;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f8f9ff;
        }

        /* Action Links */
        .action-links {
            display: flex;
            gap: 10px;
        }

        .edit-link, .delete-link {
            text-decoration: none;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .edit-link {
            background: #ebf4ff;
            color: #3182ce;
        }

        .edit-link:hover {
            background: #3182ce;
            color: white;
        }

        .delete-link {
            background: #fff5f5;
            color: #e53e3e;
        }

        .delete-link:hover {
            background: #e53e3e;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #a0aec0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .form-group {
                grid-template-columns: 1fr;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📚 Student Management System</h2>
<?php if (isset($_GET['error'])): ?>
    <div id="errorModal" style="
        position: fixed; 
        top: 20px; 
        left: 50%; 
        transform: translateX(-50%); 
        background-color: #f8d7da; 
        color: #721c24; 
        padding: 15px 30px; 
        border: 1px solid #f5c6cb; 
        border-radius: 5px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 15px;">
        
        <strong>Error:</strong> <?php echo htmlspecialchars($_GET['error']); ?>
        
        <button onclick="this.parentElement.style.display='none'" style="
            background: none; 
            border: none; 
            font-size: 20px; 
            cursor: pointer; 
            color: #721c24;">&times;</button>
    </div>
<?php endif; ?>
<?php if (isset($_GET['success'])): ?>
    <div id="successModal" style="
        position: fixed; 
        top: 20px; 
        left: 50%; 
        transform: translateX(-50%); 
        background-color: #d4edda; /* Light Green Background */
        color: #155724;            /* Dark Green Text */
        padding: 15px 30px; 
        border: 1px solid #c3e6cb; /* Green Border */
        border-radius: 5px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 15px;
        font-family: sans-serif;">
        
        <strong>Success:</strong> <?php echo htmlspecialchars($_GET['success']); ?>
        
        <button onclick="this.parentElement.style.display='none'" style="
            background: none; 
            border: none; 
            font-size: 20px; 
            cursor: pointer; 
            color: #155724;         /* Match Dark Green */
            line-height: 1;">&times;</button>
    </div>
<?php endif; ?>
    <div class="form-container">
        <h3>➕ Add New Student</h3>
        <form action="process.php" method="POST">
            <div class="form-group">
                <input type="text" name="student_id" placeholder="Student ID" required>
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="number" name="age" placeholder="Age">
                <input type="text" name="program" placeholder="Program">
                <input type="text" name="year_section" placeholder="Year & Section">
                <select name="gender">
                    <option value="Male">👨 Male</option>
                    <option value="Female">👩 Female</option>
                    <option value="Other">⚪ Other</option>
                </select>
            </div>
            <button type="submit" name="add" class="btn">Add Student</button>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>Program</th>
                    <th>Year & Section</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM students");
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch()) {
                        $genderIcon = $row['gender'] == 'Male' ? '👨' : ($row['gender'] == 'Female' ? '👩' : '⚪');
                        echo "<tr>
                            <td><strong>{$row['student_id']}</strong></td>
                            <td>{$row['full_name']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['program']}</td>
                            <td>{$row['year_section']}</td>
                            <td>{$genderIcon} {$row['gender']}</td>
                            <td>
                                <div class='action-links'>
                                    <a href='edit.php?id={$row['id']}' class='edit-link'>✏️ Edit</a>
                                    
                                </div>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='empty-state'>📭 No students found. Add your first student above! Thank You!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>