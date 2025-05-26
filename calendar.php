<?php
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username'])) {
    header("Location: loginform.php");
    exit();
}

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

// ดึงข้อมูลจากฐานข้อมูล
$username = $_SESSION['username']; // Get the username from session

// 🔥 JOIN users กับ health_profile โดยใช้ user_id
$sql = "SELECT u.email, p.daily_calorie
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
} else {
    $daily_calorie = "ไม่ระบุ";
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/calendar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>const loggedInUser = "<?php echo $username; ?>"; // ส่งค่าไปที่ JavaScript</script>
    <script>const dailyCalorieGoal = <?php echo $daily_calorie; ?>;</script>
</head>
<body>
    <div class="container">
        <button id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <!-- เเถบบาร์ด้านข้าง -->
        <div class="bar flex-column p-0" style="width: 310px; height: 1150px;">
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
                    <a href="calendar.php" class="nav-link active custom-nav">
                        <i class="fa-regular fa-calendar"></i>&nbsp;&nbsp;&nbsp;&nbsp;ปฏิทินการรับประทานอาหาร
                    </a>
                </li>
                <li class="mb-3">
                    <a href="profile.php" class="nav-link link-body-emphasis custom-nav">
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
            <div class="text-center py-4">
                <h1>ปฏิทินการรับประทานอาหาร</h1>
                <p>ติดตามการรับประทานอาหารของคุณในแต่ละวัน</p>
            </div>
            <div class="status">
                <div class="over-cal"></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>มากเกินไป</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="perfect-cal"></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>เกณฑ์ที่ดี</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="under-cal"></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>น้อยเกินไป</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <div class="calendar-header">
                <button id="prevMonthBtn"><i class="fa-solid fa-caret-left fa-beat"></i></button>
                <h2 id="currentMonth"></h2>
                <button id="nextMonthBtn"><i class="fa-solid fa-caret-right fa-beat"></i></button>
            </div>
            <div class="box_calen">
                <div id="calendar" class="calendar"></div>
            </div>
        </div>
        <!-- content -->
    </div>
    <!-- container -->

<script>
    const calendar = document.getElementById('calendar');
    const currentMonthElement = document.getElementById('currentMonth');
    const prevMonthBtn = document.getElementById('prevMonthBtn');
    const nextMonthBtn = document.getElementById('nextMonthBtn');

    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    function renderCalendar(month, year) {
        calendar.innerHTML = ''; // เคลียร์ปฏิทินก่อนหน้า

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const firstDayOfWeek = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.

        currentMonthElement.textContent = `${getMonthName(month)} ${year}`;

        // Create day name headers
        const dayNames = ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'];
        const dayNamesRow = document.createElement('div');
        dayNamesRow.classList.add('calendar-row', 'day-names');
        dayNames.forEach(dayName => {
            const dayNameElement = document.createElement('div');
            dayNameElement.classList.add('calendar-cell', 'day-name');
            dayNameElement.textContent = dayName;
            dayNamesRow.appendChild(dayNameElement);
        });
        calendar.appendChild(dayNamesRow);

        // สร้างตารางปฏิทิน
        let dayCounter = 1;
        for (let i = 0; i < 6; i++) { // Up to 6 weeks
            const calendarRow = document.createElement('div');
            calendarRow.classList.add('calendar-row');

            for (let j = 0; j < 7; j++) {
                const calendarCell = document.createElement('div');
                calendarCell.classList.add('calendar-cell');

                if (i === 0 && j < firstDayOfWeek) {
                    // Empty cells before the first day
                    calendarCell.textContent = '';
                } else if (dayCounter <= daysInMonth) {
                    calendarCell.textContent = dayCounter;
                    const formattedDate = `${year}-${(month + 1).toString().padStart(2, '0')}-${dayCounter.toString().padStart(2, '0')}`;
                    calendarCell.setAttribute('data-date', formattedDate);
                    dayCounter++;
                } else {
                    // Empty cells after the last day
                    calendarCell.textContent = '';
                }

                calendarRow.appendChild(calendarCell);
            }

            calendar.appendChild(calendarRow);
            if (dayCounter > daysInMonth) break;
        }
        getMealStatusForMonth(month, year);
    }

    function getMonthName(month) {
        const monthNames = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
            "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
        ];
        return monthNames[month];
    }

    prevMonthBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
    });

    nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    });

    // ฟังก์ชันเพื่อดึงสถานะการรับประทานอาหารจากเซิร์ฟเวอร์
    async function getMealStatusForMonth(month, year) {
        const username = loggedInUser;
        const dailyGoal = dailyCalorieGoal;
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const startDate = firstDayOfMonth.toISOString().split('T')[0];
        const endDate = lastDayOfMonth.toISOString().split('T')[0];

        try {
            const response = await fetch('http://localhost:5000/get_meal_status_for_month', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username: username, startDate: startDate, endDate: endDate })
            });

            if (response.ok) {
                const data = await response.json();
                console.log(data);
                const mealStatus = data.mealStatus;

                const calendarCells = document.querySelectorAll('.calendar-cell[data-date]');
                calendarCells.forEach(cell => {
                    const cellDate = cell.getAttribute('data-date');
                    const mealData = mealStatus[cellDate];

                    if (mealData) {
                        const totalCalories = mealData.total_calories;
                        const status = mealData.status;

                        cell.classList.remove('over-calories', 'perfect-calories', 'under-calories'); // Clear previous classes

                        if (status === 'over') {
                            cell.classList.add('over-calories');
                        } else if (status === 'perfect') {
                            cell.classList.add('perfect-calories');
                        } else if (status === 'under') {
                            cell.classList.add('under-calories');
                        }
                    } else {
                        cell.classList.remove('over-calories', 'perfect-calories', 'under-calories');
                    }
                });
            } else {
                console.error('Error fetching meal status:', response.statusText);
            }
        } catch (error) {
            console.error('Error fetching meal status:', error);
        }
    }

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

    renderCalendar(currentMonth, currentYear);
</script>
</body>
</html>
