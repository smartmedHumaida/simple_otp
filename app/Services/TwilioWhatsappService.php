<?php

namespace App\Services;

use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class TwilioWhatsappService
{
    public function sendOtp(string $phone, int $otp): MessageInstance
    {
        $twilio = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));

        $message = $twilio->messages->create("whatsapp:{$phone}", [
            "from" => "whatsapp:+".env('TWILIO_WHATSAPP_SENDER'),
            "contentSid" => env('TWILIO_OTP_TEMPLATE_SID'),
            "contentVariables" => json_encode(['1' => (string) $otp]),
        ]);
        return $message;
    }
}