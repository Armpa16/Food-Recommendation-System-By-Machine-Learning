<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "food_recommend_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// ดึงข้อมูลและทำความสะอาดอินพุตของผู้ใช้
$user_input = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

// Query database for user
$sql = "SELECT * FROM users WHERE username = '$user_input'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // ดึงข้อมูลผู้ใช้
    $user = $result->fetch_assoc();
    $hashed_password = $user['password']; //รหัสผ่านจากฐานข้อมูล
    // ตรวจสอบรหัสผ่าน
    if (password_verify($password, $hashed_password)) {
        // รหัสผ่านถูกต้อง --> เข้าระบบสำเร็จ
        $_SESSION['user_id'] = $user['users_id'];
        $_SESSION['username'] = $user['username'];

        // ตรวจสอบว่ามีข้อมูลสุขภาพ
        $user_id = $_SESSION['user_id'];
        $profile_check_sql = "SELECT * FROM profiles WHERE users_id = ?";
        $stmt_profile = $conn->prepare($profile_check_sql);
        $stmt_profile->bind_param("i", $user_id);
        $stmt_profile->execute();
        $result_profile = $stmt_profile->get_result();
        
        if ($result_profile->num_rows > 0) {
            // มีข้อมูล health profile แล้ว -> ไปหน้า index
            header("Location: index.php");
        } else {
            // ยังไม่มี health profile -> ไปหน้า health_profile.php
            header("Location: health_profile.php");
        }
        exit();
    } else {
        // Password incorrect
        header("Location: loginform.php?status=2");
        exit();
    }
} else {
    // Email not found
    header("Location: loginform.php?status=1");
    exit();
}

$conn->close();
?>
