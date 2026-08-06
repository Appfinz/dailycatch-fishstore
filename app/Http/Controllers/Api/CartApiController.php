<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CuttingStyle;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    private function getCart()
    {
        return session()->get('cart', []);
    }

    private function saveCart($cart)
    {
        session()->put('cart', $cart);
    }

    public function get()
    {
        $cart = $this->getCart();
        $items = [];
        $estimatedSubtotal = 0;

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            $cuttingStyle = isset($item['cutting_style_id']) ? CuttingStyle::find($item['cutting_style_id']) : null;

            if ($product) {
                $unitPrice = $product->sale_price_per_kg ?: $product->price_per_kg;
                $cuttingCharge = $cuttingStyle ? $cuttingStyle->additional_charge : 0;
                $qty = (float) $item['qty_kg'];

                $itemTotal = ($unitPrice * $qty) + ($cuttingCharge * $qty);
                $estimatedSubtotal += $itemTotal;

                $items[] = [
                    'cart_key' => $key,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'tamil_name' => $product->tamil_name,
                    'product_image' => $product->image,
                    'price_per_kg' => $unitPrice,
                    'cutting_style_id' => $cuttingStyle ? $cuttingStyle->id : null,
                    'cutting_style_name' => $cuttingStyle ? $cuttingStyle->name : 'Whole Fish (Uncut)',
                    'cutting_charge' => $cuttingCharge,
                    'qty_kg' => $qty,
                    'estimated_item_total' => round($itemTotal, 2),
                ];
            }
        }

        $couponCode = session()->get('applied_coupon');
        $discountAmount = 0;
        $couponMessage = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
            if ($coupon && $estimatedSubtotal >= $coupon->min_order_amount) {
                if ($coupon->discount_type === 'percentage') {
                    $discountAmount = round(($estimatedSubtotal * $coupon->discount_value) / 100, 2);
                } else {
                    $discountAmount = $coupon->discount_value;
                }
                $couponMessage = "Coupon {$couponCode} applied!";
            } else {
                session()->forget('applied_coupon');
            }
        }

        $deliveryFee = (float) Setting::get('delivery_fee', 35);
        $estimatedTotal = max(0, $estimatedSubtotal - $discountAmount + ($items ? $deliveryFee : 0));

        return response()->json([
            'status' => 'success',
            'items' => $items,
            'item_count' => count($items),
            'estimated_subtotal' => round($estimatedSubtotal, 2),
            'delivery_fee' => $items ? $deliveryFee : 0,
            'discount_amount' => round($discountAmount, 2),
            'applied_coupon' => $couponCode,
            'coupon_message' => $couponMessage,
            'estimated_total' => round($estimatedTotal, 2),
            'disclaimer' => 'Note: The final whole fish weight and bill amount will be updated after the fish is selected and weighed in our shop.',
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'cutting_style_id' => 'required|exists:cutting_styles,id',
            'qty_kg' => 'required|numeric|min:0.25|max:10.00',
        ]);

        $productId = $request->product_id;
        $cuttingStyleId = $request->cutting_style_id;
        $qtyKg = (float) $request->qty_kg;

        $cartKey = "{$productId}_{$cuttingStyleId}";
        $cart = $this->getCart();

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty_kg'] += $qtyKg;
        } else {
            $cart[$cartKey] = [
                'product_id' => $productId,
                'cutting_style_id' => $cuttingStyleId,
                'qty_kg' => $qtyKg,
            ];
        }

        $this->saveCart($cart);

        return $this->get();
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'qty_kg' => 'required|numeric|min:0.25|max:10.00',
        ]);

        $cart = $this->getCart();

        if (isset($cart[$request->cart_key])) {
            $cart[$request->cart_key]['qty_kg'] = (float) $request->qty_kg;
            $this->saveCart($cart);
        }

        return $this->get();
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
        ]);

        $cart = $this->getCart();
        if (isset($cart[$request->cart_key])) {
            unset($cart[$request->cart_key]);
            $this->saveCart($cart);
        }

        return $this->get();
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired coupon code.',
            ], 422);
        }

        session()->put('applied_coupon', $code);

        return $this->get();
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('applied_coupon');

        return response()->json([
            'status' => 'success',
            'message' => 'Cart cleared.',
        ]);
    }
}
