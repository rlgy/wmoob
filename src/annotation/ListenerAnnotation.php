<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/23 10:11
 */

namespace Wmoob\annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * 消息监听器注解类
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class ListenerAnnotation
{
    /**
     * 消息主题
     *
     * @var string
     * @Required
     */
    public $topic;

    /**
     * 消息事件名称
     *
     * @var string
     * @Required
     */
    public $name;

    /**
     * 消息权重， 越大越先处理消息
     *
     * @var int
     */
    public $weight = 0;
}