<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/23 10:11
 */

namespace Wmoob\annotation;

/**
 * 消息监听器注解类
 *
 * @Annotation
 */
class ListenerAnnotation
{
    /**
     * 消息主题
     *
     * @var string
     */
    public $topic;
    /**
     * 消息事件名称
     *
     * @var string
     */
    public $name;
}