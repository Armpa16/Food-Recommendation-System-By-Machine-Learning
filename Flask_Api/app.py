import decimal
from flask import Flask, request, jsonify
import mysql.connector
import joblib
import numpy as np
import pandas as pd
from flask_cors import CORS
from datetime import datetime


app = Flask(__name__)
CORS(app)

# โหลดโมเดล DecisionTree สำหรับทำนายประเภทอาหาร (โมเดลนี้ใช้สำหรับทำนายประเภทอาหารที่เหมาะสมกับผู้ใช้)
model = joblib.load('Flask_Api/food_recommendation_model.pkl')

# โหลดโมเดล RandomForest สำหรับทำนายประเภทอาหาร (โมเดลนี้ใช้สำหรับทำนายประเภทของอาหารแต่ละรายการ)
rf_model = joblib.load('Flask_Api/random_forest_model.pkl')

# โหลดเครื่องมือ preprocessing tools ที่ใช้คู่กับโมเดล RandomForest
scaler = joblib.load('Flask_Api/scaler.pkl')  # เครื่องมือสำหรับปรับสเกลข้อมูล
imputer = joblib.load('Flask_Api/imputer.pkl')  # เครื่องมือสำหรับจัดการกับข้อมูลที่หายไป (missing values)
label_encoder = joblib.load('Flask_Api/label_encoder.pkl')  # เครื่องมือสำหรับแปลงข้อมูลประเภทข้อความเป็นตัวเลข
category_encoder = joblib.load('Flask_Api/category_encoder.pkl') # เครื่องมือสำหรับแปลงหมวดหมู่อาหารเป็นตัวเลข

# กำหนดชื่อฟีเจอร์ (features) ที่โมเดล RandomForest ใช้ในการทำนาย
RF_MODEL_FEATURES = ['calories', 'protein', 'carbohydrate', 'sugar', 'fat', 'sodium',
                     'category_encoded', 'sugar_to_carb_ratio', 'fat_to_calorie_ratio']



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

# ฟังก์ชันทำนายประเภทอาหารด้วย ID ของอาหาร (ใช้โมเดล Random Forest)
def predict_food_type_by_id(food_id: int): # ควรใช้ food_id ให้สอดคล้องกับ schema
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    try:
        # ดึงข้อมูลอาหารจากฐานข้อมูล (ควรใช้ food_id)
        cursor.execute("SELECT * FROM food_menu WHERE food_id = %s", (food_id,))
        row = cursor.fetchone()

        if not row:
            return {'error': 'Food item not found'}

        df = pd.DataFrame([row])

        # แปลง category เป็นเลขตาม encoder ที่เทรนไว้
        df['category_encoded'] = category_encoder.transform(df[['category']])

        # คำนวณอัตราส่วนน้ำตาลต่อคาร์โบไฮเดรต (เพิ่ม 1e-8 เพื่อป้องกันการหารด้วยศูนย์)
        df['sugar_to_carb_ratio'] = df['sugar'] / (df['carbohydrate'] + 1e-8)
        # คำนวณอัตราส่วนไขมันต่อแคลอรี่ (เพิ่ม 1e-8 เพื่อป้องกันการหารด้วยศูนย์)
        df['fat_to_calorie_ratio'] = df['fat'] / (df['calories'] + 1e-8)

        # เลือกฟีเจอร์ที่ใช้ในการทำนาย
        X = df[RF_MODEL_FEATURES] # ใช้ Global RF_MODEL_FEATURES

        # จัดการข้อมูลที่หายไป (imputation) และปรับสเกลข้อมูล (scaling)
        X_imputed = imputer.transform(X)
        X_scaled = scaler.transform(X_imputed)

        # ทำนายประเภทอาหารด้วยโมเดล Random Forest
        y_pred = rf_model.predict(X_scaled)
        # แปลงผลการทำนาย (ตัวเลข) กลับเป็นชื่อประเภทอาหาร (ข้อความ)
        predicted_label = label_encoder.inverse_transform(y_pred.ravel()) # Added .ravel()

        return {
            'food_id': food_id,
            'food_name': row['food_name'], 
            'predicted_food_type': predicted_label[0]
        }

    except Exception as e:
        return {'error': str(e)}

    finally:
        if cursor: cursor.close()
        if conn and conn.is_connected(): conn.close()

# ฟังก์ชันช่วยในการทำนายประเภทอาหารสำหรับ DataFrame ของรายการอาหาร (ใช้โมเดล Random Forest)
def predict_food_types_for_dataframe(df: pd.DataFrame):
    """
    ทำนายประเภทอาหารสำหรับรายการอาหารหลายรายการที่อยู่ใน DataFrame
    โดยใช้โมเดล Random Forest ที่ฝึกไว้แล้ว
    DataFrame (df) ที่รับเข้ามาควรมีคอลัมน์ที่จำเป็นสำหรับ RF_MODEL_FEATURES

    คืนค่า:
        - numpy.ndarray ของชื่อประเภทอาหารที่ทำนายได้
        - pandas.Index ของแถวที่ผ่านการประมวลผลและใช้ในการทำนาย
    หากเกิดข้อผิดพลาด หรือไม่สามารถทำนายได้ จะคืนค่า (numpy.array([]), pandas.Index([]))
    """
    if df.empty:
        return np.array([]), pd.Index([])


    df_processed = df.copy()

    # คอลัมน์ที่ควรเป็นตัวเลขสำหรับโมเดล Random Forest
    numeric_cols_for_rf = ['calories', 'protein', 'carbohydrate', 'sugar', 'fat', 'sodium']
    for col in numeric_cols_for_rf:
        # แปลงคอลัมน์เป็นตัวเลข, ถ้าแปลงไม่ได้จะให้เป็น NaN (Not a Number)
        df_processed[col] = pd.to_numeric(df_processed[col], errors='coerce')
    
     # ลบแถวที่ข้อมูลตัวเลขที่จำเป็นสำหรับโมเดล RF เป็น NaN (หลังจากแปลงเป็นตัวเลขแล้ว)
    df_processed.dropna(subset=numeric_cols_for_rf, inplace=True)
    if df_processed.empty:
        print("ℹ️ DataFrame ว่างเปล่าหลังจากแปลงเป็นตัวเลขและลบ NaN สำหรับฟีเจอร์ RF")
        return np.array([]), pd.Index([])

    # แปลงคอลัมน์ 'category' (หมวดหมู่อาหาร)
    if 'category' not in df_processed.columns:
        print("⚠️ ไม่พบคอลัมน์ 'category' ใน DataFrame, คืนค่าการทำนายว่างเปล่า")
        return np.array([]), pd.Index([])
    
    # จัดการกับค่า NaN หรือ None ในคอลัมน์ 'category' ก่อนทำการ encode
    # โดยแทนที่ด้วย 'Unknown' หรือค่า default อื่นที่เหมาะสม
    df_processed['category'] = df_processed['category'].fillna('Unknown') # Or a more appropriate default
    
    try:
        # แปลง 'category' เป็นตัวเลขด้วย category_encoder
        df_processed['category_encoded'] = category_encoder.transform(df_processed[['category']])
    except Exception as e:
        # อาจเกิด error ถ้า category_encoder เจอหมวดหมู่ที่ไม่เคยเห็นตอนฝึกโมเดล
        # และไม่ได้ตั้งค่าให้จัดการกับ unknown values
        print(f"⚠️ เกิดข้อผิดพลาดระหว่างการ encode หมวดหมู่สำหรับ batch prediction: {e}. ตรวจสอบหมวดหมู่ที่ไม่รู้จัก")
        return np.array([]), pd.Index([])


    # สร้างฟีเจอร์ใหม่
    df_processed['sugar_to_carb_ratio'] = df_processed['sugar'] / (df_processed['carbohydrate'] + 1e-8)
    df_processed['fat_to_calorie_ratio'] = df_processed['fat'] / (df_processed['calories'] + 1e-8)

    # เลือกฟีเจอร์, จัดการข้อมูลที่หายไป (impute), และปรับสเกล (scale)
    # ตรวจสอบว่ามีฟีเจอร์ที่โมเดล RF ต้องการครบถ้วนหรือไม่
    missing_features = [f for f in RF_MODEL_FEATURES if f not in df_processed.columns]
    if missing_features:
        print(f"⚠️ ขาดฟีเจอร์สำหรับโมเดล RF: {missing_features}. คืนค่าการทำนายว่างเปล่า")
        return np.array([]), pd.Index([])
    X = df_processed[RF_MODEL_FEATURES]
    
    try:
        X_imputed = imputer.transform(X) # ใช้ imputer ที่โหลดไว้เพื่อจัดการกับข้อมูลที่หายไป
        X_scaled = scaler.transform(X_imputed) # ใช้ scaler ที่โหลดไว้เพื่อปรับสเกลข้อมูล
    except Exception as e:
        print(f"เกิดข้อผิดพลาดระหว่างการ imputation/scaling: {e}")
        return np.array([]), pd.Index([])

    if X_scaled.shape[0] == 0:  # ไม่มีข้อมูลเหลือให้ทำนาย
        print("ℹ️ ไม่มีข้อมูลสำหรับการทำนายหลังจากขั้นตอน preprocessing")
        return np.array([]), pd.Index([])

    # ทำนายผลด้วยโมเดล Random Forest
    y_pred_numeric = rf_model.predict(X_scaled)
    # แปลงผลการทำนาย (ตัวเลข) กลับเป็นชื่อประเภทอาหาร (ข้อความ)
    predicted_type_names = label_encoder.inverse_transform(y_pred_numeric.ravel()) # Added .ravel()
    # คืนค่าชื่อประเภทอาหารที่ทำนายได้ และ index ของแถวที่ผ่านการประมวลผล
    # เพื่อให้สามารถนำผลลัพธ์ไปเชื่อมโยงกับข้อมูลเดิมได้อย่างถูกต้อง
    return predicted_type_names, df_processed.index 

# ฟังก์ชันสำหรับแปลงอ็อบเจกต์ให้อยู่ในรูปแบบที่ปลอดภัยสำหรับ JSON
def convert_to_json_safe(obj):
    if isinstance(obj, list):
        # ถ้าเป็นลิสต์ ให้แปลงแต่ละไอเท็มในลิสต์ให้เป็น JSON-safe
        return [convert_to_json_safe(item) for item in obj]
    elif isinstance(obj, dict):
        # ถ้าเป็นดิกชันนารี ให้แปลงคีย์และค่าให้เป็น JSON-safe
        return {key: convert_to_json_safe(value) for key, value in obj.items()}
    elif hasattr(obj, 'item'):
        # ถ้าเป็น NumPy scalar หรือ PyTorch tensor ให้แปลงเป็น Python scalar
        return obj.item()
    else:
        # ถ้าไม่ใช่ประเภทข้างต้น, คืนค่าเดิม
        return obj


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
def get_related_food_types(predicted_food_type_code_from_user_model):
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
    return type_mapping.get(predicted_food_type_code_from_user_model, [0])

# ฟังก์ชันดึงข้อมูลผู้ใช้จากฐานข้อมูล
def get_user_data(username):
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="food_recommend_system"
        )
        cursor = conn.cursor(dictionary=True) 

        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_id_data = cursor.fetchone()

        if user_id_data:
            user_id = user_id_data['users_id'] # Access by column name
            cursor.execute(""" 
                SELECT p.age, p.weight, p.height, p.activity_level, p.bmi, p.diseases, p.gender, p.status_bmi, p.daily_calorie
                FROM users u 
                JOIN profiles p ON u.users_id = p.users_id 
                WHERE u.users_id = %s
            """, (user_id,))
            user_profile_data = cursor.fetchone()

            if user_profile_data:
                # ดึงข้อมูลผู้ใช้จากผลลัพธ์
                age, weight, height, activity_level, bmi, diseases, gender, status_bmi, daily_calorie = \
                    user_profile_data['age'], user_profile_data['weight'], user_profile_data['height'], user_profile_data['activity_level'], user_profile_data['bmi'], user_profile_data['diseases'], user_profile_data['gender'], user_profile_data['status_bmi'], user_profile_data['daily_calorie']


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

                # สร้าง dictionary ของข้อมูลผู้ใช้ที่พร้อมสำหรับโมเดล
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

    finally: # ปิดการเชื่อมต่อฐานข้อมูล
            if 'cursor' in locals() and cursor: cursor.close()
            if 'conn' in locals() and conn and conn.is_connected(): conn.close()

# ฟังก์ชันทำนายประเภทอาหาร (for user - Decision Tree model)
def predict_food_type(user_data_dict): 
    try:
        # เรียงลำดับฟีเจอร์ให้ตรงกับตอนฝึกโมเดล
        feature_names = ['Ages', 'Gender', 'Height', 'Weight', 'Bmi', 'Disease_Type', 'Activity_Level']
        input_data = [[
            user_data_dict['age'], user_data_dict['gender'], user_data_dict['height'], user_data_dict['weight'],
            user_data_dict['bmi'], user_data_dict['diseases'], user_data_dict['activity_level']
        ]]
        input_df = pd.DataFrame(input_data, columns=feature_names) # user_data should be user_data_dict

        predicted_food_type_code = model.predict(input_df)[0]
        print(f"✅ ประเภทอาหารที่โมเดルผู้ใช้ทำนาย (code): {predicted_food_type_code}")

        # แปลงค่าตัวเลขกลับเป็นข้อความ
        food_type_name = food_type_mapping.get(predicted_food_type_code, "สมดุล")
        return predicted_food_type_code, food_type_name

    except Exception as e:
        print(f"❌ Error in predict_food_type: {e}")
        return 0, "สมดุล"

# ฟังก์ชันดึงเมนูอาหาร ตามประเภทอาหารที่ทำนายจากโมเดล RF
def get_meal_set(user_predicted_food_type_name: str, target_calories: float):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    meal_set = {}
    meal_categories_to_find = ["อาหารจานหลัก", "ผลไม้", "ขนม", "เครื่องดื่ม", "ฟาสต์ฟู้ด"]
    remaining_calories = target_calories

    try:
        # ดึงรายการอาหารทั้งหมดจากฐานข้อมูล food_menu
        cursor.execute("""
            SELECT food_id, food_name, calories, protein, carbohydrate, sugar, fat, sodium, category, image_url, amount
            FROM food_menu
        """)
        all_foods_raw = cursor.fetchall()

        if not all_foods_raw:
            print("❌ No food items found in the database for get_meal_set.")
            return None

        all_foods_df = pd.DataFrame(all_foods_raw)
        # เพิ่มคอลัมน์ index เพื่อเก็บ index ต้นฉบับ
        all_foods_df['original_idx'] = all_foods_df.index


        # ทำนายประเภทอาหารสำหรับแต่ละรายการใน DataFrame
        rf_predictions_list, processed_indices = predict_food_types_for_dataframe(all_foods_df)
        processed_indices = pd.Index(processed_indices)  # ✅ แปลงให้อยู่ในรูปแบบ Index เพื่อใช้กับ .loc และ .empty ได้

        
         # ตรวจสอบว่ามีการทำนายหรือไม่
        if rf_predictions_list.size == 0: 
            print(f"ℹ️ RF Model did not return predictions for any food items (or an error occurred during prediction).")
            # กรณีไม่มีการทำนายหรือเกิดข้อผิดพลาดในการทำนาย
            all_foods_df['rf_predicted_food_type'] = pd.Series(dtype='object')
        else:
            # แปลงผลการทำนายเป็น Series และจัดเก็บในคอลัมน์ใหม่
            temp_prediction_col = pd.Series(index=all_foods_df.index, dtype='object')
            if not processed_indices.empty: # ตรวจสอบว่ามีการประมวลผลหรือไม่
                 temp_prediction_col.loc[processed_indices] = rf_predictions_list
            all_foods_df['rf_predicted_food_type'] = temp_prediction_col


        # กรองรายการอาหารที่ทำนายประเภทอาหารแล้ว
        matched_foods_df = all_foods_df[all_foods_df['rf_predicted_food_type'].isin(user_predicted_food_type_name)]


        if matched_foods_df.empty:
            print(f"❌ No food items found where RF model prediction ('{user_predicted_food_type_name}') matches for user.")
            return None

        # แสดงรายการอาหารที่ตรงกับประเภทที่ทำนาย
        for meal_category in meal_categories_to_find:
            # กรองรายการอาหารที่ตรงกับประเภทอาหารที่ต้องการ
            matched_foods_df['calories'] = pd.to_numeric(matched_foods_df['calories'], errors='coerce')
            
            candidate_meals_for_category = matched_foods_df[
                (matched_foods_df['category'] == meal_category) &
                (matched_foods_df['calories'] <= remaining_calories) &
                (matched_foods_df['calories'].notna()) # ตรวจสอบว่า calories ไม่เป็น NaN
            ]

            if not candidate_meals_for_category.empty:
                selected_meal_series = candidate_meals_for_category.sample(1).iloc[0]
                meal_details = {
                    'food_id': selected_meal_series['food_id'],
                    'food_name': selected_meal_series['food_name'],
                    'calories': float(selected_meal_series['calories']),
                    'protein': float(selected_meal_series.get('protein', 0)), # ใช้ get() เพื่อป้องกัน KeyError
                    'carbohydrate': float(selected_meal_series.get('carbohydrate', 0)),
                    'sugar': float(selected_meal_series.get('sugar',0)),
                    'fat': float(selected_meal_series.get('fat',0)),
                    'sodium': float(selected_meal_series.get('sodium',0)),
                    'category': selected_meal_series['category'],
                    'image_url': selected_meal_series.get('image_url', 'default-image.jpg'),
                    'amount': selected_meal_series.get('amount', '1 จาน')
                }
                meal_set[meal_category] = meal_details
                remaining_calories -= meal_details['calories']
                print(f"✅ Found meal for {meal_category}: {meal_details['food_name']} ({meal_details['calories']} kcal)")
            else:
                print(f"❌ No meal found for category: {meal_category} (user type: {user_predicted_food_type_name}, remaining cal: {remaining_calories:.2f})")
        
        if not meal_set:
            print(f"❌ No meals could be assembled for user type '{user_predicted_food_type_name}' with target calories {target_calories}.")
            return None
        
        print(f"✅ เมนูอาหารที่เลือก (user type: {user_predicted_food_type_name}):", meal_set)
        return meal_set

    except mysql.connector.Error as err:
        print(f"❌ MySQL Error in get_meal_set: {err}")
        return None
    except Exception as e:
        import traceback
        print(f"❌ Unexpected error in get_meal_set: {e}")
        traceback.print_exc()
        return None
    finally:
        if 'cursor' in locals() and cursor: cursor.close()
        if 'conn' in locals() and conn and conn.is_connected(): conn.close()


# API เเนะนำอาหาร
@app.route('/get_recommendations', methods=['POST'])
def get_recommendations():
    try:
        data = request.get_json()
        username = data.get('username')
    
        print(f"🔍 รับคำขอสำหรับผู้ใช้: {username}")  
        
        user_data_dict = get_user_data(username)
        if user_data_dict is None:
            print("❌ ไม่พบข้อมูลผู้ใช้")
            return jsonify({"error": "User data not found"}), 404

        daily_calorie = user_data_dict['daily_calorie']
        breakfast_calories = daily_calorie * 0.3
        lunch_calories = daily_calorie * 0.4
        dinner_calories = daily_calorie * 0.3

        food_type_code_user, food_type_name_user = predict_food_type(user_data_dict)
        print(f"💁 User's predicted food type (for meal selection): {food_type_name_user} (code: {food_type_code_user})")

        # แปลง code เป็นรหัสย่อย
        related_codes = get_related_food_types(food_type_code_user)
        related_types = [food_type_mapping[code] for code in related_codes]

        print(f"✅ ประเภทอาหารที่เกี่ยวข้อง (codes): {related_codes}")
        print(f"✅ ประเภทอาหารสำหรับค้นหา: {related_types}")

        # ส่งรายการอาหารทั้งหมดที่กรองแล้ว ไปให้ get_meal_set เลือกตามปริมาณพลังงาน
        breakfast = get_meal_set(related_types, breakfast_calories)
        lunch = get_meal_set(related_types, lunch_calories)
        dinner = get_meal_set(related_types, dinner_calories)

        error_meals = []
        if not breakfast: error_meals.append("breakfast")
        if not lunch: error_meals.append("lunch")
        if not dinner: error_meals.append("dinner")

        if error_meals:
            print(f"❌ ไม่สามารถดึงเมนูอาหารที่เหมาะสมได้สำหรับ: {', '.join(error_meals)}")
            return jsonify({"error": f"ไม่สามารถดึงข้อมูลแนะนำอาหารได้ครบทุกมื้อ (ขาด: {', '.join(error_meals)})"}), 500
        
        # แปลงข้อมูลที่ได้เป็น JSON-safe
        response_data = convert_to_json_safe({
            "daily_calorie": daily_calorie,
            "food_type": food_type_name_user,
            "food_type_code": food_type_code_user,
            "meals": {
                "breakfast": breakfast,
                "lunch": lunch,
                "dinner": dinner
            }
        })

        # แสดงผลลัพธ์ที่ได้
        print("✅ JSON Response:", response_data)
        response = jsonify(response_data)
        response.headers['Content-Type'] = 'application/json; charset=utf-8'
        return response, 200

    except Exception as e:
        import traceback
        print(f"❌ Error in get_recommendations: {e}")
        traceback.print_exc()
        return jsonify({"error": str(e)}), 500

    

# API ดึงรายการอาหารที่แนะนำ/เหมาะสม
@app.route('/get_food_list', methods=['POST'])
def get_food_list():
    try:
        data = request.get_json()
        username = data.get('username')
        if not username:
            return jsonify({"error": "Username ไม่ได้ส่งมา"}), 400
        
        # ทำนายประเภทอาหารของผู้ใช้
        user_data_dict = get_user_data(username)
        if user_data_dict is None:
            return jsonify({"error": "ไม่พบข้อมูลผู้ใช้"}), 404

        food_type_code_user, food_type_name_user = predict_food_type(user_data_dict)
        related_food_type_codes = get_related_food_types(food_type_code_user)
        related_food_type_names = [food_type_mapping[code] for code in related_food_type_codes]
        
        # ดึงอาหารทั้งหมดจากฐานข้อมูล (หรือเฉพาะบางหมวดก็ได้)
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM food_menu")
        meals = cursor.fetchall()
        if cursor: cursor.close()
        if conn and conn.is_connected(): conn.close()

        df_meals = pd.DataFrame(meals)
        predicted_types, valid_indices = predict_food_types_for_dataframe(df_meals)

        # จัดกลุ่มเมนูที่มีประเภทตรงกับผู้ใช้
        matched_meals = []
        for idx, pred_type in zip(valid_indices, predicted_types):
            if pred_type in related_food_type_names:
                matched_meals.append(meals[idx])

        response_data = {
            "user_predicted_food_type_name": food_type_name_user,
            "user_predicted_food_type_code": int(food_type_code_user),
            "queried_based_on_type_names": related_food_type_names,
            "meals": matched_meals
        }
        return jsonify(response_data), 200, {'Content-Type': 'application/json; charset=utf-8'}

    except Exception as e:
        print(f"❌ Error in get_food_list: {e}")
        return jsonify({"error": str(e)}), 500

    
# API ดึงรายการอาหารที่ไม่เหมาะสม
@app.route('/get_unsuitable_food_list', methods=['POST'])
def get_unsuitable_food_list():
    try:
        data = request.get_json()
        username = data.get('username')
        if not username:
            return jsonify({"error": "Username ไม่ได้ส่งมา"}), 400

        user_data_dict = get_user_data(username)
        if user_data_dict is None:
            return jsonify({"error": "ไม่พบข้อมูลผู้ใช้"}), 404

        predicted_code, predicted_name = predict_food_type(user_data_dict)
        suitable_codes = get_related_food_types(predicted_code)
        suitable_names = [food_type_mapping[c] for c in suitable_codes]

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM food_menu")
        meals = cursor.fetchall()
        if cursor: cursor.close()
        if conn and conn.is_connected(): conn.close()

        df_meals = pd.DataFrame(meals)
        predicted_types, valid_indices = predict_food_types_for_dataframe(df_meals)

        # ดึงรายการอาหารที่ประเภทไม่อยู่ใน suitable_names
        unmatched_meals = []
        for idx, pred_type in zip(valid_indices, predicted_types):
            if pred_type not in suitable_names:
                unmatched_meals.append(meals[idx])

        response_data = {
            "user_predicted_suitable_type_name": predicted_name,
            "user_predicted_suitable_type_codes": suitable_codes,
            "excluded_type_names": suitable_names,
            "meals": unmatched_meals
        }
        return jsonify(response_data), 200, {'Content-Type': 'application/json; charset=utf-8'}

    except Exception as e:
        print(f"❌ Error in get_unsuitable_food_list: {e}")
        return jsonify({"error": str(e)}), 500

     
# API บันทึกอาหาร
@app.route('/save_meals', methods=['POST'])
def save_meals():
    try:
        # รับข้อมูล JSON จาก request
        data = request.json
        username = data.get('username')
        meals_data = data.get('meals', {})
        date = data.get('date', datetime.now().strftime('%Y-%m-%d'))

        # ตรวจสอบข้อมูลเบื้องต้น
        if not username:
            return jsonify({'error': 'กรุณาระบุชื่อผู้ใช้'}), 400
        if not meals_data:
            return jsonify({'error': 'ไม่พบข้อมูลอาหารที่ต้องการบันทึก'}), 400

        # เชื่อมต่อกับฐานข้อมูล
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        # ตรวจสอบว่าผู้ใช้มีอยู่ในฐานข้อมูลหรือไม่
        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()
        if not user_result:
            if cursor: cursor.close()
            if conn and conn.is_connected(): conn.close()
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404
        user_id = user_result['users_id']

        meals_saved_count = 0
        # เริ่มต้นวนลูปเพื่อบันทึกข้อมูลอาหาร
        for meal_type, foods_in_meal_type in meals_data.items(): # 
            if not isinstance(foods_in_meal_type, list): # Ensure it's a list of food items
                print(f"⚠️ Skipping meal_type '{meal_type}' as its value is not a list: {foods_in_meal_type}")
                continue
            # วนลูปใน list ของรายการอาหาร (food_item)
            for food_item in foods_in_meal_type:
                if not isinstance(food_item, dict): 
                    print(f"⚠️ Skipping food_item as it's not a dictionary: {food_item} in meal_type '{meal_type}'")
                    continue
                
                # ดึงข้อมูลของอาหารแต่ละรายการ
                food_id = food_item.get('food_id') 
                food_name = food_item.get('food_name')
                calories = food_item.get('calories', 0)
                protein = food_item.get('protein', 0)
                carbohydrate = food_item.get('carbohydrate', 0)
                # amount = food_item.get('amount', '1 จาน') 
                image_url = food_item.get('image_url') 
                
                db_food_id_to_log = None

                # ตรวจสอบ food_id ว่ามีอยู่ในฐานข้อมูลหรือไม่
                if food_id and not str(food_id).startswith('temp-'):
                    cursor.execute("SELECT food_id FROM food_menu WHERE food_id = %s", (food_id,))
                    food_exists = cursor.fetchone()
                    if food_exists:
                        db_food_id_to_log = food_id
                    else: # food_id ไม่พบในฐานข้อมูล
                        print(f"⚠️ food_id {food_id} ที่ส่งมาไม่พบใน food_menu. จะพยายามเพิ่มด้วยชื่อถ้ามี.")
                
                # ถ้า food_id ไม่ถูกต้องหรือไม่พบในฐานข้อมูล
                if not db_food_id_to_log: # food_id ไม่พบหรือไม่ถูกต้อง
                    if not food_name:
                        print(f"⚠️ Skipping food item due to missing food_name and unresolved food_id: {food_item}")
                        continue
                    
                    cursor.execute("SELECT food_id FROM food_menu WHERE food_name = %s", (food_name,))
                    existing_food_by_name = cursor.fetchone()
                    if existing_food_by_name:
                        db_food_id_to_log = existing_food_by_name['food_id']
                    else:
                        # กรณีเป็นอาหารใหม่: เพิ่มรายการอาหารนี้เข้าไปในตาราง food_menu
                        # ต้องแน่ใจว่ามีข้อมูลที่จำเป็นครบถ้วนสำหรับตาราง food_menu
                        insert_query = """
                            INSERT INTO food_menu (food_name, calories, protein, carbohydrate, image_url)
                            VALUES (%s, %s, %s, %s, %s)
                        """
                        insert_values = (food_name, calories, protein, carbohydrate, image_url)
                        try:
                            cursor.execute(insert_query, insert_values)
                            db_food_id_to_log = cursor.lastrowid
                            print(f"✅ Inserted new food: '{food_name}' with food_id: {db_food_id_to_log}")
                        except mysql.connector.Error as insert_err:
                            print(f"❌ Error inserting new food '{food_name}': {insert_err}")
                            continue # Skip this food item

                # ถ้าได้ food_id ที่ถูกต้อง บันทึกข้อมูลอาหารลงใน food_history
                if db_food_id_to_log:
                    try:
                        cursor.execute("""
                            INSERT INTO food_history (users_id, food_id, his_date, meal_type)
                            VALUES (%s, %s, %s, %s)
                        """, (user_id, db_food_id_to_log, date, meal_type))
                        meals_saved_count += 1
                    except mysql.connector.Error as hist_err:
                        print(f"❌ Error inserting into food_history for food_id {db_food_id_to_log}: {hist_err}")
                else:
                    print(f"⚠️ Could not determine food_id for item: {food_item}, not saved to history.")


        conn.commit()
        return jsonify({
            'message': f'บันทึกข้อมูลอาหารสำเร็จ {meals_saved_count} รายการ',
            'success': True,
            'meals_saved': meals_saved_count
        }), 200

    except Exception as e:
        if 'conn' in locals() and conn.is_connected(): conn.rollback() # Rollback on general error
        print(f"❌ Error in save_meals: {e}")
        import traceback
        traceback.print_exc()
        return jsonify({'error': str(e), 'message': 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'}), 500
    finally:
        if 'cursor' in locals() and cursor: cursor.close()
        if 'conn' in locals() and conn and conn.is_connected(): conn.close()



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

        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()

        if not user_result:
            if cursor: cursor.close()
            if conn and conn.is_connected(): conn.close()
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404
        user_id = user_result['users_id']

        cursor.execute("SELECT COUNT(*) as count FROM food_history WHERE users_id = %s AND his_date = %s", (user_id, date))
        result = cursor.fetchone()
        has_meals = result['count'] > 0
        
        return jsonify({'has_meals': has_meals}), 200
    except Exception as e:
        return jsonify({'error': str(e)}), 500
    finally:
        if 'cursor' in locals() and cursor: cursor.close()
        if 'conn' in locals() and conn and conn.is_connected(): conn.close()

    

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

        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()
        if not user_result:
            if cursor: cursor.close()
            if conn and conn.is_connected(): conn.close()
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404
        user_id = user_result['users_id']

        # Join with food_menu to get all details
        cursor.execute("""
            SELECT fh.meal_type, fm.food_id, fm.food_name, fm.calories, fm.protein, 
                   fm.carbohydrate, fm.sugar, fm.fat, fm.sodium, fm.image_url, fm.amount,
                   fm.category
            FROM food_history fh
            JOIN food_menu fm ON fh.food_id = fm.food_id
            WHERE fh.users_id = %s AND fh.his_date = %s
        """, (user_id, date))
        results = cursor.fetchall()

        meals_by_type = {} # Renamed variable
        for row in results:
            meal_type = row['meal_type']
            if meal_type not in meals_by_type:
                meals_by_type[meal_type] = []
            
            # Convert Decimal to float for relevant fields
            food_item_details = {}
            for key, value in row.items():
                if isinstance(value, decimal.Decimal):
                    food_item_details[key] = float(value)
                else:
                    food_item_details[key] = value
            
            # Ensure all expected keys are present, providing defaults if necessary
            # The query already selects all necessary fields from food_menu
            meals_by_type[meal_type].append({
                'food_id': food_item_details['food_id'],
                'food_name': food_item_details['food_name'],
                'calories': food_item_details.get('calories', 0.0),
                'protein': food_item_details.get('protein', 0.0),
                'carbohydrate': food_item_details.get('carbohydrate', 0.0),
                'sugar': food_item_details.get('sugar', 0.0),
                'fat': food_item_details.get('fat', 0.0),
                'sodium': food_item_details.get('sodium', 0.0),
                'amount': food_item_details.get('amount', '1 จาน'),
                'image_url': food_item_details.get('image_url', 'default-image.jpg'),
                'category': food_item_details.get('category'),
            })
        
        return jsonify({'meals': meals_by_type}), 200, {'Content-Type': 'application/json; charset=utf-8'}

    except Exception as e:
        print(f"❌ Error in get_saved_meals: {e}")
        import traceback
        traceback.print_exc()
        return jsonify({'error': str(e)}), 500
    finally:
        if 'cursor' in locals() and cursor: cursor.close()
        if 'conn' in locals() and conn and conn.is_connected(): conn.close()


# API อัพเดทข้อมูลอาหารที่บันทึกไว้
@app.route('/update_meals', methods=['POST'])
def update_meals():
    # ฟังก์ชันอัพเดทข้อมูลอาหารที่บันทึกไว้สำหรับผู้ใช้ในวันนั้นๆ
    try:
        data = request.json
        username = data.get('username')
        meals_data = data.get('meals', {}) 
        date = data.get('date', datetime.now().strftime('%Y-%m-%d'))

        if not username:
            return jsonify({'error': 'กรุณาระบุชื่อผู้ใช้'}), 400

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)

        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()
        if not user_result:
            if cursor: cursor.close()
            if conn and conn.is_connected(): conn.close()
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404
        user_id = user_result['users_id']

        # ลบข้อมูลเก่าใน food_history สำหรับผู้ใช้และวันที่ที่ระบุ
        try:
            cursor.execute("DELETE FROM food_history WHERE users_id = %s AND his_date = %s", (user_id, date))
            print(f"ℹ️ Deleted old meal history for user {user_id} on date {date}. Rows affected: {cursor.rowcount}")
        except mysql.connector.Error as del_err:
            conn.rollback()
            print(f"❌ Error deleting old meal history: {del_err}")
            return jsonify({'error': 'เกิดข้อผิดพลาดในการลบข้อมูลเก่า', 'details': str(del_err)}), 500
        
        # เริ่มต้นวนลูปเพื่อบันทึกข้อมูลอาหารใหม่
        meals_saved_count = 0
        if meals_data: # ตรวจสอบว่ามีข้อมูล meals_data หรือไม่
            for meal_type, foods_in_meal_type in meals_data.items():
                if not isinstance(foods_in_meal_type, list): continue
                for food_item in foods_in_meal_type:
                    if not isinstance(food_item, dict): continue
                    
                    food_id = food_item.get('food_id')
                    food_name = food_item.get('food_name')
                    calories = food_item.get('calories', 0)
                    protein = food_item.get('protein', 0)
                    carbohydrate = food_item.get('carbohydrate', 0)
                    image_url = food_item.get('image_url')

                    db_food_id_to_log = None
                    if food_id and not str(food_id).startswith('temp-'):
                        cursor.execute("SELECT food_id FROM food_menu WHERE food_id = %s", (food_id,))
                        if cursor.fetchone():
                            db_food_id_to_log = food_id
                    
                    if not db_food_id_to_log:
                        if not food_name: continue
                        cursor.execute("SELECT food_id FROM food_menu WHERE food_name = %s", (food_name,))
                        existing_food_by_name = cursor.fetchone()
                        if existing_food_by_name:
                            db_food_id_to_log = existing_food_by_name['food_id']
                        else:
                            # กรณีเป็นอาหารใหม่: เพิ่มรายการอาหารนี้เข้าไปในตาราง food_menu
                            try:
                                cursor.execute("""
                                    INSERT INTO food_menu (food_name, calories, protein, carbohydrate, image_url)
                                    VALUES (%s, %s, %s, %s, %s)
                                """, (food_name, calories, protein, carbohydrate, image_url))
                                db_food_id_to_log = cursor.lastrowid
                            except mysql.connector.Error as insert_err:
                                print(f"❌ Error inserting new food '{food_name}' during update: {insert_err}")
                                continue 

                    if db_food_id_to_log:
                        try:
                            cursor.execute("""
                                INSERT INTO food_history (users_id, food_id, his_date, meal_type)
                                VALUES (%s, %s, %s, %s)
                            """, (user_id, db_food_id_to_log, date, meal_type))
                            meals_saved_count += 1
                        except mysql.connector.Error as hist_err:
                             print(f"❌ Error inserting into food_history for food_id {db_food_id_to_log} during update: {hist_err}")

        conn.commit()
        return jsonify({
            'message': f'อัพเดทข้อมูลอาหารสำเร็จ {meals_saved_count} รายการ',
            'success': True,
            'meals_saved': meals_saved_count
        }), 200

    except Exception as e:
        if 'conn' in locals() and conn.is_connected(): conn.rollback()
        print(f"❌ Error in update_meals: {e}")
        import traceback
        traceback.print_exc()
        return jsonify({'error': str(e), 'message': 'เกิดข้อผิดพลาดในการอัพเดทข้อมูล'}), 500
    finally:
        if 'cursor' in locals() and cursor: cursor.close()
        if 'conn' in locals() and conn and conn.is_connected(): conn.close()
        

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

        cursor.execute("SELECT users_id FROM users WHERE username = %s", (username,))
        user_result = cursor.fetchone()
        if not user_result:
            if cursor: cursor.close()
            if conn and conn.is_connected(): conn.close()
            return jsonify({'error': 'ไม่พบผู้ใช้'}), 404
        user_id = user_result['users_id']
        
        cursor.execute("SELECT daily_calorie FROM profiles WHERE users_id = %s", (user_id,))
        profile_result = cursor.fetchone() # Renamed
        if not profile_result or profile_result['daily_calorie'] is None:
            if cursor: cursor.close()
            if conn and conn.is_connected(): conn.close()
            return jsonify({'error': 'ไม่พบข้อมูลแคลอรี่รายวันสำหรับผู้ใช้ หรือค่าเป็น NULL'}), 404
        daily_calorie = float(profile_result['daily_calorie']) # Ensure float

        cursor.execute("""
            SELECT his_date, SUM(fm.calories) as total_calories
            FROM food_history fh
            JOIN food_menu fm ON fh.food_id = fm.food_id
            WHERE fh.users_id = %s AND fh.his_date BETWEEN %s AND %s
            GROUP BY his_date
        """, (user_id, start_date, end_date))
        results = cursor.fetchall()

        meal_status_by_date = {} # Renamed
        for row in results:
            # รวบรวมข้อมูลแคลอรี่ทั้งหมดในวันนั้น
            total_calories = float(row['total_calories']) if row['total_calories'] is not None else 0.0
            
            lower_bound = daily_calorie * 0.9
            upper_bound = daily_calorie * 1.1
            status = 'perfect'
            if total_calories > upper_bound: status = 'over'
            elif total_calories < lower_bound: status = 'under'
            
            # บันทึกสถานะมื้ออาหาร
            meal_status_by_date[row['his_date'].strftime('%Y-%m-%d')] = {
                'total_calories': total_calories,
                'status': status
            }
        
        return jsonify({'mealStatus': meal_status_by_date}), 200

    except Exception as e:
        print(f"❌ Error in get_meal_status_for_month: {e}")
        import traceback
        traceback.print_exc()
        return jsonify({'error': str(e)}), 500
    finally:
        if 'cursor' in locals() and cursor: cursor.close()
        if 'conn' in locals() and conn and conn.is_connected(): conn.close()
    
    
# API ดึงโภชนาการเเละข้อมูลอาหาร
@app.route('/get_food_details', methods=['POST'])
def get_food_details():
    data = request.get_json()
    food_id = data.get('food_id')

    if not food_id:
        return jsonify({"success": False, "error": "Missing food_id"}), 400

    conn = get_db_connection()
    if not conn:
         return jsonify({"success": False, "error": "Database connection failed"}), 500

    cursor = conn.cursor(dictionary=True)

    try:
        query = """
            SELECT food_id, food_name, calories, protein, fat, carbohydrate, sugar, sodium, image_url, amount, category
            FROM food_menu 
            WHERE food_id = %s
        """
        cursor.execute(query, (food_id,))
        food_details_row = cursor.fetchone() # Renamed

        if food_details_row:
            processed_food_details = {}
            numeric_fields = ['calories', 'protein', 'fat', 'carbohydrate', 'sugar', 'sodium']
            for key, value in food_details_row.items():
                if isinstance(value, decimal.Decimal):
                    processed_food_details[key] = float(value)
                elif key in numeric_fields and value is None: # Handle None for numeric fields
                    processed_food_details[key] = 0.0 
                elif value is None: # Handle None for other (string) fields
                     processed_food_details[key] = "" # Or None, depending on frontend expectation
                else:
                    processed_food_details[key] = value
            
            # Ensure default for image_url if it was "" or None from DB
            if not processed_food_details.get('image_url'):
                processed_food_details['image_url'] = 'default-image.jpg'

            return jsonify({"success": True, "food_details": processed_food_details})
        else:
            return jsonify({"success": False, "error": "Food not found"}), 404

    except mysql.connector.Error as err:
        print(f"Database query error in get_food_details: {err}")
        return jsonify({"success": False, "error": f"Database query error: {err}"}), 500
    except Exception as e:
        print(f"Unexpected error in get_food_details: {e}")
        return jsonify({"success": False, "error": f"An unexpected server error occurred: {e}"}), 500
    finally:
        if 'cursor' in locals() and cursor: cursor.close()
        if 'conn' in locals() and conn and conn.is_connected(): conn.close()



if __name__ == "__main__":
    app.run(debug=True)