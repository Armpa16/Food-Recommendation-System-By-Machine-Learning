<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_recommend_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: loginform.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$gender = $_POST['gender'];
$age = $_POST['age'];
$weight = $_POST['weight'];
$height = $_POST['height'];
$activity_level = $_POST['activity_level'];

// เช็คว่ามีค่าจาก Checkbox ไหม
$diseases = isset($_POST['diseases']) ? implode(", ", $_POST['diseases']) : "ไม่มีโรค";

// คำนวณ BMI
$height_met = $height / 100;
$bmi = $weight / ($height_met * $height_met);
$bmi = round($bmi, 2);

$status_bmi = '';
if ($bmi < 18.50) {
    $status_bmi = "น้ำหนักต่ำกว่าเกณฑ์";
} elseif ($bmi < 23.00) {
    $status_bmi = "น้ำหนักสมส่วน";
} elseif ($bmi < 25.00) {
    $status_bmi = "น้ำหนักเกินเกณฑ์";
} else {
    $status_bmi = "อ้วน";
}

// คำนวณแคลอรี่ที่ต้องการต่อวัน
if ($gender == "ชาย") {
    $bmr = 66 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);
} else {
    $bmr = 655 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
}

if ($activity_level == "น้อย") {
    $daily_calorie = $bmr * 1.375;
} elseif ($activity_level == "ปานกลาง") {
    $daily_calorie = $bmr * 1.55;
} else {
    $daily_calorie = $bmr * 1.7;
}

$daily_calorie = round($daily_calorie, 2);

// อัปเดตข้อมูลในฐานข้อมูล
$sql = "UPDATE profiles 
        SET gender = ?, age = ?, weight = ?, height = ?, diseases = ?, activity_level = ?, bmi = ?, status_bmi = ?, daily_calorie = ? 
        WHERE users_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssdsdi", 
    $gender, 
    $age, 
    $weight, 
    $height, 
    $diseases, 
    $activity_level, 
    $bmi, 
    $status_bmi, 
    $daily_calorie, 
    $user_id
);

if ($stmt->execute()) {
    header("Location: /Food/profile.php"); // กลับไปที่หน้าโปรไฟล์
    echo "<script>alert('บันทึกข้อมูลสำเร็จ');</script>";
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>


