<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function appointments($id)
    {
        $appointments = Appointment::where('patient_id',$id)
            ->with('doctor')
            ->orderBy('appointment_date','desc')
            ->get();

        return AppointmentResource::collection($appointments);
    }
}
