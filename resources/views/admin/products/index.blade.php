@extends('layouts.admin')

@section('title', 'Daily Fish Prices & Product Management - Daily Catch Admin')

@section('content')
<div class="space-y-8">
    
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Daily Fish Prices & Inventory</h1>
            <p class="text-xs text-slate-400 mt-1">Update morning daily harbor catch prices per kg and manage active products</p>
        </div>

        <button onclick="document.getElementById('addProductModal').classList.remove('hidden')" class="bg-gradient-to-r from-ocean-600 to-aqua-600 hover:from-ocean-500 hover:to-aqua-500 text-white font-black text-xs px-5 py-3 rounded-xl shadow-lg flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add New Product
        </button>
    </div>

    <!-- Quick Morning Price Updater Form -->
    <form action="{{ route('admin.products.quick-price-update') }}" method="POST" class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
        @csrf
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h3 class="font-extrabold text-base text-white flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-amber-400"></i> Quick Daily Catch Price Updater Grid
                </h3>
                <p class="text-xs text-slate-400">Update today's market rates for fresh sea fish arrival</p>
            </div>

            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs px-6 py-2.5 rounded-xl shadow transition-all">
                Save All Price Changes
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950 text-slate-400 text-xs uppercase font-bold border-b border-slate-800">
                        <th class="p-3.5 rounded-l-xl">Fish Product</th>
                        <th class="p-3.5">Category</th>
                        <th class="p-3.5">Regular Rate (₹/kg)</th>
                        <th class="p-3.5">Offer Rate (₹/kg)</th>
                        <th class="p-3.5">Stock Status</th>
                        <th class="p-3.5 text-right rounded-r-xl">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-xs">
                    @foreach($products as $index => $product)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="p-3.5 font-bold text-white">
                                <input type="hidden" name="prices[{{ $index }}][id]" value="{{ $product->id }}">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image }}" class="w-10 h-10 object-cover rounded-lg border border-slate-800">
                                    <div>
                                        <span class="block font-extrabold text-white text-xs">{{ $product->name }}</span>
                                        @if($product->tamil_name)
                                            <span class="text-[10px] text-aqua-400 font-semibold">{{ $product->tamil_name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-3.5 text-slate-400 font-medium">{{ $product->category->name }}</td>
                            <td class="p-3.5">
                                <input type="number" step="1" name="prices[{{ $index }}][price_per_kg]" value="{{ $product->price_per_kg }}" 
                                       class="w-24 bg-slate-950 border border-slate-700 text-white font-bold text-xs text-center rounded-lg py-1.5 focus:border-ocean-500">
                            </td>
                            <td class="p-3.5">
                                <input type="number" step="1" name="prices[{{ $index }}][sale_price_per_kg]" value="{{ $product->sale_price_per_kg }}" 
                                       placeholder="Optional" class="w-24 bg-slate-950 border border-slate-700 text-emerald-400 font-bold text-xs text-center rounded-lg py-1.5 focus:border-ocean-500">
                            </td>
                            <td class="p-3.5">
                                <select name="prices[{{ $index }}][availability_status]" class="bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-lg px-2.5 py-1.5">
                                    <option value="in_stock" {{ $product->availability_status === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="limited" {{ $product->availability_status === 'limited' ? 'selected' : '' }}>Limited Stock</option>
                                    <option value="out_of_stock" {{ $product->availability_status === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </td>
                            <td class="p-3.5 text-right">
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 font-bold text-xs">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>

</div>

<!-- Add Product Modal -->
<div id="addProductModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl space-y-5 text-white relative">
        <button onclick="document.getElementById('addProductModal').classList.add('hidden')" class="absolute top-5 right-5 text-slate-400 hover:text-white">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <h3 class="text-xl font-extrabold text-white">Add New Fish Product</h3>

        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Product Name (English)</label>
                <input type="text" name="name" required placeholder="e.g. Sea Bass / Koduva" 
                       class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-ocean-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Tamil Name</label>
                    <input type="text" name="tamil_name" placeholder="e.g. கொடுவா மீன்" 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Category</label>
                    <select name="category_id" required class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Price / Kg (₹)</label>
                    <input type="number" step="1" name="price_per_kg" required placeholder="500" 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Sale Price / Kg</label>
                    <input type="number" step="1" name="sale_price_per_kg" placeholder="Optional" 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Stock (Kg)</label>
                    <input type="number" step="1" name="stock_quantity" value="50" required 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Short Description</label>
                <textarea name="short_desc" rows="2" placeholder="Brief details on texture and best cooking style..." 
                          class="w-full bg-slate-950 border border-slate-700 text-xs font-medium text-white rounded-xl px-3.5 py-2"></textarea>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-ocean-600 to-aqua-600 hover:from-ocean-500 hover:to-aqua-500 text-white font-extrabold text-xs py-3 rounded-xl shadow-lg">
                Add Product to Catalog
            </button>
        </form>
    </div>
</div>
@endsection
