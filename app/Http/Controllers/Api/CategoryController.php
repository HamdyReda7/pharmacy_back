<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()         
    {
        $categories = Category::withCount('products')
            ->orderBy('id', 'asc')
            ->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الأقسام بنجاح.',
            'data' => CategoryResource::collection($categories),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page'    => $categories->lastPage(),
                'per_page'     => $categories->perPage(),
                'total'        => $categories->total(),
            ],
        ]);
    }

    public function all()
    {
        $categories = Category::withCount('products')
            ->where('status', true)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الأقسام بنجاح.',
            'data' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(StoreCategoryRequest $request)
    {
        $imageName = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = 'category_' . time() . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('img/categories'), $imageName);
        }

        $category = Category::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'image' => $imageName,
            'status' =>  true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم إضافة القسم بنجاح.',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::withCount('products')->find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'القسم غير موجود.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم جلب بيانات القسم.',
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);

        $imageName = $category->image;

        if ($request->hasFile('image')) {

            if ($category->image && File::exists(public_path('img/categories/' . $category->image))) {

                File::delete(public_path('img/categories/' . $category->image));
            }

            $image = $request->file('image');

            $imageName = 'category_' . time() . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('img/categories'), $imageName);
        }

        $category->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'image' => $imageName,
            'status' => $request->status ?? $category->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم تعديل القسم بنجاح.',
            'data' => new CategoryResource($category->fresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && File::exists(public_path('img/categories/' . $category->image))) {

            File::delete(public_path('img/categories/' . $category->image));
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف القسم بنجاح.',
        ]);
    }
}
