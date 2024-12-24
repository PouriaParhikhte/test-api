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
            $input['url'] = $_GET['url'] ?? Configs::homePageUrl();
        $input['userId'] = $input['userId'] ?? self::fetchValueFromPayload('userId');
        $input['roleId'] = $input['roleId'] ?? self::fetchValueFromPayload('roleId');
        $payload = self::payload($input);
        $secretKey = self::secretKey($payload['iat']);
        $payload['csrf'] = md5($secretKey . $payload['iat']);
        $payload['ua'] = md5($_SERVER['HTTP_USER_AGENT'] . $payload['iat']);
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
        if (isset($input))
            $payload = array_merge($payload, $input);
        return $payload;
    }

    public static function fetchValueFromPayload($index)
    {
        if (array_key_exists('token', $_COOKIE))
            return self::decodePayload(self::getPayload($_COOKIE['token']))->$index ?? '';
    }

    public static function checkCsrf(): void
    {
        $csrf = self::fetchValueFromPayload('csrf');
        $iat = self::fetchValueFromPayload('iat');
        $token = md5(self::secretKey($iat) . $iat);
        if (!hash_equals($csrf, $token))
            self::invalidToken();
    }

    public static function secretKey($timestamp = null): string
    {
        if (Helper::postRequestMethod() && self::checkIfTokenExists())
            $timestamp = self::decodePayload(self::getPayload($_COOKIE['token']))->iat;
        return md5($timestamp);
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
            if ($jwt->ua !== md5($_SERVER['HTTP_USER_AGENT'] . $jwt->iat))
                self::invalidToken();
            $token = JWT::encode((array)$jwt, $secretKey, 'HS256');
            return hash_equals($token, $_COOKIE['token']);
        } catch (Exception $exception) {
            if ($exception->getMessage() === 'Signature verification failed')
                exit('توکن نامعتبر!');
        }
    }

    public static function invalidRequest(): never
    {
        if (!Token::fetchValueFromPayload('userId'))
            Token::removeCookie('token');
        http_response_code(400);
        exit(json_encode('Invalid request!'));
    }
}
