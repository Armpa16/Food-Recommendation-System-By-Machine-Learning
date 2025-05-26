import decimal
from flask import Flask, request, jsonify
import mysql.connector
import joblib
import pandas as pd
from flask_cors import CORS
from datetime import datetime

app = Flask(__name__)
CORS(app)

# โหลดโมเดล
model = joblib.load('Flask_Api/food_recommendation_model.pkl')

# ฟังก์ชันเชื่อมต่อกับฐานข้อมูล MySQL
def get_db_connection():
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="food_recommend_system"
        )
        return conn
    except mysql.connector.Error as err:
        print(f"Error connecting to MySQL: {err}")
        raise

# Mapping ประเภทอาหาร
food_type_mapping = {
    0: "สมดุล",
    1: "น้ำตาลต่ำ",
    2: "โซเดียมต่ำ",
    3: "ไขมันต่ำ",
    4: "น้ำตาลต่ำ+โซเดียมต่ำ",
    5: "น้ำตาลต่ำ+ไขมันต่ำ",
    6: "โซเดียมต่ำ+ไขมันต่ำ",
    7: "น้ำตาลต่ำ+โซเดียมต่ำ+ไขมันต่ำ"
}
# ฟังก์ชันแปลงประเภทอาหาร
def get_related_food_types(predicted_food_type):
    type_mapping = {
        0: [0],      # สมดุล
        1: [1],      # น้ำตาลต่ำ
        2: [2],      # โซเดียมต่ำ 
        3: [3],      # ไขมันต่ำ
        4: [1, 2],   # น้ำตาลต่ำ + โซเดียมต่ำ
        5: [1, 3],   # น้ำตาลต่ำ + ไขมันต่ำ
        6: [2, 3],   # โซเดียมต่ำ + ไขมันต่ำ
        7: [1, 2, 3] # น้ำตาลต่ำ + โซเดียมต่ำ + ไขมันต่ำ
    }
    return type_mapping.get(predicted_food_type, [0])

# ฟังก์ชันดึงข้อมูลผู้ใช้จากฐานข้อมูล
def get_user_data(username):
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="food_recommend_system"
        )
        cursor = conn.cursor()

        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_id_data = cursor.fetchone()

        if user_id_data:
            user_id = user_id_data[0]
            cursor.execute(""" 
                SELECT p.age, p.weight, p.height, p.activity_level, p.bmi, p.diseases, p.gender, p.status_bmi, p.daily_calorie
                FROM users u 
                JOIN profiles p ON u.users_id = p.users_id 
                WHERE u.users_id = %s
            """, (user_id,))
            user_data = cursor.fetchone()

            cursor.close()
            conn.close()

            if user_data:
                age, weight, height, activity_level, bmi, diseases, gender, status_bmi, daily_calorie = user_data

                # แก้ปัญหาเรื่องการแปลงค่าของโรค
                diseases = diseases.replace(" ", "").strip()

                disease_mapping = {
                    'ไม่มี': 0,
                    'โรคเบาหวาน': 1,
                    'โรคความดันโลหิตสูง': 2,
                    'โรคหัวใจ': 3,
                    'โรคเบาหวาน,โรคความดันโลหิตสูง': 4,
                    'โรคเบาหวาน,โรคหัวใจ': 5,
                    'โรคความดันโลหิตสูง,โรคหัวใจ': 6,
                    'โรคเบาหวาน,โรคความดันโลหิตสูง,โรคหัวใจ': 7
                }
                disease_type = disease_mapping.get(diseases, 0)  # เริ่ม 0

                # Mapping ข้อมูล
                mappings = {
                    "gender": {'ชาย': 1, 'หญิง': 2},
                    "activity_level": {'น้อย': 1, 'ปานกลาง': 2, 'มาก': 3},
                    "status_bmi": {'น้ำหนักต่ำกว่าเกณฑ์': 1, 'น้ำหนักสมส่วน': 2, 'น้ำหนักเกินเกณฑ์': 3, 'อ้วน': 4}
                }

                user_data_dict = {
                    "age": age,
                    "weight": float(weight),
                    "height": float(height),
                    "activity_level": mappings["activity_level"].get(activity_level, 0),
                    "bmi": float(bmi),
                    "status_bmi": mappings["status_bmi"].get(status_bmi, 0),
                    "diseases": disease_type,  
                    "gender": mappings["gender"].get(gender, 0),
                    "daily_calorie": int(daily_calorie)
                }

                print("✅ ข้อมูลที่ถูกแปลงเรียบร้อย:", user_data_dict)
                return user_data_dict

        print("❌ ไม่พบข้อมูลผู้ใช้")
        return None

    except mysql.connector.Error as err:
        print(f"❌ MySQL Error: {err}")
        return None

# ฟังก์ชันทำนายประเภทอาหาร
def predict_food_type(user_data):
    try:
        # เรียงลำดับฟีเจอร์ให้ตรงกับตอนฝึกโมเดล
        feature_names = ['Ages', 'Gender', 'Height', 'Weight', 'Bmi', 'Disease_Type', 'Activity_Level']
        input_data = [[
            user_data['age'], user_data['gender'], user_data['height'], user_data['weight'],
            user_data['bmi'], user_data['diseases'], user_data['activity_level']
        ]]
        input_df = pd.DataFrame(input_data, columns=feature_names)

        predicted_food_type = model.predict(input_df)[0]  
        print(f"✅ ประเภทอาหารที่โมเดลทำนาย: {predicted_food_type}")

        # แปลงค่าตัวเลขกลับเป็นข้อความ
        food_type_name = food_type_mapping.get(predicted_food_type, "สมดุล")
        return predicted_food_type, food_type_name

    except Exception as e:
        print(f"❌ Error in predict_food_type: {e}")
        return 0, "สมดุล"

# ฟังก์ชันดึงเมนูอาหาร 
def get_meal_set(food_type_code, target_calories):
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="food_recommend_system"
        )
        cursor = conn.cursor(dictionary=True)

        meal_set = {}
        categories = ["อาหารจานหลัก", "ผลไม้", "ขนม", "เครื่องดื่ม", "ฟาสต์ฟู้ด"]
        remaining_calories = target_calories  
        
        # ดึงรายการประเภทอาหารที่เกี่ยวข้อง
        related_types = get_related_food_types(food_type_code)
        for category in categories:
            found_meal = False
            
            # หาอาหาร
            for type_code in related_types:
                food_type = food_type_mapping.get(type_code)
                print(f"🔍 Querying for category: {category}, food_type: {food_type}, max_calories: {remaining_calories}")
                
                query = """ 
                    SELECT * FROM food_menu
                    WHERE food_type = %s AND category = %s AND calories <= %s
                    ORDER BY RAND() LIMIT 1
                """
                cursor.execute(query, (food_type, category, remaining_calories))
                meal = cursor.fetchone()
                
                if meal:
                    print(f"✅ Found meal: {meal['food_name']} ({meal['calories']} kcal)")
                    meal_set[category] = meal  
                    remaining_calories -= float(meal["calories"])
                    found_meal = True
                    break
            
            if not found_meal:
                print(f"❌ No meal found for category: {category}")

        cursor.close()
        conn.close()

        if not meal_set:
            print("❌ No meals found for this food type!")
            return None

        print(f"✅ เมนูอาหารที่เลือก (type {food_type_code}):", meal_set)  
        return meal_set

    except mysql.connector.Error as err:
        print(f"❌ MySQL Error in get_meal_set: {err}")
        return None

# API เเนะนำอาหาร
@app.route('/get_recommendations', methods=['POST'])
def get_recommendations():
    try:
        data = request.get_json()
        username = data.get('username')
    
        print(f"🔍 รับคำขอสำหรับผู้ใช้: {username}")  
        
        # ตรวจสอบว่ามีชื่อผู้ใช้หรือไม่
        user_data = get_user_data(username)
        if user_data is None:
            print("❌ ไม่พบข้อมูลผู้ใช้")
            return jsonify({"error": "User data not found"}), 404

        daily_calorie = user_data['daily_calorie']
        breakfast_calories = daily_calorie * 0.3
        lunch_calories = daily_calorie * 0.4
        dinner_calories = daily_calorie * 0.3

        food_type_code, food_type_name = predict_food_type(user_data)
        breakfast = get_meal_set(food_type_code, breakfast_calories)
        lunch = get_meal_set(food_type_code, lunch_calories)
        dinner = get_meal_set(food_type_code, dinner_calories)

        if not (breakfast and lunch and dinner):
            print("❌ ไม่สามารถดึงเมนูอาหารที่เหมาะสมได้")
            return jsonify({"error": "ไม่สามารถดึงข้อมูลแนะนำอาหารได้"}), 500

        response_data = {
            "daily_calorie": daily_calorie,
            "food_type": food_type_name,
            "food_type_code": int(food_type_code),
            "meals": {
                "breakfast": breakfast,
                "lunch": lunch,
                "dinner": dinner
            }
        }

        print("✅ JSON Response:", response_data)
        return jsonify(response_data), 200, {'Content-Type': 'application/json; charset=utf-8'}

    except Exception as e:
        print(f"❌ Error in get_recommendations: {e}")
        return jsonify({"error": str(e)}), 500
    

# API ดึงรายการอาหารที่แนะนำ/เหมาะสม
@app.route('/get_food_list', methods=['POST'])
def get_food_list():
    try:
        data = request.get_json()
        username = data.get('username')
        if not username:
            print(f"❌ ไม่พบชื่อผู้ใช้: {data}")
            return jsonify({"error": "Username ไม่ได้ส่งมา"}), 400
        print(f"🔍 รับคำขอสำหรับผู้ใช้: {username}")

        user_data = get_user_data(username)
        if user_data is None:
            print(f"❌ ไม่พบข้อมูลผู้ใช้: {username}")
            return jsonify({"error": "ไม่พบข้อมูลผู้ใช้"}), 404

        # ทำนายประเภทอาหาร
        food_type_code, food_type_name = predict_food_type(user_data)
        print(f"✅ ประเภทอาหารที่ทำนาย: {food_type_name} (code: {food_type_code})")

        # ดึงประเภทอาหารที่เกี่ยวข้อง
        related_food_types = get_related_food_types(food_type_code)
        print(f"✅ ประเภทอาหารที่เกี่ยวข้อง: {related_food_types}")

        # ดึงรายการอาหารจากฐานข้อมูลตามประเภทที่เกี่ยวข้อง
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="food_recommend_system"
        )
        cursor = conn.cursor(dictionary=True)

        # สร้างรายการ query สำหรับประเภทอาหารที่เกี่ยวข้อง
        meals = []
        for food_type in related_food_types:
            food_type_name = food_type_mapping.get(food_type, "")
            if food_type_name:
                print(f"🔍 กำลังค้นหารายการอาหารที่มีประเภท: {food_type_name}")
                query = """ 
                    SELECT * FROM food_menu
                    WHERE food_type = %s
                    ORDER BY food_name
                """
                cursor.execute(query, (food_type_name,))
                meals.extend(cursor.fetchall())

        cursor.close()
        conn.close()

        if not meals:
            print(f"❌ ไม่พบรายการอาหารที่ตรงกับประเภทที่เกี่ยวข้อง: {related_food_types}")
            return jsonify({"error": "ไม่พบรายการอาหารที่ตรงกับประเภทที่ทำนาย"}), 404

        # สร้าง response
        response_data = {
            "food_type": related_food_types,
            "meals": meals
        }

        print("✅ ข้อมูลรายการอาหารที่ดึงมา:", response_data)
        return jsonify(response_data), 200

    except Exception as e:
        print(f"❌ Error: {e}")
        return jsonify({"error": str(e)}), 500
    
    
# API ดึงรายการอาหารที่ไม่เหมาะสม
@app.route('/get_unsuitable_food_list', methods=['POST'])
def get_unsuitable_food_list():
    try:
        data = request.get_json()
        username = data.get('username')
        if not username:
            print(f"❌ ไม่พบชื่อผู้ใช้ในคำขอสำหรับอาหารที่ไม่เหมาะสม: {data}")
            return jsonify({"error": "Username ไม่ได้ส่งมา"}), 400
        print(f"🔍 รับคำขอสำหรับอาหารที่ไม่เหมาะสมสำหรับผู้ใช้: {username}")

        user_data = get_user_data(username)
        if user_data is None:
            print(f"❌ ไม่พบข้อมูลผู้ใช้ (อาหารที่ไม่เหมาะสม): {username}")
            return jsonify({"error": "ไม่พบข้อมูลผู้ใช้"}), 404

        # ทำนายประเภทอาหาร
        predicted_food_type_code, predicted_food_type_name = predict_food_type(user_data)
        print(f"✅ ประเภทอาหารที่ทำนาย (สำหรับอาหารที่ไม่เหมาะสม): {predicted_food_type_name} (code: {predicted_food_type_code})")

        # ดึงประเภทอาหารที่เกี่ยวข้อง (ถือว่าเป็น "เหมาะสม")
        suitable_type_codes = get_related_food_types(predicted_food_type_code)
        print(f"✅ ประเภทอาหารที่ถือว่าเหมาะสม (codes): {suitable_type_codes}")

        # หาประเภทอาหารที่ไม่เหมาะสม
        unsuitable_food_type_names = []
        for code, name in food_type_mapping.items():
            if code not in suitable_type_codes:
                unsuitable_food_type_names.append(name)
        
        print(f"✅ ประเภทอาหารที่ไม่เหมาะสม (names): {unsuitable_food_type_names}")

        meals = []
        if unsuitable_food_type_names:
            conn = get_db_connection()
            cursor = conn.cursor(dictionary=True)

            # สร้าง query string สำหรับ IN clause
            placeholders = ', '.join(['%s'] * len(unsuitable_food_type_names))
            query = f"""
                SELECT * FROM food_menu
                WHERE food_type IN ({placeholders})
                ORDER BY food_name
            """
            cursor.execute(query, tuple(unsuitable_food_type_names))
            meals = cursor.fetchall()

            cursor.close()
            conn.close()

        if not meals:
            print(f"❌ ไม่พบรายการอาหารที่ไม่เหมาะสมสำหรับประเภท: {unsuitable_food_type_names}")
            # อาจจะคืนค่าว่างถ้าไม่พบ หรือคืน error ตามความเหมาะสม
            # return jsonify({"error": "ไม่พบรายการอาหารที่ไม่เหมาะสม"}), 404

        response_data = {
            "predicted_suitable_food_type_name": predicted_food_type_name,
            "meals": meals
        }
        print("✅ ข้อมูลรายการอาหารที่ไม่เหมาะสมที่ดึงมา:", len(meals), "รายการ")
        return jsonify(response_data), 200
    except Exception as e:
        print(f"❌ Error in get_unsuitable_food_list: {e}")
        return jsonify({"error": str(e)}), 500  
     
# API บันทึกอาหาร
@app.route('/save_meals', methods=['POST'])
def save_meals():
    try:
        data = request.json
        username = data.get('username')
        meals_data = data.get('meals', {})
        date = data.get('date', datetime.now().strftime('%Y-%m-%d'))  # รับวันที่จาก request หรือใช้ค่าปัจจุบัน

        if not username:
            return jsonify({'error': 'กรุณาระบุชื่อผู้ใช้'}), 400

        if not meals_data:
            return jsonify({'error': 'ไม่พบข้อมูลอาหารที่ต้องการบันทึก'}), 400

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        # ค้นหา user_id จาก username
        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()

        if not user_result:
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404

        user_id = user_result['users_id']

        meals_saved = 0

        for meal_type, foods in meals_data.items():
            for food_item in foods:
                food_id = food_item.get('food_id')
                food_name = food_item.get('food_name')
                calories = food_item.get('calories', 0)
                protein = food_item.get('protein', 0)
                carbohydrate = food_item.get('carbohydrate', 0)
                amount = food_item.get('amount', '1 จาน')
                image_url = food_item.get('image_url', 'default-image.jpg')

                # ตรวจสอบว่าอาหารมีอยู่ในฐานข้อมูลหรือไม่
                if food_id and not str(food_id).startswith('temp-'):
                    cursor.execute("SELECT food_id FROM food_menu WHERE food_id = %s", (food_id,))
                    food_exists = cursor.fetchone()

                    if not food_exists:
                        cursor.execute("""
                            INSERT INTO _menu (food_name, calories, protein, carbohydrate, image_url)
                            VALUES (%s, %s, %s, %s, %s)
                        """, (food_name, calories, protein, carbohydrate, image_url))
                        food_id = cursor.lastrowid
                else:
                    cursor.execute("SELECT food_id FROM food_menu WHERE food_name = %s", (food_name,))
                    existing_food = cursor.fetchone()

                    if existing_food:
                        food_id = existing_food['food_id']
                    else:
                        cursor.execute("""
                            INSERT INTO food_menu (food_name, calories, protein, carbohydrate, image_url)
                            VALUES (%s, %s, %s, %s, %s)
                        """, (food_name, calories, protein, carbohydrate, image_url))
                        food_id = cursor.lastrowid

                # บันทึกข้อมูลมื้ออาหาร
                cursor.execute("""
                    INSERT INTO food_history (users_id, food_id, his_date, meal_type)
                    VALUES (%s, %s, %s, %s)
                """, (user_id, food_id, date, meal_type))

                meals_saved += 1

        conn.commit()
        cursor.close()
        conn.close()

        return jsonify({
            'message': f'บันทึกข้อมูลอาหารสำเร็จ {meals_saved} รายการ',
            'success': True,
            'meals_saved': meals_saved
        })

    except Exception as e:
        return jsonify({'error': str(e), 'message': 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'}), 500



# API สำหรับตรวจสอบว่ามีข้อมูลอาหารที่บันทึกไว้ในวันนั้นๆหรือไม่
@app.route('/check_saved_meals', methods=['POST'])
def check_saved_meals():
    try:
        data = request.get_json()
        username = data.get('username')
        date = data.get('date')

        if not username or not date:
            return jsonify({'error': 'กรุณาระบุชื่อผู้ใช้และวันที่'}), 400

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        # ค้นหา user_id จาก username
        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()

        if not user_result:
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404

        user_id = user_result['users_id']

        # ตรวจสอบว่ามีข้อมูลอาหารที่บันทึกไว้สำหรับวันที่กำหนดหรือไม่
        cursor.execute("SELECT COUNT(*) as count FROM food_history WHERE users_id = %s AND his_date = %s", (user_id, date))
        result = cursor.fetchone()
        has_meals = result['count'] > 0

        cursor.close()
        conn.close()

        return jsonify({'has_meals': has_meals}), 200

    except Exception as e:
        return jsonify({'error': str(e)}), 500
    

# API สำหรับดึงข้อมูลอาหารที่บันทึกไว้สำหรับวันที่เลือก
@app.route('/get_saved_meals', methods=['POST'])
def get_saved_meals():
    try:
        data = request.get_json()
        username = data.get('username')
        date = data.get('date')

        if not username or not date:
            return jsonify({'error': 'กรุณาระบุชื่อผู้ใช้และวันที่'}), 400

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        # ค้นหา user_id จาก username
        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()

        if not user_result:
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404

        user_id = user_result['users_id']

        # ดึงข้อมูลอาหารที่บันทึกไว้สำหรับวันที่กำหนด
        cursor.execute("""
            SELECT fh.meal_type, fm.*
            FROM food_history fh
            JOIN food_menu fm ON fh.food_id = fm.food_id
            WHERE fh.users_id = %s AND fh.his_date = %s
        """, (user_id, date))
        results = cursor.fetchall()

        cursor.close()
        conn.close()

        meals = {}
        for row in results:
            meal_type = row['meal_type']
            if meal_type not in meals:
                meals[meal_type] = []
            meals[meal_type].append({
                'food_id': row['food_id'],
                'food_name': row['food_name'],
                'calories': row['calories'],
                'protein': row['protein'],
                'carbohydrate': row['carbohydrate'],
                'amount': row['amount'],
                'image_url': row['image_url'] if 'image_url' in row else 'default-image.jpg' # Add image_url
            })

        return jsonify({'meals': meals}), 200

    except Exception as e:
        return jsonify({'error': str(e)}), 500

# API อัพเดทข้อมูลอาหารที่บันทึกไว้
@app.route('/update_meals', methods=['POST'])
def update_meals():
    try:
        data = request.json
        username = data.get('username')
        meals_data = data.get('meals', {})
        date = data.get('date', datetime.now().strftime('%Y-%m-%d'))  # รับวันที่จาก request หรือใช้ค่าปัจจุบัน

        if not username:
            return jsonify({'error': 'กรุณาระบุชื่อผู้ใช้'}), 400

        if not meals_data:
            return jsonify({'error': 'ไม่พบข้อมูลอาหารที่ต้องการบันทึก'}), 400

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        # ค้นหา user_id จาก username
        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()

        if not user_result:
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404

        user_id = user_result['users_id']

        # ลบข้อมูลอาหารเก่า
        cursor.execute("DELETE FROM food_history WHERE users_id = %s AND his_date = %s", (user_id, date))

        meals_saved = 0

        for meal_type, foods in meals_data.items():
            for food_item in foods:
                food_id = food_item.get('food_id')
                food_name = food_item.get('food_name')
                calories = food_item.get('calories', 0)
                protein = food_item.get('protein', 0)
                carbohydrate = food_item.get('carbohydrate', 0)
                amount = food_item.get('amount', '1 จาน')
                image_url = food_item.get('image_url', 'default-image.jpg')

                # ตรวจสอบว่าอาหารมีอยู่ในฐานข้อมูลหรือไม่
                if food_id and not str(food_id).startswith('temp-'):
                    cursor.execute("SELECT food_id FROM food_menu WHERE food_id = %s", (food_id,))
                    food_exists = cursor.fetchone()

                    if not food_exists:
                        cursor.execute("""
                            INSERT INTO _menu (food_name, calories, protein, carbohydrate)
                            VALUES (%s, %s, %s, %s, %s)
                        """, (food_name, calories, protein, carbohydrate))
                        food_id = cursor.lastrowid
                else:
                    cursor.execute("SELECT food_id FROM food_menu WHERE food_name = %s", (food_name,))
                    existing_food = cursor.fetchone()

                    if existing_food:
                        food_id = existing_food['food_id']
                    else:
                        cursor.execute("""
                            INSERT INTO food_menu (food_name, calories, protein, carbohydrate)
                            VALUES (%s, %s, %s, %s, %s)
                        """, (food_name, calories, protein, carbohydrate))
                        food_id = cursor.lastrowid

                # บันทึกข้อมูลมื้ออาหาร
                cursor.execute("""
                    INSERT INTO food_history (users_id, food_id, his_date, meal_type)
                    VALUES (%s, %s, %s, %s)
                """, (user_id, food_id, date, meal_type))

                meals_saved += 1

        conn.commit()
        cursor.close()
        conn.close()

        return jsonify({
            'message': f'อัพเดทข้อมูลอาหารสำเร็จ {meals_saved} รายการ',
            'success': True,
            'meals_saved': meals_saved
        })

    except Exception as e:
        return jsonify({'error': str(e), 'message': 'เกิดข้อผิดพลาดในการอัพเดทข้อมูล'}), 500
        

# API สำหรับดึงข้อมูลสถานะมื้ออาหารสำหรับเดือนที่กำหนด
@app.route('/get_meal_status_for_month', methods=['POST'])
def get_meal_status_for_month():
    try:
        data = request.get_json()
        username = data.get('username')
        start_date_str = data.get('startDate')
        end_date_str = data.get('endDate')

        if not username or not start_date_str or not end_date_str:
            return jsonify({'error': 'กรุณาระบุชื่อผู้ใช้, วันที่เริ่มต้น, และวันที่สิ้นสุด'}), 400

        start_date = datetime.strptime(start_date_str, '%Y-%m-%d').date()
        end_date = datetime.strptime(end_date_str, '%Y-%m-%d').date()

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        # ค้นหา user_id จาก username
        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()

        if not user_result:
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404

        user_id = user_result['users_id']
        
        # ดึง daily_calorie จากตาราง profiles
        cursor.execute("SELECT daily_calorie FROM profiles WHERE users_id = %s", (user_id,))
        daily_calorie_result = cursor.fetchone()

        if not daily_calorie_result:
            return jsonify({'error': 'ไม่พบข้อมูลแคลอรี่รายวัน'}), 404

        daily_calorie = daily_calorie_result['daily_calorie']

        # ดึงข้อมูลแคลอรี่รวมของแต่ละวันในช่วงวันที่กำหนด
        cursor.execute("""
            SELECT his_date, SUM(fm.calories) as total_calories
            FROM food_history fh
            JOIN food_menu fm ON fh.food_id = fm.food_id
            WHERE fh.users_id = %s AND fh.his_date BETWEEN %s AND %s
            GROUP BY his_date
        """, (user_id, start_date, end_date))
        results = cursor.fetchall()

        cursor.close()
        conn.close()

        meal_status = {}
        for row in results:
            total_calories = int(row['total_calories'])
            lower_bound = daily_calorie * 0.9  # 90% ของ daily calorie
            upper_bound = daily_calorie * 1.1  # 110% ของ daily calorie

            if total_calories > upper_bound:
                status = 'over'
            elif total_calories < lower_bound:
                status = 'under'
            else:
                status = 'perfect'

            meal_status[str(row['his_date'])] = {
                'total_calories': total_calories,
                'status': status
            }

        return jsonify({'mealStatus': meal_status}), 200

    except Exception as e:
        return jsonify({'error': str(e)}), 500
    
# API ดึงโภชนาการเเละข้อมูลอาหาร
@app.route('/get_food_details', methods=['POST'])
def get_food_details():
    data = request.get_json()
    food_id = data.get('food_id')

    if not food_id:
        return jsonify({"success": False, "error": "Missing food_id"}), 400

    conn = get_db_connection()
    if not conn:
         # ตรวจสอบปัญหาการเชื่อมต่อ
         print("Error: Database connection failed in get_food_details")
         return jsonify({"success": False, "error": "Database connection failed"}), 500

    cursor = conn.cursor(dictionary=True) # ใช้ dictionary=True เพื่อให้ผลลัพธ์เป็น dict

    try:
        # ดึงข้อมูลอาหาร
        query = """
            SELECT
                food_id,
                food_name,
                calories,
                protein,
                fat,
                carbohydrate,
                sugar,
                sodium,
                image_url,
                amount
            FROM food_menu 
            WHERE food_id = %s
        """
        cursor.execute(query, (food_id,))
        food_details = cursor.fetchone()

        if food_details:
            # แปลง Decimal เป็น float ถ้าจำเป็น
            for key, value in food_details.items():
                if isinstance(value, decimal.Decimal):
                    food_details[key] = float(value)
                # เพิ่มการจัดการค่า None หากบางคอลัมน์อาจเป็น NULL
                elif value is None:
                    # กำหนดค่า default หรือปล่อยเป็น None ตามความเหมาะสม
                    # ตัวอย่าง: กำหนดเป็น 0 สำหรับตัวเลข หรือ '' สำหรับข้อความ
                    if key in ['calories', 'protein', 'fat', 'carbohydrate', 'sugar', 'sodium']:
                         food_details[key] = 0.0 # หรือค่า default อื่นๆ
                    elif key in ['image_url', 'amount', 'food_name']:
                         food_details[key] = '' # หรือค่า default อื่นๆ


            return jsonify({"success": True, "food_details": food_details})
        else:
            return jsonify({"success": False, "error": "Food not found"}), 404

    except mysql.connector.Error as err:
        # debug
        print(f"Database query error in get_food_details: {err}")
        print(f"Query attempted: {query % (food_id,)}") # แสดง query ที่พยายามรัน 
        return jsonify({"success": False, "error": f"Database query error: {err}"}), 500
    except Exception as e: # ดักจับ eror อื่นๆ ที่อาจเกิดขึ้น
        print(f"Unexpected error in get_food_details: {e}")
        return jsonify({"success": False, "error": f"An unexpected server error occurred: {e}"}), 500
    finally:
        # ตรวจสอบว่า conn ถูกสร้างสำเร็จก่อนปิด
        if conn and conn.is_connected():
            cursor.close()
            conn.close()


if __name__ == "__main__":
    app.run(debug=True)