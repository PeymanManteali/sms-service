<?php

namespace SmsService\Drivers;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use SmsService\contracts\SmsDriversAbstraction;
use Cryptommer\Smsir\Classes\Smsir;
use Cryptommer\Smsir\Objects\Parameters;
use SmsService\Enums\SmsStatus;

class SmsIrProvider extends SmsDriversAbstraction
{
    /**
     * @return SmsIrProvider
     * @throws GuzzleException
     */
    //
    protected function execute($type): SmsIrProvider
    {
        try {
            $apiKey = $this->provider->params['api-key'];
            $lineNumber = $this->provider->params['line-number'];
            $templateId = $this->template;
            $codeParameter = new Parameters('CODE', $this->token ?? '');
            $signParameter = new Parameters('SIGN', $this->token2 ?? '');
            $parameters = [$codeParameter, $signParameter];
            $smsIr = new Smsir($lineNumber, $apiKey);
            $smsIrSend = $smsIr->Send();
            if ($type == 'otp') {
                $verifyResponse = $smsIrSend->Verify($this->receptor, $templateId, $parameters);
            }else{
                $verifyResponse = $smsIrSend->Bulk($this->message, [$this->receptor], null, $lineNumber);
            }

            if ($verifyResponse->getStatus() === '1') {
                $this->setSendStatus(SmsStatus::Sent);
                $response_data = $verifyResponse->Data;
                if ($response_data->MessageId) {
                    $this->setRefId(strval($response_data->MessageId));
                }
            } else {
                $this->setSendStatus(SmsStatus::Failed);
                $this->sendErrorText = $verifyResponse->getMessage();
                $this->userShowedError = __('sms.verification_sms_send_fail', ['num' => 3001]);
            }

        } catch (Exception $exception) {
            $this->setSendStatus(SmsStatus::Failed);
            $this->sendErrorText = $exception->getMessage();
            $this->userShowedError = __('sms.verification_sms_send_fail', ['num' => 3002]);
        }
        return $this;
    }

    public function sendOtp(): SmsIrProvider
    {
        return $this->execute('otp');
    }

    public function sendText(): SmsIrProvider
    {
        return $this->execute('text');
    }
}
