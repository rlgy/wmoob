<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/22 16:02
 */

namespace Wmoob\listeners;

use Wmoob\Message;

abstract class AbstractListener implements ListenerInterface
{
    public function __invoke(Message $message): void
    {
        $this->handle($message);
    }
}