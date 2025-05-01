<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$dbname = "food_recommend_system";

// รับข้อมูลจาก AJAX
$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data)) {
    try {
        // ตรวจสอบข้อมูลที่ได้รับ
        $user_id = $data['user_id'];
        $his_date = $data['his_date'];
        $foods = $data['foods'];

        // ตรวจสอบว่ามีข้อมูลอาหารหรือไม่
        if (!empty($foods)) {
            // วนลูปเพื่อบันทึกข้อมูลแต่ละรายการ
            foreach ($foods as $item) {
                $stmt = $pdo->prepare("INSERT INTO food_history (users_id, food_id, his_date, meal_type) 
                                      VALUES (:user_id, :food_id, :his_date, :meal_type)");
                $stmt->execute([
                    ':user_id' => $user_id,
                    ':food_id' => $item['food_id'],
                    ':his_date' => $his_date,
                    ':meal_type' => $item['meal_type']
                ]);
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ไม่มีข้อมูลอาหาร']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ไม่มีข้อมูล']);
}
?>