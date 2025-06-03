# Food Recommendation System By Machine Learning

ระบบแนะนำอาหารอัจฉริยะสำหรับคนรักสุขภาพ ที่ช่วยให้คุณเลือกทานอาหารได้ตรงกับเป้าหมายและความต้องการของร่างกาย ด้วยการนำเทคโนโลยี Machine Learning และฐานข้อมูลโภชนาการมาใช้ เพื่อให้การดูแลสุขภาพเป็นเรื่องง่าย

---

## 🥗 ฟีเจอร์เด่น

- **แนะนำอาหารรายวัน**  
  ระบบจะใช้โมเดล Machine Learning วิเคราะห์ข้อมูลสุขภาพของคุณ เช่น โรคประจำตัว, BMI, แคลอรี่ที่ควรได้รับต่อวัน ฯลฯ แล้วนำเสนอเมนูอาหารที่เหมาะสมในแต่ละมื้อ (เช้า กลางวัน เย็น) พร้อมข้อมูลโภชนาการครบถ้วน  
- **คำนวณแคลอรี่และสารอาหารอัตโนมัติ**  
  เมื่อเลือกอาหารแล้ว ระบบจะสรุปแคลอรี่ที่ได้รับในแต่ละมื้อและรวมทั้งวัน
- **ปฏิทินการรับประทานอาหาร**  
  ดูประวัติการเลือกเมนูย้อนหลัง พร้อมสถานะการทานอาหารในเเต่ละวัน
- **ดูโปรไฟล์ส่วนตัว**  
  แก้ไขข้อมูลสุขภาพ เช่น น้ำหนัก ส่วนสูง อายุ เป้าหมาย ฯลฯ เพื่อให้ระบบแนะนำได้แม่นยำยิ่งขึ้น
- **Popup รายละเอียดอาหาร**  
  กดดูข้อมูลโภชนาการของแต่ละเมนูอย่างละเอียด เช่น เเคลอรี่ โปรตีน คาร์โบไฮเดรต ฯลฯ
- **แจ้งเตือนและอินเตอร์เฟซทันสมัย**  
  อินเตอร์เฟซสวยงาม ใช้งานง่าย พร้อมระบบแจ้งเตือนความสำเร็จหรือข้อผิดพลาด

---

## ⚙️ เทคโนโลยีที่ใช้

- **Frontend:**  
  - <img src="https://img.icons8.com/color/28/html-5.png" alt="HTML5" /> HTML5
  - <img src="https://img.icons8.com/color/28/css3.png" alt="CSS3" /> CSS3 (Bootstrap, Custom CSS)
  - <img src="https://img.icons8.com/color/28/javascript.png" alt="JavaScript" /> JavaScript
  - <img src="https://sweetalert2.github.io/images/SweetAlert2.png" width="28" alt="SweetAlert2" /> SweetAlert2

- **Backend:**  
  - <img src="https://img.icons8.com/officel/28/php-logo.png" alt="PHP" /> PHP
  - <img src="https://img.icons8.com/color/28/python--v1.png" alt="Python" /> Python
  - <img src="https://img.icons8.com/ios-filled/28/flask.png" alt="Flask" /> Flask

- **Machine Learning:**  
  - <img src="https://img.icons8.com/fluency/28/jupyter.png" alt="Jupyter" /> Jupyter Notebook  
  - <img src="https://img.icons8.com/color/28/numpy.png" alt="NumPy" /> numpy
  - <img src="https://img.icons8.com/color/28/pandas.png" alt="pandas" /> pandas
  - <img src="https://scikit-learn.org/stable/_static/scikit-learn-logo-small.png" width="28" alt="scikit-learn" /> scikit-learn

- **Database:**  
  - <img src="https://img.icons8.com/ios-filled/28/mysql-logo.png" alt="MySQL" /> MySQL

- **Tools:**  
  - <img src="https://img.icons8.com/fluent/28/visual-studio-code-2019.png" alt="VSCode" /> Visual Studio Code
  - <img src="https://img.shields.io/badge/XAMPP-FA7F18?style=flat-square&logo=xampp&logoColor=white" alt="XAMPP" width="50"/> XAMPP 

---

## 🏁 วิธีเริ่มต้นใช้งานบน XAMPP

### 1. ติดตั้ง XAMPP
- ดาวน์โหลด XAMPP จาก [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html)
- ติดตั้งและเปิดใช้งาน Apache และ MySQL ใน XAMPP Control Panel

### 2. นำโปรเจคไปไว้ใน htdocs
- ดาวน์โหลดหรือโคลนโปรเจคนี้
  
  ```bash
  git clone https://github.com/Armpa16/Food-Recommendation-System-By-Machine-Learning.git
  ```
- นำโฟลเดอร์ `Food-Recommendation-System-By-Machine-Learning` ไปวางไว้ใน `C:\xampp\htdocs\` (หรือ path htdocs ของ XAMPP ใน OS ของคุณ)

### 3. ตั้งค่าฐานข้อมูล MySQL
- เปิด phpMyAdmin (`http://localhost/phpmyadmin`)
- สร้างฐานข้อมูลใหม่ เช่น `food_recommendation`
- นำเข้าไฟล์ SQL (`food_recommend_system.sql`)
- ตรวจสอบตารางและข้อมูลตัวอย่าง

### 4. ตั้งค่าการเชื่อมต่อฐานข้อมูล
- แก้ไขไฟล์ config หรือไฟล์เชื่อมต่อฐานข้อมูล (เช่น `db_connect.php`) ให้ตรงกับชื่อฐานข้อมูล Username/Password ที่ตั้งไว้

### 5. รันเซิร์ฟเวอร์และเข้าใช้งาน
- เปิด XAMPP ให้ Apache & MySQL ทำงานอยู่
- เปิดเบราว์เซอร์ ไปที่
  
  ```
  http://localhost/Food-Recommendation-System-By-Machine-Learning/
  ```
- เริ่มใช้งานระบบเเนะนำอาหารได้เลย!

---

## 🔥 ตัวอย่างหน้าจอ

- หน้าหลักแสดงข้อมูลสุขภาพและแคลอรี่
   - ![screencapture-localhost-Food-index-php-2025-05-31-14_52_31](https://github.com/user-attachments/assets/47303a2a-1221-448b-aa62-202fd416845b)

- ระบบแนะนำอาหารพร้อมปุ่มเพิ่ม-ลบเมนู
   - ![screencapture-localhost-Food-recommendfood-php-2025-05-31-14_52_55](https://github.com/user-attachments/assets/d5a32172-d039-4a82-952e-63c6e8ed1950)

- ปฏิทินเเสดงสถานะการทานอาหาร
   - ![screencapture-localhost-Food-calendar-php-2025-05-31-14_53_18](https://github.com/user-attachments/assets/c5ffca4f-ebe7-45bc-a8ed-10c4bc1095f9)

- หน้าโปรไฟล์ส่วนตัว
   - ![screencapture-localhost-Food-profile-php-2025-05-31-14_53_28](https://github.com/user-attachments/assets/3b136009-9cd5-4043-9195-afddbe106ecc)

---

## 📬 Contact

🙏 ขอบคุณที่สนใจโปรเจค **Food Recommendation System By Machine Learning**

หากคุณมีคำถาม ปัญหา หรือข้อเสนอแนะ  
สามารถติดต่อผู้พัฒนาได้ทางช่องทางต่อไปนี้

---

**GitHub:**  
- [Armpa16 (GitHub Profile)](https://github.com/Armpa16)

**Email:**  
- panudech1419@gmail.com

**ช่องทางอื่นๆ:**  
- https://www.facebook.com/Panudech.Susankunthorn

---

> **"กินดี ชีวิตดี ด้วยการเลือกอาหารอย่างชาญฉลาด"**
