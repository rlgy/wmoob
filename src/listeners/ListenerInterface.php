<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/22 16:54
 */

namespace Wmoob\listeners;

use Wmoob\Message;

/**
 * 消息监听器接口
 */
interface ListenerInterface
{
    /**
     * @param \Wmoob\Message $message
     *
     * @return void
     */
    public function handle(Message $message): void;
}