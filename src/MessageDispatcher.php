<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/22 15:59
 */

namespace Wmoob;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Wmoob\listeners\NullListener;

class MessageDispatcher implements EventDispatcherInterface
{
    /**
     * @var \Wmoob\ListenerProvider
     */
    protected $listenerProvider;

    public function __construct(ListenerProvider $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * @inheritDoc
     * @throws
     */
    public function dispatch(object $event)
    {
        $listeners = $this->listenerProvider->getListenersForEvent($event);
        if (empty($listeners)) {
            $listeners[] = new NullListener();
        }
        foreach ($listeners as $listener) {
            $listener($event);
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }
        }
    }

}