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
    private $topicListeners = [];
    /**
     * 具体事件名称
     *
     * @var array
     */
    private $nameListeners = [];

    private function __construct(array $config)
    {
        $this->topicListeners = [];
        $this->nameListeners = [];
        try {
            foreach ($config as $item) {
                $this->register($item[0], $item[1], $item[2]);
            }
        } catch (\OutOfRangeException $e) {
            throw new \InvalidArgumentException('配置参数格式错误');
        }
    }

    /**
     * @param array $config
     *
     * @return \Wmoob\ListenerProvider
     */
    public static function createInstance($config)
    {
        return new self($config);
    }

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
     * @param string $name 具体事件名称 * 监听所有
     * @param callable $listener
     * @param boolean $append 注册位置
     */
    public function register($topic, $name, callable $listener, $append = true)
    {
        if ($name) {
            if (!isset($this->nameListeners[$name])) {
                $this->nameListeners[$name] = [];
            }
            if ($append) {
                $this->nameListeners[$name][] = [$listener, $topic];
            } else {
                array_unshift($this->nameListeners[$name], [$listener, $topic]);
            }
        }

        if ($topic && empty($name)) {
            if (!isset($this->topicListeners[$topic])) {
                $this->topicListeners[$topic] = [];
            }
            if ($append) {
                $this->topicListeners[$topic][] = $listener;
            } else {
                array_unshift($this->topicListeners[$topic], $listener);
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
        $result = $this->topicListeners['*'] ?? [];
        if ($topic != '*' && isset($this->topicListeners[$topic])) {
            $result = array_merge($result, $this->topicListeners[$topic]);
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
        $result = $this->nameListeners['*'] ?? [];
        if ($name != '*' && isset($this->nameListeners[$name])) {
            $result = array_merge($result, $this->nameListeners[$name]);
        }

        if ($topic != '*') {
            $result = array_filter($result, function ($item) use ($topic) {
                return $item[1] == $topic || $item['1'] == '*';
            });
        }

        return array_column($result, 0);
    }

}