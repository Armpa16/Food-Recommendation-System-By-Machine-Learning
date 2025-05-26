<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "food_recommend_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่ 
if (!isset($_SESSION['user_id'])) {
    die("<script>alert('กรุณาเข้าสู่ระบบก่อน!'); window.location.href='login.php';</script>");
}
$user_id = $_SESSION['user_id'];

// ตรวจสอบว่ามีผู้ใช้นี้อยู่ในตาราง users หรือไม่
$checkQuery = "SELECT COUNT(*) AS count FROM users WHERE users_id = ?";
$stmtCheck = $conn->prepare($checkQuery);
$stmtCheck->bind_param("i", $user_id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$row = $resultCheck->fetch_assoc();
if ($row['count'] == 0) {
    die("<script>alert('ไม่พบผู้ใช้ในระบบ กรุณาล็อกอินอีกครั้ง'); window.location.href='login.php';</script>");
}
$stmtCheck->close();


// รับค่าจากฟอร์ม
$gender = mysqli_real_escape_string($conn, $_POST['gender']);
$age = mysqli_real_escape_string($conn, $_POST['age']);
$weight = mysqli_real_escape_string($conn, $_POST['weight']);
$height = mysqli_real_escape_string($conn, $_POST['height']);
$diseases = mysqli_real_escape_string($conn, $_POST['diseases']);
$activity_level = mysqli_real_escape_string($conn, $_POST['activity_level']);

// คำนวณ BMI
$height_met = $height / 100;
$bmi = $weight / ($height_met * $height_met);
$bmi = round($bmi, 2); // เปลี่ยนเป็นทศนิยม 2 ตำแหน่ง


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

$daily_calorie = '';
if($gender == "ชาย") {
    //คำนวณ BMR สำหรับเพศชาย
    $bmr = 66 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);
    //คำนวณระดับกิจกรรม
    if($activity_level == "น้อย") {
        $daily_calorie = $bmr * 1.375;
    } elseif($activity_level == "ปานกลาง") {
        $daily_calorie = $bmr * 1.55;
    } elseif($activity_level == "มาก") {
        $daily_calorie = $bmr * 1.7;
    }
} elseif($gender == "หญิง") {
    //คำนวณ BMR สำหรับเพศหญิง
    $bmr = 655 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
    //คำนวณระดับกิจกรรม
    if($activity_level == "น้อย") {
        $daily_calorie = $bmr * 1.375;
    } elseif($activity_level == "ปานกลาง") {
        $daily_calorie = $bmr * 1.55;
    } elseif($activity_level == "มาก") {
        $daily_calorie = $bmr * 1.7;
    }
} else {
	echo "Gender ".$gender." is not supported at this time."; 
}


// บันทึกข้อมูล
$sql = "INSERT INTO profiles (users_id, gender, age, weight, height, diseases, activity_level, bmi, status_bmi, daily_calorie) 
        VALUES ($user_id,'$gender','$age','$weight','$height','$diseases','$activity_level','$bmi','$status_bmi','$daily_calorie')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('บันทึกข้อมูลสำเร็จ');
            window.location.href='index.php';
          </script>";
    } else {
    echo "<script>
            alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            window.location.href='health_profile.php';
        </script>";
}

$conn->close();
?>