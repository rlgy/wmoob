<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/24 10:42
 */

namespace Wmoob\listeners;

use Wmoob\exceptions\NoListenerException;
use Wmoob\Message;

class NullListener
{
    /**
     * @throws \Wmoob\exceptions\NoListenerException
     */
    public function __invoke($message)
    {
        throw new NoListenerException();
    }

}