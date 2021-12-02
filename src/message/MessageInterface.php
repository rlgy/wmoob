<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/12/2 17:02
 */

namespace Wmoob\message;

interface MessageInterface
{
    /**
     * @return \Wmoob\message\Message
     */
    public function getMessage(): Message;
}