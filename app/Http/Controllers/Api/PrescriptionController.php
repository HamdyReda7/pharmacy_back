<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Prescription\StorePrescriptionRequest;
use App\Http\Resources\PrescriptionResource;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    /**
     * Store a new prescription.
     */
    public function store(StorePrescriptionRequest $request)
    {
        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imageName = 'prescription_' .
                time() . '_' .
                rand(1000, 9999) . '.' .
                $image->getClientOriginalExtension();

            $image->move(
                public_path('img/prescriptions'),
                $imageName
            );
        }

        $prescription = Prescription::create([
            'user_id' => Auth::id(),
            'image' => $imageName,
            'status' => Prescription::PENDING,
        ]);

        $prescription->load('user');

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال الوصفة الطبية بنجاح.',
            'data' => new PrescriptionResource($prescription),
        ], 201);
    }

    /**
     * Get authenticated user's prescriptions.
     */
    public function index(Request $request)
    {
        $prescriptions = Prescription::with('user')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الوصفات الطبية بنجاح.',

            'data' => PrescriptionResource::collection(
                $prescriptions->getCollection()
            ),

            'pagination' => [
                'current_page' => $prescriptions->currentPage(),
                'last_page' => $prescriptions->lastPage(),
                'per_page' => $prescriptions->perPage(),
                'total' => $prescriptions->total(),
            ],
        ]);
    }

    /**
     * Get a specific prescription.
     */
    public function show(Request $request, $id)
    {
        $prescription = Prescription::with('user')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$prescription) {
            return response()->json([
                'status' => false,
                'message' => 'الوصفة الطبية غير موجودة.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم جلب تفاصيل الوصفة الطبية بنجاح.',
            'data' => new PrescriptionResource($prescription),
        ]);
    }
}
