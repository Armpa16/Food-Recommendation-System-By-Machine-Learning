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

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username'])) {
    header("Location: loginform.php");
    exit();
}

// ดึงข้อมูลจากฐานข้อมูล
$username = $_SESSION['username']; // Get the username from session

// 🔥 JOIN users กับ health_profile โดยใช้ user_id
$sql = "SELECT u.email, p.daily_calorie, p.age, p.gender, p.weight, p.height, p.activity_level, p.bmi, p.status_bmi, p.diseases
        FROM users u 
        JOIN profiles p ON u.users_id = p.users_id 
        WHERE u.username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['email'] = $row['email'];  // Set email in session
    $daily_calorie = $row['daily_calorie'];
    $age = $row['age'];
    $gender = $row['gender'];
    $weight = $row['weight'];
    $height = $row['height'];
    $activity_level = $row['activity_level'];
    $bmi = $row['bmi'];
    $status_bmi = $row['status_bmi'];
    $diseases = $row['diseases'];
} else {
    $daily_calorie = "ไม่ระบุ";
    $age = "ไม่ระบุ";
    $gender = "ไม่ระบุ";
    $weight = "ไม่ระบุ";
    $height = "ไม่ระบุ";
    $activity_level = "ไม่ระบุ";
    $bmi = "ไม่ระบุ";
    $status_bmi = "ไม่ระบุ";
    $diseases = "ไม่ระบุ";
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/profile.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
</head>
<body>
    <div class="container">
        <button id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <!-- เเถบบาร์ด้านข้าง -->
        <div class="bar flex-column p-0" style="width: 310px; height: auto;">
            <div class="text-center py-4">
                <i class="fa-solid fa-circle-user fs-1"></i>
                <div class="fs-4 mt-2" style="font-size: 25px; font-weight:bold;"><?php echo htmlspecialchars($_SESSION['username']);?></div>
            </div>
            <!-- เมนู -->
            <ul class="nav nav-pills flex-column px-0">
                <li class="nav-item mb-3">
                    <a href="index.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-solid fa-house"></i>&nbsp;&nbsp;&nbsp;&nbsp;หน้าหลัก
                    </a>
                </li>
                <li class="mb-3">
                    <a href="recommendfood.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-solid fa-bowl-food"></i>&nbsp;&nbsp;&nbsp;&nbsp;การแนะนำอาหาร
                    </a>
                </li>
                <li class="mb-3">
                    <a href="calendar.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-regular fa-calendar"></i>&nbsp;&nbsp;&nbsp;&nbsp;ปฏิทินการรับประทานอาหาร
                    </a>
                </li>
                <li class="mb-3">
                    <a href="profile.php" class="nav-link active custom-nav">
                        <i class="fa-regular fa-circle-user"></i>&nbsp;&nbsp;&nbsp;&nbsp;โปรไฟล์
                    </a>
                </li>
            </ul>
            <li class="mt-auto text-center ">
                <a href="loginform.php" class="nav-link link-body-emphasis custom-nav">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>&nbsp;&nbsp;ออกจากระบบ
                </a>
            </li>
        </div>


        <div class="content">
            <!-- เนื้อหา -->
            <div class="card">
                <div class="user-header">
                    <div class="user-pic">
                        <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                    </div>
                    <div class="user-info">
                        <h2><?php echo htmlspecialchars($_SESSION['username']);?></h2>
                        <p><?php echo htmlspecialchars($_SESSION['email']);?></p>
                    </div>
                    <button class="edit-btn">แก้ไข</button>
                </div>

                <form id="profileForm" method="POST" action="connectDB/update_profile.php">
                    <div class="form-group">
                        <label class="form-label">เพศ</label>
                        <select class="select-control" name="gender">
                            <option value="ชาย" <?php echo ($gender == 'ชาย') ? 'selected' : ''; ?>>ชาย</option>
                            <option value="หญิง" <?php echo ($gender == 'หญิง') ? 'selected' : ''; ?>>หญิง</option>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="age" class="form-label">อายุ</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>" placeholder="อายุ">
                                <span class="input-group-text">ปี</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="weight" class="form-label">น้ำหนัก</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($weight); ?>" placeholder="น้ำหนัก">
                                <span class="input-group-text">กก.</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="height" class="form-label">ส่วนสูง</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="height" name="height" value="<?php echo htmlspecialchars($height); ?>" placeholder="ส่วนสูง">
                                <span class="input-group-text">ซม.</span>
                            </div>
                        </div>
                    </div>


                    <div class="form-box2">
                        <label class="form-label">ระดับการทำกิจกรรม</label>
                        <select class="select-control" name="activity_level">
                            <option value="น้อย" <?php echo ($activity_level == 'น้อย') ? 'selected' : ''; ?>>น้อย</option>
                            <option value="ปานกลาง" <?php echo ($activity_level == 'ปานกลาง') ? 'selected' : ''; ?>>ปานกลาง</option>
                            <option value="มาก" <?php echo ($activity_level == 'มาก') ? 'selected' : ''; ?>>มาก</option>
                        </select>
                    </div>

                    <div class="form-group-diseases">
                        <div class="form-box">
                            <label class="form-label">โรคประจำตัว</label>
                            <!-- <div class="select-control" name="diseases"> -->
                            <?php 
                                // ตรวจสอบว่า $diseases ไม่ใช่ 'ไม่ระบุ' และไม่ว่าง
                                $diseasesArray = ($diseases !== "ไม่ระบุ" && !empty($diseases)) ? array_map('trim', explode(',', $diseases)) : [];

                            ?>
                            <input type="checkbox" name="diseases[]" value="ไม่มีโรค" 
                                <?php echo (in_array('ไม่มีโรค', $diseasesArray)) ? 'checked' : ''; ?>> ไม่มีโรค

                            <input type="checkbox" name="diseases[]" value="โรคเบาหวาน" 
                                <?php echo (in_array('โรคเบาหวาน', $diseasesArray)) ? 'checked' : ''; ?>> โรคเบาหวาน

                            <input type="checkbox" name="diseases[]" value="โรคความดันโลหิตสูง" 
                                <?php echo (in_array('โรคความดันโลหิตสูง', $diseasesArray)) ? 'checked' : ''; ?>> โรคความดันโลหิตสูง

                            <input type="checkbox" name="diseases[]" value="โรคหัวใจ" 
                                <?php echo (in_array('โรคหัวใจ', $diseasesArray)) ? 'checked' : ''; ?>> โรคหัวใจ
                        </div>
                    </div>  

                    <button type="submit" class="submit-btn">บันทึกข้อมูล</button>
                </form>

            </div>
        </div>
        <!-- content -->
    </div>
    <!-- container -->
     
    <script>
        document.getElementById('profileForm').addEventListener('submit', function(event) {
            event.preventDefault(); // ป้องกันการ submit ฟอร์มแบบปกติ

            const formData = new FormData(this);
            const submitButton = this.querySelector('.submit-btn');
            submitButton.disabled = true; // ปิดการใช้งานปุ่มขณะส่งข้อมูล
            submitButton.textContent = 'กำลังบันทึก...'; // เปลี่ยนข้อความปุ่ม

            fetch(this.action, {
                method: this.method,
                body: formData
            })
            .then(response => response.json()) // คาดหวัง JSON response จาก server
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: data.message || 'บันทึกข้อมูลสำเร็จ', // ใช้ข้อความจาก server หรือข้อความ default
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Optional: รีโหลดหน้าเพื่อแสดงข้อมูลที่อัปเดต
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: data.message || 'ไม่สามารถบันทึกข้อมูลได้', // ใช้ข้อความจาก server หรือข้อความ default
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            })
            .finally(() => {
                 submitButton.disabled = false; // เปิดใช้งานปุ่มอีกครั้ง
                 submitButton.textContent = 'บันทึกข้อมูล'; // คืนข้อความปุ่มเป็นเหมือนเดิม
            });
        });

        // --- Sidebar Toggle JavaScript ---
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.bar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    const icon = sidebarToggle.querySelector('i');
                    if (sidebar.classList.contains('open')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });

                // Close sidebar if clicking outside on mobile/tablet
                document.addEventListener('click', function(event) {
                    if (window.innerWidth <= 992 && sidebar.classList.contains('open')) {
                        const isClickInsideSidebar = sidebar.contains(event.target);
                        const isClickOnToggler = sidebarToggle.contains(event.target);

                        if (!isClickInsideSidebar && !isClickOnToggler) {
                            sidebar.classList.remove('open');
                            const icon = sidebarToggle.querySelector('i');
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }
                });
            }
        });
        // --- End Sidebar Toggle JavaScript ---

    </script>

</body>
</html>