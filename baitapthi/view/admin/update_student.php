<?php
// File: views/admin/update_student.php
session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $MaSV = $_POST['MaSV'];
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $upload_dir = "../../public/uploads/";
    
    // Lấy ảnh hiện tại của sinh viên
    $sql = "SELECT Hinh FROM SinhVien WHERE MaSV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $MaSV);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $current_image = $student['Hinh'];
    
    // Kiểm tra nếu có ảnh mới được tải lên
    if (!empty($_FILES['Hinh']['name'])) {
        $file_name = basename($_FILES["Hinh"]["name"]);
        $target_file = $upload_dir . $file_name;
        
        // Xóa ảnh cũ nếu có
        if (!empty($current_image) && file_exists($upload_dir . $current_image)) {
            unlink($upload_dir . $current_image);
        }
        
        // Kiểm tra và di chuyển file mới vào thư mục uploads
        if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file)) {
            // Cập nhật thông tin sinh viên bao gồm ảnh
            $sql = "UPDATE SinhVien SET HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=? WHERE MaSV=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $HoTen, $GioiTinh, $NgaySinh, $file_name, $MaSV);
        } else {
            $_SESSION['error'] = "Lỗi khi tải ảnh.";
            header("Location: edit.php?id=" . $MaSV);
            exit();
        }
    } else {
        // Chỉ cập nhật thông tin, không thay đổi ảnh
        $sql = "UPDATE SinhVien SET HoTen=?, GioiTinh=?, NgaySinh=? WHERE MaSV=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $HoTen, $GioiTinh, $NgaySinh, $MaSV);
    }

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = "Lỗi khi cập nhật sinh viên.";
        header("Location: edit.php?id=" . $MaSV);
    }
}
?>