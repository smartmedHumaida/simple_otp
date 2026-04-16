<?php

namespace App\Http\Controllers;

use App\Actions\SendOtpAction;
use App\Actions\VerifyOtpAction;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\OTPRequest;
use Exception;
use Log;

class OtpController extends Controller
{
    public function send(SendOtpRequest $request)
    {
        Log::channel('otp')->debug("OTP Requested for phone: +".$request['phone']);
        try {
            $result = (new SendOtpAction)->handle($request->validated());

            $otp = $result['data']['otp'];
            $receivedStatus = $result['data']['result']['status'];
            if (isset($otp)) {
                Log::channel('otp')->debug("OTP has been sent for phone: +".$request['phone']);
                OTPRequest::create([
                    'phone' => $request['phone'],
                    'otp' => hash('sha256', $otp),
                    'status' => $receivedStatus,
                    'verified' => false,
                ]);

                return response(['message' => 'OK']);
            }
           return response(['message' => 'OTP does not delivered'], 422);

        } catch (\Throwable $th) {
            Log::debug($th->getMessage());
            Log::debug("mmmmmmmmmmmmmmmmmmmm");
            return response()->json(['message' => 'Failed to send OTP'], 422);
        }
    }

    public function verify(VerifyOtpRequest $request)
    {
        try {
            $result = (new VerifyOtpAction)->handle($request->validated());
            if ($result)
                $entry = OTPRequest::where('otp', hash('sha256', $request['otp']))->first();
            if (! isset($entry['id'])) {
                Log::debug("no entry found");
                Log::debug('xxxxxxxxxxxxxxxxxxx');
                throw new Exception('Invalid');
            }
            OTPRequest::unverified()
                ->where('phone', $entry->phone)
                ->update([
                    'verified' => 1,
                    'status' => 'verified - manual',
                ]);
            Log::channel('otp')->debug("OTP verified for phone: +$entry->phone");
            return response()->json(['message' => 'OTP verified successfully']);
        } catch (\Throwable $th) {
            Log::debug($th->getMessage());
            Log::debug("mmmmmmmmmmmmmmmmmmmm");
            return response()->json(['message' => 'Failed to verify OTP'], 422);
        }
    }
}