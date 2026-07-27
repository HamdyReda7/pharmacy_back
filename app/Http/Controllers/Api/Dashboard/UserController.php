<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\User\UpdateUserStatusRequest;
use App\Http\Resources\Dashboard\UserDetailsResource;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display all users.
     */
    public function index()
    {
        $users = User::where('role', User::USER)
            ->latest()
            ->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب المستخدمين بنجاح.',
            'data' => UserResource::collection($users),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
            ],
        ]);
    }

    /**
     * Display user details.
     */
    public function show($id)
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
     * Update user status.
     */
    public function updateStatus(UpdateUserStatusRequest $request, $id)
    {
        $user = User::where('role', User::USER)->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'المستخدم غير موجود.',
            ], 404);
        }

        $user->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث حالة المستخدم بنجاح.',
            'data' => new UserResource($user),
        ]);
    }
}
