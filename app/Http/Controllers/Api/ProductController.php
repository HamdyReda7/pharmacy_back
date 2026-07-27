<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('id', 'asc')
            ->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب المنتجات بنجاح.',
            'data' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ], 200);
    }

    public function all()
    {
        $products = Product::where('status', true)
            ->whereNull('price_before')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'تم جلب المنتجات بنجاح.',
            'data' => ProductResource::collection($products),
        ]);
    }

    public function offers()
    {
        $products = Product::where('status', true)
            ->whereNotNull('price_before')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'تم جلب العروض بنجاح.',
            'data' => ProductResource::collection($products),
        ]);
    }

    public function offersAdmin()
    {
        $products = Product::with('category')
            ->where('status', true)
            ->whereNotNull('price_before')
            ->orderBy('id', 'asc')
            ->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب العروض بنجاح.',
            'data' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ], 200);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'المنتج غير موجود.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم جلب بيانات المنتج بنجاح.',
            'data' => new ProductResource($product),
        ], 200);
    }

    public function store(StoreProductRequest $request)
    {
        $images = [];

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $imageName = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('img/products'), $imageName);

                $images[] = $imageName;
            }
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'title_ar' => $request->title_ar,
            'title_en' => $request->title_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'images' => $images,
            'price_before' => $request->price_before,
            'price_after' => $request->price_after,
            'quantity' => $request->quantity,
            'status' => $request->status ?? true,
        ]);

        $product->load('category');

        return response()->json([
            'status' => true,
            'message' => 'تم إضافة المنتج بنجاح.',
            'data' => new ProductResource($product),
        ], 201);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'المنتج غير موجود.',
            ], 404);
        }

        $images = $product->images ?? [];

        // حذف الصور المحددة
        if ($request->filled('delete_images')) {

            foreach ($request->delete_images as $image) {

                if (in_array($image, $images)) {

                    $imagePath = public_path('img/products/' . $image);

                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }

                    $images = array_values(array_diff($images, [$image]));
                }
            }
        }

        // إضافة صور جديدة
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $imageName = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('img/products'), $imageName);

                $images[] = $imageName;
            }
        }

        $product->update([
            'category_id' => $request->category_id ?? $product->category_id,
            'title_ar' => $request->title_ar ?? $product->title_ar,
            'title_en' => $request->title_en ?? $product->title_en,
            'description_ar' => $request->description_ar ?? $product->description_ar,
            'description_en' => $request->description_en ?? $product->description_en,
            'images' => $images,
            'price_before' => $request->price_before ?? $product->price_before,
            'price_after' => $request->price_after ?? $product->price_after,
            'quantity' => $request->quantity ?? $product->quantity,
            'status' => $request->status ?? $product->status,
        ]);

        $product->load('category');

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث المنتج بنجاح.',
            'data' => new ProductResource($product),
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'المنتج غير موجود.',
            ], 404);
        }

        if (!empty($product->images)) {

            foreach ($product->images as $image) {

                $imagePath = public_path('img/products/' . $image);

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المنتج بنجاح.',
        ], 200);
    }
}
