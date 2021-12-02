<?php

namespace Wmoob\message;

use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * The EventDispatcherInterface is the central point of Symfony's event listener system.
 * Listeners are registered on the manager and events are dispatched through the
 * manager.
 */
interface MessageEventDispatcherInterface extends EventDispatcherInterface
{
    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param int $priority The higher this value, the earlier an event
     *                      listener will be triggered in the chain (defaults to 0)
     */
    public function addListener(string $eventName, callable $listener, int $priority = 0);

    /**
     * Adds an event subscriber.
     * The subscriber is asked for all the events it is
     * interested in and added as a listener for these events.
     */
    public function addSubscriber(MessageEventSubscriberInterface $subscriber);

    /**
     * Removes an event listener from the specified events.
     */
    public function removeListener(string $eventName, callable $listener);

    public function removeSubscriber(MessageEventSubscriberInterface $subscriber);

    /**
     * Gets the listeners of a specific event or all listeners sorted by descending priority.
     *
     * @return array<callable[]|callable>
     */
    public function getListeners(string $eventName = null);

    /**
     * Gets the listener priority for a specific event.
     * Returns null if the event or the listener does not exist.
     *
     * @return int|null
     */
    public function getListenerPriority(string $eventName, callable $listener);

    /**
     * Checks whether an event has any registered listeners.
     *
     * @return bool
     */
    public function hasListeners(string $eventName = null);
}
