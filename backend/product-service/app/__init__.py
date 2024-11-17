from flask import Flask
from .routes import product_bp

def create_app():
    app = Flask(__name__)
    app.register_blueprint(product_bp, url_prefix='/api')
    return app
