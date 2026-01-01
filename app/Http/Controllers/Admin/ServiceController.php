<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:services,code', 
            'description' => 'nullable|string'
        ]);

        
        $data = $request->all();
        $data['code'] = strtoupper($request->code);

        $service = Service::create($data);

        return response()->json(['message' => 'Layanan berhasil dibuat', 'data' => $service], 201);
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:5', Rule::unique('services')->ignore($service->id)],
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($request->code);

        $service->update($data);

        return response()->json(['message' => 'Layanan diperbarui', 'data' => $service]);
    }

    public function destroy($id)
    {
        Service::destroy($id);
        return response()->json(['message' => 'Layanan dihapus']);
    }
}