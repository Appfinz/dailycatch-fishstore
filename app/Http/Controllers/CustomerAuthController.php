<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddress;

class CustomerAuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:10',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        $customer = Customer::firstOrCreate(
            ['phone' => $phone],
            ['name' => 'Customer (' . substr($phone, -4) . ')', 'is_verified' => false]
        );

        // Standard 4-digit OTP '1234' for instant dev testing
        $customer->otp_code = '1234';
        $customer->otp_expires_at = now()->addMinutes(10);
        $customer->save();

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully! Use OTP code 1234 to log in.',
            'otp_demo' => '1234'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        $otp = $request->otp ?: $request->otp_code;

        if (strlen($phone) < 10 || empty($otp)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please provide valid 10-digit phone number and OTP code.',
            ], 422);
        }

        $customer = Customer::where('phone', $phone)->first();

        // Accept demo code 1234 or matched stored OTP code
        if (!$customer || ($otp !== '1234' && $customer->otp_code !== $otp)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP code. Please enter 1234.',
            ], 422);
        }

        $customer->is_verified = true;
        $customer->otp_code = null;
        $customer->save();

        // Create default address if none exists
        if ($customer->addresses()->count() === 0) {
            CustomerAddress::create([
                'customer_id' => $customer->id,
                'label' => 'Home',
                'flat_no' => 'No. 22',
                'street_address' => 'Thiruvalluvar Street, East Tambaram',
                'landmark' => 'Near East Tambaram Railway Station',
                'pincode' => '600059',
                'latitude' => 12.9249,
                'longitude' => 80.1278,
                'distance_km' => 0.4,
                'is_default' => true,
            ]);
        }

        session(['customer_id' => $customer->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully!',
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
            ]
        ]);
    }

    public function me()
    {
        $customerId = session('customer_id');
        if (!$customerId) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $customer = Customer::with('addresses')->find($customerId);
        if (!$customer) {
            session()->forget('customer_id');
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        return response()->json([
            'status' => 'success',
            'customer' => $customer
        ]);
    }

    public function logout()
    {
        session()->forget('customer_id');
        return response()->json(['status' => 'success', 'message' => 'Logged out successfully']);
    }
}
