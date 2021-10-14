<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/10/14 15:58
 */


namespace Wmoob;

use GuzzleHttp\ClientInterface;

/**
 * weimob cloud php skd
 */
class Wmoob extends \ArrayIterator implements Chainable
{
    use BuilderTrait;

    /**
     * 当前版本号
     */
    const LIBRARY_VERSION = '1.0.0';

    /**
     * @var \Wmoob\ClientDecorator
     */
    protected $client;

    public function __construct(array $array, ClientDecorator $client)
    {
        parent::__construct($array, self::STD_PROP_LIST | self::ARRAY_AS_PROPS);
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function chain($segment): Chainable
    {
        return $this->offsetGet($segment);
    }

    public function getDriver(): ClientDecorator
    {
        return $this->client;
    }

    protected function pathname(string $separator = '/'): string
    {
        return implode($separator, $this->simplized());
    }

    /**
     * Normalize the `$thing` by the rules: `PascalCase` -> `camelCase`
     *                                    & `camelCase` -> `camel-case`
     *                                    & `_dynamic_` -> `{dynamic}`
     *
     * @param string $thing - The string waiting for normalization
     *
     * @return string
     */
    protected function normalize(string $thing = ''): string
    {
        return preg_replace_callback_array([
                '#^[A-Z]#' => static function (array $piece): string {
                    return strtolower($piece[0]);
                },
                '#[A-Z]#' => static function (array $piece): string {
                    return '-' . strtolower($piece[0]);
                },
                '#^_(.*)_$#' => static function (array $piece): string {
                    return '{' . $piece[1] . '}';
                },
            ], $thing) ?? $thing;
    }

    /**
     * Only retrieve a copy array of the URI segments
     *
     * @return (string|int)[] - The URI segments array
     */
    protected function simplized(): array
    {
        return array_filter($this->getArrayCopy(), static function ($v) {
            return !($v instanceof Chainable);
        });
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($key): Chainable
    {
        if (!$this->offsetExists($key)) {
            $indices = $this->simplized();
            $indices[] = $this->normalize($key);
            $this->offsetSet($key, new self($indices, $this->getDriver()));
        }

        return parent::offsetGet($key);
    }
}