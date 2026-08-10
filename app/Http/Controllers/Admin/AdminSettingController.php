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
            'default_delivery_radius_km' => Setting::get('delivery_max_distance_km', '3.0'),
            'delivery_base_fee' => Setting::get('delivery_base_fee', '35'),
            'delivery_free_threshold' => Setting::get('delivery_free_threshold', '499'),
            'delivery_max_distance_km' => Setting::get('delivery_max_distance_km', '3.0'),
            'preorder_discount_amount' => Setting::get('preorder_discount_amount', '20'),
            'whatsapp_number' => Setting::get('whatsapp_number', '918778199218'),
            'shop_address' => Setting::get('shop_address', '22g, Thiruvalluvar street, East tambaram, Chennai-59'),
            'shop_phone' => Setting::get('shop_phone', '91 8778199218'),
            'firebase_api_key' => Setting::get('firebase_api_key', ''),
            'firebase_auth_domain' => Setting::get('firebase_auth_domain', ''),
            'firebase_project_id' => Setting::get('firebase_project_id', ''),
            'firebase_app_id' => Setting::get('firebase_app_id', ''),
        ];
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        Setting::set('cancellation_time_minutes', $request->cancellation_time_minutes ?: '2');
        Setting::set('delivery_base_fee', $request->delivery_base_fee ?: '35');
        Setting::set('delivery_free_threshold', $request->delivery_free_threshold ?: '499');
        Setting::set('delivery_max_distance_km', $request->delivery_max_distance_km ?: '3.0');
        Setting::set('default_delivery_radius_km', $request->delivery_max_distance_km ?: '3.0');
        Setting::set('preorder_discount_amount', $request->preorder_discount_amount ?: '20');
        Setting::set('whatsapp_number', $request->whatsapp_number ?: '918778199218');
        Setting::set('shop_address', $request->shop_address ?: '22g, Thiruvalluvar street, East tambaram, Chennai-59');
        Setting::set('shop_phone', $request->shop_phone ?: '91 8778199218');

        // Save Firebase Config
        Setting::set('firebase_api_key', $request->firebase_api_key ?: '');
        Setting::set('firebase_auth_domain', $request->firebase_auth_domain ?: '');
        Setting::set('firebase_project_id', $request->firebase_project_id ?: '');
        Setting::set('firebase_app_id', $request->firebase_app_id ?: '');

        return redirect()->back()->with('success', 'Store settings updated successfully!');
    }

    public function customers()
    {
        $customers = Customer::withCount('orders')->latest()->get();
        return view('admin.customers.index', compact('customers'));
    }
}
