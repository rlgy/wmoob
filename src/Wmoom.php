<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/12/2 15:20
 */

namespace Wmoob;

use Wmoob\message\DispatcherDecoratorInterface;
use Wmoob\message\DispatcherTrait;
use Wmoob\message\MessageEventDispatcherInterface;
use Wmoob\message\SymfonyDispatcherDecorator;

/**
 * 微盟云消息
 */
class Wmoom implements MessageEventDispatcherInterface
{
    use DispatcherTrait;

    /**@var DispatcherDecoratorInterface */
    private $decorator;

    public function __construct(DispatcherDecoratorInterface $decorator = null)
    {
        $decorator === null ? $this->decorator = static::createSymfonyDispatcherDecorator() : $this->decorator = $decorator;
    }

    public function getDispatcher(): MessageEventDispatcherInterface
    {
        return $this->decorator->getDispatcher();
    }

    private static function createSymfonyDispatcherDecorator()
    {
        return new SymfonyDispatcherDecorator();
    }

}