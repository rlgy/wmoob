<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/22 15:10
 */

namespace Wmoob\message;

/**
 * 微盟云消息对象
 */
class Message
{
    /**
     * 商户id
     *
     * @var string
     */
    public $business_id;
    /**
     * topic消息事件类型
     *
     * @var string
     */
    public $event;
    /**
     * 微盟业务系统消息id，如智慧餐厅的订单编号
     *
     * @var string
     */
    public $id;
    /**
     * 消息模式
     * 1、推送至服务商消息（适用于服务商，需要商家授权）
     * 2、推送至商户个人（适用于有自主开发能力的商家）
     *
     * @var integer
     */
    public $model;
    /**
     * 业务消息体，编码格式UTF-8
     *
     * @var string
     */
    public $msg_body;
    /**
     * 商户店铺id（新云）/商户公众号id（老云）
     *
     * @var string
     */
    public $public_account_id;
    /**
     * 防篡改签名:md5(client_id+id+client_secret)
     *
     * @var string
     */
    public $sign;
    /**
     * 防篡改签名：md5(client_id+id+msg_body+client_secret)
     *
     * @var string
     */
    public $msgSignature;
    /**
     * 业务消息主题
     *
     * @var string
     */
    public $topic;
    /**
     * 消息的版本号，以最高版本为最新消息，可以覆盖低版本消息
     *
     * @var string
     */
    public $version;
}