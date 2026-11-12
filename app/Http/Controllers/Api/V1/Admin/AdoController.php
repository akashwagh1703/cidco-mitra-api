<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ado;
use Illuminate\Http\Request;

class AdoController extends Controller
{
    public function index(Request $request)
    {
        $query = Ado::query()->orderBy('name');

        if ($request->boolean('active_only')) {
            $query->active();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'success' => true,
            'data' => $query->get(),
        ]);
    }

    public function store(Request $request)
    {
        if ($request->exists('contact') && trim((string) $request->input('contact')) === '') {
            $request->merge(['contact' => null]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ados,name',
            'contact' => ['required', 'regex:/^[0-9]{10}$/'],
            'status' => 'boolean',
        ]);

        $ado = Ado::create([
            'name' => trim($validated['name']),
            'contact' => trim($validated['contact']),
            'status' => $validated['status'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ADO created successfully',
            'data' => $ado,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $ado = Ado::findOrFail($id);

        if ($request->exists('contact') && trim((string) $request->input('contact')) === '') {
            $request->merge(['contact' => null]);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:ados,name,' . $ado->id,
            'contact' => ['sometimes', 'required', 'regex:/^[0-9]{10}$/'],
            'status' => 'boolean',
        ]);

        if (array_key_exists('name', $validated)) {
            $validated['name'] = trim($validated['name']);
        }
        if (array_key_exists('contact', $validated)) {
            $validated['contact'] = trim($validated['contact']);
        }

        $ado->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'ADO updated successfully',
            'data' => $ado,
        ]);
    }

    public function destroy($id)
    {
        $ado = Ado::findOrFail($id);
        $ado->delete();

        return response()->json([
            'success' => true,
            'message' => 'ADO deleted successfully',
        ]);
    }
}
