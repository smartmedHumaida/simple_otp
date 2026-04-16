<?php

namespace App\Actions;

use App\Models\OTPRequest;
use Exception;
use Illuminate\Support\Facades\Cache;
use Log;

class VerifyOtpAction
{
    public function handle(array $data)
    {
        try {
            $entry = OTPRequest::unverified()->where('otp', hash('sha256', (string) $data['otp']))->first();
            if (! isset($entry['id'])) {
                Log::debug("no entry found");
                Log::debug('xxxxxxxxxxxxxxxxxxx');
                throw new Exception('Invalid');
            }
            $phone = $entry->phone;
            $storedOtp = Cache::get("otp_{$phone}");

            if (! $storedOtp) {
                Log::debug("No Stored OTP found, Probably expired");
                Log::debug("$storedOtp");
                throw new Exception('Not found');
                //ToDo: potential danger: too much information
            }

            if ($storedOtp != hash('sha256', $data['otp'])) {
                Log::debug("stored otp does not match sent OTP");
                Log::debug("$storedOtp VS ".$data['otp']);
                throw new Exception('Invalid');

            }

            Cache::forget("otp_{$phone}");

            return true;
        } catch (\Throwable $th) {
            Log::debug("general exception happenedd");
            Log::debug($th->getMessage());
            throw $th;
        }
    }
}