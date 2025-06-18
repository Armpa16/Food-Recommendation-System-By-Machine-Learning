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


// รับค่าดิบจากฟอร์มสำหรับคำนวณ
$raw_gender = isset($_POST['gender']) ? $_POST['gender'] : '';
$raw_age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
$raw_weight = isset($_POST['weight']) ? (float)$_POST['weight'] : 0.0;
$raw_height = isset($_POST['height']) ? (float)$_POST['height'] : 0.0;
$raw_activity_level = isset($_POST['activity_level']) ? $_POST['activity_level'] : '';

// ประมวลผลข้อมูลโรคประจำตัว
$processed_diseases_string = "ไม่มีโรค"; // ค่าเริ่มต้นหากไม่ได้เลือก หรือเลือก "ไม่มีโรค"
if (isset($_POST['diseases']) && is_array($_POST['diseases'])) {
    $selected_diseases_array = $_POST['diseases'];
    if (!empty($selected_diseases_array)) {
        if (in_array('ไม่มีโรค', $selected_diseases_array)) {
            $processed_diseases_string = 'ไม่มีโรค'; // หากเลือก "ไม่มีโรค" ให้ใช้ค่านี้เท่านั้น
        } else {
            $processed_diseases_string = implode(', ', $selected_diseases_array); // รวมโรคอื่นๆ เป็นสตริง
        }
    }
}

// Escape ค่าที่จะบันทึกลง DB ที่เป็นสตริง
$gender_db = mysqli_real_escape_string($conn, $raw_gender);
$activity_level_db = mysqli_real_escape_string($conn, $raw_activity_level);
$diseases_db = mysqli_real_escape_string($conn, $processed_diseases_string);


// คำนวณ BMI
$height_met = $raw_height / 100;
$bmi = 0;
if ($height_met > 0) {
    $bmi = $raw_weight / ($height_met * $height_met);
}
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
if($raw_gender == "ชาย") {
    //คำนวณ BMR สำหรับเพศชาย
    $bmr = 66 + (13.7 * $raw_weight) + (5 * $raw_height) - (6.8 * $raw_age);
    //คำนวณระดับกิจกรรม
    if($raw_activity_level == "น้อย") {
        $daily_calorie = $bmr * 1.375;
    } elseif($raw_activity_level == "ปานกลาง") {
        $daily_calorie = $bmr * 1.55;
    } elseif($raw_activity_level == "มาก") {
        $daily_calorie = $bmr * 1.725;
    }
} elseif($raw_gender == "หญิง") {
    //คำนวณ BMR สำหรับเพศหญิง
    $bmr = 655 + (9.6 * $raw_weight) + (1.8 * $raw_height) - (4.7 * $raw_age);
    //คำนวณระดับกิจกรรม
    if($raw_activity_level == "น้อย") {
        $daily_calorie = $bmr * 1.375;
    } elseif($raw_activity_level == "ปานกลาง") {
        $daily_calorie = $bmr * 1.55;
    } elseif($raw_activity_level == "มาก") {
        $daily_calorie = $bmr * 1.725;
    }
} else {
	echo "Gender ".$raw_gender." is not supported at this time."; 
}


// บันทึกข้อมูล
// สังเกตว่าค่าที่เป็นตัวเลข (age, weight, height, bmi, daily_calorie) ไม่จำเป็นต้องใส่ single quote หากคอลัมน์ใน DB เป็นชนิดตัวเลข
$sql = "INSERT INTO profiles (users_id, gender, age, weight, height, diseases, activity_level, bmi, status_bmi, daily_calorie)
        VALUES ($user_id, '$gender_db', $raw_age, $raw_weight, $raw_height, '$diseases_db', '$activity_level_db', $bmi, '$status_bmi', $daily_calorie)"; 

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