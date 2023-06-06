<?php

namespace CQRS\Common\Domain\Helper;

class CommonService
{
    const CLI = 'cli';
    const PROD = 'prod';
    public static function getClientIpAdd()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        }
        return $ip;
    }

    public static function isProdEnv(): bool
    {
        return self::PROD === $_ENV['APP_ENV'];
    }

    public static function getClientDataFromServer(): array
    {
        return [
            '__ip' => self::getClientIpAdd(),
            '__software' => $_SERVER['SERVER_SOFTWARE'] ?? '',
            '__port' => $_SERVER['SERVER_PORT'] ?? '',
            '__language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '',
            '__https' => $_SERVER['HTTPS'] ?? '',
            '__user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];
    }
}