@extends('layouts.admin')

@section('title', 'Categories Management - Daily Catch Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-brand-navy">Categories Manager</h1>
            <p class="text-xs text-slate-500 font-medium">Manage seafood product categories displayed on customer web app</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Categories Table -->
        <div class="lg:col-span-8 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <h3 class="font-extrabold text-sm text-brand-navy mb-4">Active Product Categories</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 uppercase text-[10px]">
                        <tr>
                            <th class="p-3">Category</th>
                            <th class="p-3">Slug</th>
                            <th class="p-3">Products</th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($categories as $c)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3 flex items-center gap-3">
                                    <img src="{{ $c->image }}" class="w-10 h-10 object-cover rounded-xl border border-slate-200">
                                    <div>
                                        <h4 class="font-extrabold text-brand-navy">{{ $c->name }}</h4>
                                        <p class="text-[10px] text-slate-400 truncate max-w-xs">{{ $c->description }}</p>
                                    </div>
                                </td>
                                <td class="p-3 font-mono font-bold text-slate-600">{{ $c->slug }}</td>
                                <td class="p-3">
                                    <span class="bg-blue-50 text-brand-blue font-extrabold px-2.5 py-1 rounded-full text-[10px]">
                                        {{ $c->products_count }} Products
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold text-xs">
                                            <i class="fa-solid fa-trash-can"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Category Form -->
        <div class="lg:col-span-4 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="font-extrabold text-sm text-brand-navy">Add New Category</h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4 text-xs font-semibold">
                @csrf
                <div>
                    <label class="block text-slate-700 mb-1">Category Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Lobster & Squid" class="w-full border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-brand-blue">
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Short Description</label>
                    <textarea name="description" rows="2" placeholder="Brief tagline for category..." class="w-full border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-brand-blue"></textarea>
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Image URL</label>
                    <input type="url" name="image" placeholder="https://..." class="w-full border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-brand-blue">
                </div>

                <button type="submit" class="w-full bg-brand-navy hover:bg-slate-900 text-white font-extrabold py-3 rounded-xl shadow uppercase text-xs tracking-wider">
                    Create Category
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
