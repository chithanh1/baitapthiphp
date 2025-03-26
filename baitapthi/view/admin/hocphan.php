<?php
session_start();
require_once '../../config/database.php';

// Kiểm tra nếu không phải admin, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Truy vấn danh sách học phần
$sql = "SELECT * FROM HocPhan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Học Phần</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #007bff; color: white; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; border-radius: 5px; }
        .btn-add { background: green; }
        .btn-edit { background: orange; }
        .btn-delete { background: red; }
    </style>
</head>
<body>
    <h2>Quản Lý Học Phần</h2>
    <a href="add_hocphan.php" class="btn btn-add">Thêm Học Phần</a>
    <table>
        <tr>
            <th>Mã Học Phần</th>
            <th>Tên Học Phần</th>
            <th>Số Tín Chỉ</th>
            <th>Số Lượng Dự Kiến</th>
            <th>Hành Động</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['MaHP'] ?></td>
            <td><?= htmlspecialchars($row['TenHP']) ?></td>
            <td><?= $row['SoTinChi'] ?></td>
            <td><?= $row['SoLuongDuKien'] ?></td>
            <td>
                <a href="edit_hocphan.php?id=<?= $row['MaHP'] ?>" class="btn btn-edit">Sửa</a>
                <a href="delete_hocphan.php?id=<?= $row['MaHP'] ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
