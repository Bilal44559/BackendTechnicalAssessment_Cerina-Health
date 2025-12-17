<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function available(Request $request)
    {
        $query = Doctor::query();

        if ($request->specialization) {
            $query->where('specialization',$request->specialization);
        }

        if ($request->sort_by) {
            $query->orderBy(
                $request->sort_by,
                $request->get('order','asc')
            );
        }

        return DoctorResource::collection($query->get());
    }
}
