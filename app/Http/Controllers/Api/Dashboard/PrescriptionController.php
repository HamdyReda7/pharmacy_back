<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Prescription\UpdatePrescriptionStatusRequest;
use App\Http\Resources\PrescriptionResource;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
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

    public function pending()
    {
        $prescriptions = Prescription::where('status', Prescription::PENDING)
            ->latest()
            ->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الوصفات الطبية المعلقة.',
            'data' => PrescriptionResource::collection($prescriptions),
            'pagination' => [
                'current_page' => $prescriptions->currentPage(),
                'last_page'    => $prescriptions->lastPage(),
                'per_page'     => $prescriptions->perPage(),
                'total'        => $prescriptions->total(),
            ],
        ]);
    }

    public function show($id)
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

    public function updateStatus(UpdatePrescriptionStatusRequest $request, $id)
    {
        $prescription = Prescription::find($id);

        if (!$prescription) {
            return response()->json([
                'status' => false,
                'message' => 'الوصفة الطبية غير موجودة.',
            ], 404);
        }

        $prescription->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث حالة الوصفة الطبية بنجاح.',
            'data' => new PrescriptionResource($prescription),
        ]);
    }

    public function destroy($id)
    {
        $prescription = Prescription::find($id);

        if (!$prescription) {
            return response()->json([
                'status' => false,
                'message' => 'الوصفة الطبية غير موجودة.',
            ], 404);
        }

        if ($prescription->image && file_exists(public_path('img/prescriptions/' . $prescription->image))) {
            unlink(public_path('img/prescriptions/' . $prescription->image));
        }

        $prescription->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف الوصفة الطبية بنجاح.',
        ]);
    }
}
