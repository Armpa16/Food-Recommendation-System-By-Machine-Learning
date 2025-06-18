<?php
session_start();

// ตรวจสอบเข้าระบบ
if (isset($_SESSION['username'])) {
    
} else {
    header("Location: loginform.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta charset="UTF-8"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health_Profile</title>
    <link rel="stylesheet" href="health_profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="health_data">
        <a href="loginform.php"><i class="fa-solid fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;ย้อนกลับ</a>
        <h1>กรุณากรอกข้อมูลของคุณ</h1>
        <p>เพื่อให้เราเเนะนำอาหารที่เหมาะสมสำหรับคุณ</p><br><br>
        <form method="POST" action="save_health_data.php">
            <div class="input_gender">
                <span>เพศ:</span><br>
                <input type="radio" name="gender" value="ชาย" required>&nbsp; ชาย &nbsp;&nbsp;&nbsp;
                <input type="radio" name="gender" value="หญิง">&nbsp; หญิง <br><br>
            </div>
            <div class="field">
                <div class="input_age">
                    <label>อายุ:</label><br>
                    <input type="number" name="age" required>&nbsp;&nbsp; ปี &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                
                <div class="input_weight">
                    <label>น้ำหนัก:</label><br>
                    <input type="number" name="weight" required>&nbsp;&nbsp; กก.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>

                <div class="input_height">
                    <label>ส่วนสูง:</label><br>
                    <input type="number" name="height" required>&nbsp;&nbsp;ซม. <br><br>
                </div>
            </div>
            <div class="input_diseases">
                <span>โรคประจำตัว:</span><br>
                <!-- <input type="checkbox" name="diseases" value="ไม่มีโรค" required>&nbsp; ไม่มีโรค &nbsp;&nbsp;&nbsp; -->
                <input type="checkbox" name="diseases[]" value="ไม่มีโรค">&nbsp; ไม่มีโรค &nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="diseases[]" value="โรคเบาหวาน">&nbsp; โรคเบาหวาน &nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="diseases[]" value="โรคความดันโลหิตสูง">&nbsp;โรคความดันโลหิตสูง &nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="diseases[]" value="โรคหัวใจ">&nbsp; โรคหัวใจ <br><br>
            </div>

            <div class="input_activity_level">
                <span>ระดับการทำกิจกรรม:</span><br>
                <input type="radio" name="activity_level" value="น้อย" required>&nbsp; น้อย &nbsp;&nbsp;&nbsp;
                <input type="radio" name="activity_level" value="ปานกลาง">&nbsp; ปานกลาง &nbsp;&nbsp;&nbsp;
                <input type="radio" name="activity_level" value="มาก"> &nbsp;มาก <br><br><br>
            </div>

            <button type="submit">บันทึกข้อมูล</button>
        </form>
    </div>
</body>
</html>