<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Setting;
use Illuminate\Http\Request;

class LocationApiController extends Controller
{
    public function validateRadius(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $lat1 = (float) $request->latitude;
        $lon1 = (float) $request->longitude;

        // Fetch primary branch (East Tambaram)
        $branch = Branch::first();
        $lat2 = $branch ? (float) $branch->latitude : 12.9249;
        $lon2 = $branch ? (float) $branch->longitude : 80.1278;
        $maxRadiusKm = $branch ? (float) $branch->delivery_radius_km : 3.0;

        // Haversine formula
        $earthRadiusKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distanceKm = round($earthRadiusKm * $c, 2);

        $isWithin = $distanceKm <= $maxRadiusKm;
        $whatsappNumber = Setting::get('whatsapp_number', '918778199218');

        if ($isWithin) {
            $message = "Your location is within our {$maxRadiusKm} KM delivery zone ({$distanceKm} KM away). Direct delivery available!";
        } else {
            $message = "Currently we deliver only within our {$maxRadiusKm} KM service area. Your location is {$distanceKm} KM away. Please contact us on WhatsApp to check special delivery options.";
        }

        return response()->json([
            'status' => 'success',
            'is_within_radius' => $isWithin,
            'distance_km' => $distanceKm,
            'max_radius_km' => $maxRadiusKm,
            'message' => $message,
            'whatsapp_url' => "https://wa.me/{$whatsappNumber}?text=" . urlencode("Hi Daily Catch Fish Shop, my location is {$distanceKm} KM away. Can you deliver to my address?"),
        ]);
    }
}
