<?php

namespace SMSService\SmsProviders;

use Exception;
use SMSService\contracts\SmsDriversAbstraction;
use SmsService\Enums\SmsStatus;

class Sms0098Provider extends SmsDriversAbstraction
{
    /**
     * @return Sms0098Provider
     */
    public function execute($text): Sms0098Provider
    {
        $text = urlencode($text);
        $username = $this->provider->params['username'];
        $password = $this->provider->params['password'];
        $from = $this->provider->params['from'];

        try {

            $url = "https://www.0098sms.com/sendsmslink.aspx?FROM=$from&TO=$this->receptor&TEXT=$text&USERNAME=$username&PASSWORD=$password&DOMAIN=0098";

            $fetch = file_get_contents($url);
            $fetch = (int)$fetch;

            if ($fetch !== 0) {
                $this->sendErrorText = __('sms.0098_sms_not_zero', ['fetch' => $fetch]);
                $this->userShowedError = __('sms.verification_sms_send_fail', ['num' => 3003]);
            } else {
                $this->SetSendStatus(SmsStatus::Sent);
            }

        } catch (Exception $exception) {
            $this->setSendStatus(SmsStatus::Failed);
            $this->sendErrorText = $exception->getMessage();
            $this->userShowedError = __('sms.verification_sms_send_fail', ['num' => 3004]);
        }
        return $this;
    }

    public function sendOtp()
    {
        $text = __('sms.your_verification_code_site_name', ['token' => $this->token, 'token2' => $this->token2]);
        $this->execute($text);
    }
    public function sendText()
    {
        $text = $this->message;
        $this->execute($text);
    }

}
