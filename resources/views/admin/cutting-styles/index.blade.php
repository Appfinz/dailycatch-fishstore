@extends('layouts.admin')

@section('title', 'Cutting Styles & Fees - Daily Catch Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-brand-navy">Cutting Styles & Processing Fees</h1>
            <p class="text-xs text-slate-500 font-medium">Manage visual fish cutting options (Curry cut, Fry cut, Boneless, Steak) & fees</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Cutting Styles Table -->
        <div class="lg:col-span-8 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <h3 class="font-extrabold text-sm text-brand-navy mb-4">Configured Cutting Options</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200 uppercase text-[10px]">
                        <tr>
                            <th class="p-3">Cutting Style</th>
                            <th class="p-3">Tamil Name</th>
                            <th class="p-3">Extra Charge</th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($cuttingStyles as $cs)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3 flex items-center gap-3">
                                    <img src="{{ $cs->image }}" class="w-10 h-10 object-cover rounded-xl border border-slate-200">
                                    <div>
                                        <h4 class="font-extrabold text-brand-navy">{{ $cs->name }}</h4>
                                        <p class="text-[10px] text-slate-400 truncate max-w-xs">{{ $cs->description }}</p>
                                    </div>
                                </td>
                                <td class="p-3 font-bold text-brand-blue">{{ $cs->tamil_name ?: '-' }}</td>
                                <td class="p-3">
                                    <span class="font-extrabold {{ $cs->additional_charge > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                        {{ $cs->additional_charge > 0 ? '+₹' . number_format($cs->additional_charge, 0) : 'FREE' }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('admin.cutting-styles.destroy', $cs->id) }}" method="POST" onsubmit="return confirm('Delete this cutting style?')">
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

        <!-- Add Cutting Style Form -->
        <div class="lg:col-span-4 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="font-extrabold text-sm text-brand-navy">Add Cutting Style</h3>

            <form action="{{ route('admin.cutting-styles.store') }}" method="POST" class="space-y-4 text-xs font-semibold">
                @csrf
                <div>
                    <label class="block text-slate-700 mb-1">Style Name (English) *</label>
                    <input type="text" name="name" required placeholder="e.g. Sura Puttu Cut" class="w-full border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-brand-blue">
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Tamil Name</label>
                    <input type="text" name="tamil_name" placeholder="e.g. சுறா புட்டு வெட்டு" class="w-full border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-brand-blue">
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="2" placeholder="Cutting instructions..." class="w-full border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-brand-blue"></textarea>
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Additional Cleaning Charge (₹)</label>
                    <input type="number" step="0.01" name="additional_charge" required value="0" class="w-full border border-slate-200 rounded-xl px-3 py-2 font-bold focus:outline-none focus:border-brand-blue">
                </div>

                <div>
                    <label class="block text-slate-700 mb-1">Image URL</label>
                    <input type="url" name="image" placeholder="https://..." class="w-full border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-brand-blue">
                </div>

                <button type="submit" class="w-full bg-brand-navy hover:bg-slate-900 text-white font-extrabold py-3 rounded-xl shadow uppercase text-xs tracking-wider">
                    Add Cutting Style
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
