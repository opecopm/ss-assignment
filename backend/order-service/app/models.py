from pymongo import MongoClient

# Replace <username>, <password>, and <database> with your MongoDB Atlas details
client = MongoClient("mongodb+srv://admin:CRVkPnvOpjuAxmNv@cluster0.zz7t7bl.mongodb.net/order_service_db?retryWrites=true&w=majority")

# Use the correct database name as per your connection string
db = client['order_service_db']

def connect_to_db():
    return db
