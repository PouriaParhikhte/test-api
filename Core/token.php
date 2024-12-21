<?php

namespace Core;

use Core\Helpers\Helper;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    public static function generate(array $input = null): void
    {
        if (Helper::getRequestMethod())
            $input['url'] = $_REQUEST['url'] ?? Configs::homePageUrl();
        $input['userId'] = $input['userId'] ?? self::getUserId();
        $input['roleId'] = $input['roleId'] ?? self::getRoleId();
        $payload = self::payload($input);
        $secretKey = self::secretKey($payload['iat']);
        $cookieValue = JWT::encode($payload, $secretKey, 'HS256');
        setcookie('token', $cookieValue, 0, '/');
    }

    private static function payload(array $input = null): array
    {
        $timestamp = microtime(true);
        $payload = [
            'iss' => Configs::baseUrl(),
            'iat' => $timestamp,
            'nbf' => $timestamp,
            'exp' => $timestamp + 86400,
        ];
        $input['csrf'] = self::csrfToken($timestamp);
        if (isset($input))
            $payload['data'] = $input;
        return $payload;
    }

    public static function getUserId(): int
    {
        return isset($_COOKIE['token']) ? self::decodePayload(self::getPayload($_COOKIE['token']))->data->userId : 0;
    }

    public static function getRoleId(): int
    {
        return isset($_COOKIE['token']) ? self::decodePayload(self::getPayload($_COOKIE['token']))->data->roleId : 0;
    }

    public static function getMessage($index): string|null
    {
        if (isset($_COOKIE['token']))
            return self::decodePayload(self::getPayload($_COOKIE['token']))->data->$index ?? '';
        return null;
    }

    public static function getUrl(): string
    {
        return isset($_COOKIE['token']) ? self::decodePayload(self::getPayload($_COOKIE['token']))->data->url : Configs::homePageUrl();
    }

    public static function csrfToken($timestamp): string
    {
        $csrfToken = md5($timestamp);
        setcookie('csrf', $csrfToken, 0, '/');
        return md5($csrfToken);
    }

    public static function checkCsrf(): void
    {
        $csrfToken = self::checkIfTokenExists()->decodePayload(self::getPayload($_COOKIE['token']))->data->csrf ?? null;
        if (!array_key_exists('csrf', $_COOKIE) || !hash_equals($csrfToken, md5($_COOKIE['csrf'])))
            self::invalidToken();
    }

    public static function secretKey($timestamp = null): string
    {
        if (Helper::postRequestMethod() && self::checkIfTokenExists())
            $iat = self::decodePayload(self::getPayload($_COOKIE['token']))->iat;
        return md5($iat ?? $timestamp);
    }

    public static function verify(): void
    {
        if (!self::verifyJwt())
            self::invalidToken();
    }

    public static function removeCookie(string $cookieName): void
    {
        if (isset($_COOKIE[$cookieName]))
            setcookie($cookieName, '', time() - 3600, '/');
    }

    public static function logout()
    {
        self::generate(['userId' => 0, 'roleId' => 0]);
        throw new Exception;
    }

    public static function removeDataFromToken()
    {
        $token = self::decodePayload(self::getPayload($_COOKIE['token']));
        $token->data = [];
        self::generate([]);
    }

    public static function checkIfTokenExists(): self
    {
        if (!isset($_COOKIE['token']))
            self::invalidToken();
        return new self;
    }

    public static function invalidToken()
    {
        self::removeCookie('token');
        http_response_code(401);
        exit('توکن نامعتبر!');
    }

    public static function getPayload($token): array
    {
        $jwt = explode('.', $token, 3);
        if (count($jwt) < 3)
            self::invalidToken();
        return $jwt;
    }

    public static function decodePayload($payload): mixed
    {
        $payload = base64_decode($payload[1]);
        return json_decode($payload);
    }

    public static function showErrorMessage($cookieName = 'message'): void
    {
        if (isset($_COOKIE[$cookieName])) {
            echo $_COOKIE[$cookieName];
            self::removeCookie($cookieName);
        }
    }

    private static function verifyJwt(): bool
    {
        try {
            $secretKey = self::secretKey();
            $jwt = JWT::decode($_COOKIE['token'], new Key($secretKey, 'HS256'));
            $token = JWT::encode((array)$jwt, $secretKey, 'HS256');
            return hash_equals($token, $_COOKIE['token']);
        } catch (Exception $exception) {
            if ($exception->getMessage() === 'Signature verification failed')
                exit('توکن نامعتبر!');
        }
    }
}
