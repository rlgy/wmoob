<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/10/14 17:46
 */

namespace Wmoob;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface Chainable
{
    /**
     * Chainable the given `$segment` with the `ClientDecoratorInterface` instance
     *
     * @param string|int $segment - The sgement or `URI`
     */
    public function chain($segment): Chainable;

    /**
     * Create and send an HTTP GET request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function get(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP PUT request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function put(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP POST request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function post(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP PATCH request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function patch(array $options = []): ResponseInterface;

    /**
     * Create and send an HTTP DELETE request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function delete(array $options = []): ResponseInterface;

    /**
     * Create and send an asynchronous HTTP GET request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function getAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP PUT request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function putAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP POST request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function postAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP PATCH request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function patchAsync(array $options = []): PromiseInterface;

    /**
     * Create and send an asynchronous HTTP DELETE request.
     *
     * @param array<string,string|int|bool|array|mixed> $options Request options to apply.
     */
    public function deleteAsync(array $options = []): PromiseInterface;
}