<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/12/2 14:22
 */

namespace Wmoob\message;

use Symfony\Component\EventDispatcher\EventDispatcher;

class SymfonyDispatcherDecorator extends DispatcherDecorator implements MessageEventDispatcherInterface
{
    /**@var \Symfony\Component\EventDispatcher\EventDispatcher */
    private $dispatcher;

    public function __construct()
    {
        $this->dispatcher = static::createDispatcher();
    }

    function getDispatcher(): MessageEventDispatcherInterface
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event)
    {
        return $this->dispatcher->dispatch($event);
    }

    /**
     * @inheritDoc
     */
    public function addListener(string $eventName, callable $listener, int $priority = 0)
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);
    }

    /**
     * @inheritDoc
     */
    public function addSubscriber(MessageEventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (\is_string($params)) {
                $this->addListener($eventName, [$subscriber, $params]);
            } elseif (\is_string($params[0])) {
                $this->addListener($eventName, [$subscriber, $params[0]], $params[1] ?? 0);
            } else {
                foreach ($params as $listener) {
                    $this->addListener($eventName, [$subscriber, $listener[0]], $listener[1] ?? 0);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function removeListener(string $eventName, callable $listener)
    {
        $this->dispatcher->removeListener($eventName, $listener);
    }

    public function removeSubscriber(MessageEventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (\is_array($params) && \is_array($params[0])) {
                foreach ($params as $listener) {
                    $this->removeListener($eventName, [$subscriber, $listener[0]]);
                }
            } else {
                $this->removeListener($eventName, [$subscriber, \is_string($params) ? $params : $params[0]]);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getListeners(string $eventName = null)
    {
        return $this->dispatcher->getListeners($eventName);
    }

    /**
     * @inheritDoc
     */
    public function getListenerPriority(string $eventName, callable $listener)
    {
        return $this->dispatcher->getListenerPriority($eventName, $listener);
    }

    /**
     * @inheritDoc
     */
    public function hasListeners(string $eventName = null)
    {
        return $this->dispatcher->hasListeners($eventName);
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private static function createDispatcher()
    {
        return new EventDispatcher();
    }

}