<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/10/14 16:52
 */

namespace Wmoob;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\UriTemplate\UriTemplate;
use Psr\Http\Message\ResponseInterface;

/**
 * Decorate the `GuzzleHttp\Client` instance
 */
class ClientDecorator
{
    /**
     * @var ClientInterface 客户端
     */
    protected $driver;

    /**
     * @var array
     */
    protected static $defaults = [
        'base_uri' => 'https://dopen.weimob.com/',
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json; charset=utf-8',
        ],
    ];

    /**
     * Deep merge the input with the defaults
     *
     * @param array<string,string|int|bool|array|mixed> $config - The configuration.
     *
     * @return array<string, string|mixed> - With the built-in configuration.
     */
    protected static function withDefaults(array ...$config): array
    {
        return array_replace_recursive(static::$defaults, ['headers' => static::userAgent()], ...$config);
    }

    /**
     * Prepare the `User-Agent` value key/value pair
     *
     * @return array<string, string>
     */
    protected static function userAgent(): array
    {
        return [
            'User-Agent' => implode(' ', [
                sprintf('wmoob/%s', Wmoob::LIBRARY_VERSION),
                sprintf('GuzzleHttp/%d', ClientInterface::MAJOR_VERSION),
                sprintf('curl/%s', ((array)call_user_func('\curl_version'))['version'] ?? 'unknown'),
                sprintf('(%s/%s)', PHP_OS, php_uname('r')),
                sprintf('PHP/%s', PHP_VERSION),
            ])
        ];
    }

    /**
     * Decorate the `GuzzleHttp\Client` factory
     * Acceptable \$config parameters stucture
     *   - mchid: string - The merchant ID
     *   - serial: string - The serial number of the merchant certificate
     *   - privateKey: \OpenSSLAsymmetricKey|\OpenSSLCertificate|object|resource|string - The merchant private key.
     *   - certs: array<string, \OpenSSLAsymmetricKey|\OpenSSLCertificate|object|resource|string> - The wechatpay
     * platform serial and certificate(s), `[$serial => $cert]` pair
     *   - secret?: string - The secret key string (optional)
     *   - merchant?: array{key?: string, cert?: string} - The merchant private key and certificate array. (optional)
     *   - merchant<?key, string|string[]> - The merchant private key(file path string). (optional)
     *   - merchant<?cert, string|string[]> - The merchant certificate(file path string). (optional)
     *
     * @param array<string,string|int|bool|array|mixed> $config - `\GuzzleHttp\Client`, `APIv3` and `APIv2`
     *     configuration settings.
     */
    public function __construct(array $config = [])
    {
        $this->driver = static::createApiClient($config);
    }

    /**
     * Identify the `protocol` and `uri`
     *
     * @param string $uri - The uri string.
     *
     * @return string
     */
    private static function prepare(string $uri): string
    {
        if (stripos($uri, 'fuwu/') === 0) {
            return $uri;
        }
        if (preg_match('#v1/|v2/#', $uri)) {
            return preg_replace(['#v1/#', '#v2/#'], ['api/1_0/', 'api/2_0/'], $uri);
        }
        if ($uri) {
            return 'api/1_0/' . $uri;
        }
        return '';
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        return $this->driver->request($method, UriTemplate::expand(static::prepare($uri), $options), $options);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function requestAsync(string $method, string $uri, array $options = []): PromiseInterface
    {
        return $this->driver->requestAsync($method, UriTemplate::expand(static::prepare($uri), $options), $options);
    }

    /**
     * 创建api请求的客户端
     *
     * @param array $config
     *
     * @return \GuzzleHttp\ClientInterface
     */
    private static function createApiClient(array $config): ClientInterface
    {
        $stack = isset($config['handler']) && ($config['handler'] instanceof HandlerStack) ? (clone $config['handler']) : HandlerStack::create();
        $config['handler'] = $stack;
        return new Client(static::withDefaults($config));
    }
}