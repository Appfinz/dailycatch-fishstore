<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OtpApiController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        // Generate 4-digit OTP code (default testing code: 1234 or random)
        $otpCode = '1234'; // Fixed for fast testing/demo, or rand(1000, 9999)

        $customer = Customer::firstOrCreate(
            ['phone' => $phone],
            ['is_verified' => false]
        );

        $customer->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "OTP sent successfully to +91 {$phone}",
            'demo_otp' => $otpCode,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp_code' => 'required|string',
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'landmark' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        $customer = Customer::where('phone', $phone)->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer profile not found. Please request OTP again.',
            ], 404);
        }

        if ($request->otp_code !== '1234' && $customer->otp_code !== $request->otp_code) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP code. Please enter 1234 for testing.',
            ], 422);
        }

        // Auto create/update profile
        $customer->update([
            'name' => $request->name ?: ($customer->name ?: 'Valued Customer'),
            'address' => $request->address ?: $customer->address,
            'landmark' => $request->landmark ?: $customer->landmark,
            'latitude' => $request->latitude ?: $customer->latitude,
            'longitude' => $request->longitude ?: $customer->longitude,
            'is_verified' => true,
            'otp_code' => null,
        ]);

        // Save into session for web session auth
        session(['customer_phone' => $customer->phone, 'customer_id' => $customer->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Mobile number verified successfully!',
            'customer' => $customer,
        ]);
    }
}
