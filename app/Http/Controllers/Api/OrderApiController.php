<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\CuttingStyle;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|min:10|max:15',
            'delivery_type' => 'required|in:delivery,pickup',
            'delivery_address' => 'required_if:delivery_type,delivery|nullable|string',
            'landmark' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'delivery_slot' => 'nullable|string',
            'is_preorder' => 'nullable|boolean',
            'delivery_date' => 'nullable|date',
            'payment_method' => 'required|string',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.cutting_style_id' => 'required|exists:cutting_styles,id',
            'cart_items.*.qty_kg' => 'required|numeric|min:0.1',
        ]);

        // Require customer session / authentication
        $customerId = session('customer_id');
        if (!$customerId) {
            return response()->json([
                'status' => 'error',
                'require_otp' => true,
                'message' => 'Please log in with Mobile OTP to complete your order.',
            ], 401);
        }

        $branch = Branch::first(); // East Tambaram Branch (lat: 12.9249, lng: 80.1278)
        $lat = $request->latitude ? (float)$request->latitude : 12.9249;
        $lng = $request->longitude ? (float)$request->longitude : 80.1278;

        // Strict 3KM Location Radius Validation
        if ($request->delivery_type === 'delivery') {
            $distance = $this->calculateDistance($lat, $lng);
            $maxRadius = (float) Setting::get('delivery_max_distance_km', 3.0);

            if ($distance > $maxRadius) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Delivery location is outside our 3KM service area. Your location is {$distance} KM away from our East Tambaram store.",
                ], 422);
            }
        }

        $phone = preg_replace('/[^0-9]/', '', $request->customer_phone);

        $customer = Customer::find($customerId);
        if ($customer) {
            $customer->update([
                'name' => $request->customer_name,
                'address' => $request->delivery_address,
                'landmark' => $request->landmark,
                'latitude' => $lat,
                'longitude' => $lng,
            ]);
        }

        $orderNumber = 'DC' . date('Ymd') . rand(1000, 9999);
        $cancellationTimerMins = (int) Setting::get('cancellation_time_minutes', 2);
        $cancellationExpiresAt = now()->addMinutes($cancellationTimerMins);

        $estimatedSubtotal = 0;
        $orderItemsData = [];

        foreach ($request->cart_items as $item) {
            $product = Product::with('cuttingStyles')->find($item['product_id']);
            $cuttingStyle = CuttingStyle::find($item['cutting_style_id']);

            $unitPrice = $product->sale_price_per_kg ?: $product->price_per_kg;

            // Fish-wise pivot extra fee check
            $cuttingCharge = 0;
            if ($cuttingStyle) {
                $pivotStyle = $product->cuttingStyles->firstWhere('id', $cuttingStyle->id);
                if ($pivotStyle && $pivotStyle->pivot && $pivotStyle->pivot->additional_charge !== null) {
                    $cuttingCharge = (float) $pivotStyle->pivot->additional_charge;
                } else {
                    $cuttingCharge = (float) $cuttingStyle->additional_charge;
                }
            }

            $qty = (float) $item['qty_kg'];
            $itemTotal = ($unitPrice * $qty) + ($cuttingCharge * $qty);

            $estimatedSubtotal += $itemTotal;

            // Auto-deduct stock quantity & auto-update availability status
            if ($product) {
                $newStock = max(0, (float)$product->stock_quantity - $qty);
                $newStatus = $product->availability_status;
                if ($newStock <= 0) {
                    $newStatus = 'out_of_stock';
                } elseif ($newStock <= 5 && $newStatus === 'in_stock') {
                    $newStatus = 'limited';
                }
                $product->update([
                    'stock_quantity' => $newStock,
                    'availability_status' => $newStatus,
                ]);
            }

            $orderItemsData[] = [
                'product_id' => $product->id,
                'cutting_style_id' => $cuttingStyle ? $cuttingStyle->id : null,
                'product_name' => $product->name,
                'cutting_style_name' => $cuttingStyle ? $cuttingStyle->name : 'Whole Fish',
                'unit_price_per_kg' => $unitPrice,
                'cutting_charge' => $cuttingCharge,
                'requested_qty_kg' => $qty,
                'estimated_item_total' => round($itemTotal, 2),
            ];
        }

        // Configurable Delivery Fee & Threshold Rule
        $baseFee = (float) Setting::get('delivery_base_fee', 35);
        $freeThreshold = (float) Setting::get('delivery_free_threshold', 499);
        $deliveryCharge = ($request->delivery_type === 'delivery' && $estimatedSubtotal < $freeThreshold) ? $baseFee : 0;

        $discountAmount = 0;
        $couponCode = session()->get('applied_coupon');
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            if ($coupon && $estimatedSubtotal >= $coupon->min_order_amount) {
                $discountAmount = ($coupon->discount_type === 'percentage')
                    ? round(($estimatedSubtotal * $coupon->discount_value) / 100, 2)
                    : $coupon->discount_value;
            }
        }

        // Pre-order Discount (₹20 or configured setting)
        $isPreorder = (bool) $request->is_preorder;
        $preorderDiscount = $isPreorder ? (float) Setting::get('preorder_discount_amount', 20) : 0.00;
        $deliveryDate = $isPreorder && $request->delivery_date ? $request->delivery_date : date('Y-m-d');

        $estimatedTotal = max(0, $estimatedSubtotal + $deliveryCharge - $discountAmount - $preorderDiscount);

        $order = Order::create([
            'order_number' => $orderNumber,
            'branch_id' => $branch ? $branch->id : 1,
            'customer_id' => $customer ? $customer->id : null,
            'customer_name' => $request->customer_name,
            'customer_phone' => $phone,
            'delivery_type' => $request->delivery_type,
            'delivery_address' => $request->delivery_address,
            'landmark' => $request->landmark,
            'latitude' => $lat,
            'longitude' => $lng,
            'delivery_slot' => $request->delivery_slot ?: 'Morning Slot (07:00 AM - 08:00 AM)',
            'is_preorder' => $isPreorder,
            'delivery_date' => $deliveryDate,
            'payment_method' => 'cod', // Cash on Delivery / Pay on Delivery
            'payment_status' => 'pending',
            'status' => 'awaiting_fulfilment',
            'estimated_subtotal' => round($estimatedSubtotal, 2),
            'delivery_charge' => round($deliveryCharge, 2),
            'discount_amount' => round($discountAmount, 2),
            'preorder_discount' => round($preorderDiscount, 2),
            'estimated_total' => round($estimatedTotal, 2),
            'cancellation_expires_at' => $cancellationExpiresAt,
        ]);

        foreach ($orderItemsData as $oItem) {
            $oItem['order_id'] = $order->id;
            OrderItem::create($oItem);
        }

        // Clear cart session
        session()->forget('cart');
        session()->forget('applied_coupon');

        $whatsappNumber = Setting::get('whatsapp_number', '918778199218');
        $whatsappMessage = "Order Placed! Order ID: {$order->order_number}\nTotal Est: ₹{$order->estimated_total}\nPayment: Cash on Delivery\nStatus: Awaiting Fulfilment. We will weigh the fish and send the final bill link!";

        return response()->json([
            'status' => 'success',
            'message' => 'Order placed successfully!',
            'order' => $order->load('items'),
            'order_number' => $order->order_number,
            'whatsapp_url' => "https://wa.me/{$whatsappNumber}?text=" . urlencode($whatsappMessage),
        ]);
    }

    public function show($orderNumber)
    {
        $order = Order::with(['items.product', 'items.cuttingStyle', 'branch'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $canCancel = false;
        $secondsRemaining = 0;

        if ($order->status === 'awaiting_fulfilment' && $order->cancellation_expires_at) {
            $now = now();
            if ($now->lt($order->cancellation_expires_at)) {
                $canCancel = true;
                $secondsRemaining = $now->diffInSeconds($order->cancellation_expires_at);
            }
        }

        $whatsappNumber = Setting::get('whatsapp_number', '918778199218');

        return response()->json([
            'status' => 'success',
            'order' => $order,
            'can_cancel' => $canCancel,
            'cancel_seconds_remaining' => $secondsRemaining,
            'whatsapp_number' => $whatsappNumber,
        ]);
    }

    public function cancel(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($order->status === 'cancelled') {
            return response()->json([
                'status' => 'error',
                'message' => 'This order is already cancelled.',
            ], 422);
        }

        if ($order->cancellation_expires_at && now()->gt($order->cancellation_expires_at)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cancellation window (2 minutes) has expired. Please contact us on WhatsApp.',
            ], 422);
        }

        $order->update([
            'status' => 'cancelled',
            'admin_notes' => 'Order cancelled by customer within 2-minute cancellation window.',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Order cancelled successfully.',
            'order' => $order,
        ]);
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
