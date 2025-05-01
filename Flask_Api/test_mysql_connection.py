import mysql.connector

try:
    conn = mysql.connector.connect(
        host="localhost",  # หรือ "127.0.0.1"
        user="root",
        password="",
        database="food_recommend_system"
    )
    print("Connected to MySQL successfully!")
    conn.close()
except mysql.connector.Error as err:
    print(f"Error: {err}")

