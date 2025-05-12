<?php
session_start();
header('Content-Type: application/json'); // ส่งข้อมูลเป็น JSON

// --- การเชื่อมต่อฐานข้อมูล ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_recommend_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    // ส่ง JSON กลับไปพร้อมข้อผิดพลาด
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit(); // จบการทำงานทันที
}

// --- ตรวจสอบการเข้าสู่ระบบ ---
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
    exit();
}

$user_id = $_SESSION['user_id'];

$gender = $_POST['gender'];
$age = $_POST['age'];
$weight = $_POST['weight'] ?? null; // ใช้ null coalescing operator เพื่อความปลอดภัย
$height = $_POST['height'] ?? null;
$activity_level = $_POST['activity_level'];

// การจัดการข้อมูล diseases (checkbox)
$diseases_array = isset($_POST['diseases']) && is_array($_POST['diseases']) ? $_POST['diseases'] : [];
// ถ้าเลือก "ไม่มีโรค" ให้ใช้ค่านี้ค่าเดียว หรือถ้าไม่เลือกเลยก็ให้เป็น "ไม่มีโรค"
if (in_array('ไม่มีโรค', $diseases_array) || empty($diseases_array)) {
    $diseases_array = ['ไม่มีโรค'];
}
$diseases = implode(',', $diseases_array); // รวมเป็น string คั่นด้วย comma

// --- ตรวจสอบข้อมูล Input เบื้องต้น (ควรเพิ่มการตรวจสอบให้ละเอียดขึ้น) ---
if (empty($gender) || empty($age) || empty($weight) || empty($height) || empty($activity_level)) {
     echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
     exit();
}

// --- คำนวณ BMI, Status BMI, Daily Calorie ---
$bmi = 0;
$status_bmi = '';
$daily_calorie = 0;
$bmr = 0;

$status_bmi = '';
if (!empty($height) && $height > 0) {
    $height_met = $height / 100;
    if (!empty($weight) && $weight > 0) {
        $bmi = round($weight / ($height_met * $height_met), 2);

        if ($bmi < 18.50) {
            $status_bmi = "น้ำหนักต่ำกว่าเกณฑ์";
        } elseif ($bmi < 23.00) {
            $status_bmi = "น้ำหนักสมส่วน";
        } elseif ($bmi < 25.00) {
            $status_bmi = "น้ำหนักเกินเกณฑ์";
        } elseif ($bmi >= 25.00) {
            $status_bmi = "อ้วน";
        } else {
             $status_bmi = "ไม่สามารถคำนวณได้";
        }
    }
} else {
     $status_bmi = "ไม่สามารถคำนวณได้ (ส่วนสูงไม่ถูกต้อง)";
}


if (!empty($age) && !empty($weight) && !empty($height)) {
    if ($gender == "ชาย") {
        $bmr = 66 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);
    } elseif ($gender == "หญิง") {
        $bmr = 655 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
    }

    if ($bmr > 0) {
        if ($activity_level == "น้อย") {
            $daily_calorie = $bmr * 1.375;
        } elseif ($activity_level == "ปานกลาง") {
            $daily_calorie = $bmr * 1.55;
        } elseif ($activity_level == "มาก") {
            $daily_calorie = $bmr * 1.7;
        }
    }
}
$daily_calorie = round($daily_calorie); // ปัดเศษแคลอรี่

// --- เตรียมคำสั่ง SQL UPDATE ด้วย Prepared Statement ---
$sql = "UPDATE profiles 
        SET gender = ?, age = ?, weight = ?, height = ?, diseases = ?, activity_level = ?, bmi = ?, status_bmi = ?, daily_calorie = ?
        WHERE users_id = ?";

$stmt = $conn->prepare($sql);

// ตรวจสอบว่า prepare สำเร็จหรือไม่
if ($stmt === false) {
     echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
     $conn->close();
     exit();
}

// --- Bind Parameters ---
// ประเภทข้อมูล: s = string, i = integer, d = double
$stmt->bind_param("siisssdsii", // ตรวจสอบประเภทข้อมูลให้ตรงกับคอลัมน์ใน DB
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
    // ตรวจสอบว่ามีการอัปเดตข้อมูลจริงหรือไม่
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    } else {
        // ไม่มีการอัปเดต อาจจะเพราะข้อมูลเหมือนเดิม หรือไม่พบ user_id
        // ถือว่าสำเร็จถ้าไม่มี error เพราะข้อมูลใน DB ตรงกับที่ส่งมาแล้ว
        echo json_encode(['success' => true, 'message' => 'ข้อมูลเป็นปัจจุบันแล้ว']);
        // หรืออาจจะแจ้งว่า: echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลโปรไฟล์ หรือข้อมูลไม่มีการเปลี่ยนแปลง']);
    }
} else {
    // เกิดข้อผิดพลาดระหว่าง execute
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $stmt->error]);
}

// --- ปิด Statement และ Connection ---
$stmt->close();
$conn->close();
?>