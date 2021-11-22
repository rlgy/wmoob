<?php
/**
 * Created by PhpStorm.
 * Author: rlgyzhcn
 * Mail <rlgyzhcn@gmail.com>
 * Date: 2021/11/22 15:19
 */

namespace Wmoob\listeners;

use Wmoob\Message;

class SingValidatorListener extends AbstractListener
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
        if (!$this->validate($message)) {
            $message->stopped = true;
        }
    }

    /**
     * 验证签名
     *
     * @param \Wmoob\Message $message
     *
     * @return bool
     */
    private function validate(Message $message)
    {
        if (strcasecmp($message->sign, md5($this->client_id . $message->id . $this->client_secret)) !== 0) {
            return false;
        }
        if (strcasecmp($message->msgSignature,
                md5($this->client_id . $message->id . $message->msg_body . $this->client_secret)) !== 0) {
            return false;
        }
        return true;
    }

}