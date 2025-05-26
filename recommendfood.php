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
    <title>RecommendFood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/recommendfood.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>const loggedInUser = "<?php echo $username; ?>"; // ส่งค่าไปที่ JavaScript</script>
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
                    <a href="recommendfood.php" class="nav-link active custom-nav">
                        <i class="fa-solid fa-bowl-food"></i>&nbsp;&nbsp;&nbsp;&nbsp;การแนะนำอาหาร
                    </a>
                </li>
                <li class="mb-3">
                    <a href="calendar.php" class="nav-link link-body-emphasis custom-nav">
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
            <div class="boxtop">
                <h1 id="daily_calories">อาหารที่แนะนำสำหรับคุณในวันนี้ <span id="total-calories">0</span> กิโลแคลอรี</h1>
                <button class="get-recommendations-btn">เเนะนำอาหาร</button>
            </div>
            <br>
            <div class="calendar-container">
                <div class="control-button" id="prevBtn"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="calendar-slider" id="calendarSlider">
                    <!-- Calendar week will be generated here -->
                </div>
                <div class="control-button" id="nextBtn"><i class="fa-solid fa-chevron-right"></i></div>
            </div>
            <!-- calendar-container -->
            <br>
    
            <div id="recommendedFoods" class="meals-container">
                <!-- อาหารจะแสดงที่นี่ -->
            </div>

            <!-- Submit Button -->
            <button class="submit-btn">บันทึก</button>
            <button class="edit-btn" style="display:none;">แก้ไข</button>
                
            
             

        </div>
        <!-- content -->
    </div>
    <!-- container -->






<script>
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
    
// ========================================================================================================================
        // ปุ่มเลื่อนปฏิทิน
        document.addEventListener('DOMContentLoaded', function() {
        const calendarSlider = document.getElementById('calendarSlider');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        let currentDate = new Date();
        let currentWeekStart = new Date(currentDate);
        currentWeekStart.setDate(currentDate.getDate() - currentDate.getDay()); // Start from Sunday
        // ฟังก์ชันสำหรับการสร้างปฏิทิน
        function renderWeek(startDate) {
            const calendar = document.createElement('div');
            calendar.classList.add('calendar');

            const days = document.createElement('div');
            days.classList.add('days');
            ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'].forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.classList.add('day');
                dayElement.textContent = day;
                days.appendChild(dayElement);
            });
            calendar.appendChild(days);

            const dates = document.createElement('div');
            dates.classList.add('dates');

            for (let i = 0; i < 7; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                const dateElement = document.createElement('div');
                dateElement.classList.add('date');
                dateElement.textContent = date.getDate();
                dateElement.setAttribute('data-date', date.toISOString().split('T')[0]); // Add data-date attribute

                if (date.toDateString() === currentDate.toDateString()) {
                    dateElement.classList.add('today');
                }

                dateElement.addEventListener('click', function() {
                    document.querySelectorAll('.date').forEach(el => el.classList.remove('selected-day', 'today'));
                    dateElement.classList.add('selected-day');
                    
                    // อัปเดตเป็นวันที่เลือก
                    currentDate = new Date(this.getAttribute('data-date'));
                    
                    loadFoodDataForSelectedDate(currentDate);
                });
                
                dates.appendChild(dateElement);
            }

            calendar.appendChild(dates);
            calendarSlider.innerHTML = '';
            calendarSlider.appendChild(calendar);
            
            // ไฮไลต์สีวันที่เลือก
            highlightSelectedDate();
        }

        prevBtn.addEventListener('click', () => {
            currentWeekStart.setDate(currentWeekStart.getDate() - 7);
            // อัปเดตสัปดาห์ใหม่ ปุ่มก่อนหน้า
            currentDate = new Date(currentWeekStart);
            renderWeek(currentWeekStart);
            loadFoodDataForSelectedDate(currentDate);
        });

        nextBtn.addEventListener('click', () => {
            currentWeekStart.setDate(currentWeekStart.getDate() + 7);
            // อัปเดตสัปดาห์ใหม่ ปุ่มถัดไป
            currentDate = new Date(currentWeekStart);
            renderWeek(currentWeekStart);
            loadFoodDataForSelectedDate(currentDate);
        });
        
        function highlightSelectedDate() {
            const selectedDate = currentDate.toISOString().split('T')[0];
            const dateElements = document.querySelectorAll('.date');
            dateElements.forEach(dateElement => {
                if (dateElement.getAttribute('data-date') === selectedDate) {
                    dateElement.classList.add('selected-day');
                }
            });
        }

        renderWeek(currentWeekStart);
        highlightSelectedDate();
    });

// ========================================================================================================================
    // ฟังก์ชันจัดการคลิกบนการ์ดอาหาร (Event Delegation)
    function handleFoodCardClick(event) {
        const foodCard = event.target.closest('.food-card'); // หา element .food-card ที่ใกล้ที่สุด

        // ตรวจสอบว่าไม่ได้คลิกที่ปุ่มลบ หรือปุ่มเพิ่มใน popup
        const isDeleteButton = event.target.closest('.delete-food-btn');
        const isAddButtonInPopup = event.target.closest('.food-selection-popup .add-food');

        if (foodCard && !isDeleteButton && !isAddButtonInPopup) {
            const foodId = foodCard.dataset.foodId; // ดึง food_id จาก data attribute
            if (foodId && !foodId.startsWith('temp-')) { // ตรวจสอบว่ามี foodId และไม่ใช่ temporary id
                console.log("Clicked Food ID:", foodId);
                fetchAndShowFoodDetails(foodId);
            } else if (foodId.startsWith('temp-')) {
                 console.log("Clicked temporary food card, no details available.");
                 // อาจจะแสดงข้อความว่าข้อมูลยังไม่สมบูรณ์
            } else {
                console.log("Food ID not found on clicked card.");
            }
        }
    }

    // ========================================================================================================================
    // ฟังก์ชันดึงข้อมูลและแสดง Popup 
    async function fetchAndShowFoodDetails(foodId) {
        // แสดง loading indicator (ถ้าต้องการ)
        showLoadingPopup();

        try {
            const response = await fetch('http://localhost:5000/get_food_details', { 
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                // ส่ง food_id ไปใน body
                body: JSON.stringify({ food_id: foodId })
            });

            // ซ่อน loading indicator
            hideLoadingPopup();

            if (response.ok) {
                const data = await response.json();
                if (data.success && data.food_details) {
                    displayFoodDetailsPopup(data.food_details);
                } else {
                    console.error('Error fetching food details:', data.error || 'Unknown error');
                    alert('ไม่สามารถดึงข้อมูลอาหารได้: ' + (data.error || 'ข้อผิดพลาดไม่ทราบสาเหตุ'));
                }
            } else {
                console.error('Error fetching food details: Server responded with status', response.status);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์เพื่อดึงข้อมูลอาหาร');
            }
        } catch (error) {
            // ซ่อน loading indicator
            hideLoadingPopup();
            console.error('Error fetching food details:', error);
            alert('เกิดข้อผิดพลาดในการดึงข้อมูลอาหาร: ' + error.message);
        }
    }

    // ========================================================================================================================
    // ฟังก์ชันสร้างและแสดง Popup รายละเอียดอาหาร 
    function displayFoodDetailsPopup(foodDetails) {
        // สร้าง overlay
        const overlay = document.createElement('div');
        overlay.className = 'popup2-overlay food-details-overlay'; // เพิ่ม class เฉพาะ

        // สร้าง popup container
        const popup = document.createElement('div');
        popup.className = 'food-details-popup';

        // สร้างเนื้อหา Popup 
        popup.innerHTML = `
            <div class="popup-header">
                <h3>ข้อมูลโภชนาการ</h3>
                <button class="close-popup-btn"><i class="fa-solid fa-rectangle-xmark"></i></button>
            </div>
            <div class="popup-content">
                <img src="${foodDetails.image_url || 'img/default-food.png'}" alt="${foodDetails.food_name || 'รูปอาหาร'}" class="popup-food-image">
                <h4 class="popup-food-name">${foodDetails.food_name || 'ชื่ออาหาร'} <p>${foodDetails.amount || '1 หน่วยบริโภค'}</p></h4>
                <ul class="nutrition-list">
                    <li><i class="fa-solid fa-fire"></i> พลังงาน: <strong>${foodDetails.calories !== undefined ? foodDetails.calories.toFixed(0) : 'N/A'}</strong>&nbsp; กิโลแคลอรี</li>
                    <li><i class="fa-solid fa-drumstick-bite"></i> โปรตีน: <strong>${foodDetails.protein !== undefined ? foodDetails.protein.toFixed(1) : 'N/A'}</strong>&nbsp; กรัม</li>
                    <li><i class="fa-solid fa-bread-slice"></i> คาร์โบไฮเดรต: <strong>${foodDetails.carbohydrate !== undefined ? foodDetails.carbohydrate.toFixed(1) : 'N/A'}</strong>&nbsp; กรัม</li>
                    <li><i class="fa-solid fa-bacon"></i> ไขมัน: <strong>${foodDetails.fat !== undefined ? foodDetails.fat.toFixed(1) : 'N/A'}</strong>&nbsp; กรัม</li>
                    <li><i class="fa-solid fa-candy-cane"></i> น้ำตาล: <strong>${foodDetails.sugar !== undefined ? foodDetails.sugar.toFixed(1) : 'N/A'}</strong>&nbsp; กรัม</li>
                    <li><i class="fa-solid fa-mortar-pestle"></i> โซเดียม: <strong>${foodDetails.sodium !== undefined ? foodDetails.sodium.toFixed(0) : 'N/A'}</strong>&nbsp; มิลลิกรัม</li>
                    <!-- เพิ่มเติมตามข้อมูลที่มี เช่น วิตามิน, แร่ธาตุ -->
                    ${foodDetails.vitamins ? `<li><i class="fa-solid fa-pills"></i> วิตามิน: ${foodDetails.vitamins}</li>` : ''}
                    ${foodDetails.minerals ? `<li><i class="fa-solid fa-gem"></i> แร่ธาตุ: ${foodDetails.minerals}</li>` : ''}
                </ul>
                ${foodDetails.description ? `<p class="food-description">${foodDetails.description}</p>` : ''}
            </div>
        `;

        // เพิ่ม popup ไปยัง DOM
        overlay.appendChild(popup);
        document.body.appendChild(overlay);

        // เพิ่ม event listener สำหรับปุ่มปิด
        const closeButtons = overlay.querySelectorAll('.close-popup-btn, .close-popup-btn-footer');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                if (document.body.contains(overlay)) {
                    document.body.removeChild(overlay);
                }
            });
        });

        // ปิด popup เมื่อคลิกนอก popup
        overlay.addEventListener('click', function(event) {
            if (event.target === overlay) {
                 if (document.body.contains(overlay)) {
                    document.body.removeChild(overlay);
                }
            }
        });
    }

    // ========================================================================================================================
    // ฟังก์ชันแสดง/ซ่อน Loading (ตัวอย่างง่ายๆ) 
    function showLoadingPopup() {
        const existingLoading = document.querySelector('.loading-overlay');
        if (existingLoading) return; 

        const overlay = document.createElement('div');
        overlay.className = 'popup-overlay loading-overlay';
        overlay.innerHTML = '<div class="loading-spinner"><div></div><div></div><div></div><div></div></div>'; // Spinner CSS
        document.body.appendChild(overlay);
    }

    function hideLoadingPopup() {
        const overlay = document.querySelector('.loading-overlay');
        if (overlay && document.body.contains(overlay)) {
            document.body.removeChild(overlay);
        }
    }



// ========================================================================================================================
        // ฟังก์ชันสำหรับโหลดข้อมูลอาหารตามวันที่เลือก
        function loadFoodDataForSelectedDate(selectedDate) {
            const formattedDate = selectedDate.toISOString().split('T')[0]; // แปลงวันที่เป็นรูปแบบ YYYY-MM-DD
            const username = loggedInUser;

            // ตรวจสอบว่ามีข้อมูลบันทึกสำหรับวันนี้หรือไม่
            checkSavedMeals(username, formattedDate)
                .then(hasSavedMeals => {
                    if (hasSavedMeals) {
                        // ถ้ามีข้อมูลบันทึก ให้ดึงข้อมูลจากฐานข้อมูล
                        getSavedMeals(username, formattedDate);
                        hideRecommendationButton(); // ซ่อนปุ่มแนะนำ
                        showEditButton(); // แสดงปุ่มแก้ไข
                        hideAddFoodButton(); // ซ่อนปุ่มเพิ่มอาหาร
                        hideDeleteFoodButton(); // ซ่อนปุ่มลบอาหาร
                    } else {
                        // ถ้าไม่มีข้อมูลบันทึก ให้แสดงเทมเพลตเปล่า
                        displayMealSections();
                        showRecommendationButton(); 
                        hideEditButton(); 
                        showSubmitButton(); 
                        showAddFoodButton(); 
                    }
                })
                .catch(error => {
                    console.error('Error checking saved meals:', error);
                    // กรณีเกิดข้อผิดพลาด ให้แสดงเทมเพลตเปล่า
                    displayMealSections();
                    showRecommendationButton(); // แสดงปุ่มแนะนำ
                    hideEditButton(); // ซ่อนปุ่มแก้ไข
                    showSubmitButton(); // เสดงปุ่มบันทึก
                    showAddFoodButton(); // เเสดงปุ่มเพิ่มอาหาร
                });
        }

// ========================================================================================================================       
        // ฟังก์ชันสำหรับแสดงปุ่มเพิ่มอาหารสำหรับแต่ละมื้อ
        function displayMealSections() {
            const recommendedFoodsDiv = document.getElementById('recommendedFoods');
            recommendedFoodsDiv.innerHTML = ''; // เคลียร์เนื้อหาเดิม

            const mealNames = ['breakfast', 'lunch', 'dinner'];
            const mealTranslation = {
                'breakfast': 'อาหารเช้า',
                'lunch': 'อาหารกลางวัน',
                'dinner': 'อาหารเย็น'
            };

            mealNames.forEach(mealName => {
                const mealSection = document.createElement('div');
                mealSection.classList.add('meal-section');
                mealSection.setAttribute('data-meal-type', mealName);

                const mealHeader = document.createElement('div');
                mealHeader.classList.add('meal-header');
                mealHeader.innerHTML = `
                    <h3>${mealTranslation[mealName]}</h3>
                    <span class="meal-calories">0 แคลอรี</span>
                    <button class="add-food-btn" data-meal-type="${mealName}"><i class="fa-solid fa-plus"></i></button>
                `;

                const mealBody = document.createElement('div');
                mealBody.classList.add('meal-body', 'empty-meal');
                mealBody.textContent = 'ไม่มีข้อมูลรายการอาหาร';

                mealSection.appendChild(mealHeader);
                mealSection.appendChild(mealBody);
                recommendedFoodsDiv.appendChild(mealSection);
            });

            // เพิ่ม Event Listener สำหรับปุ่มเพิ่มอาหาร
            addFoodButtonListeners();
            // รีเซ็ตแคลอรี่รวมทั้งหมด
            resetTotalCalories();
        }
        // ฟังก์ชันสำหรับรีเซ็ตแคลอรี่รวมทั้งหมด
        function resetTotalCalories() {
            const totalCaloriesElement = document.getElementById('total-calories');
            if (totalCaloriesElement) {
                totalCaloriesElement.textContent = '0';
            }
        }

// ========================================================================================================================
        // ฟังก์ชันสำหรับตรวจสอบว่ามีข้อมูลบันทึกสำหรับวันนี้หรือไม่
        async function checkSavedMeals(username, date) {
            try {
                const response = await fetch('http://localhost:5000/check_saved_meals', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: username, date: date })
                });

                if (response.ok) {
                    const data = await response.json();
                    return data.has_meals; // คืนค่าว่ามีข้อมูลบันทึกหรือไม่
                } else {
                    throw new Error('Error checking saved meals');
                }
            } catch (error) {
                console.error('Error checking saved meals:', error);
                return false; // ถ้าเกิดข้อผิดพลาด ให้ถือว่าไม่มีข้อมูลบันทึก
            }
        }

// ========================================================================================================================
        // ฟังก์ชันสำหรับดึงข้อมูลอาหารที่บันทึกไว้
        async function getSavedMeals(username, date) {
            try {
                const response = await fetch('http://localhost:5000/get_saved_meals', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: username, date: date })
                });

                if (response.ok) {
                    const data = await response.json();
                    displaySavedMeals(data);
                } else {
                    throw new Error('Error fetching saved meals');
                }
            } catch (error) {
                console.error('Error fetching saved meals:', error);
                document.getElementById('recommendedFoods').innerHTML = `<div class="error-message"><p>เกิดข้อผิดพลาด: ${error.message}</p></div>`;
            }
        }

// ========================================================================================================================
        // ฟังก์ชันสำหรับแสดงข้อมูลอาหารที่บันทึกไว้
        function displaySavedMeals(data) {
            console.log('Saved meals data:', data);
            let totalDailyCalories = 0;
            let mealsHTML = '';
            const meals = data.meals;

            const orderedMealNames = ['breakfast', 'lunch', 'dinner'];
            const mealTranslation = {
                'breakfast': 'อาหารเช้า',
                'lunch': 'อาหารกลางวัน',
                'dinner': 'อาหารเย็น'
            };

            orderedMealNames.forEach(mealName => {
                if (meals.hasOwnProperty(mealName)) {
                    const meal = meals[mealName];
                    const translatedMealName = mealTranslation[mealName];

                    if (meal && meal.length > 0) {
                        let totalMealCalories = 0;
                        let mealItemsHTML = '';

                        meal.forEach(food => {
                            mealItemsHTML += `
                                <div class="food-card" data-food-id="${food.food_id || 'temp-' + Date.now()}" data-calories="${food.calories}">
                                    <button class="delete-food-btn" style="display:none;"><i class="fa-solid fa-trash"></i></button>
                                    <img src="${food.image_url || 'default-image.jpg'}" class="food-image" alt="${food.food_name}">
                                    <div class="food-info">
                                        <h4 class="food-name">${food.food_name}</h4>
                                        <p class="food-calories">${food.calories} แคลอรี</p>
                                        <p class="food-nutrients">
                                            🔥 ${food.calories} | 🥩 ${food.protein}g | 🍞 ${food.carbohydrate}g 
                                        </p>
                                        <div class="food-actions">
                                            <span class="food-name">${food.food_name}</span>
                                            <span class="food-amount">${food.amount || '1 จาน'}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                            totalMealCalories += parseFloat(food.calories);
                        });

                        totalDailyCalories += totalMealCalories;

                        mealsHTML += `
                            <div class="meal-section" data-meal-type="${mealName}">
                                <div class="meal-header">
                                    <h3>${translatedMealName}</h3>
                                    <span class="meal-calories">${totalMealCalories.toFixed(0)} แคลอรี</span>
                                    <button class="add-food-btn" data-meal-type="${mealName}" style="display:none;"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="meal-body">${mealItemsHTML}</div>
                            </div>
                        `;
                    } else {
                        mealsHTML += `
                            <div class="meal-section" data-meal-type="${mealName}">
                                <div class="meal-header">
                                    <h3>${translatedMealName}</h3>
                                    <span class="meal-calories">0 แคลอรี</span>
                                    <button class="add-food-btn" data-meal-type="${mealName}" style="display:none;"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                <div class="meal-body empty-meal">ไม่มีข้อมูลรายการอาหาร</div>
                            </div>
                        `;
                    }
                }
            });

            document.getElementById('total-calories').textContent = totalDailyCalories.toFixed(0);
            document.getElementById('recommendedFoods').innerHTML = mealsHTML;
            
            hideAddFoodButton(); // ซ่อนปุ่มเพิ่มอาหารเมื่อแสดงข้อมูลที่บันทึกไว้
            hideDeleteFoodButton(); // ซ่อนปุ่มลบอาหารเมื่อแสดงข้อมูลที่บันทึกไว้
            showEditButton(); // แสดงปุ่มแก้ไขเมื่อแสดงข้อมูลที่บันทึกไว้
            addDeleteFoodListeners(); // เรียกใช้เพิ่มปุ่มลบอาหาร
            addFoodButtonListeners(); // เรียกใช้ปุ่มเพิ่มอาหาร
        }
        // ฟังก์ชันสำหรับซ่อนปุ่มบันทึก
        function hideSubmitButton() {
            const submitButton = document.querySelector('.submit-btn');
            if (submitButton) {
                submitButton.style.display = 'none';
            }
        }
        // ฟังก์ชันสำหรับแสดงปุ่มแก้ไข
        function showEditButton() {
            const editButton = document.querySelector('.edit-btn');
            if (editButton) {
                editButton.style.display = 'block';
            }
            hideSubmitButton();
        }
        // ฟังก์ชันสำหรับซ่อนปุ่มแก้ไข
        function hideEditButton() {
            const editButton = document.querySelector('.edit-btn');
            if (editButton) {
                editButton.style.display = 'none';
            }
        }
        // ฟังก์ชันสำหรับแสดงปุ่มบันทึก
        function showSubmitButton() {
            const submitButton = document.querySelector('.submit-btn');
            if (submitButton) {
                submitButton.style.display = 'block';
            }
            showAddFoodButton(); // เเสดงปุ่มเพิ่มอาหาร
            showDeleteFoodButton(); // เเสดงปุ่มลบอาหาร
            hideEditButton(); // ซ่อนปุ่มแก้ไข
        }
        document.addEventListener("DOMContentLoaded", function () {
            // เรียกใช้ฟังก์ชันเเนะนำอาหาร
            document.querySelector(".get-recommendations-btn").addEventListener("click", function () {
                getFoodRecommendations(loggedInUser);
            });
            document.querySelector(".edit-btn").addEventListener("click", function () {
                showSubmitButton();
            });
        });

        // ฟังก์ชันสำหรับแสดงปุ่มเพิ่มอาหาร
        function showAddFoodButton() {
            const addFoodButtons = document.querySelectorAll('.add-food-btn');
            addFoodButtons.forEach(button => {
                button.style.display = 'block';
            });
        }

        // ฟังก์ชันสำหรับซ่อนปุ่มเพิ่มอาหาร
        function hideAddFoodButton() {
            const addFoodButtons = document.querySelectorAll('.add-food-btn');
            addFoodButtons.forEach(button => {
                button.style.display = 'none';
            });
        }
        // ฟังก์ชันสำหรับแสดงปุ่มลบอาหาร
        function showDeleteFoodButton() {
            const deleteFoodButtons = document.querySelectorAll('.delete-food-btn');
            deleteFoodButtons.forEach(button => {
                button.style.display = 'block';
            });
        }

        // ฟังก์ชันสำหรับซ่อนปุ่มลบอาหาร
        function hideDeleteFoodButton() {
            const deleteFoodButtons = document.querySelectorAll('.delete-food-btn');
            deleteFoodButtons.forEach(button => {
                button.style.display = 'none';
            });
        }

        // ฟังก์ชันสำหรับเพิ่ม Event Listener ปุ่มเพิ่มอาหาร
        const recommendedFoodsContainer = document.getElementById('recommendedFoods');
        if (recommendedFoodsContainer) {
            recommendedFoodsContainer.addEventListener('click', handleFoodCardClick);
        }

        
        // โหลดข้อมูลเมื่อหน้าเว็บเปิด
        window.onload = function () {
            if (loggedInUser) {
                // โหลดข้อมูลอาหารสำหรับวันที่ปัจจุบัน
                loadFoodDataForSelectedDate(new Date());
            } else {
                console.error('ไม่พบข้อมูลผู้ใช้ที่ล็อกอิน');
            }
        };


// ========================================================================================================================
         // ดึงข้อมูลแนะนำอาหารจาก API
         async function getFoodRecommendations(username) {
            try {
                const response = await fetch('http://localhost:5000/get_recommendations', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: username })
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('Data received:', data); // Debugging log

                    let totalDailyCalories = 0;
                    const meals = data.meals;
                    let mealsHTML = '';

                    // กำหนดลำดับของมื้ออาหาร
                    const orderedMealNames = ['breakfast', 'lunch', 'dinner'];
                    const mealTranslation = {
                        'breakfast': 'อาหารเช้า',
                        'lunch': 'อาหารกลางวัน',
                        'dinner': 'อาหารเย็น'
                    };

                    orderedMealNames.forEach(mealName => {
                        if (meals.hasOwnProperty(mealName)) { // เช็คว่ามื้ออาหารนี้มีอยู่ใน API
                            const meal = meals[mealName];
                            const translatedMealName = mealTranslation[mealName];

                            if (meal && Object.keys(meal).length > 0) {
                                let totalMealCalories = 0;
                                let mealItemsHTML = '';

                                Object.values(meal).forEach(food => {
                                    mealItemsHTML += `
                                        <div class="food-card" data-food-id="${food.food_id || 'temp-' + Date.now()}" data-calories="${food.calories}">
                                            <button class="delete-food-btn"><i class="fa-solid fa-trash"></i></button>
                                            <img src="${food.image_url || 'default-image.jpg'}" class="food-image" alt="${food.food_name}">
                                            <div class="food-info">
                                                <h4 class="food-name">${food.food_name}</h4>
                                                <p class="food-calories">${food.calories} แคลอรี</p>
                                                <p class="food-nutrients">
                                                    🔥 ${food.calories} | 🥩 ${food.protein}g | 🍞 ${food.carbohydrate}g
                                                </p>
                                                <div class="food-actions">
                                                    <span class="food-name">${food.food_name}</span>
                                                    <span class="food-amount">${food.amount || '1 จาน'}</span>
                                                </div>
                                            </div>
                                        </div>
                                    `;


                                    totalMealCalories += parseFloat(food.calories);
                                });

                                totalDailyCalories += totalMealCalories;

                                mealsHTML += `
                                    <div class="meal-section" data-meal-type="${mealName}">
                                        <div class="meal-header">
                                            <h3>${translatedMealName}</h3>
                                            <span class="meal-calories">${totalMealCalories.toFixed(0)} แคลอรี</span>
                                            <button class="add-food-btn" data-meal-type="${mealName}"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                        <div class="meal-body">${mealItemsHTML}</div>
                                    </div>
                                `;
                            } else {
                                mealsHTML += `
                                    <div class="meal-section" data-meal-type="${mealName}">
                                        <div class="meal-header">
                                            <h3>${translatedMealName}</h3>
                                            <span class="meal-calories">0 แคลอรี</span>
                                            <button class="add-food-btn" data-meal-type="${mealName}"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                        <div class="meal-body empty-meal">ไม่มีข้อมูลรายการอาหาร</div>
                                    </div>
                                `;
                            }
                        }
                    });

                    document.getElementById('total-calories').textContent = totalDailyCalories.toFixed(0);
                    document.getElementById('recommendedFoods').innerHTML = mealsHTML;

                    // เพิ่ม Event Listener สำหรับปุ่มเพิ่มอาหาร
                    addFoodButtonListeners();
                    // เพิ่ม Event Listener สำหรับปุ่มลบอาหาร
                    addDeleteFoodListeners();
                    showRecommendationButton();

                } else {
                    document.getElementById('recommendedFoods').innerHTML = `<div class="error-message"><p>ไม่สามารถดึงข้อมูลแนะนำอาหารได้</p></div>`;
                }
            } catch (error) {
                console.error('Error fetching recommendations:', error);
                document.getElementById('recommendedFoods').innerHTML = `<div class="error-message"><p>เกิดข้อผิดพลาด: ${error.message}</p></div>`;
            }
        }
        // ฟังก์ชันสำหรับซ่อนปุ่มแนะนำ
        function hideRecommendationButton() {
            const recommendationButton = document.querySelector('.get-recommendations-btn');
            if (recommendationButton) {
                recommendationButton.style.display = 'none';
            }
        }

        // ฟังก์ชันสำหรับแสดงปุ่มแนะนำ
        function showRecommendationButton() {
            const recommendationButton = document.querySelector('.get-recommendations-btn');
            if (recommendationButton) {
                recommendationButton.style.display = 'block';
            }
        }
        // เพิ่มฟังก์ชันสำหรับปุ่มลบอาหาร
        function addDeleteFoodListeners() {
            const deleteButtons = document.querySelectorAll('.delete-food-btn');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const foodCard = this.closest('.food-card');
                    const mealBody = foodCard.parentElement;
                    const mealSection = mealBody.closest('.meal-section');
                    const mealType = mealSection.getAttribute('data-meal-type');
                    
                    // ดึงค่าแคลอรี่ของอาหารที่จะลบ
                    const foodCalories = parseFloat(foodCard.getAttribute('data-calories')) || 0;
                    
                    // ลบรายการอาหารออกจาก UI
                    mealBody.removeChild(foodCard);
                    
                    // อัพเดทแคลอรี่ของมื้อนี้
                    updateMealCalories(mealSection, -foodCalories);
                    
                    // อัพเดทแคลอรี่ทั้งหมด
                    updateTotalCalories();
                    
                    // ตรวจสอบว่ามีรายการอาหารเหลืออยู่หรือไม่
                    if (mealBody.children.length === 0) {
                        mealBody.innerHTML = 'ไม่มีข้อมูลรายการอาหาร';
                        mealBody.classList.add('empty-meal');
                        
                        // รีเซ็ตจำนวนแคลอรี่ของมื้อนี้เป็น 0
                        const mealCalories = mealSection.querySelector('.meal-calories');
                        if (mealCalories) {
                            mealCalories.textContent = '0 แคลอรี';
                        }
                    }
                    
                    // บันทึกการเปลี่ยนแปลงไปยังเซิร์ฟเวอร์ (ถ้าต้องการ)
                    saveMealToServer(mealType, Array.from(mealBody.querySelectorAll('.food-card')).map(card => {
                        return {
                            food_id: card.getAttribute('data-food-id'),
                            food_name: card.querySelector('.food-name').textContent,
                            calories: parseFloat(card.getAttribute('data-calories')),
                            protein: card.querySelector('.food-nutrients').textContent.match(/🥩\s*(\d+(\.\d+)?)/)[1],
                            carbohydrate: card.querySelector('.food-nutrients').textContent.match(/🍞\s*(\d+(\.\d+)?)/)[1],
                            amount: card.querySelector('.food-amount').textContent,
                            image_url: card.querySelector('.food-image').src
                        };
                    }));
                });
            });
        }

        // ฟังก์ชันสำหรับอัพเดทแคลอรี่ของมื้ออาหาร
        function updateMealCalories(mealSection, caloriesChange) {
            const mealCaloriesElement = mealSection.querySelector('.meal-calories');
            if (mealCaloriesElement) {
                const currentCalories = parseFloat(mealCaloriesElement.textContent) || 0;
                const newCalories = Math.max(0, currentCalories + caloriesChange);
                mealCaloriesElement.textContent = `${newCalories.toFixed(0)} แคลอรี`;
            }
        }

        // ฟังก์ชันอัพเดทจำนวนแคลอรี่ทั้งหมด
        function updateTotalCalories() {
            let totalDailyCalories = 0;
            const mealCaloriesElements = document.querySelectorAll('.meal-calories');
            
            // วนลูปผ่านแต่ละมื้ออาหารเพื่อรวมแคลอรี่
            mealCaloriesElements.forEach(element => {
                const caloriesText = element.textContent;
                const calories = parseFloat(caloriesText) || 0;
                totalDailyCalories += calories;
            });
            
            // อัพเดทแคลอรี่ทั้งหมดใน UI
            const totalCaloriesElement = document.getElementById('total-calories');
            if (totalCaloriesElement) {
                totalCaloriesElement.textContent = totalDailyCalories.toFixed(0);
            }
        }

// ========================================================================================================================
        // ฟังก์ชันเพิ่ม Event Listener สำหรับปุ่มเพิ่มอาหาร
        function addFoodButtonListeners() {
            const addFoodButtons = document.querySelectorAll('.add-food-btn');
            
            addFoodButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const mealType = this.getAttribute('data-meal-type');
                    showFoodSelectionPopup(mealType);
                });
            });
        }

        // ฟังก์ชันเเสดงป๊อปอัพสำหรับเลือกอาหาร
        async function showFoodSelectionPopup(mealType) {
            try {
                // ดึงชื่อผู้ใช้จาก localStorage
                localStorage.setItem('loggedInUsername', loggedInUser);
                const username = localStorage.getItem('loggedInUsername');
                
                if (!username) {
                    alert('กรุณาล็อกอินเพื่อเลือกอาหาร');
                    return;
                }

                // สร้าง overlay และ popup container
                const overlay = document.createElement('div');
                overlay.className = 'popup-overlay';

                const popup = document.createElement('div');
                popup.className = 'food-selection-popup';

                // สร้างส่วนหัวของป๊อปอัพ
                const mealTranslation = {
                    'breakfast': 'อาหารเช้า',
                    'lunch': 'อาหารกลางวัน',
                    'dinner': 'อาหารเย็น'
                };

                popup.innerHTML = `
                    <div class="popup-header">
                        <h3>เพิ่มรายการ ${mealTranslation[mealType]}</h3>
                        <button class="close-popup-btn"><i class="fa-solid fa-rectangle-xmark"></i></button>
                    </div>
                    <div class="popup-search">
                        <input type="text" id="food-search" placeholder="ค้นหาอาหาร...">
                    </div>
                    <div class="popup-content">
                        <div class="food-loading">กำลังโหลดรายการอาหาร...</div>
                    </div>
                    <div class="popup-footer">
                        <select class="options-food">
                            <option value="suitable">อาหารที่เหมาะสม</option>
                            <option value="unsuitable">อาหารที่ไม่เหมาะสม</option>    
                        </select>
                        <group class="btn-group">
                            <button class="submit-food-btn">เพิ่ม <span class="selected-count">(0)</span></button>
                            <button class="cancel-food-btn">ยกเลิก</button>
                        </group>
                    </div>
                `;

                // เพิ่ม popup ไปยัง DOM
                overlay.appendChild(popup);
                document.body.appendChild(overlay);

                // เพิ่ม event listeners สำหรับปุ่มปิดและยกเลิก
                const closeBtn = popup.querySelector('.close-popup-btn');
                const cancelBtn = popup.querySelector('.cancel-food-btn');
                const searchInput = popup.querySelector('#food-search');
                const submitBtn = popup.querySelector('.submit-food-btn');
                const foodOptionsSelect = popup.querySelector('.options-food');
                const popupContent = popup.querySelector('.popup-content');
                
                closeBtn.addEventListener('click', () => document.body.removeChild(overlay));
                cancelBtn.addEventListener('click', () => document.body.removeChild(overlay));

                // สร้างตัวแปรเก็บรายการอาหารที่เลือก
                const selectedFoods = [];

                // เพิ่ม event listener สำหรับปุ่มเพิ่ม (submit)
                submitBtn.addEventListener('click', () => {
                    if (selectedFoods.length > 0) {
                        // เพิ่มรายการอาหารที่เลือกไปยังหน้าหลัก
                        addSelectedFoodsToMeal(selectedFoods, mealType);
                    }
                    document.body.removeChild(overlay);
                });
                
                let currentFoodType = 'suitable'; // ค่าเริ่มต้น
                let allFetchedFoods = []; // เก็บรายการทั้งหมดสำหรับประเภทปัจจุบันเพื่อการกรอง

                // ฟังก์ชันโหลดรายการอาหารสำหรับผู้ใช้เลือกเอง
                async function loadAndRenderFoods(type) {
                    currentFoodType = type;
                    popupContent.innerHTML = '<div class="food-loading">กำลังโหลดรายการอาหาร...</div>'; // เสดงข้อความโหลดข้อมูล
                    allFetchedFoods = []; // ล้างรายการก่อนหน้า

                    let apiUrl = 'http://localhost:5000/get_food_list'; // อาหารที่เหมาะสม
                    if (type === 'unsuitable') {
                        apiUrl = 'http://localhost:5000/get_unsuitable_food_list'; // อาหารที่ไม่เหมาะสม
                    }
                
                    try {
                        const response = await fetch(apiUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ username: username }) // ส่งชื่อผู้ใช้ไป
                        });

                        if (response.ok) {
                            const foodData = await response.json();
                            console.log(`Data for ${type} food:`, foodData);
                            if (foodData && Array.isArray(foodData.meals)) {
                                allFetchedFoods = foodData.meals;
                            } else {
                                console.error(`Invalid data structure for ${type} food:`, foodData);
                                allFetchedFoods = [];
                            }
                            renderMealSet({ meals: allFetchedFoods }, popupContent, mealType, selectedFoods, popup);
                        } else {
                            popupContent.innerHTML = `<div class="error-message">ไม่สามารถโหลดรายการอาหาร (${type}) ได้</div>`;
                            allFetchedFoods = [];
                        }
                    } catch (error) {
                        console.error(`Error fetching ${type} foods:`, error);
                        popupContent.innerHTML = `<div class="error-message">เกิดข้อผิดพลาดในการโหลดรายการอาหาร (${type})</div>`;
                        allFetchedFoods = [];
                    }
                }

                // โหลดประเภทอาหารเริ่มต้น
                loadAndRenderFoods(currentFoodType);

                // ตัวรับค่าของการคลิกเลือกอาหาร เหมาะ/ไม่เหมาะ
                foodOptionsSelect.addEventListener('change', (event) => {
                    selectedFoods.length = 0; // ล้างรายการอาหารที่เลือก
                    updateSelectedCount(0, popup); // อัพเดทจำนวนอาหารที่เลือก
                    searchInput.value = ''; // เคลียร์ช่องค้นหา
                    loadAndRenderFoods(event.target.value);
                });

                // ตัวรับอินพุตค้นหา
                searchInput.addEventListener('input', () => {
                    const searchTerm = searchInput.value.toLowerCase();
                    const filteredData = {
                        meals: allFetchedFoods.filter(meal =>
                            meal.food_name.toLowerCase().includes(searchTerm)
                        )
                    };
                    renderMealSet(filteredData, popupContent, mealType, selectedFoods, popup);
                });

            } catch (error) {
                console.error('Error showing food popup:', error);
                alert('เกิดข้อผิดพลาดในการแสดงรายการอาหาร');
            }
        }

// ========================================================================================================================
        function renderMealSet(foodData, container, mealType, selectedFoods, popup) {
            const mealTranslation = {
                'breakfast': 'อาหารเช้า',
                'lunch': 'อาหารกลางวัน',
                'dinner': 'อาหารเย็น'
            };

            container.innerHTML = ''; // เคลียร์เนื้อหาเก่า

            // ตรวจสอบว่า meals มีข้อมูลหรือไม่
            const meals = foodData.meals;
            if (!Array.isArray(meals) || meals.length === 0) {
                const noFoodMessage = document.createElement('div');
                noFoodMessage.classList.add('no-foods');
                noFoodMessage.innerText = 'ไม่พบรายการอาหารที่เหมาะสม';
                container.appendChild(noFoodMessage);
                return;
            }

            const categoryContainer = document.createElement('div');
            categoryContainer.classList.add('meal-category');

            let mealItemsHTML = '';  // เก็บเนื้อหาของการ์ดอาหาร

            meals.forEach(meal => {
                // ตรวจสอบว่าอาหารนี้ถูกเลือกหรือไม่
                const isSelected = selectedFoods.some(food => food.food_id === meal.food_id);
                const addBtnClass = isSelected ? 'add-food selected' : 'add-food';
                const addBtnIcon = isSelected ? 'fa-check' : 'fa-plus';

                mealItemsHTML += `
                    <div class="food-card" data-food-id="${meal.food_id}">
                        <button class="${addBtnClass}" data-food-id="${meal.food_id}"><i class="fa-solid ${addBtnIcon}"></i></button>
                        <img src="${meal.image_url || 'default-image.jpg'}" class="food-image" alt="${meal.food_name}">
                        <div class="food-info">
                            <h4 class="food-name">${meal.food_name}</h4>
                            <p class="food-calories">${meal.calories} แคลอรี</p>
                            <p class="food-nutrients">
                                🔥 ${meal.calories} | 🥩 ${meal.protein}g | 🍞 ${meal.carbohydrate}g 
                            </p>
                            <div class="food-actions">
                                <span class="food-name">${meal.food_name}</span>
                                <span class="food-amount">${meal.amount}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            categoryContainer.innerHTML += mealItemsHTML;  // เพิ่มการ์ดอาหารลงใน category
            container.appendChild(categoryContainer);

            // เพิ่ม event listener สำหรับปุ่ม add-food
            const addFoodBtns = container.querySelectorAll('.add-food');
            addFoodBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const foodId = this.getAttribute('data-food-id');
                    const foodCard = this.closest('.food-card');
                    
                    // ดึงข้อมูลอาหารที่จำเป็น
                    const foodName = foodCard.querySelector('.food-name').textContent;
                    const calories = parseFloat(foodCard.querySelector('.food-calories').textContent);
                    const foodNutrients = foodCard.querySelector('.food-nutrients').textContent;
                    const foodAmount = foodCard.querySelector('.food-amount') ? foodCard.querySelector('.food-amount').textContent : '1 จาน';
                    const imageUrl = foodCard.querySelector('.food-image').src;
                    
                    // แยกข้อมูลโปรตีนและคาร์โบไฮเดรตจาก foodNutrients
                    const proteinMatch = foodNutrients.match(/🥩\s*(\d+(\.\d+)?)/);
                    const carbMatch = foodNutrients.match(/🍞\s*(\d+(\.\d+)?)/);
                    
                    const protein = proteinMatch ? proteinMatch[1] : '0';
                    const carbohydrate = carbMatch ? carbMatch[1] : '0';
                    
                    // ตรวจสอบว่าอาหารนี้ถูกเลือกแล้วหรือไม่
                    const existingIndex = selectedFoods.findIndex(food => food.food_id === foodId);
                    
                    if (existingIndex >= 0) {
                        // ถ้าเลือกแล้ว ให้ลบออก
                        selectedFoods.splice(existingIndex, 1);
                        this.classList.remove('selected');
                        this.innerHTML = '<i class="fa-solid fa-plus"></i>';
                    } else {
                        // ถ้ายังไม่ได้เลือก ให้เพิ่มเข้าไป
                        selectedFoods.push({
                            food_id: foodId,
                            food_name: foodName,
                            calories: calories,
                            protein: protein,
                            carbohydrate: carbohydrate,
                            amount: foodAmount,
                            image_url: imageUrl
                        });
                        this.classList.add('selected');
                        this.innerHTML = '<i class="fa-solid fa-check"></i>';
                    }
                    
                    // อัพเดทจำนวนที่เลือกบนปุ่ม "เพิ่ม"
                    updateSelectedCount(selectedFoods.length, popup);
                });
            });
        }

// ========================================================================================================================
        // ฟังก์ชันอัพเดทจำนวนอาหารที่เลือกบนปุ่ม "เพิ่ม"
        function updateSelectedCount(count, popup) {
            const countElement = popup.querySelector('.selected-count');
            countElement.textContent = `(${count})`;
            
            // เพิ่มการเปลี่ยนสีปุ่มเมื่อมีการเลือกอาหาร
            const submitBtn = popup.querySelector('.submit-food-btn');
            if (count > 0) {
                submitBtn.classList.add('has-items');
            } else {
                submitBtn.classList.remove('has-items');
            }
        }

        // ฟังก์ชันเพิ่มรายการอาหารที่เลือกไปยังหน้าหลัก
        function addSelectedFoodsToMeal(selectedFoods, mealType) {
            // หา meal-section ที่มี data-meal-type ตรงกับ mealType
            const mealSection = document.querySelector(`.meal-section[data-meal-type="${mealType}"]`);
            if (!mealSection) {
                console.error(`ไม่พบส่วนอาหาร ${mealType} ในหน้าหลัก`);
                return;
            }

            // หา meal-body ภายใน meal-section
            const mealBody = mealSection.querySelector('.meal-body');
            if (!mealBody) {
                console.error(`ไม่พบ meal-body ใน ${mealType}`);
                return;
            }

            // ลบข้อความ "ไม่มีข้อมูลรายการอาหาร" ถ้ามี
            if (mealBody.classList.contains('empty-meal')) {
                mealBody.innerHTML = '';
                mealBody.classList.remove('empty-meal');
            }

            // เพิ่มรายการอาหารที่เลือกไปยัง meal-body
            let totalMealCalories = 0;
            selectedFoods.forEach(food => {
                const foodElement = document.createElement('div');
                foodElement.className = 'food-card';
                foodElement.setAttribute('data-food-id', food.food_id);
                // เพิ่ม data-calories เพื่อใช้ในการคำนวณเมื่อลบ
                foodElement.setAttribute('data-calories', food.calories);

                foodElement.innerHTML = `
                    <button class="delete-food-btn"><i class="fa-solid fa-trash"></i></button>
                    <img src="${food.image_url}" class="food-image" alt="${food.food_name}">
                    <div class="food-info">
                        <h4 class="food-name">${food.food_name}</h4>
                        <p class="food-calories">${food.calories} แคลอรี</p>
                        <p class="food-nutrients">
                            🔥 ${food.calories} | 🥩 ${food.protein}g | 🍞 ${food.carbohydrate}g 
                        </p>
                        <div class="food-actions">
                            <span class="food-name">${food.food_name}</span>
                            <span class="food-amount">${food.amount}</span>
                        </div>
                    </div>
                `;

                // เพิ่ม event listener สำหรับปุ่มลบ
                const deleteBtn = foodElement.querySelector('.delete-food-btn');
                deleteBtn.addEventListener('click', function() {
                    // ดึงค่าแคลอรี่ของอาหารที่กำลังจะลบ
                    const foodCalories = parseFloat(foodElement.getAttribute('data-calories')) || 0;
                    
                    // ลบรายการอาหารจาก UI
                    mealBody.removeChild(foodElement);
                    
                    // อัพเดทจำนวนแคลอรี่ของมื้อนี้
                    updateMealCalories(mealSection, -foodCalories);
                    
                    // อัพเดทจำนวนแคลอรี่ทั้งหมด
                    updateTotalCalories();
                    
                    // ตรวจสอบว่ามีรายการอาหารเหลืออยู่หรือไม่
                    if (mealBody.children.length === 0) {
                        mealBody.innerHTML = 'ไม่มีข้อมูลรายการอาหาร';
                        mealBody.classList.add('empty-meal');
                        
                        // รีเซ็ตจำนวนแคลอรี่ของมื้อนี้เป็น 0
                        const mealCalories = mealSection.querySelector('.meal-calories');
                        if (mealCalories) {
                            mealCalories.textContent = '0 แคลอรี';
                        }
                    }
                    
                    // บันทึกการเปลี่ยนแปลงไปยัง server (ต้องสร้างฟังก์ชันนี้เพิ่มเติม)
                    saveMealToServer(mealType, getMealFoodData(mealBody));
                });

                mealBody.appendChild(foodElement);
                totalMealCalories += parseFloat(food.calories);
            });

            // อัพเดทจำนวนแคลอรี่ของมื้อนี้
            updateMealCalories(mealSection, totalMealCalories);

            // อัพเดทจำนวนแคลอรี่ทั้งหมด
            updateTotalCalories();
            
            // บันทึกข้อมูลลง localStorage (เพื่อใช้ชั่วคราว) หรือส่งไปที่ API
            saveMealToServer(mealType, selectedFoods);
        }

        // ฟังก์ชันสำหรับอัพเดทแคลอรี่ของมื้ออาหาร
        function updateMealCalories(mealSection, caloriesChange) {
            const mealCaloriesElement = mealSection.querySelector('.meal-calories');
            if (mealCaloriesElement) {
                // ดึงจำนวนแคลอรี่ปัจจุบัน
                const currentCaloriesText = mealCaloriesElement.textContent;
                const currentCalories = parseFloat(currentCaloriesText) || 0;
                const newTotalCalories = Math.max(0, currentCalories + caloriesChange);
                mealCaloriesElement.textContent = `${newTotalCalories.toFixed(0)} แคลอรี`;
            }
        }

        // ฟังก์ชันอัพเดทจำนวนแคลอรี่ทั้งหมด
        function updateTotalCalories() {
            let totalDailyCalories = 0;
            const mealCaloriesElements = document.querySelectorAll('.meal-calories');
            
            mealCaloriesElements.forEach(element => {
                const caloriesText = element.textContent;
                // แยกตัวเลขออกจากข้อความ "แคลอรี"
                const calories = parseFloat(caloriesText) || 0;
                totalDailyCalories += calories;
            });
            
            const totalCaloriesElement = document.getElementById('total-calories');
            if (totalCaloriesElement) {
                totalCaloriesElement.textContent = totalDailyCalories.toFixed(0);
            }
        }

        
        function addFoodButtonListeners() {
            const addFoodButtons = document.querySelectorAll('.add-food-btn');
            addFoodButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const mealType = this.getAttribute('data-meal-type');
                    showFoodSelectionPopup(mealType);
                });
            });
        }

        // เพิ่ม CSS สำหรับปุ่มที่เลือกแล้ว
        const style = document.createElement('style');
        style.textContent = `
            .add-food.selected {
                background-color: #4CAF50;
                color: white;
            }
            
            .submit-food-btn.has-items {
                background-color: #4CAF50;
                color: white;
            }
            
            .selected-count {
                font-weight: bold;
            }
        `;
        document.head.appendChild(style);

        document.addEventListener('DOMContentLoaded', function() {
            addFoodButtonListeners();
        });
        
// ========================================================================================================================
        // ฟังก์ชันบัรทึกข้อมูลอาหารไปยังเซิร์ฟเวอร์
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".submit-btn").addEventListener("click", function () {
                saveMeals();
            });
        });

        // กำหนด currentWeekStart นอกฟังก์ชัน renderWeek เพื่อให้สามารถเข้าถึงได้ทั่วทั้งสคริปต์
        // กำหนด currentWeekStart เป็นวันอาทิตย์ของสัปดาห์ปัจจุบัน
        let currentWeekStart = new Date();
        currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay()); // Start from Sunday

        function saveMeals() {
            const username = loggedInUser;
            const selectedDateElement = document.querySelector('.date.selected-day');
            let date;
            if (selectedDateElement) {
                date = selectedDateElement.getAttribute('data-date');
                console.log("Selected Date:", date);
            } else {
                date = new Date().toISOString().split("T")[0];
                console.log("Default Date (Today):", date);
            }
            const mealsData = collectMealData();

            if (!mealsData || Object.keys(mealsData).length === 0) {
                alert("ไม่มีข้อมูลอาหาร กรุณาเลือกเมนู!");
                return;
            }

            checkSavedMeals(username, date)
                .then(hasSavedMeals => {
                    // ส่งข้อมูลไปยัง api เพื่อบันทึกข้อมูลอาหาร
                    let apiUrl = 'http://127.0.0.1:5000/save_meals';
                    if (hasSavedMeals) {
                        // ถ้ามีบันทึกแล้ว ไป api อัพเดท
                        apiUrl = 'http://127.0.0.1:5000/update_meals';
                    }
                    fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            username: username,
                            date: date,
                            meals: mealsData
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("บันทึกสำเร็จ!");
                            // window.location.reload();
                        } else {
                            alert("เกิดข้อผิดพลาด: " + data.error);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("เกิดข้อผิดพลาดในการส่งข้อมูล");
                    });
                })
                .catch(error => {
                    console.error('Error checking saved meals:', error);
                    alert("เกิดข้อผิดพลาดในการตรวจสอบข้อมูล");
                });
        }
        
        // ฟังก์ชันเก็บข้อมูลอาหาร ก่อนบันทึก
        function collectMealData() {
            let meals = {};
            document.querySelectorAll(".meal-section").forEach(mealElement => {
                let mealType = mealElement.dataset.mealType; // เช่น breakfast, lunch, dinner
                let foods = [];

                mealElement.querySelectorAll(".food-card").forEach(foodElement => {
                    let foodData = {
                        food_id: foodElement.dataset.foodId || null, 
                        food_name: foodElement.querySelector(".food-name").textContent,
                        calories: parseFloat(foodElement.dataset.calories) || 0,
                        protein: parseFloat(foodElement.querySelector(".food-nutrients").textContent.match(/🥩\s*(\d+(\.\d+)?)/)[1]) || 0,
                        carbohydrate: parseFloat(foodElement.querySelector(".food-nutrients").textContent.match(/🍞\s*(\d+(\.\d+)?)/)[1]) || 0,
                        amount: foodElement.querySelector(".food-amount").textContent || "1 จาน",
                        image_url: foodElement.querySelector(".food-image").src
                    };
                    foods.push(foodData);
                });

                if (foods.length > 0) {
                    meals[mealType] = foods;
                }
            });

            return meals;
        }

        



    
    </script>

</body>
</html>