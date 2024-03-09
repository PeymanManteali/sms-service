<?php

namespace SmsService;

use Exception;
use SmsService\Models\SmsProvider;


class SmsService
{
    public ?object $provider = null;
    public ?object $driver = null;
    public ?string $receptor = null;

    /**
     * @throws Exception
     */
    public function setDriver(): void
    {
        $driver = config('sms.providers.' . $this->provider->method . '.driver');
        if (!$driver) {
            throw new Exception('SMS driver not found');
        }
        $this->driver = new $driver($this->provider);
    }

    public function to($receptor): static
    {
        $this->receptor = $receptor;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function withTemplate($templateId = null): static
    {
        if (!$templateId) {
            $defaultTemplateId = $this->provider->params['template_id'] ?? null;
            if ($defaultTemplateId) {
                $this->driver->setTemplate($defaultTemplateId);
            } else {
                throw new Exception('Set template id');
            }
        } else {
            $this->driver->setTemplate($templateId);
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    private function setType($type = '*'): void
    {
        $this->provider = SmsProvider::getActiveProvider(type: $type)?? null;

        if (!$this->provider) {
            throw new Exception('You dont have an active SMS provider with type: ' . $type);
        }
        $this->setDriver();
    }

    /**
     * @throws Exception
     */
    public function sendOtp(array|string $tokens): object
    {
        if (!$this->receptor) throw new Exception('First set receptor');
        $this->setType('otp');
        $this->driver->setToken($tokens);
        $this->driver->setReceptor($this->receptor);
        $response = $this->driver->sendOtp();
        $this->checkStatus($response);
        return $response;
    }

    /**
     * @throws Exception
     */
    public function sendText($message)
    {
        if (!$this->receptor) throw new Exception('First set receptor');
        $this->setType('text');
        $this->driver->setReceptor($this->receptor);
        $this->driver->setMessage($message);
        $response = $this->driver->sendText();
        $this->checkStatus($response);
        return $response;
    }

    private function checkStatus($response): void
    {
        $status = $response->sendStatus ?? null;
        if ($status !== 'SENT') {
            $this->provider->deactivate();
        }
    }
}
