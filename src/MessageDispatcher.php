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

class MessageDispatcher implements EventDispatcherInterface
{
    /**
     * 容器接口
     *
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     * @throws
     */
    public function dispatch(object $event)
    {
        /**@var \Psr\EventDispatcher\ListenerProviderInterface $listenerProvider */
        $listenerProvider = $this->container->get(ListenerProvider::class);

        foreach ($listenerProvider->getListenersForEvent($event) as $listener) {
            $listener($event);
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }
        }
    }

}