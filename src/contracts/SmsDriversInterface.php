<?php

namespace SMSService\contracts;

interface SmsDriversInterface
{
    public function sendOtp();
    public function sendText();
}
