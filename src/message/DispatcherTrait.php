<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/12/2 15:14
 */

namespace Wmoob\message;

trait  DispatcherTrait
{
    abstract public function getDispatcher(): MessageEventDispatcherInterface;

    /**
     * @inheritDoc
     */
    public function dispatch(object $event, string $eventName = null): object
    {
        return $this->getDispatcher()->dispatch($event, $eventName);
    }

    /**
     * @inheritDoc
     */
    public function addListener(string $eventName, callable $listener, int $priority = 0)
    {
        $this->getDispatcher()->addListener($eventName, $listener, $priority);
    }

    /**
     * @inheritDoc
     */
    public function addSubscriber(MessageEventSubscriberInterface $subscriber)
    {
        $this->getDispatcher()->addSubscriber($subscriber);
    }

    /**
     * @inheritDoc
     */
    public function removeListener(string $eventName, callable $listener)
    {
        $this->getDispatcher()->removeListener($eventName, $listener);
    }

    public function removeSubscriber(MessageEventSubscriberInterface $subscriber)
    {
        $this->getDispatcher()->removeSubscriber($subscriber);
    }

    /**
     * @inheritDoc
     */
    public function getListeners(string $eventName = null)
    {
        return $this->getDispatcher()->getListeners($eventName);
    }

    /**
     * @inheritDoc
     */
    public function getListenerPriority(string $eventName, callable $listener)
    {
        return $this->getDispatcher()->getListenerPriority($eventName, $listener);
    }

    /**
     * @inheritDoc
     */
    public function hasListeners(string $eventName = null)
    {
        return $this->getDispatcher()->hasListeners($eventName);
    }
}