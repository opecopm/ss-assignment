from flask import Blueprint, request, jsonify
from bson import ObjectId
from pymongo.errors import PyMongoError
from marshmallow import Schema, fields, ValidationError
from .models import connect_to_db

db = connect_to_db()

product_bp = Blueprint('product', __name__)

# Helper function to convert MongoDB ObjectId to string
def serialize_product(product):
    product["_id"] = str(product["_id"])
    return product

# Define Marshmallow schema for validation
class ProductSchema(Schema):
    name = fields.String(required=True)
    price = fields.Float(required=True)
    category = fields.String(required=False, allow_none=True)
    description = fields.String(required=False, allow_none=True)

product_schema = ProductSchema()
product_update_schema = ProductSchema(partial=True)  # Allow partial updates

@product_bp.route('/product', methods=['POST'])
def add_product():
    try:
        # Validate input using Marshmallow
        product = product_schema.load(request.json)
        result = db.products.insert_one(product)
        product["_id"] = str(result.inserted_id)
        return jsonify(product), 201
    except ValidationError as err:
        return jsonify({"error": err.messages}), 400
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500

@product_bp.route('/product/<product_id>', methods=['PUT'])
def update_product(product_id):
    try:
        # Validate input for partial updates
        product = product_update_schema.load(request.json)
        result = db.products.update_one(
            {"_id": ObjectId(product_id)}, {"$set": product}
        )
        if result.matched_count == 0:
            return jsonify({"error": "Product not found"}), 404
        return jsonify({"message": "Product updated successfully"}), 200
    except ValidationError as err:
        return jsonify({"error": err.messages}), 400
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500

@product_bp.route('/product/<product_id>', methods=['DELETE'])
def delete_product(product_id):
    try:
        result = db.products.delete_one({"_id": ObjectId(product_id)})
        if result.deleted_count == 0:
            return jsonify({"error": "Product not found"}), 404
        return jsonify({"message": "Product deleted"}), 200
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500

@product_bp.route('/product/<product_id>', methods=['GET'])
def get_product_details(product_id):
    try:
        product = db.products.find_one({"_id": ObjectId(product_id)})
        if not product:
            return jsonify({"error": "Product not found"}), 404
        return jsonify(serialize_product(product)), 200
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500

@product_bp.route('/products', methods=['GET'])
def list_products():
    try:
        products = list(db.products.find())
        return jsonify([serialize_product(product) for product in products]), 200
    except PyMongoError as e:
        return jsonify({"error": str(e)}), 500
