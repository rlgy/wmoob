<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/12/2 14:07
 */

namespace Wmoob\message;

use Psr\EventDispatcher\StoppableEventInterface;

class MessageEvent implements StoppableEventInterface
{
    private $propagationStopped = false;

    /**@var \Wmoob\message\Message */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return \Wmoob\message\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    /**
     * Stops the propagation of the event to further event listeners.
     * If multiple event listeners are connected to the same event, no
     * further event listener will be triggered once any trigger calls
     * stopPropagation().
     */
    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }

}