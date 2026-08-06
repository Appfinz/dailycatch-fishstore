<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items', 'customer'])->latest();

        if ($request->has('status') && !empty($request->status) && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15);
        $counts = [
            'all' => Order::count(),
            'awaiting_fulfilment' => Order::where('status', 'awaiting_fulfilment')->count(),
            'final_bill_ready' => Order::where('status', 'final_bill_ready')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'out_for_delivery' => Order::where('status', 'out_for_delivery')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'items.cuttingStyle', 'branch', 'customer'])->findOrFail($id);
        $whatsappNumber = Setting::get('whatsapp_number', '918778199218');

        return view('admin.orders.show', compact('order', 'whatsappNumber'));
    }

    public function updateWeight(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.actual_qty_kg' => 'required|numeric|min:0.05',
        ]);

        $finalSubtotal = 0;

        foreach ($request->items as $itemData) {
            $item = OrderItem::findOrFail($itemData['id']);
            $actualQty = (float) $itemData['actual_qty_kg'];

            $unitPrice = (float) $item->unit_price_per_kg;
            $cuttingCharge = (float) $item->cutting_charge;

            $itemFinalTotal = round(($unitPrice * $actualQty) + ($cuttingCharge * $actualQty), 2);
            $finalSubtotal += $itemFinalTotal;

            $item->update([
                'actual_qty_kg' => $actualQty,
                'final_item_total' => $itemFinalTotal,
            ]);
        }

        $deliveryCharge = (float) $order->delivery_charge;
        $discountAmount = (float) $order->discount_amount;
        $finalTotal = max(0, $finalSubtotal + $deliveryCharge - $discountAmount);

        $order->update([
            'final_subtotal' => round($finalSubtotal, 2),
            'final_total' => round($finalTotal, 2),
            'status' => 'final_bill_ready',
            'weight_updated_at' => now(),
        ]);

        // Clean customer phone number
        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        $billUrl = route('orders.track', $order->order_number);
        $whatsappMessage = "Hello {$order->customer_name}!\nYour fish at Daily Catch Fish Shop has been weighed & prepared fresh.\n\nOrder #{$order->order_number}\nEstimated Total: ₹{$order->estimated_total}\nFINAL BILL: ₹{$order->final_total}\n\nView your detailed invoice here: {$billUrl}\nThank you for choosing Daily Catch!";

        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($whatsappMessage);

        return redirect()->back()->with([
            'success' => "Actual fish weights updated successfully! Order status changed to 'Final Bill Ready'.",
            'whatsapp_url' => $whatsappUrl,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:awaiting_fulfilment,final_bill_ready,preparing,out_for_delivery,delivered,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $order->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes ?: $order->admin_notes,
            'payment_status' => ($request->status === 'delivered') ? 'paid' : $order->payment_status,
        ]);

        return redirect()->back()->with('success', "Order status updated to '" . ucwords(str_replace('_', ' ', $request->status)) . "'.");
    }
}
