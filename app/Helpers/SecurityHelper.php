<?php

namespace App\Helpers;

class SecurityHelper
{
    /**
     * Sanitize input to prevent XSS
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Clean and validate email
     */
    public static function cleanEmail(string $email): string
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    /**
     * Clean and validate URL
     */
    public static function cleanUrl(string $url): string
    {
        return filter_var(trim($url), FILTER_SANITIZE_URL);
    }

    /**
     * Escape output for HTML
     */
    public static function escape(string $output): string
    {
        return htmlspecialchars($output, ENT_QUOTES, 'UTF-8', true);
    }

    /**
     * Validate and sanitize phone number
     */
    public static function sanitizePhone(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone);
    }
}


