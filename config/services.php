<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'safenest' => [
        'python_api' => [
            'url' => env('SAFENEST_PYTHON_API_URL'),
            'key' => env('SAFENEST_PYTHON_API_KEY'),
            'timeout' => (int) env('SAFENEST_PYTHON_API_TIMEOUT', 10),
        ],
        'recommendation' => [
            'base_url' => env('SAFENEST_RECOMMENDER_URL'),
            'api_key' => env('SAFENEST_RECOMMENDER_KEY'),
            'timeout' => (int) env('SAFENEST_RECOMMENDER_TIMEOUT', 5),
        ],
        'risk_analyzer' => [
            'base_url' => env('SAFENEST_RISK_ANALYZER_URL'),
            'api_key' => env('SAFENEST_RISK_ANALYZER_KEY'),
            'timeout' => (int) env('SAFENEST_RISK_ANALYZER_TIMEOUT', 5),
        ],
        'fraud_detection' => [
            'base_url' => env('SAFENEST_FRAUD_URL'),
            'api_key' => env('SAFENEST_FRAUD_KEY'),
            'timeout' => (int) env('SAFENEST_FRAUD_TIMEOUT', 5),
        ],
        'chatbot' => [
            'provider' => env('SAFENEST_CHATBOT_PROVIDER', 'dialogflow'),
            'project_id' => env('SAFENEST_CHATBOT_PROJECT_ID'),
            'api_key' => env('SAFENEST_CHATBOT_API_KEY'),
            'base_url' => env('SAFENEST_CHATBOT_URL'),
            'timeout' => (int) env('SAFENEST_CHATBOT_TIMEOUT', 10),
        ],
    ],

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'log'),
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'from' => env('TWILIO_FROM_NUMBER'),
        ],
        'nexmo' => [
            'api_key' => env('NEXMO_API_KEY'),
            'api_secret' => env('NEXMO_API_SECRET'),
            'from' => env('NEXMO_FROM_NUMBER'),
        ],
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

];
