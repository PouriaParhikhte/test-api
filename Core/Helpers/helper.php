<?php

namespace Core\Helpers;

use Core\Configs;
use Core\Token;

class Helper
{
    public static function getMessage($message, $responseCode = 302): never
    {
        self::responseCode($responseCode);
        exit(json_encode($message));
    }

    public static function responseCode($responseCode = 200): self
    {
        http_response_code($responseCode);
        return new static;
    }

    public static function notFound(): never
    {
        header('HTTP/1.1 404 Not Found', true, 404);
        exit('<center><h1>404</h1><h2>Not Found!</h2></center>');
    }

    public static function log($logs = []): string
    {
        $logs = [
            'userIp' => $_SERVER['REMOTE_ADDR'],
            'url' => $_REQUEST['url'] ?? Configs::homePageUrl(),
            'responseCode' => http_response_code()
        ];
        if (isset($_COOKIE['message']))
            $logs['message'] = $_COOKIE['message'];
        return implode(',', $logs);
    }

    public static function arrayToString(array $input, $separator = ''): string
    {
        if ($input)
            return implode($separator, $input);
    }

    public static function replaceArrayValuesWithPlaceholder(array $input): array
    {
        $count = count($input);
        return array_fill(0, $count, '?');
    }

    public static function getRequestMethod(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function postRequestMethod(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function redirect($message = null, int $responseCode = 302, $index = 'message'): never
    {
        if ($message !== '')
            Token::generate([$index => $message]);
        $url = Configs::baseUrl() . Token::getUrl();
        header("location:$url", true, $responseCode);
        exit;
    }

    public static function redirectTo($url, $message = null, $responseCode = 302): never
    {
        if ($message !== '')
            Token::generate(['message' => $message]);
        $url = Configs::baseUrl() . $url;
        header("location:$url", true, $responseCode);
        exit;
    }

    public static function fetchMessage(string $index)
    {
        if (isset($_COOKIE['token']))
            return Token::decodePayload(Token::getPayload($_COOKIE['token']))->data->$index ?? null;
    }

    public static function replacePersianhNumbersWithEnglishNumbers(string &$input): void
    {
        $englishNumbers = range(0, 9);
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $input = str_replace($englishNumbers, $persianNumbers, $input);
    }

    public static function minifier($page)
    {
        $file = file($page);
        $file = array_map('trim', $file);
        $file = implode('', $file);
        $file = str_replace('<?php', '<?php ', $file);
        $page = substr($page, 0, -4);
        $page .= '.min.php';
        file_put_contents($page, $file);
    }

    public static function invalidRequest(): never
    {
        if (!Token::getUserId())
            Token::removeCookie('token');
        http_response_code(400);
        exit(json_encode('Invalid request!'));
    }

    public static function createPasswordHash(&$password): void
    {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }

    public static function passwordVerify($password, $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function showMessage($message): never
    {
        exit($message);
    }

    public static function getCookieValue($cookieName): string|null
    {
        if (array_key_exists($cookieName, $_COOKIE)) {
            $cookieValue = $_COOKIE[$cookieName];
            setcookie($cookieName, '', time() - 3600, '/');
            return $cookieValue;
        }
        return null;
    }

    public static function getRequestHeaders()
    {
        return getallheaders();
    }

    public static function getArrayKeysAsString(array $input, &$output, $separator = ','): self
    {
        $keys = array_keys($input);
        $output = self::arrayToString($keys, $separator);
        return new static;
    }

    public static function getArrayValuesAsString(array $input, &$output, $separator = ','): self
    {
        $placeholder = self::replaceArrayValuesWithPlaceholder($input);
        $output = self::arrayToString($placeholder, $separator);
        return new static;
    }

    public static function sendMessageOrRedirect($message, $responseCode, $cookieName = 'message'): never
    {
        $headers = self::getRequestHeaders();
        if (isset($headers['type']) && $headers['type'] === 'xhr') {
            http_response_code($responseCode);
            exit($message);
        }
        self::redirect($message, $responseCode, $cookieName);
    }
}
