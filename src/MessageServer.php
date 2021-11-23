<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/23 10:46
 */

namespace Wmoob;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Psr\Container\ContainerInterface;
use RegexIterator;
use Wmoob\annotation\ListenerAnnotation;

/**
 * 微盟消息服务
 */
class MessageServer
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;
    /**
     * @var \Wmoob\MessageDispatcher
     */
    protected $messageDispatch;

    /**
     * 监听器的命名空间数组
     *
     * @var string[]
     */
    protected $listenersNamespaces;

    public function __construct(ContainerInterface $container, $listenersNamespaces = [])
    {
        $this->container = $container;
        $this->listenersNamespaces = $listenersNamespaces;
        $this->init();
    }

    public function init()
    {
        AnnotationRegistry::loadAnnotationClass(ListenerAnnotation::class);
        $annotationReader = new AnnotationReader();
        $listeners = [];
        // 扫描目录获取注解参数
        foreach ($this->listenersNamespaces as $ns => $path) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            $iterator = new RegexIterator($iterator, '/.php$/');

            foreach ($iterator as $item) {
                if ($item->isDir()) {
                    continue;
                }
                $class = $ns . str_replace('/', "\\", substr($item->getPathname(), strlen($path), -4));
                if (!class_exists($class, false)) {
                    continue;
                }

                $reflectClass = new \ReflectionClass($class);
                $annotation = $annotationReader->getClassAnnotation($reflectClass, ListenerAnnotation::class);
                if ($annotation !== null && ($listener = $this->buildListenerByClassAnnotation($annotation,
                            $reflectClass) !== false)) {
                    array_push($listeners, $listener);
                }

                foreach ($reflectClass->getMethods() as $reflectionMethod) {
                    $reflectionMethodAnnotation = $annotationReader->getMethodAnnotation($reflectionMethod,
                        ListenerAnnotation::class);
                    if ($reflectionMethodAnnotation !== null && ($listener = $this->buildListenerByMethodAnnotation($reflectionMethodAnnotation,
                            $reflectionMethod, $reflectClass)) !== false) {
                        array_push($listeners, $listener);
                    }
                }
            }
        }
        $listenerProvider = ListenerProvider::createInstance($listeners);
        $this->messageDispatch = new MessageDispatcher($listenerProvider);
    }

    /**
     * 根据类名构建监听器
     *
     * @param ListenerAnnotation $annotation
     * @param \ReflectionClass $reflectionClass
     *
     * @return array|false
     * @throws
     */
    public function buildListenerByClassAnnotation($annotation, $reflectionClass)
    {
        $obj = $this->createObject($reflectionClass);
        if ($obj === false || !is_callable($obj)) {
            return false;
        }
        return [$annotation->topic, $annotation->name, $obj, $annotation->weight];
    }

    /**
     * 根据类名构建监听器
     *
     * @param ListenerAnnotation $annotation
     * @param \ReflectionMethod $reflectionMethod
     * @param \ReflectionClass $reflectionClass
     *
     * @return array|false
     * @throws
     */
    public function buildListenerByMethodAnnotation($annotation, $reflectionMethod, $reflectionClass)
    {
        $listener = null;

        if (!$reflectionMethod->isPublic()) {
            return false;
        }
        if ($reflectionMethod->isStatic()) {
            $listener = [$reflectionMethod->class, $reflectionMethod->name];
        } else {
            $obj = $this->createObject($reflectionClass);
            if ($obj === false) {
                return false;
            }
            $listener = [$obj, $reflectionMethod->name];
        }

        return [$annotation->topic, $annotation->name, $listener, $annotation->weight];
    }

    /**
     * @param \ReflectionClass $reflectClass
     *
     * @return false|mixed|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createObject($reflectClass)
    {
        $class = $reflectClass->name;

        if (!$this->container->has($class)) {
            if ($reflectClass->isInstantiable()) {
                try {
                    return $reflectClass->newInstance();
                } catch (\ReflectionException $e) {
                    return false;
                }
            }

            return false;
        }
        return $this->container->get($class);
    }

    /**
     * @throws
     */
    public function dispatch(Message $message): void
    {
        $this->messageDispatch->dispatch($message);
    }
}