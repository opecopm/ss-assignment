from flask import Blueprint, request, jsonify
from bson import ObjectId
from pymongo.errors import PyMongoError
from marshmallow import Schema, fields, ValidationError
from .models import connect_to_db

db = connect_to_db()
order_bp = Blueprint('order', __name__)

# Helper function to convert MongoDB ObjectId to string
def serialize_order(order):
    order["_id"] = str(order["_id"])
    return order

# Define Marshmallow schema for validation
class OrderSchema(Schema):
    product_id = fields.String(required=True)
    quantity = fields.Integer(required=True)
    total_price = fields.Float(required=True)
    status = fields.String(required=True)
    customer = fields.Dict(required=True)  # Embedded customer data as a dictionary

order_schema = OrderSchema()
order_update_schema = OrderSchema(partial=True)  # Allow partial updates

@order_bp.route('/order', methods=['POST'])
def add_order():
    try:
        # Validate input using Marshmallow
        order = order_schema.load(request.json)
        
        # Insert the order (including customer data) into the database
        result = db.orders.insert_one(order)
        
        # Return the order with the new MongoDB _id
        order["_id"] = str(result.inserted_id)
        return jsonify(order), 201
    except ValidationError as err:
        return jsonify({"error": err.messages}), 400
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500

@order_bp.route('/order/<order_id>', methods=['PUT'])
def update_order(order_id):
    try:
        # Validate input for partial updates
        order = order_update_schema.load(request.json)
        
        # Update the order in the database
        result = db.orders.update_one(
            {"_id": ObjectId(order_id)}, {"$set": order}
        )
        
        # Handle case where order is not found
        if result.matched_count == 0:
            return jsonify({"error": "Order not found"}), 404
        
        return jsonify({"message": "Order updated successfully"}), 200
    except ValidationError as err:
        return jsonify({"error": err.messages}), 400
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500

@order_bp.route('/order/<order_id>', methods=['DELETE'])
def delete_order(order_id):
    try:
        result = db.orders.delete_one({"_id": ObjectId(order_id)})
        
        # Handle case where order is not found
        if result.deleted_count == 0:
            return jsonify({"error": "Order not found"}), 404
        
        return jsonify({"message": "Order deleted"}), 200
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500

@order_bp.route('/order/<order_id>', methods=['GET'])
def get_order_details(order_id):
    try:
        order = db.orders.find_one({"_id": ObjectId(order_id)})
        
        # Handle case where order is not found
        if not order:
            return jsonify({"error": "Order not found"}), 404
        
        return jsonify(serialize_order(order)), 200
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500

@order_bp.route('/orders', methods=['GET'])
def list_orders():
    try:
        orders = list(db.orders.find())
        return jsonify([serialize_order(order) for order in orders]), 200
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500
