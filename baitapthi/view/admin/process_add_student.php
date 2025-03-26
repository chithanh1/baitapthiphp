<?php
// File: views/admin/process_add_student.php
session_start();
require_once '../../config/database.php';

// Kiểm tra nếu dữ liệu được gửi đi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $MaSV = $_POST['MaSV'];
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];

    // Xử lý upload ảnh
    $upload_dir = "../../public/uploads/";
    $file_name = "";
    if (!empty($_FILES['Hinh']['name'])) {
        $file_name = basename($_FILES["Hinh"]["name"]);
        $target_file = $upload_dir . $file_name;
        
        if (!move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file)) {
            $_SESSION['error'] = "Lỗi khi tải ảnh.";
            header("Location: add_student.php");
            exit();
        }
    }

    // Kiểm tra xem Mã SV đã tồn tại chưa
    $checkSQL = "SELECT * FROM SinhVien WHERE MaSV = '$MaSV'";
    $checkResult = $conn->query($checkSQL);
    if ($checkResult->num_rows > 0) {
        $_SESSION['error'] = "Mã sinh viên đã tồn tại!";
        header("Location: add_student.php");
        exit();
    }

    // Thêm sinh viên vào database
    $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh) 
            VALUES ('$MaSV', '$HoTen', '$GioiTinh', '$NgaySinh', '$file_name')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = "Lỗi khi thêm sinh viên.";
        header("Location: add_student.php");
    }
}
?>
