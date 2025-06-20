<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = Subcategory::with('category')->get();
        return response()->json([
            'status' => 'success',
            'data' => $subcategories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'is_active' => 'boolean'
            ]);

            // Check if subcategory with same name exists
            $existingSubcategory = Subcategory::where('name', $validated['name'])->first();
            if ($existingSubcategory) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A subcategory with this name already exists',
                    'errors' => [
                        'name' => ['This name is already taken']
                    ]
                ], 422);
            }

            $validated['slug'] = Str::slug($validated['name']);

            $subcategory = Subcategory::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Subcategory created successfully',
                'data' => $subcategory
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create subcategory',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        return response()->json([
            'status' => 'success',
            'data' => $subcategory->load('category')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'is_active' => 'boolean'
            ]);

            // Check if another subcategory with same name exists
            $existingSubcategory = Subcategory::where('name', $validated['name'])
                ->where('id', '!=', $subcategory->id)
                ->first();
            
            if ($existingSubcategory) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A subcategory with this name already exists',
                    'errors' => [
                        'name' => ['This name is already taken']
                    ]
                ], 422);
            }

            $validated['slug'] = Str::slug($validated['name']);

            $subcategory->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Subcategory updated successfully',
                'data' => $subcategory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update subcategory',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        try {
            $subcategory->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Subcategory deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete subcategory'
            ], 500);
        }
    }
} 