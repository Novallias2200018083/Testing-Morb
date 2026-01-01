<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CounterController extends Controller
{
    public function index(Request $request)
    {
       
        $query = Counter::with(['activeUser', 'services']); 

        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:counters,name|max:50',
            'status' => 'required|in:open,closed',
            'services' => 'array', 
            'services.*' => 'exists:services,id'
        ]);

        $counter = Counter::create([
            'name' => $request->name,
            'status' => $request->status,
            'active_user_id' => $request->active_user_id
        ]);

        
        if ($request->has('services')) {
            $counter->services()->sync($request->services);
        }

        return response()->json(['message' => 'Loket berhasil dibuat', 'data' => $counter], 201);
    }

    public function update(Request $request, $id)
    {
        $counter = Counter::findOrFail($id);
        
        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('counters')->ignore($counter->id)],
            'status' => 'required|in:open,closed',
            'services' => 'array'
        ]);
        
        $counter->update([
            'name' => $request->name,
            'status' => $request->status,
            'active_user_id' => $request->active_user_id
        ]);

       
        if ($request->has('services')) {
            $counter->services()->sync($request->services);
        }

        return response()->json(['message' => 'Konfigurasi loket diperbarui']);
    }

    public function destroy($id)
    {
        Counter::destroy($id);
        return response()->json(['message' => 'Loket dihapus']);
    }
}