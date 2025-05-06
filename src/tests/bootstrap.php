<?php

// Mock global WordPress functions for testing
if (!function_exists('get_userdata')) {
    function get_userdata($userId)
    {
        return (object) [
            'ID' => $userId,
            'display_name' => "TestUser{$userId}",
        ];
    }
}

if (!function_exists('current_time')) {
    function current_time($type = 'mysql', $gmt = 0): string
    {
        return '2025-04-20 12:00:00';
    }
}

if (!function_exists('__')) {
    function __($text, $domain = null)
    {
        return $text;
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain = null)
    {
        return $text;
    }
}
