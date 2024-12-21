<?php

namespace Core;

class Configs
{
    public static function hostName(): string
    {
        return '127.0.0.1';
    }

    public static function username(): string
    {
        return 'root';
    }

    public static function password(): string
    {
        return 'fhv9wRB6kCmxfeY4';
    }

    public static function database(): string
    {
        return 'api';
    }

    public static function charset(): string
    {
        return 'utf8mb4';
    }

    public static function domain(): string
    {
        return 'test-api/';
    }

    public static function baseUrl(): string
    {
        return "http://$_SERVER[HTTP_HOST]/" . self::domain();
    }

    public static function homePageUrl(): string
    {
        return 'home';
    }

    public static function perPage(): int
    {
        return 3;
    }
}
