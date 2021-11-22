<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/22 16:36
 */

namespace Wmoob;

use Psr\EventDispatcher\ListenerProviderInterface;
use Wmoob\listeners\ListenerInterface;

class ListenerProvider implements ListenerProviderInterface
{
    /**
     * 主题监听器
     *
     * @var array
     */
    private static $topicListeners = [];
    /**
     * 具体事件名称
     *
     * @var array
     */
    private static $nameListeners = [];

    /**
     * @inheritDoc
     */
    public function getListenersForEvent($event): iterable
    {
        $listeners = [];
        $listeners = array_merge($listeners, $this->getListenersByTopic($event->topic));
        return array_merge($listeners, $this->getListenersByName($event->event, $event->topic));
    }

    /**
     * 注册事件监听器
     *
     * @param string $topic 主题 * 监听所有
     * @param string $name 具体事件名称 * 监听所有主题
     * @param \Wmoob\listeners\ListenerInterface $listener
     * @param boolean $append 注册位置
     */
    public function register($topic, $name, ListenerInterface $listener, $append = true)
    {
        if (!is_callable($listener)) {
            $listener = function (Message $message) use ($listener) {
                $listener->handle($message);
            };
        }

        if ($name) {
            if (!isset(static::$nameListeners[$name])) {
                static::$nameListeners[$name] = [];
            }
            if ($append) {
                static::$nameListeners[$name][] = [$listener, $topic];
            } else {
                array_unshift(static::$nameListeners[$name], [$listener, $topic]);
            }
        }

        if ($topic && empty($name)) {
            if (!isset(static::$topicListeners[$topic])) {
                static::$topicListeners[$topic] = [];
            }
            if ($append) {
                static::$topicListeners[$topic][] = $listener;
            } else {
                array_unshift(static::$topicListeners[$topic], $listener);
            }
        }
    }

    /**
     * @param $topic
     *
     * @return array
     */
    public function getListenersByTopic($topic)
    {
        $result = static::$topicListeners['*'] ?? [];
        if ($topic != '*' && isset(static::$topicListeners[$topic])) {
            $result = array_merge($result, static::$topicListeners[$topic]);
        }
        return $result;
    }

    /**
     * @param string $name
     * @param string $topic
     *
     * @return array
     */
    public function getListenersByName($name, $topic)
    {
        $result = static::$nameListeners[$name] ?? [];
        if ($name != '*' && isset(static::$nameListeners[$name])) {
            $result = array_merge($result, static::$nameListeners[$name]);
        }

        if ($topic != '*') {
            $result = array_filter($result, function ($item) use ($topic) {
                return $item[1] == $topic;
            });
        }

        return array_column($result, 0);
    }

}