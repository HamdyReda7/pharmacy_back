<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest as AdminStoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest as AdminUpdateAdminRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Dashboard\UserDetailsResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PrescriptionResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    /**
     * Display all admins.
     */
    public function admins()
    {
        $admins = User::where('role', User::ADMIN)
            ->latest()
            ->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'Admins retrieved successfully.',
            'data' => UserResource::collection($admins),
            'pagination' => [
                'current_page' => $admins->currentPage(),
                'last_page'    => $admins->lastPage(),
                'per_page'     => $admins->perPage(),
                'total'        => $admins->total(),
            ]
        ]);
    }

    /**
     * Display all users.
     */
    public function users()
    {
        $users = User::where('role', User::USER)
            ->latest()
            ->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'data' => UserResource::collection($users),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
            ]
        ]);
    }

    public function showuser($id)
    {
        $user = User::where('role', User::USER)
            ->with([
                'orders.items.product',
                'prescriptions',
            ])
            ->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'المستخدم غير موجود.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم جلب بيانات المستخدم.',
            'data' => new UserDetailsResource($user),
        ]);
    }

    /**
     * Display all categories.
     */
    public function category()
    {
        $categories = Category::withCount('products')
            ->orderBy('id', 'asc')
            ->paginate(5);
        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully.',
            'data' => CategoryResource::collection($categories),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page'    => $categories->lastPage(),
                'per_page'     => $categories->perPage(),
                'total'        => $categories->total(),
            ]
        ]);
    }

    /**
     * Display all products.
     */
    public function product()
    {
        $products = Product::latest()->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully.',
            'data' => ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ]
        ]);
    }

    public function showproduct($id)
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

    public function orders()
    {
        $orders = Order::latest()->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data' => OrderResource::collection($orders),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ]
        ]);
    }

    public function showorder(Request $request, $id)
    {
        $order = Order::with(['items.product'])
            ->withCount('items')
            ->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'الطلب غير موجود.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم جلب تفاصيل الطلب.',
            'data' => new OrderResource($order),
        ]);
    }
    public function prescriptions()
    {
        $prescriptions = Prescription::latest()->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الوصفات الطبية.',
            'data' => PrescriptionResource::collection($prescriptions),
            'pagination' => [
                'current_page' => $prescriptions->currentPage(),
                'last_page'    => $prescriptions->lastPage(),
                'per_page'     => $prescriptions->perPage(),
                'total'        => $prescriptions->total(),
            ],
        ]);
    }

    public function showprescription($id)
    {
        $prescription = Prescription::find($id);

        if (!$prescription) {
            return response()->json([
                'status' => false,
                'message' => 'الوصفة الطبية غير موجودة.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم جلب تفاصيل الوصفة الطبية.',
            'data' => new PrescriptionResource($prescription),
        ]);
    }

    
    /**
     * Display single admin.
     */

    public function showAdmin($id)
    {
        $admin = User::where('role', User::ADMIN)
            ->find($id);

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($admin),
        ]);
    }

    /**
     * Store new admin.
     */
    public function storeAdmin(AdminStoreAdminRequest $request)
    {
        $imageName = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $extension = $image->getClientOriginalExtension();

            $imageName = 'admin_' . uniqid() . '.' . $extension;

            $image->move(public_path('img/users'), $imageName);
        }

        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'image'    => $imageName,
            'role'     => User::ADMIN,
            'status'   => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully.',
            'data'    => new UserResource($admin),
        ], 201);
    }

    /**
     * Update admin.
     */
    public function updateAdmin(AdminUpdateAdminRequest $request, $id)
    {
        $admin = User::where('role', User::ADMIN)->find($id);

        if (!$admin) {

            return response()->json([
                'success' => false,
                'message' => 'Admin not found.',
            ], 404);
        }

        $imageName = $admin->image;

        if ($request->hasFile('image')) {

            if ($admin->image && File::exists(public_path('img/users/' . $admin->image))) {
                File::delete(public_path('img/users/' . $admin->image));
            }

            $image = $request->file('image');

            $extension = $image->getClientOriginalExtension();

            $imageName = 'admin_' . uniqid() . '.' . $extension;

            $image->move(public_path('img/users'), $imageName);
        }

        $admin->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'image'  => $imageName,
            'status' => $request->status,
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $admin->password,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin updated successfully.',
            'data' => new UserResource($admin->fresh()),
        ]);
    }

    /**
     * Delete admin.
     */
    public function destroyAdmin($id)
    {
        $admin = User::where('role', User::ADMIN)->find($id);

        if (!$admin) {

            return response()->json([
                'success' => false,
                'message' => 'Admin not found.',
            ], 404);
        }

        if ($admin->image && File::exists(public_path('img/users/' . $admin->image))) {
            File::delete(public_path('img/users/' . $admin->image));
        }

        $admin->tokens()->delete();

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin deleted successfully.',
        ]);
    }
}
