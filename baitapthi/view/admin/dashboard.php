<?php
// File: views/admin/dashboard.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
require_once '../../config/database.php';
$students = $conn->query("SELECT * FROM SinhVien");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            margin-right: 10px;
        }
        .btn:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .logout {
            float: right;
            padding: 10px;
            background: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout:hover {
            background: darkred;
        }
        .nav-links {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Chào mừng, <?= $_SESSION['admin'] ?>!</h2>
        <a href="logout.php" class="logout">Đăng xuất</a>
        
        <div class="nav-links">
            <a href="hocphan.php" class="btn">Quản lý Học Phần</a>
            <a href="dangkyhocphan.php" class="btn">Quản lý Đăng Ký Học Phần</a>
        </div>
        
        <h3>Danh sách Sinh Viên</h3>
        <a href="add_student.php" class="btn">Thêm Sinh Viên</a>
        <table>
            <tr>
                <th>Mã SV</th>
                <th>Họ Tên</th>
                <th>Giới Tính</th>
                <th>Ngày Sinh</th>
                <th>Ảnh</th>
                <th>Hành động</th>
            </tr>
            <?php while ($student = $students->fetch_assoc()): ?>
                <tr>
                    <td><?= $student['MaSV'] ?></td>
                    <td><?= $student['HoTen'] ?></td>
                    <td><?= $student['GioiTinh'] ?></td>
                    <td><?= $student['NgaySinh'] ?></td>
                    <td>
                        <?php if (!empty($student['Hinh'])): ?>
                            <img src="../../public/uploads/<?= $student['Hinh'] ?>" width="50">
                        <?php else: ?>
                            Chưa có ảnh
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $student['MaSV'] ?>" class="btn">Sửa</a>
                        <a href="delete.php?id=<?= $student['MaSV'] ?>" class="btn" style="background: red;" onclick="return confirm('Xóa sinh viên này?')">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
