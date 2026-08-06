<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CuttingStyle;

class AdminCuttingStyleController extends Controller
{
    public function index()
    {
        $cuttingStyles = CuttingStyle::orderBy('sort_order', 'asc')->get();
        return view('admin.cutting-styles.index', compact('cuttingStyles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'additional_charge' => 'required|numeric|min:0',
        ]);

        CuttingStyle::create([
            'name' => $request->name,
            'tamil_name' => $request->tamil_name,
            'description' => $request->description,
            'image' => $request->image ?: 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=400&q=80',
            'additional_charge' => $request->additional_charge,
            'sort_order' => CuttingStyle::count() + 1,
        ]);

        return redirect()->back()->with('success', 'Cutting style added successfully!');
    }

    public function destroy($id)
    {
        $cs = CuttingStyle::findOrFail($id);
        $cs->delete();
        return redirect()->back()->with('success', 'Cutting style deleted successfully!');
    }
}
