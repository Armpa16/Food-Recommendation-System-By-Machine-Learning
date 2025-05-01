<?php
session_start();

// ตรวจสอบว่าเซสชันผู้ใช้มีอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    // หากไม่มีการเข้าสู่ระบบ ให้ส่งไปหน้า login
    header("Location: loginform.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // รับ user_id จากเซสชัน

// URL ของ Flask API
$url = 'http://127.0.0.1:5000/get_recommendations';

// ข้อมูลที่ต้องการส่งไป
$data = array('user_id' => $user_id);

// ตั้งค่า HTTP headers และส่งข้อมูลในรูปแบบ JSON
$options = array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-Type: application/json',
        'content' => json_encode($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

// แสดงผลลัพธ์ที่ได้รับจาก API
if ($result !== FALSE) {
    $response = json_decode($result, true);
    if (isset($response['recommendations'])) {
        $recommendations = $response['recommendations'];
        echo "คำแนะนำอาหารของวันนี้:<br>";
        echo "อาหารเช้า: " . $recommendations['breakfast'] . "<br>";
        echo "อาหารกลางวัน: " . $recommendations['lunch'] . "<br>";
        echo "อาหารเย็น: " . $recommendations['dinner'] . "<br>";
    } else {
        echo "ไม่สามารถดึงข้อมูลคำแนะนำได้";
    }
} else {
    echo "ไม่สามารถเชื่อมต่อกับ API ได้";
}
?>
