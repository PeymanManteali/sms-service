<?php

namespace SmsService\Drivers;

use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\KavenegarApi;
use SmsService\contracts\SmsDriversAbstraction;
use SmsService\Enums\SmsStatus;

class KavenegarProvider extends SmsDriversAbstraction
{
    public function sendOtp(): static
    {
        try {
            $apiKey = $this->provider->params['api-key'];
            $templateId = $this->template;
            $api = new KavenegarApi($apiKey);
            $response = $api->VerifyLookup(
                receptor: $this->receptor,
                token: $this->token,
                token2: $this->token2,
                token3: $this->token3,
                template: $templateId
            );
            if (is_array($response) && isset($response[0])) {
                $this->setRefId($response[0]->messageid);
            }
            $this->SetSendStatus(SmsStatus::Sent);
        } catch (ApiException $exception) {
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            $this->SetSendStatus(SmsStatus::Failed);
            $this->sendErrorText = $exception->errorMessage();
            $this->userShowedError = __('sms.verification_sms_send_fail', ['num' => 3007]);
        } catch (HttpException $exception) {
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            $this->SetSendStatus(SmsStatus::Failed);
            $this->sendErrorText = $exception->errorMessage();
            $this->userShowedError = __('sms.verification_sms_send_fail', ['num' => 3008]);
        }
        return $this;
    }

    public function sendText(): static
    {
        try {
            $apiKey = $this->provider->params['api-key'];
            $api = new KavenegarApi($apiKey);
            $response = $api->send(
                sender: $this->provider->params['sender'] ?? null,
                receptor: $this->receptor ?? null,
                message: $this->message ?? null
            );
            if (is_array($response) && isset($response[0])) {
                $this->setRefId($response[0]->messageid);
            }
            $this->SetSendStatus(SmsStatus::Sent);
        } catch (ApiException $exception) {
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            $this->SetSendStatus(SmsStatus::Failed);
            $this->sendErrorText = $exception->errorMessage();
            $this->userShowedError = __('sms.verification_sms_send_fail', ['num' => 3007]);
        } catch (HttpException $exception) {
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            $this->SetSendStatus(SmsStatus::Failed);
            $this->sendErrorText = $exception->errorMessage();
            $this->userShowedError = __('sms.verification_sms_send_fail', ['num' => 3008]);
        }
        return $this;
    }
}
