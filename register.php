<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="box_left">
        <div class="text_box">
            <h1>เริ่มต้นสุขภาพที่ดีไปกับเรา</h1>
            <p>สมัครสมาชิกเพื่อรับเมนูอาหารเฉพาะสำหรับคุณ<br>
            เราพร้อมดูแลคุณในทุกมื้อ</p>
        </div>
    </div>
    <!-- box_left -->

    <div class="box_right">
        <form method="POST" action="connectDB/insertUser.php" > 
            <h1>สร้างบัญชีของคุณ</h1><br><br>
            <div class="name">
                <label>ชื่อผู้ใช้</label><br><br>
                <input type="text" name="username"  placeholder="กรุณากรอกชื่อผู้ใช้ของคุณ" required>
                <!-- <i class="fa-solid fa-lock"></i> -->
            </div><br>
            <div class="mail">
                <label>อีเมล</label><br><br>
                <input type="email" name="email"  placeholder="กรุณากรอกอีเมลของคุณ" required>
                <!-- <i class="fa-solid fa-lock"></i> -->
            </div><br>
            <div class="pass">
                <label>รหัสผ่าน</label><br><br>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" name="password" id="password" placeholder="กรุณากรอกรหัสผ่านของคุณ" required>
                    <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 15px; cursor: pointer; color:#C2C2C2;"></i>
                </div>
                <!-- <i class="fa-solid fa-lock"></i> -->
            </div><br>
            <div class="con_pass">
                <label>ยืนยันรหัสผ่าน</label><br><br>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" name="c_password" id="c_password" placeholder="กรุณากรอกรหัสผ่านของคุณอีกครั้ง" required>
                    <i class="fas fa-eye" id="toggleCPassword" style="position: absolute; right: 15px; cursor: pointer; color:#C2C2C2;"></i>
                </div>
                <!-- <i class="fa-solid fa-lock"></i> -->
            </div><br><br>
            <button type="submit">สร้างบัญชี</button><br><br>
            <div class="regis">
                <hr>&nbsp;&nbsp;&nbsp;&nbsp;มีบัญชีผู้ใช้แล้ว? ลงชื่อเข้าใช้&nbsp;&nbsp;&nbsp;&nbsp;<hr>
            </div><br><br>
            <a href="loginform.php">ลงชื่อเข้าใช้</a>
            <?php
                if(isset($_GET['status'])){
                    //ตรวจสอบพบ error 
                    echo "<script>";
                    $status=$_GET['status'];
                if($status==1){
                    echo "alert(\"ไม่พบผู้ใช้งาน\")";
                }else if($status==2) {
                    //echo "alert(\"รหัสผ่านไม่ถูกต้อง\")";
                    echo "alert(\"รหัสผ่านไม่ถูกต้อง\");";
                }
                    echo "</script>";
                }
            ?>
        </form>  
    </div>
    <!-- box_right-->

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleCPassword = document.querySelector('#toggleCPassword');
        const c_password = document.querySelector('#c_password');

        function addToggleListener(toggleElement, passwordElement) {
            if (toggleElement && passwordElement) {
                toggleElement.addEventListener('click', function (e) {
                    // toggle the type attribute
                    const type = passwordElement.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordElement.setAttribute('type', type);
                    // toggle the eye slash icon
                    if (type === 'password') {
                        this.classList.remove('fa-eye-slash');
                        this.classList.add('fa-eye');
                    } else {
                        this.classList.remove('fa-eye');
                        this.classList.add('fa-eye-slash');
                    }
                });
            }
        }

        addToggleListener(togglePassword, password);
        addToggleListener(toggleCPassword, c_password);

        // CSS for hover effect (can also be in register.css)
        document.querySelectorAll('.pass i, .con_pass i').forEach(icon => {
            icon.addEventListener('mouseover', () => icon.style.color = '#000000');
            icon.addEventListener('mouseout', () => icon.style.color = '#C2C2C2');
        });
    </script>
</body>
</html>