<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $total = 0;

            $products = [];

            foreach ($request->items as $item) {

                $product = Product::find($item['product_id']);

                if (!$product || !$product->status) {
                    return response()->json([
                        'status' => false,
                        'message' => 'أحد المنتجات غير متوفر.',
                    ], 422);
                }

                if ($product->quantity < $item['quantity']) {
                    return response()->json([
                        'status' => false,
                        'message' => "الكمية المطلوبة من {$product->title_ar} غير متوفرة.",
                    ], 422);
                }

                $price = $product->price_after;
                $itemTotal = $price * $item['quantity'];

                $total += $itemTotal;

                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total' => $itemTotal,
                ];
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => 'ORD-' . time() . rand(100, 999),

                'name' => $request->name,
                'phone' => $request->phone,
                'governorate' => $request->governorate,
                'address' => $request->address,
                'notes' => $request->notes,

                'total' => $total,
            ]);

            foreach ($products as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);
            }

            $order->load('items.product');

            return response()->json([
                'status' => true,
                'message' => 'تم إنشاء الطلب بنجاح.',
                'data' => new OrderResource($order),
            ], 201);
        });
    }

    public function index(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الطلبات بنجاح.',
            'data' => OrderResource::collection($orders),
        ]);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with('items.product')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

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
}
