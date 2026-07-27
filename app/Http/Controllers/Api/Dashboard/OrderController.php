<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Order\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * عرض جميع الطلبات
     */
    public function index()
    {
        $orders = Order::with(['items.product'])
            ->withCount('items')
            ->latest()
            ->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الطلبات.',
            'data' => OrderResource::collection($orders),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    /**
     * عرض الطلبات الجديدة (Pending)
     */
    public function pending()
    {
        $orders = Order::with(['items.product'])
            ->withCount('items')
            ->where('status', Order::PENDING)
            ->latest()
            ->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الطلبات المعلقة.',
            'data' => OrderResource::collection($orders),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    /**
     * عرض تفاصيل طلب
     */
    public function show($id)
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

    /**
     * تغيير حالة الطلب
     */
    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        $order = Order::with('items.product')->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'الطلب غير موجود.',
            ], 404);
        }

        DB::transaction(function () use ($request, $order) {

            if (
                $request->status === Order::COMPLETED &&
                $order->status !== Order::COMPLETED
            ) {

                foreach ($order->items as $item) {

                    if ($item->product->quantity < $item->quantity) {
                        abort(response()->json([
                            'status' => false,
                            'message' => "الكمية المتوفرة من {$item->product->title_ar} غير كافية.",
                        ], 422));
                    }
                }

                foreach ($order->items as $item) {
                    $item->product->decrement('quantity', $item->quantity);
                }
            }

            $order->update([
                'status' => $request->status,
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث حالة الطلب.',
            'data' => new OrderResource(
                $order->fresh()->load(['items.product'])
            ),
        ]);
    }

    /**
     * حذف الطلب
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'الطلب غير موجود.',
            ], 404);
        }

        $order->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف الطلب بنجاح.',
        ]);
    }
}
