<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/loginform.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="box_left">
        <form method="POST" action="login.php" > 
            <h1>เข้าสู่ระบบบัญชีของคุณ</h1><br><br>
            <div class="name">
                <label>ชื่อผู้ใช้</label><br><br>
                <input type="text" name="username"  placeholder="กรุณากรอกชื่อผู้ใช้ของคุณ" required>
                <!-- <i class="fa-solid fa-lock"></i> -->
            </div><br>
            <div class="pass">
                <label>รหัสผ่าน</label><br><br>
                <input type="password" name="password"  placeholder="กรุณากรอกรหัสผ่านของคุณ" required>
                <!-- <i class="fa-solid fa-lock"></i> -->
            </div><br><br><br>
            <button type="submit">เข้าสู่ระบบ</button><br><br>
            <div class="regis">
                <hr>&nbsp;&nbsp;&nbsp;&nbsp;หากยังไม่มีบัญชี&nbsp;&nbsp;&nbsp;&nbsp;<hr>
            </div><br><br>
            <a href="register.php">สมัครสมาชิก</a>
            <?php
                if (isset($_GET['status'])) {
                    $status = $_GET['status'];
                    if ($status == 1){
                        echo "<script>alert('ชื่อผู้ใช้งานไม่ถูกต้อง');</script>";
                    }else if($status==2) {
                        echo "<script>alert('รหัสผ่านไม่ถูกต้อง');</script>";  
                }
            }
            ?>
        </form>  
    </div>
    <!-- box_left -->
    <div class="box_right">
        <div class="text_box">
            <h1>ยินดีต้อนรับ!</h1>
            <p>ดูแลสุขภาพของคุณ เริ่มต้นที่นี่<br>
            เข้าสู่ระบบเพื่อรับคำแนะนำอาหารที่เหมาะกับคุณ</p>
        </div>
    </div>
    <!-- box_right -->
</body>
</html>