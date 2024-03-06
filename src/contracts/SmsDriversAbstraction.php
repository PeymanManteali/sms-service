<?php

namespace SMSService\contracts;

use SmsService\Enums\SmsStatus;

abstract class SmsDriversAbstraction implements SmsDriversInterface
{
    public string $sendErrorText;
    public string $userShowedError;
    public string $sendStatus;
    public string $refId;
    public object $provider;
    public string $template;
    public string $receptor;
    public string $token;
    public string $token2;
    public string $token3;
    public string $message;

    public function __construct($provider)
    {
        $this->setProvider($provider);
        $this->setTemplate();
    }

    public function setProvider($provider): void
    {
        $this->provider = $provider;
    }

    public function setTemplate($template = null): void
    {
        if ($template) {
            $this->template = $template;
        } else {
            $this->template = $this->provider->params['template-id'];
        }
    }

    public function setReceptor($receptor)
    {
        $this->receptor = $receptor;
    }

    public function setToken($tokens)
    {
        if (is_array($tokens)) {
            $this->token = $tokens[0] ?? null;
            $this->token2 = $tokens[1] ?? null;
            $this->token3 = $tokens[2] ?? null;
        } else {
            $this->token = $tokens;
        }
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param SmsStatus $sendStatus
     * @return void
     */
    public function setSendStatus(SmsStatus $sendStatus): void
    {
        $this->sendStatus = $sendStatus->value;
    }

    /**
     * @return mixed
     */
    public function getRefId(): mixed
    {
        return $this->refId;
    }

    /**
     * @param $refId
     */
    public function setRefId($refId): void
    {
        $this->refId = $refId;
    }
}
