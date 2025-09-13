<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Subcategory::with('category')->where('active', true);
        
        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        $subcategories = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $subcategories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'price' => 'required|numeric|min:0',
                'active' => 'boolean'
            ]);

            // Check unique constraint manually
            $exists = Subcategory::where('category_id', $validated['category_id'])
                                ->where('name', $validated['name'])
                                ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una subcategoría con ese name en esta categoría',
                    'errors' => ['name' => ['Este name ya existe en la categoría seleccionada']]
                ], 422);
            }

            $subcategory = Subcategory::create($validated);
            $subcategory->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Subcategoría creada exitosamente',
                'data' => $subcategory
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory): JsonResponse
    {
        $subcategory->load('category');
        
        return response()->json([
            'success' => true,
            'data' => $subcategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory): JsonResponse
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'price' => 'required|numeric|min:0',
                'active' => 'boolean'
            ]);

            // Check unique constraint manually (excluding current record)
            $exists = Subcategory::where('category_id', $validated['category_id'])
                                ->where('name', $validated['name'])
                                ->where('id', '!=', $subcategory->id)
                                ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una subcategoría con ese name en esta categoría',
                    'errors' => ['name' => ['Este name ya existe en la categoría seleccionada']]
                ], 422);
            }

            $subcategory->update($validated);
            $subcategory->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Subcategoría actualizada exitosamente',
                'data' => $subcategory
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory): JsonResponse
    {
        // Soft delete - just mark as inactive
        $subcategory->update(['active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Subcategoría desactiveda exitosamente'
        ]);
    }

    /**
     * Get subcategories by category
     */
    public function getByCategory(Category $category): JsonResponse
    {
        $subcategories = $category->subcategories()->where('active', true)->get();
        
        return response()->json([
            'success' => true,
            'data' => $subcategories
        ]);
    }
}
