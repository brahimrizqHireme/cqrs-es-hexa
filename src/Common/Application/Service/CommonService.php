<?php

namespace CQRS\Common\Application\Service;

class CommonService
{
    const CLI = 'cli';
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

    public static function getClientDataFromServer(): array
    {
        return [
            'ip' => self::getClientIpAdd(),
            'software' => $_SERVER['SERVER_SOFTWARE'] ?? '',
            'port' => $_SERVER['SERVER_PORT'] ?? '',
            'language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '',
            'https' => $_SERVER['HTTPS'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];
    }
}