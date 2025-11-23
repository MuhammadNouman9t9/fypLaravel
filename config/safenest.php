<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SafeNest Application Configuration
    |--------------------------------------------------------------------------
    |
    | Centralized configuration for SafeNest platform services. These values
    | draw from environment variables so production secrets remain outside
    | the repository. Update the corresponding SAFENEST_* variables in the
    | environment when wiring external services.
    |
    */

    'ai' => [
        'recommendation' => [
            'enabled' => (bool) env('SAFENEST_RECOMMENDER_ENABLED', true),
            'model' => env('SAFENEST_RECOMMENDER_MODEL', 'hybrid'),
        ],
        'risk_analyzer' => [
            'enabled' => (bool) env('SAFENEST_RISK_ANALYZER_ENABLED', true),
            'default_threshold' => (int) env('SAFENEST_RISK_THRESHOLD', 70),
        ],
        'fraud_detection' => [
            'enabled' => (bool) env('SAFENEST_FRAUD_ENABLED', true),
            'alert_channel' => env('SAFENEST_FRAUD_ALERT_CHANNEL', 'security'),
        ],
        'chatbot' => [
            'enabled' => (bool) env('SAFENEST_CHATBOT_ENABLED', true),
            'language' => env('SAFENEST_CHATBOT_LANGUAGE', 'en'),
        ],
    ],

    'security' => [
        'two_factor_enforced' => (bool) env('SAFENEST_TWO_FACTOR_ENFORCED', false),
        'session_timeout' => (int) env('SAFENEST_SESSION_TIMEOUT', 1800),
        'audit_log_channel' => env('SAFENEST_AUDIT_LOG_CHANNEL', 'stack'),
    ],

    'support' => [
        'expert_inbox_email' => env('SAFENEST_SUPPORT_INBOX'),
        'office_hours_timezone' => env('SAFENEST_SUPPORT_TIMEZONE', 'UTC'),
    ],

    'locales' => [
        'default' => env('APP_LOCALE', 'en'),
        'supported' => [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
        ],
    ],

    'timezones' => [
        'supported' => [
            'UTC',
            'America/New_York',
            'America/Chicago',
            'America/Denver',
            'America/Los_Angeles',
            'Europe/London',
            'Europe/Berlin',
            'Asia/Dubai',
            'Asia/Karachi',
            'Australia/Sydney',
        ],
    ],

];

