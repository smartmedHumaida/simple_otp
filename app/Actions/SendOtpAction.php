<?php

namespace App\Actions;

use App\Services\TwilioWhatsappService;
use Cache;
use Exception;
use Log;

class SendOtpAction
{
    public function handle(array $data)
    {
        try {
            $phone = $data['phone'];
            if (Cache::has("otp_{$phone}")) {
                throw new Exception('OTP already sent, try again in 1 minute');
            }

            $otp = random_int(100000, 999999);

            $message = (new TwilioWhatsappService)->sendOtp($phone, $otp);


            Log::channel('otp')->debug("OTP Response with status $message->status");
            // verify sending succeeded
            if (! $message || $message->status !== 'queued' && $message->status !== 'sent') {
                throw new Exception('Failed to send OTP');
            }

            Cache::put("otp_{$phone}", hash('sha256', $otp), now()->addMinute());


            return ['data' => ['otp' => $otp, 'result' => $message->toArray()], 'message' => "Sent"];

        } catch (Exception $e) {
            throw $e;
        }
    }
}