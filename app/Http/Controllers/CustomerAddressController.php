<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAddress;

class CustomerAddressController extends Controller
{
    public function index()
    {
        $customerId = session('customer_id');
        if (!$customerId) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $addresses = CustomerAddress::where('customer_id', $customerId)->orderBy('is_default', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'addresses' => $addresses
        ]);
    }

    public function store(Request $request)
    {
        $customerId = session('customer_id');
        if (!$customerId) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $request->validate([
            'label' => 'required|string|max:50',
            'street_address' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
        ]);

        $lat = $request->latitude ? (float)$request->latitude : 12.9249;
        $lng = $request->longitude ? (float)$request->longitude : 80.1278;

        // Calculate distance from East Tambaram branch (12.9249, 80.1278)
        $distance = $this->calculateDistance($lat, $lng);

        if ($request->is_default) {
            CustomerAddress::where('customer_id', $customerId)->update(['is_default' => false]);
        }

        $address = CustomerAddress::create([
            'customer_id' => $customerId,
            'label' => $request->label,
            'flat_no' => $request->flat_no,
            'street_address' => $request->street_address,
            'landmark' => $request->landmark,
            'pincode' => $request->pincode,
            'latitude' => $lat,
            'longitude' => $lng,
            'distance_km' => $distance,
            'is_default' => $request->is_default ? true : false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Address saved successfully!',
            'address' => $address
        ]);
    }

    public function destroy($id)
    {
        $customerId = session('customer_id');
        if (!$customerId) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $address = CustomerAddress::where('customer_id', $customerId)->where('id', $id)->first();
        if ($address) {
            $address->delete();
        }

        return response()->json(['status' => 'success', 'message' => 'Address removed']);
    }

    private function calculateDistance($lat1, $lon1, $lat2 = 12.9249, $lon2 = 80.1278)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return round($earthRadius * $c, 2);
    }
}
