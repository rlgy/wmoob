<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/22 15:19
 */

namespace Wmoob\listeners;

use Wmoob\exceptions\SignException;
use Wmoob\Message;

/**
 * 签名校验监听器
 */
class SignValidatorListener extends AbstractListener
{
    /**
     * 应用id
     *
     * @var string
     */
    private $client_id;
    /**
     * 应用secret
     *
     * @var string
     */
    private $client_secret;

    public function __construct($client_id, $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    /**
     * @inheritDoc
     */
    public function handle(Message $message): void
    {
        if (strcasecmp($message->sign, md5($this->client_id . $message->id . $this->client_secret)) !== 0) {
            throw new SignException("消息签名验证失败");
        }
        if (strcasecmp($message->msgSignature,
                md5($this->client_id . $message->id . $message->msg_body . $this->client_secret)) !== 0) {
            throw new SignException("消息内容签名验证失败");
        }
    }

}