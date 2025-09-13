<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::with('subcategorias')->where('activa', true)->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255|unique:categories',
                'descripcion' => 'nullable|string|max:500',
                'activa' => 'boolean'
            ]);

            $category = Category::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente',
                'data' => $category
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
    public function show(Category $category): JsonResponse
    {
        $category->load('subcategorias');
        
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255|unique:categories,nombre,' . $category->id,
                'descripcion' => 'nullable|string|max:500',
                'activa' => 'boolean'
            ]);

            $category->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente',
                'data' => $category
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
    public function destroy(Category $category): JsonResponse
    {
        // Soft delete - just mark as inactive
        $category->update(['activa' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Categoría desactivada exitosamente'
        ]);
    }
}
