<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Customer;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'cancellation_time_minutes' => Setting::get('cancellation_time_minutes', '2'),
            'default_delivery_radius_km' => Setting::get('default_delivery_radius_km', '3.0'),
            'whatsapp_number' => Setting::get('whatsapp_number', '918778199218'),
            'delivery_fee' => Setting::get('delivery_fee', '35'),
            'shop_address' => Setting::get('shop_address', '22g, Thiruvalluvar street, East tambaram, Chennai-59'),
            'shop_phone' => Setting::get('shop_phone', '91 8778199218'),
        ];
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        Setting::set('cancellation_time_minutes', $request->cancellation_time_minutes ?: '2');
        Setting::set('default_delivery_radius_km', $request->default_delivery_radius_km ?: '3.0');
        Setting::set('whatsapp_number', $request->whatsapp_number ?: '918778199218');
        Setting::set('delivery_fee', $request->delivery_fee ?: '35');
        Setting::set('shop_address', $request->shop_address ?: '22g, Thiruvalluvar street, East tambaram, Chennai-59');
        Setting::set('shop_phone', $request->shop_phone ?: '91 8778199218');

        return redirect()->back()->with('success', 'Store settings updated successfully!');
    }

    public function customers()
    {
        $customers = Customer::withCount('orders')->latest()->get();
        return view('admin.customers.index', compact('customers'));
    }
}
