<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$dbname = "food_recommend_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize user inputs
$user = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$c_password = mysqli_real_escape_string($conn, $_POST['c_password']);


// Check if username already exists
$sql_check = "SELECT * FROM users WHERE username = '$user'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    echo "<script>
            alert('ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว กรุณาเลือกชื่ออื่น');
            window.location.href='/Food/register.php';
          </script>";
    exit();
}
// Check if passwords match
if ($password !== $c_password) {
    echo "<script>
            alert('รหัสผ่านไม่ตรงกัน กรุณาลองใหม่');
            window.location.href='/Food/register.php';
          </script>";
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// Insert data into the database
$sql = "INSERT INTO users (username, email, password) 
        VALUES ('$user','$email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('สร้างบัญชีสำเร็จ! กรุณากลับไปล็อกอิน');
            window.location.href='/Food/loginform.php';
          </script>";
} else {
    echo "<script>
    alert('เกิดข้อผิดพลาด: " . addslashes($conn->error) . "');
    window.location.href='/Food/register.php';
  </script>";
}

$conn->close();
?>
