@extends('layouts.admin')

@section('title', 'Daily Fish Prices & Product Management - Daily Catch Admin')

@section('content')
<div class="space-y-8">
    
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Daily Fish Prices & Inventory</h1>
            <p class="text-xs text-slate-300 mt-1">Update morning daily harbor catch prices per kg, manage active products & cutting styles</p>
        </div>

        <button onclick="openAddProductModal()" class="bg-blue-600 hover:bg-blue-500 text-white font-black text-xs px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 transition-all">
            <i class="fa-solid fa-plus text-sm"></i> + Add New Product
        </button>
    </div>

    <!-- Quick Morning Price Updater Form -->
    <form action="{{ route('admin.products.quick-price-update') }}" method="POST" class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
        @csrf
        <div class="flex items-center justify-between border-b border-slate-800 pb-4 flex-wrap gap-4">
            <div>
                <h3 class="font-extrabold text-base text-white flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-amber-400"></i> Quick Daily Catch Price Updater Grid
                </h3>
                <p class="text-xs text-slate-400">Update today's market rates for fresh sea fish arrival (All {{ count($products) }} Products)</p>
            </div>

            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs px-6 py-2.5 rounded-xl shadow transition-all flex items-center gap-2">
                <i class="fa-solid fa-check"></i> Save All Price Changes
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950 text-slate-300 text-xs uppercase font-bold border-b border-slate-800">
                        <th class="p-3.5 rounded-l-xl">Fish Product</th>
                        <th class="p-3.5">Category</th>
                        <th class="p-3.5">Weight Type</th>
                        <th class="p-3.5">Regular Rate (₹/kg)</th>
                        <th class="p-3.5">Offer Rate (₹/kg)</th>
                        <th class="p-3.5">Stock (Kg)</th>
                        <th class="p-3.5">Stock Status</th>
                        <th class="p-3.5 text-right rounded-r-xl">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-xs">
                    @forelse($products as $index => $product)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="p-3.5 font-bold text-white">
                                <input type="hidden" name="prices[{{ $index }}][id]" value="{{ $product->id }}">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image }}" class="w-11 h-11 object-cover rounded-lg border border-slate-700 shadow-sm bg-slate-950">
                                    <div>
                                        <span class="block font-extrabold text-white text-xs">{{ $product->name }}</span>
                                        @if($product->tamil_name)
                                            <span class="text-[11px] text-sky-400 font-semibold">{{ $product->tamil_name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-3.5 text-slate-300 font-medium">{{ $product->category ? $product->category->name : 'Sea Fish' }}</td>
                            <td class="p-3.5">
                                @if($product->has_weight_variation)
                                    <span class="bg-amber-500/20 text-amber-300 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-500/30">Varies Post-Cut</span>
                                @else
                                    <span class="bg-emerald-500/20 text-emerald-300 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-500/30">Exact Weight</span>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <input type="number" step="1" name="prices[{{ $index }}][price_per_kg]" value="{{ $product->price_per_kg }}" 
                                       class="w-24 bg-slate-950 border border-slate-700 text-white font-bold text-xs text-center rounded-lg py-1.5 focus:border-blue-500">
                            </td>
                            <td class="p-3.5">
                                <input type="number" step="1" name="prices[{{ $index }}][sale_price_per_kg]" value="{{ $product->sale_price_per_kg }}" 
                                       placeholder="Optional" class="w-24 bg-slate-950 border border-slate-700 text-emerald-400 font-bold text-xs text-center rounded-lg py-1.5 focus:border-blue-500">
                            </td>
                            <td class="p-3.5">
                                <div class="flex items-center gap-1">
                                    <input type="number" step="0.5" min="0" name="prices[{{ $index }}][stock_quantity]" value="{{ $product->stock_quantity }}" 
                                           class="w-20 bg-slate-950 border border-slate-700 text-white font-bold text-xs text-center rounded-lg py-1.5 focus:border-blue-500">
                                    <span class="text-[10px] text-slate-400 font-bold">Kg</span>
                                </div>
                            </td>
                            <td class="p-3.5">
                                <select name="prices[{{ $index }}][availability_status]" class="bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-lg px-2.5 py-1.5">
                                    <option value="in_stock" {{ $product->availability_status === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="limited" {{ $product->availability_status === 'limited' ? 'selected' : '' }}>Limited Stock</option>
                                    <option value="out_of_stock" {{ $product->availability_status === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </td>
                            <td class="p-3.5 text-right space-x-2">
                                <button type="button" 
                                        onclick="openEditProductModal({{ json_encode($product) }})" 
                                        class="bg-slate-800 hover:bg-slate-700 text-sky-400 hover:text-white font-bold text-xs px-2.5 py-1.5 rounded-lg border border-slate-700 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 font-bold text-xs p-1.5">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">No products added yet. Click "+ Add New Product" above to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

</div>

<!-- Add Product Modal -->
<div id="addProductModal" class="fixed inset-0 bg-slate-950/85 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl space-y-5 text-white relative max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('addProductModal').classList.add('hidden')" class="absolute top-5 right-5 text-slate-400 hover:text-white">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <h3 class="text-xl font-extrabold text-white flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-blue-500"></i> Add New Fish Product
        </h3>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <!-- Product Image Section (URL + File Upload + Live Preview) -->
            <div class="p-4 bg-slate-950 rounded-2xl border border-slate-800 space-y-3">
                <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider">Product Photo / Image</label>
                
                <div class="flex items-center gap-4">
                    <img id="addImagePreview" 
                         src="https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=600&q=80" 
                         class="w-16 h-16 rounded-xl object-cover border-2 border-slate-700 bg-slate-900 shrink-0">
                    
                    <div class="flex-1 space-y-2">
                        <div>
                            <input type="url" name="image" id="addImageUrlInput" 
                                   placeholder="Paste Image URL (https://...)" 
                                   oninput="document.getElementById('addImagePreview').src = this.value || 'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=600&q=80'"
                                   class="w-full bg-slate-900 border border-slate-700 text-xs font-medium text-white rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500">
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">OR Upload File:</span>
                            <input type="file" name="image_file" accept="image/*" 
                                   onchange="previewUploadedFile(this, 'addImagePreview')"
                                   class="text-[11px] text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-slate-800 file:text-sky-300 hover:file:bg-slate-700 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Product Name (English) <span class="text-rose-400">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Sea Bass / Koduva" 
                       class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Tamil Name</label>
                    <input type="text" name="tamil_name" placeholder="e.g. கொடுவா மீன்" 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Category <span class="text-rose-400">*</span></label>
                    <select name="category_id" required class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Price / Kg (₹) <span class="text-rose-400">*</span></label>
                    <input type="number" step="1" name="price_per_kg" required placeholder="500" 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Sale Price / Kg</label>
                    <input type="number" step="1" name="sale_price_per_kg" placeholder="Optional" 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Stock (Kg) <span class="text-rose-400">*</span></label>
                    <input type="number" step="1" name="stock_quantity" value="50" required 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 text-xs font-bold text-slate-300 cursor-pointer">
                    <input type="checkbox" name="has_weight_variation" value="1" checked class="accent-blue-500 w-4 h-4">
                    <span>Weight Varies Post-Cut? (Medium / Large Fish)</span>
                </label>
                <p class="text-[10px] text-slate-400 ml-6 mt-0.5">Uncheck for small fish (Nethili/Anchovy) where exact requested weight is supplied.</p>
            </div>

            <!-- Fish-wise Cutting Style Assignment & Custom Extra Fees -->
            <div>
                <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider mb-2">Fish-wise Cutting Styles & Extra Fees</label>
                <div class="grid grid-cols-1 gap-2 max-h-44 overflow-y-auto pr-1 bg-slate-950 p-3 rounded-xl border border-slate-800">
                    @foreach($cuttingStyles as $cs)
                        <div class="flex items-center justify-between text-xs py-1 border-b border-slate-900 last:border-none">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="cutting_styles[]" value="{{ $cs->id }}" checked class="accent-blue-500">
                                <span class="font-bold text-white">{{ $cs->name }}</span>
                            </label>
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] text-slate-400">Extra ₹</span>
                                <input type="number" step="1" name="cutting_style_fees[{{ $cs->id }}]" value="{{ $cs->additional_charge }}" placeholder="Default {{ $cs->additional_charge }}" class="w-16 bg-slate-900 border border-slate-700 text-white text-[11px] font-bold px-2 py-0.5 rounded text-center">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Short Description</label>
                <textarea name="short_desc" rows="2" placeholder="Brief details on texture and best cooking style..." 
                          class="w-full bg-slate-950 border border-slate-700 text-xs font-medium text-white rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs py-3.5 rounded-xl shadow-lg transition-all">
                + Add Product to Catalog
            </button>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="fixed inset-0 bg-slate-950/85 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl space-y-5 text-white relative max-h-[90vh] overflow-y-auto">
        <button onclick="document.getElementById('editProductModal').classList.add('hidden')" class="absolute top-5 right-5 text-slate-400 hover:text-white">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <h3 class="text-xl font-extrabold text-white flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-sky-400"></i> Edit Fish Product
        </h3>

        <form id="editProductForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            <!-- Product Image Section -->
            <div class="p-4 bg-slate-950 rounded-2xl border border-slate-800 space-y-3">
                <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider">Product Photo / Image</label>
                
                <div class="flex items-center gap-4">
                    <img id="editImagePreview" 
                         src="" 
                         class="w-16 h-16 rounded-xl object-cover border-2 border-slate-700 bg-slate-900 shrink-0">
                    
                    <div class="flex-1 space-y-2">
                        <div>
                            <input type="url" name="image" id="editImageUrlInput" 
                                   placeholder="Paste Image URL (https://...)" 
                                   oninput="document.getElementById('editImagePreview').src = this.value"
                                   class="w-full bg-slate-900 border border-slate-700 text-xs font-medium text-white rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500">
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">OR Upload File:</span>
                            <input type="file" name="image_file" accept="image/*" 
                                   onchange="previewUploadedFile(this, 'editImagePreview')"
                                   class="text-[11px] text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-slate-800 file:text-sky-300 hover:file:bg-slate-700 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Product Name (English) <span class="text-rose-400">*</span></label>
                <input type="text" name="name" id="editName" required 
                       class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Tamil Name</label>
                    <input type="text" name="tamil_name" id="editTamilName" 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Category <span class="text-rose-400">*</span></label>
                    <select name="category_id" id="editCategoryId" required class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Price / Kg (₹) <span class="text-rose-400">*</span></label>
                    <input type="number" step="1" name="price_per_kg" id="editPrice" required 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Sale Price / Kg</label>
                    <input type="number" step="1" name="sale_price_per_kg" id="editSalePrice" placeholder="Optional" 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Stock (Kg) <span class="text-rose-400">*</span></label>
                    <input type="number" step="1" name="stock_quantity" id="editStock" required 
                           class="w-full bg-slate-950 border border-slate-700 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 text-xs font-bold text-slate-300 cursor-pointer">
                    <input type="checkbox" name="has_weight_variation" id="editWeightVariation" value="1" class="accent-blue-500 w-4 h-4">
                    <span>Weight Varies Post-Cut? (Medium / Large Fish)</span>
                </label>
                <p class="text-[10px] text-slate-400 ml-6 mt-0.5">Uncheck for small fish (Nethili/Anchovy) where exact requested weight is supplied.</p>
            </div>

            <!-- Fish-wise Cutting Style Assignment -->
            <div>
                <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider mb-2">Fish-wise Cutting Styles & Extra Fees</label>
                <div class="grid grid-cols-1 gap-2 max-h-44 overflow-y-auto pr-1 bg-slate-950 p-3 rounded-xl border border-slate-800">
                    @foreach($cuttingStyles as $cs)
                        <div class="flex items-center justify-between text-xs py-1 border-b border-slate-900 last:border-none">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="cutting_styles[]" value="{{ $cs->id }}" id="edit_cs_{{ $cs->id }}" class="accent-blue-500 edit-cs-checkbox">
                                <span class="font-bold text-white">{{ $cs->name }}</span>
                            </label>
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] text-slate-400">Extra ₹</span>
                                <input type="number" step="1" name="cutting_style_fees[{{ $cs->id }}]" id="edit_cs_fee_{{ $cs->id }}" value="{{ $cs->additional_charge }}" class="w-16 bg-slate-900 border border-slate-700 text-white text-[11px] font-bold px-2 py-0.5 rounded text-center">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Short Description</label>
                <textarea name="short_desc" id="editShortDesc" rows="2" 
                          class="w-full bg-slate-950 border border-slate-700 text-xs font-medium text-white rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-extrabold text-xs py-3.5 rounded-xl shadow-lg transition-all">
                Save & Update Product
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddProductModal() {
        document.getElementById('addProductModal').classList.remove('hidden');
    }

    function openEditProductModal(product) {
        const form = document.getElementById('editProductForm');
        form.action = `/admin/products/${product.id}`;

        document.getElementById('editName').value = product.name || '';
        document.getElementById('editTamilName').value = product.tamil_name || '';
        document.getElementById('editCategoryId').value = product.category_id || '';
        document.getElementById('editPrice').value = product.price_per_kg || '';
        document.getElementById('editSalePrice').value = product.sale_price_per_kg || '';
        document.getElementById('editStock').value = product.stock_quantity || '50';
        document.getElementById('editShortDesc').value = product.short_desc || '';
        document.getElementById('editImageUrlInput').value = product.image || '';
        document.getElementById('editImagePreview').src = product.image || 'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=600&q=80';
        document.getElementById('editWeightVariation').checked = Boolean(product.has_weight_variation);

        // Reset cutting style checkboxes
        document.querySelectorAll('.edit-cs-checkbox').forEach(cb => cb.checked = false);

        // Check assigned cutting styles and populate fees
        if (product.cutting_styles && Array.isArray(product.cutting_styles)) {
            product.cutting_styles.forEach(cs => {
                const cb = document.getElementById(`edit_cs_${cs.id}`);
                if (cb) cb.checked = true;
                const feeInput = document.getElementById(`edit_cs_fee_${cs.id}`);
                if (feeInput && cs.pivot && cs.pivot.additional_charge !== null) {
                    feeInput.value = cs.pivot.additional_charge;
                }
            });
        }

        document.getElementById('editProductModal').classList.remove('hidden');
    }

    function previewUploadedFile(input, previewImgId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewImgId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
