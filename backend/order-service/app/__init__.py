from flask import Flask
from .routes import order_bp

def create_app():
    app = Flask(__name__)
    app.register_blueprint(order_bp, url_prefix='/api')
    return app
