<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/10/14 17:47
 */

namespace Wmoob;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

trait BuilderTrait
{
    abstract public function getDriver(): ClientDecorator;

    abstract protected function pathname(string $separator = '/'): string;

    public function get(array $options = []): ResponseInterface
    {
        return $this->getDriver()->request('GET', $this->pathname(), $options);
    }

    public function put(array $options = []): ResponseInterface
    {
        return $this->getDriver()->request('PUT', $this->pathname(), $options);
    }

    public function post(array $options = []): ResponseInterface
    {
        return $this->getDriver()->request('POST', $this->pathname(), $options);
    }

    public function patch(array $options = []): ResponseInterface
    {
        return $this->getDriver()->request('PATCH', $this->pathname(), $options);
    }

    public function delete(array $options = []): ResponseInterface
    {
        return $this->getDriver()->request('DELETE', $this->pathname(), $options);
    }

    public function getAsync(array $options = []): PromiseInterface
    {
        return $this->getDriver()->requestAsync('GET', $this->pathname(), $options);
    }

    public function putAsync(array $options = []): PromiseInterface
    {
        return $this->getDriver()->requestAsync('PUT', $this->pathname(), $options);
    }

    public function postAsync(array $options = []): PromiseInterface
    {
        return $this->getDriver()->requestAsync('POST', $this->pathname(), $options);
    }

    public function patchAsync(array $options = []): PromiseInterface
    {
        return $this->getDriver()->requestAsync('PATCH', $this->pathname(), $options);
    }

    public function deleteAsync(array $options = []): PromiseInterface
    {
        return $this->getDriver()->requestAsync('DELETE', $this->pathname(), $options);
    }
}