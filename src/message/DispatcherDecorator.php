<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/12/2 15:23
 */

namespace Wmoob\message;

abstract class DispatcherDecorator
{
    abstract function getDispatcher(): MessageEventDispatcherInterface;
}