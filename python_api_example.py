"""
SafeNest Python AI Service Example
This is an example Python Flask API that can be integrated with Laravel PHP application.

Installation:
    pip install flask flask-cors scikit-learn pandas numpy

Run:
    python python_api_example.py
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np
import pandas as pd
from datetime import datetime
import os

app = Flask(__name__)
CORS(app)  # Allow cross-origin requests from Laravel

# API Key for security
API_KEY = os.getenv('PYTHON_API_KEY', 'your-secret-key-here')


def verify_api_key():
    """Verify API key from request headers"""
    auth_header = request.headers.get('Authorization', '')
    if not auth_header.startswith('Bearer '):
        return False
    token = auth_header.replace('Bearer ', '')
    return token == API_KEY


@app.route('/recommendations', methods=['POST'])
def get_recommendations():
    """AI Product Recommendations using Collaborative & Content-Based Filtering"""
    if not verify_api_key():
        return jsonify({'error': 'Unauthorized'}), 401

    data = request.json
    user_id = data.get('user_id')
    context = data.get('context', {})

    # Example: Collaborative Filtering
    # In real implementation, you'd load user purchase history from database
    # and calculate similarity with other users

    # Example: Content-Based Filtering
    property_type = context.get('property_type', '')
    property_size = context.get('property_size', 0)

    # Simple recommendation logic (replace with actual ML model)
    recommendations = []

    if property_type in ['apartment', 'condo']:
        recommendations = [1, 2, 3, 4, 5]  # Indoor cameras, smart locks
    elif property_type in ['house', 'villa']:
        recommendations = [6, 7, 8, 9, 10]  # Outdoor cameras, alarm systems
    else:
        recommendations = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]  # All products

    return jsonify({
        'success': True,
        'product_ids': recommendations,
        'algorithm': 'hybrid',
        'confidence': 0.85
    })


@app.route('/risk-analyzer', methods=['POST'])
def analyze_risk():
    """AI Security Risk Analyzer - Classification & Scoring Algorithm"""
    if not verify_api_key():
        return jsonify({'error': 'Unauthorized'}), 401

    data = request.json
    input_data = data.get('input', {})

    property_type = input_data.get('property_type', '')
    property_size = int(input_data.get('property_size', 0))
    occupancy_pattern = input_data.get('occupancy_pattern', 'unknown')
    neighborhood_profile = input_data.get('neighborhood_profile', 'unknown')
    entry_points = int(input_data.get('entry_points', 0))
    exit_points = int(input_data.get('exit_points', 0))
    has_security_system = bool(input_data.get('has_security_system', False))
    previous_incidents = bool(input_data.get('previous_incidents', False))

    # Calculate risk score (0-100, higher = more secure)
    score = 50.0  # Base score

    # Property type scoring
    property_scores = {
        'apartment': 10, 'condo': 10,
        'house': 5, 'villa': 5,
        'townhouse': 8, 'commercial': 3
    }
    score += property_scores.get(property_type, 0)

    # Property size scoring
    if property_size < 500:
        score += 5
    elif property_size > 2000:
        score -= 10

    # Occupancy pattern scoring
    occupancy_scores = {
        'always_occupied': 15,
        'mostly_occupied': 10,
        'partially_occupied': 5,
        'rarely_occupied': -10,
        'vacant': -20
    }
    score += occupancy_scores.get(occupancy_pattern, 0)

    # Neighborhood profile scoring
    neighborhood_scores = {
        'very_safe': 15,
        'safe': 10,
        'moderate': 0,
        'risky': -15,
        'high_crime': -25
    }
    score += neighborhood_scores.get(neighborhood_profile, 0)

    # Entry/exit points penalty
    total_points = entry_points + exit_points
    if total_points > 6:
        score -= 15
    elif total_points > 4:
        score -= 10
    elif total_points > 2:
        score -= 5

    # Security system bonus
    if has_security_system:
        score += 15

    # Previous incidents penalty
    if previous_incidents:
        score -= 20

    # Normalize to 0-100
    score = max(0, min(100, score))

    # Determine risk level
    if score >= 80:
        risk_level = 'low'
    elif score >= 60:
        risk_level = 'moderate'
    elif score >= 40:
        risk_level = 'high'
    else:
        risk_level = 'critical'

    # Generate recommendations
    recommendations = []
    if score < 40:
        recommendations.append({
            'priority': 'critical',
            'title': 'Immediate Security Measures Required',
            'description': 'Install comprehensive security system immediately',
            'products': [1, 2, 3]
        })

    return jsonify({
        'success': True,
        'score': round(score, 2),
        'risk_level': risk_level,
        'recommendations': recommendations,
        'analysis': {
            'score_breakdown': {
                'base_score': 50,
                'final_score': round(score, 2)
            },
            'risk_factors': [
                'Multiple entry points' if total_points > 4 else None,
                'High-risk neighborhood' if neighborhood_profile in ['risky', 'high_crime'] else None
            ]
        }
    })


@app.route('/fraud-detection/payment', methods=['POST'])
def detect_fraud():
    """AI Fraud Detection - Anomaly Detection (Supervised/Unsupervised Learning)"""
    if not verify_api_key():
        return jsonify({'error': 'Unauthorized'}), 401

    data = request.json
    amount = float(data.get('amount', 0))
    user_id = data.get('user_id')
    payment_method = data.get('payment_method', '')

    # Anomaly Detection Algorithm
    risk_score = 0.0
    flags = []
    reasons = []

    # Amount anomaly detection
    # In real implementation, you'd check against user's historical average
    if amount > 1000:
        risk_score += 30
        flags.append('unusual_amount')
        reasons.append('High-value transaction')

    # Payment method risk
    risky_methods = ['prepaid_card', 'gift_card', 'cryptocurrency']
    if payment_method in risky_methods:
        risk_score += 25
        flags.append('risky_payment_method')
        reasons.append('High-risk payment method')

    # Velocity check (would check database for recent orders)
    # For now, simplified logic
    if user_id is None:  # Guest checkout
        risk_score += 20
        flags.append('guest_checkout')
        reasons.append('Guest checkout has higher risk')

    # Determine risk level
    if risk_score >= 70:
        risk_level = 'critical'
    elif risk_score >= 50:
        risk_level = 'high'
    elif risk_score >= 30:
        risk_level = 'moderate'
    else:
        risk_level = 'low'

    return jsonify({
        'success': True,
        'risk_score': round(risk_score, 2),
        'risk_level': risk_level,
        'flags': flags,
        'reason': '; '.join(reasons),
        'metadata': {
            'amount': amount,
            'payment_method': payment_method
        }
    })


@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'healthy',
        'service': 'SafeNest AI Service',
        'timestamp': datetime.now().isoformat()
    })


if __name__ == '__main__':
    port = int(os.getenv('PORT', 5000))
    app.run(host='0.0.0.0', port=port, debug=True)

