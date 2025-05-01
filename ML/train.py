# Re-run the ER diagram generation since execution state was reset
from graphviz import Digraph

# สร้าง ER Diagram
er = Digraph('ER Diagram', filename='er_diagram', format='png')
er.attr(rankdir='LR', size='10')

# Entities
er.node('Users', shape='box', label='Users\n(user_id, username, password, age, weight, height,\nactivity_level, chronic_disease, daily_calories)')
er.node('Food_Menu', shape='box', label='Food_Menu\n(food_id, food_name, calories, protein, carbs, sugar, fat, sodium, portion_size)')
er.node('Recommendations', shape='box', label='Recommendations\n(rec_id, user_id, date)')
er.node('Recommendation_Details', shape='box', label='Recommendation_Details\n(rec_detail_id, rec_id, meal_type, food_id)')
er.node('Food_History', shape='box', label='Food_History\n(history_id, user_id, date, meal_type, food_id, is_custom)')
er.node('Calendar_Status', shape='box', label='Calendar_Status\n(cal_id, user_id, date, status)')

# Relationships
er.edge('Users', 'Recommendations', label='1:N (user_id)')
er.edge('Recommendations', 'Recommendation_Details', label='1:N (rec_id)')
er.edge('Recommendation_Details', 'Food_Menu', label='N:1 (food_id)')

er.edge('Users', 'Food_History', label='1:N (user_id)')
er.edge('Food_History', 'Food_Menu', label='N:1 (food_id)')

er.edge('Users', 'Calendar_Status', label='1:N (user_id)')

# แสดงผล Diagram
er_path = '/mnt/data/er_diagram.png'
er.render(er_path, format='png', cleanup=False)
er_path

